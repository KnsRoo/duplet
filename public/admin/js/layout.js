var Layout = function() {

    var id = sf('body>nav a.active')[0].href;
    this._tabs = {};

    this._content = sf('#layout-content');
    this._tabs[id] = {

        wrapper: sf('.layout-wrapper', this._content)[0],
        url: window.location.href,
        css: []

    };

    this.setCurrentTab(this._tabs[id]);

}

Layout.prototype.setCurrentTab = function(tab) {

    this._currentTab = tab;
    this._currentTab.setOpt = function(key, value) { this[key] = value; }

}

Layout.prototype.getCurrentTab = function() { return this._currentTab; }

Layout.prototype.openModule = function(elem) {

    if(this._tabs[elem.href]){

        this._currentTab.url = window.location.href;
        sf('body>nav .active').rmAttr('class', 'active');
        sf(elem).addAttr('class', 'active');
        this._content.inner = '';

        sf.addNode(this._tabs[elem.href].wrapper, this._content[0]);
        sf.changeURL(this._tabs[elem.href].url);
        sf.destroyCss(this._currentTab.css);
        sf.requireCss(this._tabs[elem.href].css);
        this.setCurrentTab(this._tabs[elem.href]);

    }
    else {

        var self = this;
        this.send({
            url: elem.href,
            method: 'GET',
            before:  function(resp){

                self._currentTab.url = window.location.href;
                sf.changeURL(elem.href);
                sf('body>nav .active').rmAttr('class', 'active');
                sf(elem).addAttr('class', 'active');

                self._content.inner = '';

                sf.destroyCss(self._currentTab.css);

                self._content.inner = '<div class="layout-wrapper">' + resp.content + '</div>';
                self._tabs[elem.href] = {

                    wrapper: sf('.layout-wrapper', self._content)[0],
                    url: elem.href,
                    css: resp.css

                };

                self.setCurrentTab(self._tabs[elem.href]);

            },
            after: function(resp){

                setTimeout(function() {
                    layout.loader.hide();
                }, 200);

            }
        });

    }

}

Layout.prototype.loader = {

    show: function() {
        sf('#content-loader').addAttr('class', 'show');
    },
    hide: function() {
        sf('#content-loader').rmAttr('class', 'show');
    }

};

Layout.prototype.send = function(url, data, target, after) {


    var data = Object.prototype.isPrototypeOf(url) ? url : {
        url: url,
        data: data,
        target: target,
        callback: null,
        before: null,
        after: after,
        method: null
    };

    var req = sf.ajax({
        dst: data.url ? data.url : window.location.href,
        method: data.method ? data.method : 'POST',
        context: this,
        callback: data.callback ? data.callback : function(req) {
            try {

                var resp = JSON.parse(req.responseText);
                sf.alert(resp.notify);

                if(auth.protect(resp)){
                    data.before && data.before(resp);
                    if(data.target) data.target.innerHTML = resp.content;
                    if(resp.actions) new Function(resp.actions)();
                    this._currentTab.css = [].concat(this._currentTab.css, resp.css);
                    sf.requireCss(resp.css);
                    sf.requireJs(resp.js);
                    data.after && data.after(resp);
                }
            }
            catch(e) {

                if (e.name !== 'TypeError') {
                    sf.alert('Получены не корректные данные, попробуйте ещё раз.', 'err');
                }
            }
            layout.loader.hide();
        },
        fallback: function() {
            this.loader.hide();
            sf.alert('Возникли проблемы с подключением.', 'err');
        },
    });

    if (req) {
        this.loader.show();
        req.send(data.data);
    }

}

Layout.prototype.checkAll = function() {
    sf('input[type="checkbox"].checker').each(function() {
        this.checked = true;
    });
}

Layout.prototype.uncheckAll = function() {
    sf('input[type="checkbox"].checker').each(function() {
        this.checked = false;
    });
}

Layout.prototype.sel = function(id) {
       var elem = sf('#' + id)[0];
       elem.focus();
       elem.select();
}


sf.ready(function(){
    layout = new Layout;
});
