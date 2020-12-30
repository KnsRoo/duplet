

class CatalogView {

    async getAdditionalPropertiesHtml(data) {

        let ht = '';

        for(let field in data) {

            ht += await this._renderAdditionalProperty(field, data[field]);

        }

        return ht;

    }

    async _renderAdditionalProperty(field, data) {

        switch(data['type']) {

            case 'Products': 
                return await this._renderProductsProperty(field, data);
            case 'Products Single': 
                return await this._renderProductsProperty(field, data);
            case 'Images': 
                return await this._renderImagesProperty(field, data);
            default: return '';

        }

    }

    async _renderProductsProperty(field, data) {

        let value = JSON.stringify(data['value']);
        let order = data['order'];
        let type = data['type'];

        let ht = '';
        ht += `<input type='text' hidden data-eav data-field='${field}' value='${JSON.stringify(value)}'>`;

        ht += `
<div>
    <div>${type}: ${field}</div>
    <input class='sm-input'
        data-field='${field}'
        type="text"
        value='${value}'
        onchange='catalog.updateExtraProperty(this);'>
    <button class="rm-button"
        data-field='${field}'
        onclick="catalog.removeExtraProperty(this); return false;">
        +
    </button>
</div>`;

        ht = `
<div class='extra-type'>
    <div class='extra-type-title'>${type}: ${field}</div>
    <div class='extra-type-content type-product-single'>
        ${ht}
    </div>
</div>`;

        return ht;

    }

    async _renderImagesProperty(field, data) {

        let productId = document.querySelector(`[id='product-id']`).value;

        let ht = '';
        let value = data['value'];
        if(!Array.isArray(value)) value = [];

        let order = data['order'];
        let type = data['type'];

        ht += `<input type='text' hidden data-eav data-field='${field}' value='${JSON.stringify(value)}'>`;

        let cnt = 0;

        for(let imageId of value) {

            let options = {
                credentials: 'include',
                method : 'GET',
            };

            let style = '';

            if(imageId) {

                let response = await fetch(`/admin/files/files/${imageId}/href`, options);

                response = await response.json();

                if(response) style = ` style='background-image: url("${response}")'`;

            }

            let id = this.hash(`${cnt}${field}`);

            ht += `
<div class='one-image'>

    <input id='img-${id}' value='${imageId}' hidden>

    <div class='imgbg'${style}></div>
    <div class='control'>
        <div class='append sm-dialog'>
            <input id="ref-${id}" class="dialog-trigger" type="checkbox">
            <label for="ref-${id}">
                <div class='sm-action-button set-image' title="установить изображение"></div>
            </label>

            <div class="frame">
                <div class="head">
                    Заголовок
                    <label for="ref-${id}" class="closer">x</label>
                </div>
                <div class="content">
                    <div class="filesMin"
                        data-optional="true"
                        data-types="jpg,bmp,png,gif,svg"
                        data-checkable="true"
                        data-set-value="#img-${id}"
                        data-onchange="catalog.updateImage('${field}', sf('#img-${id}')[0].value, ${cnt}); refreshExtraProperties();"></div>
                </div>
            </div>

        </div>
        <div class='remove'>
            <input class='image-remove' hidden>
            <label for='remove-image'>
                <div class="sm-action-button rm-image"
                    title="удалить изображение"
                    onclick="catalog.removeImage('${field}', ${cnt}); refreshExtraProperties();"></div>
            </label>
        </div>
    </div>
</div>`;

            cnt++;

        }

        ht += `
<div class='one-butt'>
    <button class='sm-button square-butt add'
        onclick='catalog.appendExtraImage("${field}"); catalog.updateExtraProperty("${field}");'>
    </button>
</div>
<div class='one-butt'>
    <button class='rm-button big-font'
        data-field='${field}'
        onclick='catalog.removeExtraProperty(this);'>+
    </button>
</div>
`;

    ht = `
<div class='extra-type'>
    <div class='extra-type-title'>${type}: ${field}</div>
    <div class='flex-row'>
        ${ht}
    </div>
</div>
    `;

        return ht;

    }

    hash(str) {

        let hash = 0;

        if (str.length == 0) return hash;

        for (let i = 0; i < str.length; i++) {

            let char = str.charCodeAt(i);

            hash = ((hash<<5)-hash)+char;

            hash = hash & hash; // Convert to 32bit integer

        }

        return hash;

    }

    renderVue(field, data) {
    
    }

}
