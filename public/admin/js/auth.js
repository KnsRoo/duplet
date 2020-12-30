Auth = function(){

	this.window = sf('#ajax-auth-form');
	this.form = sf('.form', this.window);

}

Auth.prototype.protect = function(data){

	this.wait(false);
	if(!data.auth) return true;
	this.open();

}

Auth.prototype.open = function(){

	var self = this;
	this.window.css('display', 'block');
	setTimeout(function() { self.form.css('top', '30vh'); }, 100);

}

Auth.prototype.close = function(){

	this.window.rmAttr('class', 'load');
	this.form.rmAttr('class', 'error');
	this.window.css('display', 'none');

}

Auth.prototype.wait = function(state){

	state ? this.window.addAttr('class', 'load') :
		this.window.rmAttr('class', 'load');

}

Auth.prototype.error = function(){
	console.log('fdsf');
	this.form.addAttr('class', 'error');
	this.window.rmAttr('class', 'load');

}

Auth.prototype.send = function(){

	var self = this;
	var fd = new FormData(this.form[0]);
	layout.send({
		url: 'login',
		data: fd,
		after: function(req) {
			self.wait(false);
			if(!req.auth)self.close();
		}
	});
	this.wait(true);

}

sf.ready(function(){ auth = new Auth; });
