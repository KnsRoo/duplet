var Gallery = function() {}

Gallery.prototype.drag = function() {

    sf.drag(
        function(event) {
            var event = event ? event : window.event;
            event.preventDefault();

            var dt = event.dataTransfer;
            var self = sf(this);
            var cat = self.attr('data-id');
            var product = dt.getData('text');

            layout.send({
                url: document.location.href + '/update-cid/id-' + product + '/cid-' + cat,
                target: sf('.layout-wrapper')[0],
                method: 'GET',
                after: function() {

                    gallery.drag();

                    CKEDITOR.replaceAll('ckeditor');
                    sf('.filesMin').each(function(){
                        FilesMin.bind(this, this.dataset);
                    });

                }
            });
        },
        sf('[data-class="drag-item"]'),
        sf('[data-class="drag-drop-place"]'),
        'data-link'
    );
}

Gallery.prototype.closeEmemy = function(elem, enemyId) {

    if (elem.checked == true) {
        var enemyElem = sf('#' + enemyId)[0];
        enemyElem.checked == true ? enemyElem.checked = false : '';
    }
}

Gallery.prototype.changeImage = function(elem) {

    var url = '';

    if (elem.hasAttribute('ext') && elem.hasAttribute('value')) {

        var id = elem.getAttribute('value');
        var ext = elem.getAttribute('ext');
        var imgName = id + '-150x150' + '.' + ext;
        var url = 'url(/data/' + imgName + ')';

    }

    sf('.module-data .edit-data .img-data .img').css('background-image', url);

}

Gallery.prototype.smartChangeImage = function(elem, productId) {

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

            gallery.drag();

            CKEDITOR.replaceAll('ckeditor');
            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });

        }
    });

}

Gallery.prototype.moveTo = function(href) {

    layout.send({
        url: href,
        target: sf('.layout-wrapper')[0],
        method: 'GET',
        after: function() {

            gallery.drag();
            sf.changeURL(href);

            CKEDITOR.replaceAll('ckeditor');
            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });

        }
    });
}

Gallery.prototype.post = function(form) {

    for(var id in CKEDITOR.instances)
        CKEDITOR.instances[id].updateElement();

    var fd = new FormData(form);
;
    layout.send({
        url: form.action,
        data: fd,
        target: sf('.layout-wrapper')[0],
        method: 'POST',
        after: function() {

            gallery.drag();

            CKEDITOR.replaceAll('ckeditor');
            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });

        }
    });
}


Gallery.prototype.getPreview = function (elem, size) {

    var id = elem.getAttribute('value');

    layout.send({

        url: '/admin/files/get-preview-' + id + '?size=' + size,
        method: 'GET',
        after: function (res) {

            var preview = res.content ? 'url(' + res.content + ')' : '';
            sf('.img-data.inline.relative .img').css('background-image', preview);

        }

    });

}

Gallery.prototype.changeLabel = function(elem) {
    var label = sf('label[for="' + elem.id + '"]');
    elem.files.length == 0 ? label.inner = 'Выбрать файл(-ы)' : '';
    elem.files.length == 1 ? label.inner = elem.files[0].name : '';
    elem.files.length > 1 ? label.inner = 'Кол-во выбранных файлов: ' + elem.files.length : '';
}

sf.ready(function() {

    gallery = new Gallery;
    gallery.drag();

});
