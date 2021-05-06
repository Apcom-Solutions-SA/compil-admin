const auth = {
  state: {
    authUser: window.App.authUser  // same with server session
  },
  mutations: {
    authUserMutation(state, payload) {
      state.authUser = payload;
      window.localStorage.setItem("authUser", JSON.stringify(payload)); // this is useful for spa
    },
    logoutMutation(state) {
      state.authUser = null;
      window.localStorage.removeItem("authUser");  // this is useful for spa
    },
  },
  actions: {
    async registerAction({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios.post("/register", payload)
          .then(({ data }) => {
            if (data.user) commit("authUserMutation", data.user);
            resolve(data);
          })
          .catch(error => {
            reject(error);
          });
      })
    },
    async loginAction({ commit }, payload) {
      return new Promise((resolve, reject) => {
        axios.post("/login", payload)
          .then(({ data }) => {
            console.log('loginAction', data); 
            if (data.user) commit("authUserMutation", data.user);
            resolve(data);
          })
          .catch(error => {
            reject(error);
          });
      })
    },
    async logoutAction({ state, commit }) {
      if (!state.authUser) window.location.replace("/login");
      return new Promise((resolve, reject) => {
        axios.post("/logout").then(({ data }) => {
          commit("logoutMutation");
          resolve(data);
        })
          .catch(error => {
            reject(error);
          })
      })

    },
    async fetchAuthUserInfo({ state, commit }) {
      if (!state.authUser) return;
      return new Promise((resolve, reject) => {
        axios.get("/api/users/" + state.authUser.id)
          .then(({ data }) => {
            // console.log(data);                    
            if (data) commit("authUserMutation", data);
            window.sessionStorage.setItem("authUserUpdated", Date.now());    // window.sessionStorage.getItem("authUserUpdated")  1601982016864                
            resolve(data);
          })
          .catch(error => {
            reject(error);
          });
      })
    }
  },
  getters: {
    signedIn: state => !!state.authUser,
    authRoleId: (state) => {
      if (state.authUser) return state.authUser.role_id;
    },
    authUserId: (state) => {
      if (state.authUser) return state.authUser.id;
    },
    isAdmin: (state) => {
      if (state.authUser) return state.authUser.role_id === 1;
    },
  }
}


export default auth