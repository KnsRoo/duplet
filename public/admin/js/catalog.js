var Catalog = function() {
    this._dragPool = [];
}

Catalog.prototype.drag = function(data, node) {

    var self = this;
    var attrTitle = 'data-product';
    var fd = new FormData();
    var cat = node.getAttribute('data-id');

    if (data.message.type == 'product' && this._dragPool.length > 0) {
        this._dragPool.forEach( function(id) {
            fd.append('multi[]', id);
        })
    }

    for (var key in data.message)
        fd.append(key, data.message[key]);

    layout.send({
        url: '/admin/catalog' + '/update-cid/cid-' + cat + '/',
        data: fd,
        target: sf('.layout-wrapper')[0],
        method: 'POST',
        after: function() {

            self.initDND();

            CKEDITOR.replaceAll('ckeditor');
            sf('.filesMin').each( function() {
                FilesMin.bind(this, this.dataset);
            });

        }
    });

}

Catalog.prototype.closeEmemy = function(elem, enemyId) {
    if (elem.checked == true) {
        var enemyElem = sf('#' + enemyId)[0];
        enemyElem.checked == true ? enemyElem.checked = false : '';
    }
}

Catalog.prototype.getCatPreview = function (elem, size) {

    var id = elem.getAttribute('value');

    if (!id) sf('.cat-edit .cat-picture').css('background-image', '');

    else layout.send({

        url: '/admin/files/get-preview-' + id + '?size=' + size,
        method: 'GET',
        after: function (res) {

            var preview = res.content ? 'url(' + res.content + ')' : '';
            sf('.cat-edit .cat-picture').css('background-image', preview);

        }

    });

}

Catalog.prototype.getProduct = function (elem) {

    console.log(elem.getAttribute('dst-attribute'));
    let dst = elem.getAttribute('dst-attribute');


    let data = {
        dst: dst,
        method: 'GET',
        callback: (req) => {

            let data = JSON.parse(req.responseText);

            if (data.status == 'ok') {
                popup.insertData(data.content);
                popup.open();
            }

        },
        fallBack: () => {

            sf.alert('Проблема с подключением.', 'err');

        },
    }

    sf.ajax(data).send();

}

Catalog.prototype.getProductPreview = function (elem, size) {

    var id = elem.getAttribute('value');

    if (!id) sf('.product-edit .product-picture').css('background-image', '');

    else layout.send({

        url: '/admin/files/get-preview-' + id + '?size=' + size,
        method: 'GET',
        after: function (res) {

            var preview = res.content ? 'url(' + res.content + ')' : '';
            sf('.product-edit .product-picture').css('background-image', preview);

        }

    });

}

Catalog.prototype.updatePictureForProduct = function(elem, productId) {

    var pictureId = elem.getAttribute('value');

    var fd = new FormData;
    fd.append('picture', pictureId);
    fd.append('_method', 'PUT');

    layout.send({

        url: '/admin/catalog/update-picture/id-' + productId,
        target: sf('.layout-wrapper')[0],
        method: 'POST',
        data: fd,
        after: function() {

            catalog.drag();

            CKEDITOR.replaceAll('ckeditor');
            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });

        }
    });

}

Catalog.prototype._smartChangeImage = function(elem, productId) {

    var url = '';
    if (elem.hasAttribute('ext') && elem.hasAttribute('value')) {

        var id = elem.getAttribute('value');
        var ext = elem.getAttribute('ext');
        var imgName = id + '-250x250' + '.' + ext;
        var url = 'url(/data/' + imgName + ')';

    }

    sf('label[for="file-manager-toggler-' + productId + '"].image').css('background-image', url);

    layout.send({
        url: document.location.href + '/update-image/id-' + productId + '/image-' + id,
        target: sf('.layout-wrapper')[0],
        method: 'GET',
        after: function() {

            catalog.drag();

            CKEDITOR.replaceAll('ckeditor');
            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });

        }
    });

}

Catalog.prototype.deleteTag = function (id) {

    var fd = new FormData();
    fd.append('_method', 'DELETE');
    fd.append('tag_id', id);

    layout.send({
        url: '/admin/catalog/delete-tag',
        method: 'POST',
        data: fd,
        after: function (res) {

            if (res.status !== 'ok') {

                sf.alert(res.notify);
                return;

            }

            sf('.tag-' + id).each(function (node) {

                node.parentNode.removeChild(node);

            });

        }
    })

}

Catalog.prototype.deleteTag = function (id) {

    var fd = new FormData();
    fd.append('_method', 'DELETE');
    fd.append('tag_id', id);

    layout.send({
        url: '/admin/catalog/delete-tag',
        method: 'POST',
        data: fd,
        after: function (res) {

            if (res.status !== 'ok') {

                sf.alert(res.notify);
                return;

            }

            sf('.tag-' + id).each(function (node) {

                node.parentNode.removeChild(node);

            });

        }
    })

}

Catalog.prototype.moveTo = function(href) {

    var self = this;
    layout.send({
        url: href,
        target: sf('.layout-wrapper')[0],
        method: 'GET',
        after: function() {

            sf.changeURL(href);

            self.initDND();

            CKEDITOR.replaceAll('ckeditor');
            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });

        }
    });

}

Catalog.prototype.post = function(form) {

    for(var id in CKEDITOR.instances)
        CKEDITOR.instances[id].updateElement();

    var fd = new FormData(form);

    layout.send({
        url: form.action,
        data: fd,
        target: sf('.layout-wrapper')[0],
        method: 'POST',
        after: function() {

            CKEDITOR.replaceAll('ckeditor');
            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });

        }
    });
}

Catalog.prototype.addToDragCont = function(elem) {

    if (elem.checked)
        this._dragPool.push(elem.value);
    else {
        var index = this._dragPool.indexOf(elem.value);
        if (index > -1) {
            this._dragPool.splice(index, 1);
        }
    }

}

Catalog.prototype.initDND = function() {
    var t = document.querySelectorAll('[data-subscribe]');

    t.forEach( function(node) {
        new DSender(node);
    } );

    DChannel.init();
    DChannel.setHandler(['cat'], 'reciver-drop', catalog.drag.bind(catalog));
}

Catalog.prototype.getTab = function() {

    new Tabs('.tab');

}

sf.ready(function() {

    catalog = new Catalog;
    catalog.initDND();
    catalog.getTab();

});
