import './bootstrap';
import gameFullScreen from './game/gameModal';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

//модальное окно игры
Alpine.data('gameModal', gameFullScreen);

Alpine.start();
