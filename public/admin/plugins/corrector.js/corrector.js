'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Corrector = function () {
	function Corrector(input) {
		var _this = this;

		_classCallCheck(this, Corrector);

		this._input = input;

		this._state = '';

		this._pattern = '';

		this._vars = {
			'X': '\\d'
		};

		if (input.hasAttribute('data-pattern')) this._pattern = input.getAttribute('data-pattern');else if (input.hasAttribute('placeholder')) this._pattern = input.getAttribute('placeholder');

		if (input.hasAttribute('data-pattern-vars')) {

			var vars = input.getAttribute('data-pattern-vars');
			this._vars = this.strToVars(vars);
		}

		input.addEventListener('keydown', function () {
			_this.keepState();
		});
		input.addEventListener('input', function () {

			_this.emulateInsert(_this._input.value);
		});

		this.emulateInsert(this._input.value);
	}

	_createClass(Corrector, [{
		key: 'emulateInsert',
		value: function emulateInsert(value) {

			this._input.value = '';

			for (var i = 0; i != value.length; i++) {

				this.keepState();
				this._input.value += value[i];

				if (!this.correct()) return false;
			}

			this.keepState();
			return true;
		}
	}, {
		key: 'strToVars',
		value: function strToVars(str) {
			return new Function('return ' + str)();
		}
	}, {
		key: 'keepState',
		value: function keepState() {
			this._state = this._input.value;
		}
	}, {
		key: 'correct',
		value: function correct() {

			var value = this._input.value;
			var symbol = value[value.length - 1];

			if (this._pattern.length < value.length) {

				this._input.value = this._state;
				return false;
			}

			var pattern = this._pattern.slice(0, value.length);

			var exp = this.toRegExp(pattern);

			if (!value.match(exp)) {

				var item = this._pattern[value.length - 1];

				if (!(item in this._vars)) {

					var state = this._state.substr(0, value.length - 1);
					this._input.value = state + item;
					this.keepState();

					this._input.value += symbol;

					return this.correct();
				} else {

					this._input.value = this._state;
					return false;
				}
			} else return true;
		}
	}, {
		key: 'toRegExp',
		value: function toRegExp(pattern) {

			pattern = pattern.replace(/(\W)/g, '\\$1');

			for (var k in this._vars) {
				pattern = pattern.split(k).join(this._vars[k]);
			}

			return new RegExp(pattern);
		}
	}, {
		key: 'setPattern',
		value: function setPattern(pattern) {
			this._pattern = pattern;
		}
	}, {
		key: 'setVars',
		value: function setVars(vars) {
			this._vars = vars;
		}
	}]);

	return Corrector;
}();