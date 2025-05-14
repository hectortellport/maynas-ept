// Detectar movimiento del mouse
        let timeout;
        const nav = document.getElementById('mainNav');
        const hamburger = document.querySelector('.hamburger');
        const menuLinks = document.querySelector('.menu-links');

        // Control de transparencia
        document.addEventListener('mousemove', () => {
            nav.style.background = 'rgba(0,0,0,0.8)';
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                nav.style.background = 'rgba(0,0,0,0)';
            }, 2000);
        });

        // Control del scroll
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.style.background = 'rgba(0,0,0,0.8)';
            } else {
                nav.style.background = 'rgba(0,0,0,0)';
            }
        });

        // Menú responsive
        hamburger.addEventListener('click', () => {
            menuLinks.classList.toggle('active');
        });

        // Cerrar menú al hacer click fuera
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !menuLinks.contains(e.target)) {
                menuLinks.classList.remove('active');
            }
        });

        // Cerrar menú al hacer click en un enlace (para móviles)
        menuLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                menuLinks.classList.remove('active');
            });
        });