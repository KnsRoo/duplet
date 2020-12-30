"use strict";

var ondesc = document.querySelector('.js-description');
var onchar = document.querySelector('.js-characteristic');

var _char = document.querySelector('.card__characteristic');

var description = document.querySelector('.card__description');
var color = document.querySelector('.color');

ondesc.onclick = function () {
  description.classList.remove('hidden');

  _char.classList.add('hidden');

  ondesc.classList.remove('color');
  onchar.classList.add('color');
};

onchar.onclick = function () {
  description.classList.add('hidden');

  _char.classList.remove('hidden');

  ondesc.classList.add('color');
  onchar.classList.remove('color');
};