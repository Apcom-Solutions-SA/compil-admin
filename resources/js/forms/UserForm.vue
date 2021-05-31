<template>
  <form v-on:submit.prevent="mode=='new'?add():update()">
    <div class="form-group row">
      <label
        for="email"
        class="col-sm-3"
      >{{ $t('admin.email') }} <span class="text-danger">*</span></label>
      <input
        id="email"
        type="text"
        class="form-control col-sm-7"
        v-model="item.email"
        required
      >
    </div>

    <template v-if="role_id==1">
      <div class="form-group row">
        <label
          for="password"
          class="col-sm-3"
        >{{ $t('admin.password') }}</label>
        <input
          id="password"
          type="password"
          class="form-control col-sm-7"
          v-model="item.password"
        >
      </div>

      <div class="form-group row">
        <label
          for="password_confirmation"
          class="col-sm-3"
        >{{ $t('admin.password_confirm') }}</label>
        <input
          id="password_confirmation"
          type="password"
          class="form-control col-sm-7"
          v-model="item.password_confirmation"
        >
      </div>
    </template>

    <template v-if="role_id==3">
      <div class="form-group row">
        <label
          for="key_private"
          class="col-sm-3"
        >{{ $t('admin.key_private') }}</label>
        <input
          id="key_private"
          type="text"
          class="form-control col-sm-7"
          v-model="item.key_private"
        >
      </div>

      <div class="form-group row">
        <label
          for="key_public"
          class="col-sm-3"
        >{{ $t('admin.key_public') }}</label>
        <input
          id="key_public"
          type="text"
          class="form-control col-sm-7"
          v-model="item.key_public"
        >
      </div>

      <div class="form-group row">
        <label
          for="key_parent"
          class="col-sm-3"
        >{{ $t('admin.key_parent') }}</label>
        <input
          id="key_parent"
          type="text"
          class="form-control col-sm-7"
          v-model="item.key_parent"
        >
      </div>
    </template>

    <div class="text-right">
      <button
        type="submit"
        class="btn btn-info text-white"
      >Valider</button>
    </div>
  </form>
</template>

<script>
export default {
  props: ['mode', 'item_edit', 'role_id'],
  data() {
    return {
      item: {}
    }
  },
  created() {
    if (this.mode == 'edit') {
      this.item = this.item_edit;
    }
  },
  methods: {
    add() {
      if (this.role_id > 0) this.item.role_id = this.role_id;
      axios.post('/users', this.item)
        .then(({ data }) => {
          this.$emit('created', data.user);
          flash(data.message);
        })
        .catch(error => {
          flash(error.response && error.response.data, 'danger');
        });
    },

    update() {
      axios.patch('/users/' + this.item.id, this.item)
        .then(({ data }) => {
          this.$emit('updated', data.user);
          flash(data.message);
        })
        .catch(error => {
          flash(error.response && error.response.data, 'danger');
        });
    }
  }
};
</script>