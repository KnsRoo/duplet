var Eav = function() {}

Eav.prototype.getEavPicture = function (elem, size, eavType) {

    var id = elem.getAttribute('value');

    var previewBlock = sf('.product-edit .product-picture-' + eavType);

    if (!id) previewBlock.css('background-image', '');

    else layout.send({

        url: '/admin/files/get-preview-' + id + '?size=' + size,
        method: 'GET',
        after: function (res) {

            var preview = res.content ? 'url(' + res.content + ')' : '';
            previewBlock.css('background-image', preview);

        }

    });

}

sf.ready(function() {

    eav = new Eav;

});
