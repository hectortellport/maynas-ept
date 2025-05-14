document.addEventListener('DOMContentLoaded', function() {
    // Mostrar el modal automáticamente
    const modal = document.getElementById('countdownModal');
    modal.style.display = 'block';
    
    // Elementos del contador
    const daysElement = document.getElementById('days');
    const hoursElement = document.getElementById('hours');
    const minutesElement = document.getElementById('minutes');
    const secondsElement = document.getElementById('seconds');



   
    
    // Botón para cerrar el modal con animación
    const closeBtn = document.querySelector('.close');
    closeBtn.addEventListener('click', function() {
        // Aplicar clases para la animación
        modal.classList.add('hiding');
        modal.classList.add('fade-out');
        
        // Esperar a que termine la animación antes de ocultar
        setTimeout(function() {
            modal.style.display = 'none';
            // Remover clases para futuras aperturas (si las hubiera)
            modal.classList.remove('hiding');
            modal.classList.remove('fade-out');
        }, 400); // Duración igual a la transición CSS (400ms)
    });



    
    
    // Eliminamos el event listener que cerraba al hacer clic fuera del modal
    // window.addEventListener('click', function(event) {
    //     if (event.target === modal) {
    //         modal.style.display = 'none';
    //     }
    // });
    
    // Fecha del evento: 28 de Julio 2025
    const eventDate = new Date('July 28, 2025 00:00:00').getTime();
    
    // Actualizar el contador cada segundo
    const countdown = setInterval(function() {
        // Fecha y hora actual
        const now = new Date().getTime();
        
        // Diferencia entre la fecha del evento y la actual
        const distance = eventDate - now;
        
        // Cálculos de tiempo
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        // Mostrar resultados
        daysElement.textContent = formatTime(days);
        hoursElement.textContent = formatTime(hours);
        minutesElement.textContent = formatTime(minutes);
        secondsElement.textContent = formatTime(seconds);
        
        // Si el contador llega a cero
        if (distance < 0) {
            clearInterval(countdown);
            document.querySelector('.modal-header h1').textContent = '¡El evento ha comenzado!';
            document.querySelector('.countdown-container').innerHTML = `
                <div class="event-started" style="width:100%; text-align:center; padding:20px;">
                    <p style="font-size:1.5rem; color:#d32f2f; font-weight:bold;">¡Bienvenidos al gran evento!</p>
                </div>
            `;
            document.querySelector('.event-description').style.display = 'none';
        }
    }, 1000);
    
    // Formatear el tiempo para mostrar siempre 2 dígitos
    function formatTime(time) {
        return time < 10 ? `0${time}` : time;
    }
});