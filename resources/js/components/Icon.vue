<template>
  <div class="icon" :class="[`icon--${name}`, `icon--${size}`, { 'has-align-fix': fixAlign }]">
    <img :src="require(`../icons/svg/${name}.svg`)" :alt="`${name}`" />
  </div>
</template>

<script>
/* tiptap icons */ 
export default {
  props: {
    name: {
      type: String,
      default: '',
    },
    size: {
      type: String,
      default: 'normal',
    },
    modifier: {
      type: Object,
      default: null,
    },
    fixAlign: {
      type: Boolean,
      default: false,
    },
  },
  // created: function() {}
}
</script>