<template>
  <div>
    <div class="d-flex mb-2">
      <strong>{{ $t('admin.notes')}}</strong>
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
      </div>
    </div>

    <table class="table table-hover table-layout-fixed">
      <thead>
        <tr>
          <th>ID</th>
          <th>{{ $t('admin.reference')}}</th>
          <th>{{ $t('admin.title')}}</th>
          <th>{{ $t('admin.introduction')}}</th>
          <th>{{ $t('admin.tags')}}</th>
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
          <td>{{ item.reference }}</td>
          <td>{{ item.title}}</td>
          <td>{{ item.introduction }}</td>
          <td>{{ item.tags }}</td>
          <td v-if="!isEmpty(groups)"> <span v-if=item.group>{{ item.group.name}}</span></td>
          <td>
            <i
              class="fas fa-trash-alt text-danger"
              @click="show_delete_modal(item, index)"
            ></i>
          </td>
        </tr>
      </tbody>
    </table>
    <paginator
      :dataSet="dataSet"
      @note_changed="fetch"
    ></paginator>   

    <modal
      name="delete_modal"
      :width=900
      height="auto"
      :scrollable="true"
    >
      <div class="p-3">
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
      path: '/notes',
      group: null,
    };
  },
  created() {
    this.fetch();
    // console.log('groups',this.groups); 
  },
  methods: {
  }
}
</script>