require('./bootstrap');
window.$ = require('jquery');
require('./categoryIsChosen');
require('./MosquitoSystems/getProfile');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
