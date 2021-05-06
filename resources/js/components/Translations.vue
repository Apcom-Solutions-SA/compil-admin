<template>
  <table class="table">
    <thead>
      <tr>
        <th width="15%">{{ $t('translation.key')}}</th>
        <th v-for="locale in locales" :key="locale">{{ locale}}</th>
        <th v-if="delete_enabled">&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="(translation, key) in items"
        :key="key"
      >
        <td>{{ key }}</td>
        <td v-for="locale in locales" :key="locale">
          <translate-editable
            :value="translation[locale]? translation[locale].value: ''"
            :class-name="className(translation[locale], locale)"
            :is-edit="false"
            :multi="true"
            :name=" locale + '|' + key "
            :group="group"
          >
          </translate-editable>
        </td>
        <td v-if="delete_enabled">
          <span
            class="fa fa-trash"
            @click="show_delete_modal(key)"
          ></span>
        </td>
      </tr>
    </tbody>
    <modal
      name="delete_modal"
      width="900"
      height="auto"
    >
      <div class="modal-container">
        <p>{{ $t('translation.confirm_delete_key') + ' <' + key_delete + '> ' }}</p>
        <div class="text-right">
          <button
            class="btn btn-primary"
            @click="delete_item"
          >{{ $t('front.confirm') }}</button>
        </div>
      </div>
    </modal>
  </table>
</template>

<script>
export default {
  props: ['locales', 'delete_enabled', 'translations', 'group'],
  data() {
    return {
      items: [],
      key_delete: null,
    }
  },
  created() {
    this.items = this.translations;
    console.log('created items', this.items);
  },
  methods: {
    className(t, locale) {
      let status = t === undefined ? 0 : t.status;
      return 'text-editable status-' + status + 'locale-' + locale;
    },
    // delete
    show_delete_modal(key) {
      this.key_delete = key;
      this.$modal.show('delete_modal');
    },
    hide_delete_modal() {
      this.$modal.hide('delete_modal');
    },
    delete_item() {
      if (this.key_delete) {
        var group = this.group;
        var key = this.key_delete;
        axios.post(`/translations/delete/${group}/${key}`).then(() => {
          delete this.items[this.key_delete];
          this.key_delete = null;
          this.hide_delete_modal();
        });
      }
    },
  }
}
</script>