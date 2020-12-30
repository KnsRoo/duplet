'use strict';

class LimitChars {

	constructor(element = null) {

		this._element = element;

		if (element.hasAttribute('maxlength'))
			this.setMaxLength(element.getAttribute('maxlength'));

		else this._maxLength = 0;

		if (element.hasAttribute('data-count-output'))
			this._countOutput = element.getAttribute('data-count-output');

		else this._countOutput = null;

		element.addEventListener('input', () => { this._input(); });

		this._input();

	}

	_input() {

		let value = this._element.value;
		let maxLength = parseInt(this._maxLength);

		if (maxLength && value.length > maxLength)
			this._element.value = value.substr(0, maxLength);

		if (this._countOutput) {

			let nodes = document.querySelectorAll(this._countOutput);
			nodes = [].slice.call(nodes);

			nodes.forEach((elem) => {

				if (maxLength) { elem.innerHTML = maxLength - value.length; }

				else elem.innerHTML = value.length;

			});

		}

	}

	setCountOutput(selector) {

		this._countOutput = selector;
		return this;

	}

	setMaxLength(length) {

		this._maxLength = parseInt(length);
		return this;

	}

}
