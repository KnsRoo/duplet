var Journal = function() {}

sf.ready(function() {

	journal = new Journal;
	sf('.correction').each(function(elem) {
		new Corrector(elem);
	});

});
