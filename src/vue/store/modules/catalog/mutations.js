export default {
    setCatalogGroups(state, items) {
      state.catalogGroups = items
    },
    setCatalogFilters(state, items) {
      state.filters = items
    },
    setFilterGroupId(state, id) {
      state.filterGroupId = id;
    },
    setGroupId(state, id) {
      state.groupId = id;
    },
  };