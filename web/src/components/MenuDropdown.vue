<template>
  <div class="ui dropdown item">
    <div class="default text">{{ placeholder }}</div>
    <i class="dropdown icon"></i>
    <div class="menu">
      <slot></slot>
    </div>
  </div>
</template>

<script>
import $ from 'jquery'

export default {
  name: 'MenuDropdown',
  props: {
    value: {},
    placeholder: String,
    multiple: Boolean,
    isNumber: Boolean,
    useLabels: Boolean,
  },
  data () {
    return {
      selector: undefined,
      current: undefined,
      timeout: undefined,
    }
  },
  mounted () {
    this.selector = $(this.$el);
    this.selector.addClass(this.multiple ? 'multiple' : '');
    this.selector.dropdown({ 
      ignoreDiacritics: true,
      useLabels: this.useLabels,
      fullTextSearch: 'exact',
      message: {
        count: this.placeholder + ': {count}',
        noResults: 'No hay resultados.'
      }
    });
    const value = this.format(this.value);
    this.selector.dropdown('set exactly', value);
    this.current = this.selector.dropdown('get value');
    this.selector.dropdown('setting', 'onChange', () => {
      clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        const to = this.selector.dropdown('get value');
        if(this.current !== to) {
          const value = this.parse(to);
          this.$emit('input', value);
          this.current = to;
        }
      });
    });
  },
  watch: {
    value (to) {
      const value = this.format(to);
      this.selector.dropdown('set exactly', value);
      this.current = this.selector.dropdown('get value');
    },
  },
  methods: {
    format (value) {
      const notEmpty = value !== undefined && value !== null;
      const cleaned = notEmpty ? (this.multiple ? value.map(String).sort() : String(value)) : '';
      return cleaned;
    },
    parse (value) {
      const notEmpty = value !== '';
      const cleaned = this.multiple ? (notEmpty ? (this.isNumber ? value.split(',').sort().map(Number) : value.split(',').sort()) : []) : (notEmpty ? (this.isNumber ? Number(value) : value) : undefined);
      return cleaned;
    },
  }
}
</script>
