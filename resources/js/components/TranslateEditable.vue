<template>
  <div :class="className">
    <div
      class="edit-area"
      v-if="isEditMode"
    >
      <form @submit="toggleEdit">
        <textarea
          class="form-control"
          v-if="multi"
          v-model="text"
        ></textarea>
        <input
          type="text"
          v-if="!multi"
          v-model="text"
        />
        <button
          class="btn btn-light btn-sm"
          type="submit"
        ><i class="fas fa-check"></i></button>
        <button
          class="btn btn-light btn-sm"
          @click="isEdit=false;"
        ><i class="fas fa-times"></i></button>
      </form>
    </div>
    <div
      class="text-area pointer"
      v-if="!isEditMode"
      @click="toggleEdit"
    >
      <template v-if="text">{{text}}</template>
      <div
        style="display: inline-block; width:20px;height:20px;"
        v-else
      ></div>
    </div>
  </div>
</template>

<script>
/**
 * The text-editable component is used to edit text
 * 
 * Input:
 * value: A string value
 * is-edit: A boolean indicating whether or not the component is in EDIT mode
 * class-name: A string containing class names to pass to the component DIV
 * 
 */
export default {
  name: 'TranslateEditable',
  props: ['value', 'is-edit', 'class-name', 'multi', 'name', 'group'],
  data() {
    return {
      text: this.value,
      isEditMode: this.isEdit
    }
  },
  methods: {
    toggleEdit(e) {
      e.preventDefault();
      this.isEditMode = !this.isEditMode;
      if (!this.isEditMode && this.text !== this.value) {
        this.onSubmit(this.text)
      }
    },
    onSubmit() {
      axios.post('/translations/edit/' + this.group, { 'name': this.name, value: this.text });
    }
  }
}
</script>