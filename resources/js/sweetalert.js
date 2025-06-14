import Swal from 'sweetalert2/dist/sweetalert2.js';

// Asignar Swal al objeto window para acceso global
window.Swal = Swal;

// Función simple para mostrar alertas de éxito
window.showSuccessAlert = (title, text) => {
  Swal.fire({
    title: title || '¡Éxito!',
    text: text || 'Operación completada correctamente',
    icon: 'success',
    confirmButtonColor: '#3085d6'
  });
};


    document.addEventListener('DOMContentLoaded', function () {
        Livewire.on('projectCreated', projectId => {
            Swal.fire({
                title: '¡Éxito!',
                text: 'El proyecto ha sido creado exitosamente.',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        });
    });
