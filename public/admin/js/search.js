'use strict';

class Search {

    getResults(query = "") {

        searchView.hideResults();
        if (query.length < 3) return;

        fetch(`/admin/search/api/get-results?query=${query}`, {
            method: 'GET',
            cache: 'force-cache',
            credentials: 'same-origin',
        })
            .then(res => {

                if (res.status === 200) {

                    return res.json();

                } else {

                    throw new Error(res.statusText);

                }

            })
            .then(res => {

                if (!res.length) return;
                let items = res.slice(0, 10).map(searchView.getProductItem);
                searchView.showResults(items);

            })
            .catch((e) => {

                searchView.hideResults();
                console.log(e);

            });

    }

}

const search = new Search;
