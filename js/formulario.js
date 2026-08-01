// ==========================================
// PROCESAMIENTO DEL FORMULARIO DE CONTACTO
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    const formulario = document.getElementById('form-contacto');
    const respuestaContenedor = document.getElementById('form-respuesta');

    if (formulario && respuestaContenedor) {
        formulario.addEventListener('submit', function(e) {
            e.preventDefault(); // Evita que la página se recargue

            // Cambiamos el estado del botón mientras se envía
            const boton = formulario.querySelector('.boton-form');
            const textoOriginal = boton.textContent;
            boton.textContent = 'Enviando...';
            boton.disabled = true;

            // Recolectamos los datos del formulario
            const datos = new FormData(formulario);

            // Enviamos la petición al archivo PHP
            fetch(formulario.getAttribute('action'), {
                method: 'POST',
                body: datos
            })
            .then(res => res.json()) // Esperamos una respuesta en formato JSON
            .then(data => {
                // Restauramos el botón
                boton.textContent = textoOriginal;
                boton.disabled = false;

                // Mostramos el mensaje del servidor
                respuestaContenedor.textContent = data.mensaje;

                if (data.status === 'success') {
                    respuestaContenedor.style.color = '#dbfa7e'; // Tu color verde claro de éxito[cite: 3]
                    formulario.reset(); // Limpia los campos del formulario
                } else {
                    respuestaContenedor.style.color = '#f9eadc'; // Tu color base claro para resaltar errores[cite: 3]
                }
            })
            .catch(error => {
                boton.textContent = textoOriginal;
                boton.disabled = false;
                respuestaContenedor.textContent = 'Hubo un error en la conexión. Inténtalo más tarde.';
                respuestaContenedor.style.color = '#f9eadc';
            });
        });
    }
});