import '../scss/catalog.scss';
import Catalog from '../vue/catalog';
import headerSearch from './global';

document.addEventListener('DOMContentLoaded', () => {
  headerSearch()
  new Catalog('#catalog');
})
