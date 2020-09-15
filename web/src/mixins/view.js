function parse(route, specs) {
  if(route) {
      const view = {};
      for(const key in specs) {
          const spec = specs[key];
          if(Object.prototype.hasOwnProperty.call(route.query, key)) {
              let value = route.query[key];
              if(spec.isArray && !Array.isArray(value)) value = [value];
              if(spec.isNumber) value = spec.isArray ? value.map(Number) : Number(value);
              view[key] = value;
          } else if(spec.isArray) {
              view[key] = [];
          } else if(spec.canBeUndefined) {
              view[key] = undefined;
          } else {
            view[key] = specs[key].defaultValue;
          }
      }
      return view;
  }
  return undefined;
}

function format(view, specs) {
  let query = {};
  for(const key in specs) {
    const spec = specs[key];
    let value = view[key];
    if(value !== undefined) {
      if(!spec.isArray || value.length > 0) {
        if(spec.isNumber) value = spec.isArray ? value.map(String) : String(value);
        query[key] = value;
      }
    }
  }
  return query;
}

function equals(view1, view2) {
  return JSON.stringify(view1) === JSON.stringify(view2);
}

function resetPagination(view, prev) {
  return prev !== undefined && !equals(view, prev) && prev.from === view.from && view.from !== 0;
}

export default {
  data () {
    return {
      specs: {},
      paginated: false,
      initialized: false,
      status: 'success',
      view: { from: 0 },
      size: 10,
      total: 0,
    }
  },
  methods: {
    initView (specs, paginated) {
      this.specs = specs;
      this.paginated = paginated;
      const view = parse(this.$route, this.specs);
      const query = format(view, this.specs);
      if(!equals(query, this.$route.query)) {
        this.$router.replace({ query }).catch(() => {});
      } else {
        this.routeChanged(this.$route);
      }
    },
    routeChanged (to, from) {
      const view = parse(to, this.specs);
      const prev = parse(from, this.specs);
      if(this.paginated && resetPagination(view, prev)) {
        const query = format({ ...view, from: 0 }, this.specs);
        this.$router.replace({ query }).catch(() => {});
      } else {
        this.view = view;
        this.viewChanged();
      }
    },
    setLoading () {
      this.status = 'loading';
    },
    setSuccess () {
      this.status = 'success';
      this.initialized = true;
    },
    setFailure () {
      this.status = 'failure';
    },
    firstPage () {
      this.view.from = 0;
    },
    previousPage () {
      this.view.from = Math.max(this.view.from - this.size, 0);
    },
    nextPage () {
      if(this.view.from + this.size < this.total) {
        this.view.from = this.view.from + this.size; 
      } 
    },
    lastPage () {
      this.view.from = this.total % this.size === 0 ? (this.total - this.size) : (this.total - (this.total % this.size));
    },
    viewChanged () {
      //console.log('View changed');
    },
  },
  watch: {
    $route (to, from) {
      this.routeChanged(to, from);
    },
    view: {
      handler (to) {
        const query = format(to, this.specs);
        this.$router.push({ query }).catch(() => {});
      },
      deep: true
    }
  },
  computed: {
    loading () {
      return this.status === 'loading';
    },
    success () {
      return this.status === 'success';
    },
    failure () {
      return this.status === 'failure';
    },
    page () {
      return this.size < this.total ? String(this.view.from + 1) + ' - ' + String(Math.min(this.view.from + this.size, this.total)) + ' / ' : '';
    },
    showPagination () {
      return this.total > this.size;
    },
  },
}
