import { mapGetters } from "vuex";

export default {
  data() {
    return {
      pathname: window.location.pathname,
      csrfToken: document.head.querySelector('meta[name="csrf-token"]').content,
      windowWidth: window.innerWidth,
      small: window.innerWidth < 768,
      development: process.env.NODE_ENV === 'development',
    };
  },
  computed: {
    settings() {
      return this.$store.state.settings.settings;
    },
    authUser() {
      return this.$store.state.auth.authUser;
    },
    // getters from vuex Auth
    ...mapGetters(["authRoleId", "signedIn", "authUserId", "isAdmin"]),
  },
  methods: {
    // for shopping cart, get pathname from photo.thumb_asset or photo.path 
    getPath(url_string) {
      const url = new URL(url_string);
      return url.pathname;
    },
    dateDisplay(date) {
      if (date) return moment(date, 'YYYY-MM-DD h:mm:ss').format('DD.MM.YYYY');
    },
    time_display(date) {
      if (date) return moment(date, 'YYYY-MM-DD h:mm:ss').format('hh:mm:ss');
    },
    priceDisplay(number) {
      return number.toFixed(2);
    },
    toFormData: function (obj) {
      var form_data = new FormData();
      for (var key in obj) {
        if (obj[key] !== null && key !== 'image') form_data.append(key, obj[key]);
      }
      return form_data;
    },
    nullData: function (obj) {
      for (var key in obj) {
        obj[key] = null;
      }
      return obj;
    },
    ucfirst(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },

    color_switch(active) {
      return active ? 'success' : 'default'
    },

    updateAttribute(url, attribute, value) {
      axios.patch(url, {
        [attribute]: value
      });
    },

    toggleAvailable(url, available) {
      axios.patch(url, {
        'available': available
      });
    },

    // used in PageIndex.vue
    toggleField(url, field, value) {
      let Obj = {};
      Obj[field] = value;
      console.log(Obj);
      axios.patch(url, Obj);
    },

    arrayRemove(arr, value) {
      return arr.filter(function (ele) {
        return ele != value;
      });
    },

    isEmpty(obj) {
      for (var key in obj) {
        if (obj.hasOwnProperty(key))
          return false;
      }
      return true;
    },

    check_login(path) {
      if (window.App.signedIn) window.location.href = path;
      else this.$modal.show('login_modal');
    },

    getGroupName(id) {
      const elem = this.groups.find(elem => elem.id == id);
      if (elem) return elem.name;
    },

    getCategoryName(id) {
      var elem = this.categories.find(elem => elem.id == id);
      if (elem) return elem.name;
    },

    getAlbumName(id) {
      var elem = this.albums.find(elem => elem.id == id);
      if (elem) return elem.name;
    },

    getRoleName(id) {
      var elem = this.roles.find(elem => elem.id == id);
      if (elem) return elem.name;
    },

    getStateName(id) {
      var elem = this.states.find(elem => elem.id == id);
      if (elem) return elem.name;
    },

    redirect(redirectUrl) {
      window.location.replace(redirectUrl);
    },

    image_asset(folder, image) {
      return `/images/${folder}/${image}`;
    },

    // Degeorges%20Rapha%C3%ABl-6313.jpg
    thumb_asset(folder, image) {
      const path = decodeURI(image);
      return `/images/${folder}/thumb/${path}`;
    },

    array_select(data, keys) {
      const result = data.map(e => {
        const obj = {};
        keys.forEach(k => obj[k] = e[k])
        return obj;
      });
      return result;
    },
  }
}