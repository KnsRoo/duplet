var Backups = function() {}

Backups.prototype.moveTo = function(href) {
	layout.send({
		url: href,
		target: sf('.module-data')[0],
		method: 'GET',
		after: function() {

			sf.changeURL(href);

		}
	});
}

Backups.prototype.post = function(form) {
	var fd = new FormData(form);

	layout.send({
		url: form.action,
		data: fd,
		target: sf('.module-data')[0],
		method: 'POST',
		after: function() {

			sf.changeURL(href);

		}
	});
}

sf.ready(function() {

	backups = new Backups;

});
