import Vue from 'vue';
import Layout from './layout/layout.vue';
import store from '../store';




export default class Catalog {
  constructor(selector) {
    Vue.filter("fix-namber", function (value) {
      return Math.round(value * 100) / 100
    })
    const el = document.querySelector('#catalog');
    localStorage.setItem("filterId", el.getAttribute('data-filter-link'));

    new Vue({
      el: selector,
      render: h => h(Layout),
       store,
      async created() {
        this.$store.dispatch('catalog/init', el.getAttribute('data-filter-link'));
    }
    });
  }

}
