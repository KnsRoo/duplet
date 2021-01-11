import '../scss/news.scss';
import headerSearch from './global';
import News from '../vue/news';

document.addEventListener('DOMContentLoaded', () => {
    headerSearch()
    new News('#news');
})
