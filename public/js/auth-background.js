document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('particles');
    const ctx = canvas.getContext('2d');
    
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    const particles = [];
    const particleCount = Math.floor(window.innerWidth / 10);
    
    // Colores para modo claro y oscuro
    const lightModeColor = 'rgba(59, 130, 246, 0.5)';
    const darkModeColor = 'rgba(99, 102, 241, 0.5)';
    
    // Detecta el modo actual
    const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    let particleColor = isDarkMode ? darkModeColor : lightModeColor;
    
    // Escucha cambios en el modo de color
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
        particleColor = event.matches ? darkModeColor : lightModeColor;
    });
    
    // Clase Partícula
    class Particle {
        constructor() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.size = Math.random() * 3 + 1;
            this.speedX = Math.random() * 1 - 0.5;
            this.speedY = Math.random() * 1 - 0.5;
        }
        
        update() {
            this.x += this.speedX;
            this.y += this.speedY;
            
            if (this.x < 0 || this.x > canvas.width) {
                this.speedX = -this.speedX;
            }
            
            if (this.y < 0 || this.y > canvas.height) {
                this.speedY = -this.speedY;
            }
        }
        
        draw() {
            ctx.fillStyle = particleColor;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.closePath();
            ctx.fill();
        }
    }
    
    // Crear partículas
    function init() {
        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }
    }
    
    // Conectar partículas cercanas
    function connect() {
        let opacity = 1;
        for (let a = 0; a < particles.length; a++) {
            for (let b = a; b < particles.length; b++) {
                const distance = Math.sqrt(
                    Math.pow(particles[a].x - particles[b].x, 2) + 
                    Math.pow(particles[a].y - particles[b].y, 2)
                );
                
                if (distance < 100) {
                    opacity = 1 - (distance / 100);
                    ctx.strokeStyle = `rgba(59, 130, 246, ${opacity})`;
                    if (isDarkMode) {
                        ctx.strokeStyle = `rgba(99, 102, 241, ${opacity})`;
                    }
                    ctx.lineWidth = 0.5;
                    ctx.beginPath();
                    ctx.moveTo(particles[a].x, particles[a].y);
                    ctx.lineTo(particles[b].x, particles[b].y);
                    ctx.stroke();
                }
            }
        }
    }
    
    // Animación
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        for (let i = 0; i < particles.length; i++) {
            particles[i].update();
            particles[i].draw();
        }
        
        connect();
        requestAnimationFrame(animate);
    }
    
    // Redimensionar canvas
    window.addEventListener('resize', function() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });
    
    init();
    animate();
});