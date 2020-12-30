var Users = function() {}

Users.prototype.makeInputMask = function (node, mask) {

	// Convert mask to object with symbols string position
	var matches = {};
	for (var i = 0; i != mask.length; i ++) {
		(mask[i] != 'n' && mask[i] != 'c' && mask[i] != '*') && (function () {
			matches[i] = mask[i];
		})();
	}

	node.onkeydown = function (event) {
		// Get charCode for the key pressed
		var charCode = event.keyCode ? event.keyCode : event.which;
		var chr = (charCode >= 32) ? String.fromCharCode(charCode) : null;

		// For NumLock numbers
		switch (charCode) {
			case 96: chr = 0; break;
			case 97: chr = 1; break;
			case 98: chr = 2; break;
			case 99: chr = 3; break;
			case 100: chr = 4; break;
			case 101: chr = 5; break;
			case 102: chr = 6; break;
			case 103: chr = 7; break;
			case 104: chr = 8; break;
			case 105: chr = 9; break;
		}

		// Core valid keys
		var vCodes = [8, 9, 13, 20, 27, 35, 36, 37, 38, 39, 40, 46, 91, 92, 93, 144, 154];
		var vFlag = 0;
		for (var i = 0; i != vCodes.length; i ++) { (charCode == vCodes[i]) ? vFlag = 1 : ''; }

		// If not system valid key
		if (!vFlag) {
			users.insertMaskSymbols(node, matches, chr, mask);
			return false;
		}

	}

}

Users.prototype.insertMaskSymbols = function (node, matches, chr, mask) {

	// Inserting mask-symbols if needed
	for (var key in matches) {
		key == node.value.length && (function () {
			node.value += matches[key];
		})();
	}

	// Get needed RegExp for current symbol
	var num_reg = /[0-9]/;
	var char_reg = /[a-zA-Zа-яА-ЯёЁ]/;
	var all_reg = /[0-9a-zA-Zа-яА-ЯёЁ]/;
	var reg = (mask[node.value.length] == 'n') ? num_reg : ((mask[node.value.length] == 'c') ? char_reg : ((mask[node.value.length] == '*') ? all_reg : false));

	// Inserting current symbol after mask-symbol
	(reg && reg.test(chr) && node.value.length < mask.length) && (function () {
		node.value += chr;
	})();

}

sf.ready(function() {

	users = new Users;
	sf('.correct').each(function (elem) {
		new Corrector(elem);
	});

});
