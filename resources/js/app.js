import './bootstrap';
import main from './game/gameModal';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.data('gameClick', main);

Alpine.start();
