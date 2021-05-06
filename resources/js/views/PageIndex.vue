<template>
  <div>
    <div class="d-flex mb-2">
      <strong>{{ $t('admin.pages')}}</strong>
      <div class="flex-right-parent ml-auto">
        <!-- group selection -->
        <v-select
          v-model="group"
          :options="groups"
          label="name"
          placeholder="Groupe"
          @input="fetch(1)"
          v-if="groups.length>0"
        />

        <input
          type="text"
          v-model="search_input"
          placeholder="recherche"
          v-on:keyup.enter="fetch(1)"
          class="search-input"
        >
        <button
          class="btn btn-info btn-sm"
          @click="show_add_modal()"
        ><i class="fas fa-plus"></i></button>
      </div>
    </div>

    <table class="table table-hover table-layout-fixed">
      <thead>
        <tr>
          <th>ID</th>
          <th>{{ $t('admin.title')}}</th>
          <th>{{ $t('admin.active')}}</th>
          <th>{{ $t('admin.footer_active')}}</th>
          <th v-if="!isEmpty(groups)">{{ $t('admin.group')}}</th>
          <th>{{ $t('admin.action')}}</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in items"
          :key="item.id"
        >
          <td>{{ item.id }}</td>
          <td>{{ item.title}}</td>
          <td>
            <switches
              v-model="item.active"
              :emit-on-mount="false"
              @input="toggleField('/pages/' + item.id, 'active',item.active)"
              theme="bootstrap"
              :color="color_switch(item.active)"
            ></switches>
          </td>
          <td>
            <switches
              v-model="item.footer"
              :emit-on-mount="false"
              @input="toggleField('/pages/' + item.id, 'footer', item.footer)"
              theme="bootstrap"
              :color="color_switch(item.footer)"
            ></switches>
          </td>
          <td v-if="!isEmpty(groups)"> <span v-if=item.group>{{ item.group.name}}</span></td>
          <td>
            <i
              class="fas fa-edit text-primary"
              @click="show_edit_modal(item)"
            ></i>
            <i
              class="fas fa-trash-alt text-danger"
              @click="show_delete_modal(item, index)"
            ></i>
            <a
              :href=" '/pages/'+item.slug"
              v-if='item.slug'
            ><i class="fas fa-eye text-indigo"></i> </a>
          </td>
        </tr>
      </tbody>
    </table>
    <paginator
      :dataSet="dataSet"
      @page_changed="fetch"
    ></paginator>

    <modal
      name="add_modal"
      :width=1200
      height="auto"
      :scrollable="true"
    >
      <div class="card">
        <div class="card-header">
          <h3>Nouvelle Page</h3>
          <button
            class="btn btn-secondary btn-sm ml-auto"
            @click="$modal.hide('add_modal')"
          >&times;</button>
        </div>
        <div class="card-body">
          <page-form
            mode="new"
            @created="add"
            :groups=groups
            :locales=locales
          />
        </div>
      </div>
    </modal>

    <modal
      name="edit_modal"
      :width=1200
      height="auto"
      :scrollable="true"
      @before-open="(event)=>{in_edit_modal=event.params.item}"
    >
      <div class="card">
        <div class="card-header">
          <h3>Modifier Page</h3>
          <button
            class="btn btn-secondary btn-sm ml-auto"
            @click="$modal.hide('edit_modal')"
          >&times;</button>
        </div>
        <div class="card-body">
          <page-form
            mode="edit"
            @updated="update"
            :item_edit="in_edit_modal"
            :groups=groups
            :locales=locales
            :translatables=translatables
          />
        </div>
      </div>
    </modal>

    <modal
      name="delete_modal"
      :width=900
      height="auto"
      :scrollable="true"
    >
      <div class="modal-container">
        <p>{{ $t('admin.delete_confirm')}}</p>
        <div class="text-right">
          <button
            class="btn btn-info text-white"
            @click="delete_item"
          >{{ $t('admin.validate') }}</button>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
import collection from '../mixins/collection';
export default {
  mixins: [collection],
  props: ['groups', 'locales'],
  data() {
    return {
      path: '/pages',
      group: null,
      translatables: ['title', 'introduction', 'content'],   // Page Model
    };
  },
  created() {
    this.fetch();
    // console.log('groups',this.groups); 
  },
  methods: {
    update(data) {
      let item = this.items.find(elem => elem.id === data.id);
      if (item) {
        item.title = data.title; 
        item.introduction  = data.introduction; 
        item.content = data.content; 
      }
      this.$modal.hide('edit_modal');
    },
  }
}
</script>