var User = function() {

    var phoneInputs = document.querySelectorAll('.phone-v');
    var dateInputs = document.querySelectorAll('.date-v');

    phoneInputs.forEach(function(phone) {
        new Corrector(phone);
    });

    dateInputs.forEach(function(date) {
        new Corrector(date);
    });

}
User.prototype.addFields = function() {

    var container = document.querySelector('.inputs-container');

    var template = document.querySelector('.counter.container .template')
        .innerHTML;

    var newContainer = document.createElement('div');
    newContainer.innerHTML = template;

    container.append(newContainer);

}

User.prototype.addFields = function(el) {

    var container = el.parentNode.parentNode;

    var template = document.querySelector('.template')
        .innerHTML;

    var newContainer = document.createElement('div');
    newContainer.innerHTML = template;

    container.insertBefore(newContainer, el.parentNode);

}

sf.ready(function(){

    user = new User;

});

