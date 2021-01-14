
export default {
    init({dispatch, commit}, id ){
      commit("setFilterGroupId", id);
      commit("setGroupId", id);
    },
  
    fetchCatalogGroups({ commit }, link) {
      fetch(link).then(response => {
        return response.json();
      })
        .then(result => {
          let items = result._embedded.items
          commit('setCatalogGroups', items);
        });
    },
  };