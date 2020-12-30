
var Interview = function() { }

Interview.prototype.drag = function() {
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

                    interview.drag();

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

Interview.prototype.moveTo = function(href) {
    layout.send({
        url: href,
        target: sf('.layout-wrapper')[0],
        method: 'GET',
        after: function() {

            interview.drag();
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

Interview.prototype.post = function(form) {
    for(var id in CKEDITOR.instances)
        CKEDITOR.instances[id].updateElement();

    var fd = new FormData(form);

    layout.send({
        url: form.action,
        data: fd,
        target: sf('.layout-wrapper')[0],
        method: 'POST',
        after: function() {

            interview.drag();

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



Interview.prototype.getPreview = function (elem, size) {

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

    interview = new Interview;
    interview.drag();

    sf('.wrapper-edit-box .correct').each(function (elem) {
        new Corrector(elem);
    });

    sf('.limit-chars').each(function (elem) {
        new LimitChars(elem);
    });

});
