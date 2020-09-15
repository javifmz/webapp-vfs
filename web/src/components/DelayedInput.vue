<template>
  <div class="ui icon input">
    <input type="text" :placeholder="placeholder" :value="value" @input="debounce($event.target.value)">
    <i class="search icon" :class="icon"></i>
  </div>
</template>

<script>
export default {
  name: 'DelayedInput',
  props: {
    value: String,
    placeholder: String,
    icon: String,
    delay: {
      type: Number,
      default: 400,
    },
  },
  data () {
    return {
      timeout: null,
    }
  },
  methods: {
    debounce (value) {
      if(this.timeout) clearTimeout(this.timeout);
      this.timeout = setTimeout(() => this.$emit('input', value), this.delay);
    }
  }
}
</script>
