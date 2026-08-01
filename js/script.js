const boton = document.querySelector('.boton-toggle');
const menu = document.querySelector('.navbar--container-menu'); 
const enlaces = document.querySelectorAll('.navbar--container-menu a');

if (boton && menu) {
    // abre y cierra menu y actualizar aria-expanded
    boton.addEventListener('click', () => {
        const estaabierto = menu.classList.toggle('active');
        boton.setAttribute('aria-expanded', estaabierto); //accesibilidad
        document.body.style.overflow = estaabierto ? 'hidden' : ''; //bloquea el scroll
    });
    
    enlaces.forEach(enlace => {
        enlace.addEventListener('click', () => {
        menu.classList.remove('active');
        boton.setAttribute('aria-expanded', false);
        document.body.style.overflow = '';
    });

    });
    //cierra el menu al hacer clic fuera del menu
document.addEventListener('click', (e) => {
            const clickfuera = !menu.contains(e.target) && !boton.contains(e.target);
            if (clickfuera && menu.classList.contains('active')) { 
            menu.classList.remove('active');
            boton.setAttribute('aria-expanded', false);
            document.body.style.overflow = '';
        }
            
        });
}

else{
    console.error("No se encontraron los elementos necesarios");
} 

//limpiar menu 
let timer;
window.addEventListener("resize", () => {
  // 1. Agregamos la clase que quita las transiciones al body
  document.body.classList.add("stop-transitions");

  // 2. Limpiamos el temporizador previo
  clearTimeout(timer);

  // 3. Esperamos 400ms después de que termine el movimiento para quitar la clase
  timer = setTimeout(() => {
    document.body.classList.remove("stop-transitions");
  }, 400);
});


// Solución para limpiar el menú al redimensionar
window.addEventListener('resize', () => {
    // Si la pantalla es más ancha que el punto de corte (76.8em = 1228px aprox)
    if (window.innerWidth > 1228) { 
        // 1. Removemos la clase active del menú
        menu.classList.remove('active');
        
        // 2. Restauramos el scroll del cuerpo
        document.body.style.overflow = '';
        
        // 3. Reseteamos el estado de accesibilidad del botón
        boton.setAttribute('aria-expanded', false);
    }
});