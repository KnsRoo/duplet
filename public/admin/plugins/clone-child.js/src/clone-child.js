'use strict';

class CloneChild {

	constructor(container) {

		const addButton = 'data-add-button';
		const rmButton = 'data-rm-button';
		const template = 'data-template';

		this._nodes = [];
		this._template = null;
		this._container = null;
		this._filters = { add: null, rm: null };

		if (!(container instanceof HTMLElement))
			throw new Error('container is not a HTMLElement');

		this._container = container;

		if (container.hasAttribute(template)) {

			let selector = container.getAttribute(template);
			this._template = document.querySelector(selector);

		}

		if (container.hasAttribute(addButton)) {

			let selector = container.getAttribute(addButton);
			let button = document.querySelector(selector);
			button.addEventListener('click', () => { this.addClone() });

		}

		if (container.hasAttribute(rmButton)) {

			let selector = container.getAttribute(rmButton);
			let button = document.querySelector(selector);
			button.addEventListener('click', () => { this.rmClone() });

		}

	}

	addClone() {

		if (!(this._template instanceof HTMLElement))
			throw new Error('template not found');

		let clone = this._template.cloneNode(true);

		if (this._filters.add) this._filters.add(clone);
		this._nodes.push(clone);
		this._container.appendChild(clone);

	}

	rmClone() {

		if (!(this._template instanceof HTMLElement))
			throw new Error('template not found');

		if (!this._nodes.length) return false;

		let clone = this._nodes.pop();

		if (this._filters.rm) this._filters.rm(clone);
		this._container.removeChild(clone);

		return true;

	}

	setTemplate(elem) {

		if (!(elem instanceof HTMLElement))
			throw new Error('elem is not a HTMLElement');

		this._template = elem;
		return this;

	}

	onAdd(call) {

		if (!(call instanceof Function))
			throw new Error('call is not a function');

		this._filters.add = call;
		return this;
	
	}

	onRm(call) {

		if (!(call instanceof Function))
			throw new Error('call is not a function');

		this._filters.rm = call;
		return this;

	}

}
