var Pa = function() { }

Pa.prototype.changeLabel = function(elem) {
    var label = sf('label[for="' + elem.id + '"]');
    elem.files.length == 0 ? label.inner = 'Выбрать файл(-ы)' : '';
    elem.files.length == 1 ? label.inner = elem.files[0].name : '';
    elem.files.length > 1 ? label.inner = 'Кол-во выбранных файлов: ' + elem.files.length : '';
}

sf.ready(function() { pa = new Pa; });
