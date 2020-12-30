'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var LimitChars = function () {
	function LimitChars() {
		var _this = this;

		var element = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;

		_classCallCheck(this, LimitChars);

		this._element = element;

		if (element.hasAttribute('maxlength')) this.setMaxLength(element.getAttribute('maxlength'));else this._maxLength = 0;

		if (element.hasAttribute('data-count-output')) this._countOutput = element.getAttribute('data-count-output');else this._countOutput = null;

		element.addEventListener('input', function () {
			_this._input();
		});

		this._input();
	}

	_createClass(LimitChars, [{
		key: '_input',
		value: function _input() {

			var value = this._element.value;
			var maxLength = parseInt(this._maxLength);

			if (maxLength && value.length > maxLength) this._element.value = value.substr(0, maxLength);

			if (this._countOutput) {

				var nodes = document.querySelectorAll(this._countOutput);
				nodes = [].slice.call(nodes);

				nodes.forEach(function (elem) {

					if (maxLength) {
						elem.innerHTML = maxLength - value.length;
					} else elem.innerHTML = value.length;
				});
			}
		}
	}, {
		key: 'setCountOutput',
		value: function setCountOutput(selector) {

			this._countOutput = selector;
			return this;
		}
	}, {
		key: 'setMaxLength',
		value: function setMaxLength(length) {

			this._maxLength = parseInt(length);
			return this;
		}
	}]);

	return LimitChars;
}();