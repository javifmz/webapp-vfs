<template>
  <a class="item" :data-value="dataValue">
    <slot></slot>
  </a>
</template>

<script>    
export default {
  name: 'DropdownOption',
  props: {
    value: {},
  },
  data () {
    return {
    dataValue: this.value == null ? '' : this.value
    }
  }
}
</script>
