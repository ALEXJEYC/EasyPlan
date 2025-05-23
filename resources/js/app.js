import './bootstrap';
import './chat';
import anime from 'animejs';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

anime({
  targets: '#box',
  translateX: 250,
  duration: 1000,
  easing: 'easeInOutQuad'
});