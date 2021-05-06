const module = {
  state: {
    settings: JSON.parse(window.localStorage.getItem("settings")),
  },
  mutations: {
    settingsMutation(state, payload) {
      state.settings = payload;
      window.localStorage.setItem("settings", JSON.stringify(payload));
      window.sessionStorage.setItem("settingsUpdated", Date.now());
    },
    updateSetting(state, payload) {
      state.settings[payload.key] = payload.value;
      window.sessionStorage.removeItem("settingsUpdated");
      console.log('updateSetting',state.settings); 
    }
  },
  actions: {
    async fetchSettings({ commit }, payload) {
      console.log("fetching settings"); 
      return new Promise((resolve, reject) => {
        axios.get("/api/list/settings", payload)
          .then(({ data }) => {
            commit("settingsMutation", data);
            resolve(data);
          })
          .catch(error => {
            reject(error);
          });
      })
    },

    // {key: key, value: value}
    async updateSetting({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios.patch('/settings/update', payload)
          .then(({ data }) => {
            commit("updateSetting", data);
            resolve(data);
          }).catch(error => {
            reject(error);
          });
      })
    }

  },
  getters: {
    // not used
    get_setting: state => (key) => {
      return state.settings[key];
    }
  }
}

export default module