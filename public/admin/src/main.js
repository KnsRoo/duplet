import "core-js/stable";
import "regenerator-runtime/runtime";

import AdminCatalog from '@websm/admin-catalog-vue'

document.querySelectorAll('[id="props-app"]')
    .forEach(el => new AdminCatalog(el));
