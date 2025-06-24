import './bootstrap';
//import './sweetalert';  // Esta línea debe estar al inicio
import './chat';


import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

anime({
  targets: '#box',
  translateX: 250,
  duration: 1000,
  easing: 'easeInOutQuad'
});