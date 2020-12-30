var PagesMin = function(targetNode, settings){

	this.pwd = '';
	this.targetNode = targetNode;
	targetNode.pagesMin = this;
	this.settings = settings;
	this.cwd();

}

PagesMin.prototype.cwd = function(cid){

	var cid = cid ? cid : '';
	var self = this;
	var fd = new FormData;
	fd.append('settings', JSON.stringify(this.settings));
	layout.send('pagesmin/' + cid, fd, self.targetNode, function(resp){
		self.pwd = cid;
	});

}

sf.ready(function(){
	sf('.pagesMin').each(function(){
		new PagesMin(this, this.dataset);
	});
});
