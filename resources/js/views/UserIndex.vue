<template>
  <div>
    <div class="d-flex mb-2">      
      <strong v-if="role_id==2">{{ $t('admin.admins')}}</strong>
      <strong v-if="role_id==3">{{ $t('admin.users')}}</strong>
      <div class="flex-right-parent ml-auto">
        <!-- search button -->
        <input
          type="text"
          v-model="search_input"
          placeholder="recherche"
          v-on:keyup.enter="fetch(1)"
          class="search-input"
        >
        <!-- add button -->
        <button
          class="btn btn-info btn-sm"
          @click="$modal.show('add_modal')"
        ><i class="fas fa-plus"></i></button>
      </div>
    </div>
    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>{{ $t('admin.email') }}</th>
          <template v-if="role_id==3">
            <th>{{ $t('admin.key_private') }}</th>
            <th>{{ $t('admin.key_public') }}</th>
            <th>{{ $t('admin.key_parent') }}</th>
          </template>
          <th>Active</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in items"
          :key="item.id"
        >
          <td>{{ item.id }}</td>
          <td>{{ item.email}}</td>
          <template v-if="role_id==3">
            <td>{{ item.key_private }}</td>
            <td>{{ item.key_public }}</td>
            <td>{{ item.key_parent }}</td>
          </template>
          <td>
            <switches
              v-model="item.active"
              :emit-on-mount="false"
              @input="updateAttribute(`/users/${item.id}`, 'active', $event)"
              theme="bootstrap"
              :color="color_switch(item.active)"
            ></switches>
          </td>
          <td>
            <i
              class="fas fa-edit text-info"
              @click="show_edit_modal(item)"
            ></i>
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
      @page_changed="fetch"
    ></paginator>

    <modal
      name="add_modal"
      width="900"
      height="auto"
      :scrollable="true"
    >
      <div class="card">
        <div class="card-header">
          <h4 v-if="role_id==2">{{ $t('admin.employee')}}</h4>
          <h4 v-if="role_id==3">{{ $t('admin.clients')}}</h4>
          <button
            class="btn btn-secondary btn-sm ml-auto"
            @click="$modal.hide('add_modal')"
          >&times;</button>
        </div>
        <div class="card-body">
          <user-form
            mode="new"
            @created="add"
            :role_id=role_id
          ></user-form>
        </div>
      </div>
    </modal>

    <modal
      name="edit_modal"
      width="900"
      height="auto"
      :scrollable="true"
      @before-open="(event)=>{in_edit_modal=event.params.item}"
    >
      <div class="card">
        <div class="card-header">
          <h4 v-if="role_id==2">{{ $t('admin.employee')}}</h4>
          <h4 v-if="role_id==3">{{ $t('admin.clients')}}</h4>
          <button
            class="btn btn-secondary btn-sm ml-auto"
            @click="$modal.hide('edit_modal')"
          >&times;</button>
        </div>
        <div class="card-body">
          <user-form
            mode="edit"
            @updated="update"
            :item_edit="in_edit_modal"
            :role_id=role_id
          ></user-form>
        </div>
      </div>
    </modal>

    <modal
      name="delete_modal"
      width="900"
      height="auto"
      :scrollable="true"
    >
      <div class="modal-container">
        <p>{{ $t('admin.delete_confirm')}}</p>
        <div class="text-right">
          <button
            class="btn btn-info text-white"
            @click="delete_item"
          >Valider</button>
        </div>
      </div>
    </modal>

    <modal
      name="groups_modal"
      width="900"
      height="auto"
      :scrollable="true"
      @before-open="(event)=>{in_edit_modal=event.params.item}"
    >
      <div
        class="card"
        v-if="in_edit_modal"
        style="min-height: 60vh;"
      >
        <div class="card-header">
          <h3>{{ $t('admin.groups')}}</h3>
          <button
            class="btn btn-secondary btn-sm ml-auto"
            @click="$modal.hide('groups_modal')"
          >&times;</button>
        </div>
        <div class="card-body">
          <user-groups
            @posted="update_groups"
            :user_id=in_edit_modal.id
            :groups="groups"
          />
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
import collection from '../mixins/collection';
export default {
  props: ['groups', 'role_id', 'id'],
  mixins: [collection],
  data() {
    return {
      path: '/users',
    };
  },
  created() {
    console.log(this.groups);
  },
  methods: {
    url(page) {
      if (!page) {
        let query = location.search.match(/page=(\d+)/);
        page = query ? query[1] : 1;
      }
      var url = '/api/users' + `?page=${page}`;
      if (this.role_id) url += `&role_id=${this.role_id}`;
      if (this.search_input.length > 0) url += `&search=${this.search_input}`;
      return url;
    },
    getGroupName(group_id) {
      if (group_id > 0) var group = this.groups.find(elem => elem.id == group_id);
      if (group) return group.name;
    },
    // groups
    update_groups(data) {
      this.fetch();
    }
  }
}
</script>