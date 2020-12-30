var Pages = function() { }

Pages.prototype.drag = function() {
    sf.drag(
        function(event) {
            var event = event ? event : window.event;
            event.preventDefault();

            var dt = event.dataTransfer;
            var self = sf(this);
            var cid = self.attr('data-id');
            var page = dt.getData('text');

            layout.send({
                url: document.location.href + '/update-cid/id-' + page + '/cid-' + cid,
                target: sf('.layout-wrapper')[0],
                method: 'GET',
                after: function() {

                    pages.drag();

                    sf('.wrapper-edit-box .correct').each(function (elem) {
                        new Corrector(elem);
                    });

                    sf('.limit-chars').each(function (elem) {
                        new LimitChars(elem);
                    });

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

Pages.prototype.moveTo = function(href) {
    layout.send({
        url: href,
        target: sf('.layout-wrapper')[0],
        method: 'GET',
        after: function() {

            pages.drag();
            sf.changeURL(href);

            CKEDITOR.replaceAll('ckeditor');

            sf('.wrapper-edit-box .correct').each(function (elem) {
                new Corrector(elem);
            });

            sf('.limit-chars').each(function (elem) {
                new LimitChars(elem);
            });

            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });
        }
    });
}

Pages.prototype.post = function(form) {
    for(var id in CKEDITOR.instances)
        CKEDITOR.instances[id].updateElement();

    var fd = new FormData(form);

    layout.send({
        url: form.action,
        data: fd,
        target: sf('.layout-wrapper')[0],
        method: 'POST',
        after: function() {

            pages.drag();

            sf('.wrapper-edit-box .correct').each(function (elem) {
                new Corrector(elem);
            });

            sf('.limit-chars').each(function (elem) {
                new LimitChars(elem);
            });

            CKEDITOR.replaceAll('ckeditor');
            sf('.filesMin').each(function(){
                FilesMin.bind(this, this.dataset);
            });

        }
    });
}

Pages.prototype.clearPicture = function(id) {

    var fd = new FormData();
    fd.append('_method', 'PUT');

    layout.send({
        url: '/admin/pages/clear-picture/' + id,
        method: 'POST',
        data: fd,
    });

}

Pages.prototype.changeImage = function(elem) {

    var url = '';

    if (elem.hasAttribute('ext') && elem.hasAttribute('value')) {

        var id = elem.getAttribute('value');
        var ext = elem.getAttribute('ext');
        var imgName = id + '-150x150' + '.' + ext;
        var url = 'url(/data/' + imgName + ')';

    }

    sf('.wrapper-edit-box .picture').css('background-image', url);

}

Pages.prototype.getPreview = function (elem, size) {

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

Pages.prototype.deleteTag = function (id) {

    var fd = new FormData();
    fd.append('_method', 'DELETE');
    fd.append('tag_id', id);

    layout.send({
        url: '/admin/pages/delete-tag',
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

Pages.prototype.getTab = function() {

    new Tabs('.tab');
}

sf.ready(function() {

    pages = new Pages;
    pages.drag();
    pages.getTab();

    sf('.wrapper-edit-box .correct').each(function (elem) {
        new Corrector(elem);
    });

    sf('.limit-chars').each(function (elem) {
        new LimitChars(elem);
    });

});
