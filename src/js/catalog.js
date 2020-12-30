import '../scss/catalog.scss';
import Catalog from '../vue/catalog';
import headerSearch from './global';

document.addEventListener('DOMContentLoaded', () => {
  headerSearch()
})



document.addEventListener('DOMContentLoaded', () => {
  new Catalog('#catalog');
})
