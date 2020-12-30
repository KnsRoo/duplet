var Manufacturers = function() { }

Manufacturers.prototype.moveTo = function(href) {
    layout.send({
        url: href,
        target: sf('.layout-wrapper')[0],
        method: 'GET',
        after: function() {

            sf.changeURL(href);

            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });

        }
    });
}

Manufacturers.prototype.post = function(form) {

    var fd = new FormData(form);

    layout.send({
        url: form.action,
        data: fd,
        target: sf('.layout-wrapper')[0],
        method: 'POST',
        after: function() {

            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });

        }
    });
}

Manufacturers.prototype.clearPicture = function(id) {

    var fd = new FormData();
    fd.append('_method', 'PUT');

    layout.send({
        url: '/admin/manufacturers/clear-picture/' + id,
        method: 'POST',
        data: fd,
    });

}

Manufacturers.prototype.changeImage = function(elem) {

    var url = '';

    if (elem.hasAttribute('ext') && elem.hasAttribute('value')) {

        var id = elem.getAttribute('value');
        var ext = elem.getAttribute('ext');
        var imgName = id + '-150x150' + '.' + ext;
        var url = 'url(/data/' + imgName + ')';

    }

    sf('.wrapper-edit-box .picture').css('background-image', url);

}

Manufacturers.prototype.getPreview = function (elem, size) {

    var id = elem.getAttribute('value');

    if (!id) sf('.wrapper-edit-box .picture').css('background-image', '');

    else layout.send({

        url: '/admin/files/get-preview-' + id + '?size=' + size,
        method: 'GET',
        after: function (res) {

            var preview = res.content ? 'url(' + res.content + ')' : '';
            sf('.wrapper-edit-box .picture').css('background-image', preview);

        }

    });

}

sf.ready(function() {

    manufacturers = new Manufacturers;

});
