import './bootstrap';

import '../css/app.css';

import Alpine from 'alpinejs';
import peity from 'peity';

window.Alpine = Alpine;

Alpine.start();

jQuery(".donutChart").peity("donut");
