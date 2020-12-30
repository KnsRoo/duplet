'use strict';

class SearchView {

    constructor() {

        this._popup = document.querySelector('#search-results');

    }

    getProductItem(item) {

        let { title, price, ref, picture, } = item;

        let style = '';
        if (picture) {

            style = `background-image: url(${picture});`;

        }

        return `
            <a href="${ref}" class="serch-item">
                <div class="picture" style="${style}"></div>
                <div class="title">${title}</div>
                <div class="price">${price} руб.</div>
            </a>
        `;

    }

    showResults(results = []) {

        let popup = this._popup;

        popup.innerHTML = results
            .reduce((perv, current) => {

                return `${perv} ${current}`;

            });

        popup.classList.add('active');
        popup.classList.remove('hidden');

    }

    hideResults() {

        let popup = this._popup;

        popup.innerHTML = '';
        popup.classList.remove('active');
        popup.classList.add('hidden');

    }

}

const searchView = new SearchView;
