var Files = function() {}

Files.prototype.drag = function() {
	sf.drag(
		function(event) {

			var event = event ? event : window.event;
			event.preventDefault();

			var dt = event.dataTransfer;
			var self = sf(this);
			var folder = self.attr('data-id');
			var file = dt.getData('text');

			var fd = new FormData;
			fd.append('_method', 'PUT');

			layout.send({
				url: 'files/update-cid/id-' + file + '/cid-' + folder,
				target: sf('.layout-wrapper')[0],
				data: fd,
				method: 'POST',
				after: function() {

					myfiles.drag();

				}
			});

		},
		sf('[data-class="drag-item"]'),
		sf('[data-class="drag-drop-place"]'),
		'data-link'
	);
}

Files.prototype.changeLabel = function(elem) {
	var label = sf('label[for="' + elem.id + '"]');
	elem.files.length == 0 ? label.inner = 'Выбрать файл(-ы)' : '';
	elem.files.length == 1 ? label.inner = elem.files[0].name : '';
	elem.files.length > 1 ? label.inner = 'Кол-во выбранных файлов: ' + elem.files.length : '';
}

Files.prototype.moveTo = function(href) {
	layout.send({
		url: href,
		target: sf('.layout-wrapper')[0],
		method: 'GET',
		after: function() {

			myfiles.drag();
			sf.changeURL(href);

		}
	});
}

Files.prototype.post = function(form) {
	var fd = new FormData(form);

	layout.send({
		url: form.action,
		data: fd,
		target: sf('.layout-wrapper')[0],
		method: 'POST',
		after: function() {

			myfiles.drag();

		}
	});
}

sf.ready(function() {

	myfiles = new Files;
	myfiles.drag();

});
