var FilesMin = function() {

    this.pwd = '';
    this.targetNode = {};
    this.options = {};

}

FilesMin.prototype.cwd = function(cid, page) {

    var cid = cid ? cid : '';
    var page = page ? page : 1;
    var self = this;
    var fd = new FormData();
    fd.append('options', JSON.stringify(this.options));

    var data = {

        url: 'filesmin/' + cid + this.getUriOptions() + '&page=' + page,
        method: 'GET',
        target: self.targetNode,
        data: fd,
        after: function(){ self.pwd = cid; }

    };

    layout.send(data);

}

FilesMin.prototype.uploadFiles = function(elem) {

    var self = this;
    var form = document.createElement('form');
    form.enctype = 'multipart/form-data';
    form.appendChild(elem);

    var fd = new FormData(form);
    fd.append('options', JSON.stringify(this.options));

    layout.send(
        'filesmin/' + this.pwd + this.getUriOptions(),
        fd,
        self.targetNode
    );

}

FilesMin.prototype.getUriOptions = function() {

    var tmp = [];

    for(key in this.options){
        tmp.push(key + '=' + encodeURIComponent(this.options[key]));
    }

    var options = tmp.join('&');

    return options ? '?' + options : ''; 

}

FilesMin.bind = function(targetNode, options) {

    if(!HTMLElement.prototype.isPrototypeOf(targetNode))
        throw('targetNode is not HTMLElement.');

    if(!Object.prototype.isPrototypeOf(targetNode.dataset))
        targetNode.dataset = {};

    var autoload = sf(targetNode).attr('autoload');

    switch(autoload){
        case 'true':
            autoload = true;
        break;

        case 'false':
            autoload = false;
        break;

        default:
            autoload = true;
        break;
    }

    var obj = new this

    obj.pwd = '';
    obj.targetNode = targetNode;
    obj.options = Object.prototype.isPrototypeOf(options) ? options : targetNode.dataset;

    targetNode.filesMin = obj;

    if(autoload) obj.cwd();

}

FilesMin.setValue = function (selector, value) {

    var elem = document.querySelector(selector);
    if (elem) {

        elem.value = value;

    }

}

FilesMin.load = function(node) {

    if(!node.filesMin)
        throw('not found filesMin object.');

    node.filesMin.cwd();

}

FilesMin.dragFile = function(event, type, target, inner) {

    var event = event ? event : window.event;
    var data;

    console.log(target);

    type == 'image' ?
    (function(){
        data = document.createElement('img');
        data.src = target;
    })()
     :
    (function(){
        data = document.createElement('a');
        data.href = target;
        data.innerHTML = inner;
    })();

    var tmp = document.createElement('div');
    tmp.appendChild(data);
    event.dataTransfer.setData('text/html', tmp.innerHTML);
    delete(tmp);

}

sf.ready(function(){
    sf('.filesMin').each(function(){
        FilesMin.bind(this);
    });
});

