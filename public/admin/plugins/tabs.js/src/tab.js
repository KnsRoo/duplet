'use strict';

class Tab {

	constructor(node) {

		this._node = node;
		this._status = null;
		this._frame = null;

		this._extractFrame();
		this._extractStatus();

	}

	_extractFrame() {

		let node = this._node;
		let frame = node.dataset['tabFrame'];
		if (!frame) return;

		frame = document.querySelector(frame);
		if (!frame) return;

		this.combineWithFrame(frame);

	}

	_extractStatus() {

		let node = this._node;
		let status = node.dataset['tabSelected'];

		if (status) this.show();
		else this.hide();

	}

	combineWithFrame(node) {

		this._frame = node;

	}

	show() {

		let node = this._node;
		let frame = this._frame;

		node.classList.add('active')
		node.classList.remove('inactive');

		frame.classList.add('active')
		frame.classList.remove('inactive');

		this._status = 'active';

	}

	hide() {

		let node = this._node;
		let frame = this._frame;

		node.classList.add('inactive')
		node.classList.remove('active');

		frame.classList.add('inactive')
		frame.classList.remove('active');

		this._status = 'inactive';

	}

	toggle() {

		if (this._status === 'inactive')
			this.show();

		else this.hide();

	}

}

export default Tab;
