import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// import Sortable from 'sortablejs/modular/sortable.complete.esm.js';
import Sortable from 'sortablejs';
window.Sortable = Sortable;
Sortable.default;