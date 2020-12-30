'use strict';

import Tab from "tab.js";

class Tabs {

	constructor(selector = null) {

		this._tabsPool = [];

		if (selector !== null) this.add(selector);

	}

	add(selector = ".tab") {

		let nodeList = document.querySelectorAll(selector);

		let nodes = [].slice.call(nodeList);

		nodes.forEach(node => {

			let tab = new Tab(node);

			this._tabsPool.push(tab);

			node.addEventListener('click', () => {

				this.hideAll();
				tab.show();

			});

		});

	}

	hideAll() {

		let tabs = this._tabsPool;

		tabs.forEach(tab => {

			tab.hide();

		});

	}

}

window.Tabs = Tabs;

export default Tabs;
