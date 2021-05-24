<template>
  <form
    v-on:submit.prevent="mode=='new'? add(): update()"
    class="container"
  >
    <!-- group selection -->
    <div
      class="form-group row"
      v-if="groups.length>0"
    >
      <label
        for="group_id"
        class="col-sm-4"
      >{{ $t('admin.group')}}: <span class="text-danger">*</span></label>
      <select
        v-model="item.group_id"
        class="form-control col-sm-8"
        required
      >
        <option
          v-for="group in groups"
          :key="group.id"
          :value="group.id"
        >{{ group.name }}</option>
      </select>
    </div>

    <!-- translable fields -->
    <ul class="nav nav-tabs">
      <li
        v-for="(locale, index) of locales"
        :key="locale"
      >
        <a
          data-toggle="tab"
          :href="'#tab_' + locale"
          class="nav-link text-uppercase"
          :class="{ active : index==0 }"
        >{{ locale }}</a>
      </li>
    </ul>

    <div class="tab-content">
      <div
        v-for="(locale, index) of locales"
        :key="locale"
        :id="'tab_' + locale"
        class="tab-pane fade"
        :class="{ show : index==0, active: index==0}"
      >
        <!-- title -->
        <div class="form-group">
          <label
            class="required"
            :for="'title_' + locale"
          >{{ $t('admin.title', locale) }}</label>
          <input
            class="form-control"
            type="text"
            :id="'title_' + locale"
            v-model="item['title_' + locale]"
          >
        </div>

        <!-- introduction -->
        <div class="form-group">
          <label :for="'introduction_'+locale">{{ $t('admin.introduction', locale) }}
          </label>
          <input
            :id="'introduction_'+locale"
            type="text"
            class="form-control"
            v-model="item['introduction_'+locale]"
          >
        </div>

        <!-- content -->
        <div class="form-group">
          <label :for="'content_'+locale">{{ $t('admin.content', locale) }}</label>
          <tip-tap v-model="item['content_'+locale]" />
        </div>
      </div>
    </div>

    <!-- submit button -->
    <div class="text-right">
      <button
        type="submit"
        class="btn btn-primary"
      >{{ $t('admin.save') }}</button>
    </div>

  </form>
</template>

<script>
export default {
  props: ['groups', 'mode', 'item_edit', 'locales', 'translatables'],
  data() {
    return {
      item: {},
    }
  },
  created() {
    if (this.mode == 'edit') {
      this.item = this.item_edit;
      for (const translatable of this.translatables) {
        for (const locale of this.locales) {
          if (this.item[translatable] && this.item[translatable][locale]){
            this.item[translatable + '_' + locale] = this.item[translatable][locale]; 
          }
        }
      }
    }
  },
  methods: {
    add() {
      console.log(this.item);
      axios.post('/pages', this.item)
        .then(({ data }) => {
          console.log(data);
          this.$emit('created', data.page);
          flash(this.$t('admin.added_success'));
        })
        .catch(error => {
          flash(error.response && error.response.data, 'danger');
        });
    },

    update() {
      console.log(this.item);
      axios.patch('/pages/' + this.item.id, this.item)
        .then(({ data }) => {
          console.log(data);
          this.$emit('updated', data.page);
          flash(this.$t('admin.updated_success'));
        }).catch(error => {
          flash(error.response.data, 'danger');
        });

    },
  }
};
</script>