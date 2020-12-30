var Settings = function() {}

Settings.prototype.moveTo = function(href) {
	layout.send({
		url: href,
		target: sf('.layout-wrapper')[0],
		method: 'GET',
		after: function() {

			sf.changeURL(href);

		}
	});
}

Settings.prototype.post = function(form) {
	var fd = new FormData(form);

	layout.send({
		url: form.action,
		data: fd,
		target: sf('.layout-wrapper')[0],
		method: 'POST'
	});
}

sf.ready(function() {

	settings = new Settings;

});
