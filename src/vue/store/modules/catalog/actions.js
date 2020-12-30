
export default {
    init({dispatch, commit}, id ){
      commit("setFilterGroupId", id);
      commit("setGroupId", id);
    },
  
    fetchCatalogGroups({ commit }) {
      const link = api.catalogGroups;
      fetch(link).then(response => {
        return response.json();
      })
        .then(result => {
          let items = result._embedded.items
          commit('setCatalogGroups', items);
        });
    },
    fetchFilters({ commit, getters }) {

      const id = localStorage.getItem("filterId");
      const origin = document.location.origin;
      const link = `${origin}/api/catalog/groups`;
      fetch(link)
        .then(response => response.json())
        .then(result => {
          // console.log(result);
          let items = result._embedded.items
          console.log(items);
  
          commit('setCatalogFilters', items)
          // this.filters = result._embedded.items;
          // this.items.forEach(element => {
          //     element.count = 1;
          //     this.items.push(element);
          // });
        });
    }
  };