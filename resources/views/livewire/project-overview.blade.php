<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-8 transition-all duration-300 transform hover:shadow-2xl relative overflow-hidden">
    <!-- Efecto de partículas -->
    <div id="particles" class="absolute inset-0 pointer-events-none"></div>

    <!-- Descripción del Proyecto -->
    <div class="mb-10 bg-blue-50/80 dark:bg-gray-800/80 p-6 rounded-xl border-l-4 border-blue-500 backdrop-blur-lg transition-all duration-300 hover:shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-blue-700 dark:text-blue-300 flex items-center">
            <svg class="w-7 h-7 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
            </svg>
            Descripción del Proyecto
        </h2>
        <p class="text-gray-700 dark:text-gray-300 leading-relaxed text-lg">
            {{ $project->description }}
        </p>
    </div>

    <!-- Controles de personalización -->
    <div class="flex justify-center gap-4 mb-8">
        <div class="color-picker flex gap-2">
            <button class="w-6 h-6 rounded-full bg-blue-600 border-2 border-white shadow-md transition-transform hover:scale-125" data-color="#3B82F6"></button>
            <button class="w-6 h-6 rounded-full bg-emerald-600 border-2 border-white shadow-md transition-transform hover:scale-125" data-color="#10B981"></button>
            <button class="w-6 h-6 rounded-full bg-purple-600 border-2 border-white shadow-md transition-transform hover:scale-125" data-color="#8B5CF6"></button>
        </div>
        <div class="chart-type flex gap-2">
            <button class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-sm transition-all hover:bg-blue-600 hover:text-white" data-type="doughnut">Dona</button>
            <button class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-sm transition-all hover:bg-blue-600 hover:text-white" data-type="bar">Barras</button>
        </div>
    </div>

    <!-- Contenedor del gráfico -->
    <div class="relative w-80 h-80 mx-auto mb-8 group cursor-pointer"
         id="chartContainer"
         onclick="toggleProgress()">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-200 to-blue-100 dark:from-gray-900 dark:to-blue-900/20 rounded-full opacity-0 group-hover:opacity-30 transition-opacity duration-300"></div>
        
        <canvas id="progressChart" width="450" height="450" class="relative z-10 transition-transform duration-500 group-hover:scale-110 hover:!scale-125 origin-center"></canvas>

        <!-- Texto del porcentaje -->
        <div id="progressText" class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none select-none transition-all duration-500">
            <div class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500 drop-shadow-2xl">
                <span>0</span><span class="text-3xl">%</span>
            </div>
            <span class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Completado
            </span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let chart, currentProgress = 0, originalProgress = @json($progress);
    let isAnimating = false;
    let currentColor = '#3B82F6';
    let chartType = 'doughnut';

    // Inicialización automática
    document.addEventListener('DOMContentLoaded', () => {
        initChart();
        animateProgress(originalProgress);
        
        // Event listeners para controles
        document.querySelectorAll('.color-picker button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                currentColor = e.target.dataset.color;
                updateChartColor(currentColor);
            });
        });
        
        document.querySelectorAll('.chart-type button').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const newType = e.target.dataset.type;
                animateTextTransition(newType);
                chartType = newType;
                initChart();
                animateProgress(currentProgress);
            });
        });
    });

    function animateTextTransition(newType) {
        const progressText = document.getElementById('progressText');
        anime({
            targets: progressText,
            translateX: newType === 'bar' ? '120%' : '0%',
            opacity: [0.5, 1],
            scale: [0.9, 1],
            duration: 800,
            easing: 'easeInOutQuad'
        });
    }

    function updateChartColor(newColor) {
        const gradient = chart.ctx.createLinearGradient(0, 0, 400, 0);
        gradient.addColorStop(0, newColor);
        gradient.addColorStop(1, `${newColor}DD`);
        
        chart.data.datasets[0].backgroundColor[0] = gradient;
        chart.update();
        
        // Actualizar gradiente del texto
        document.querySelector('#progressText div').style.backgroundImage = 
            `linear-gradient(to right, ${newColor}, ${newColor}DD)`;
    }

    function createParticles(event) {
        const container = document.getElementById('particles');
        for(let i = 0; i < 15; i++) {
            const particle = document.createElement('div');
            particle.className = 'absolute w-2 h-2 bg-current rounded-full';
            particle.style.color = currentColor;
            particle.style.left = `${event.clientX}px`;
            particle.style.top = `${event.clientY}px`;
            
            anime({
                targets: particle,
                left: `${event.clientX + anime.random(-50, 50)}px`,
                top: `${event.clientY + anime.random(-50, 50)}px`,
                opacity: [0.8, 0],
                scale: [1, 2],
                duration: 1000,
                easing: 'easeOutExpo',
                complete: () => particle.remove()
            });
            
            container.appendChild(particle);
        }
    }

    function animateProgress(target) {
        isAnimating = true;
        anime({
            targets: { value: currentProgress },
            value: target,
            easing: 'easeOutElastic(1, .5)',
            duration: 2000,
            update: (anim) => {
                const val = Math.round(anim.animations[0].currentValue);
                chart.data.datasets[0].data = chartType === 'doughnut' 
                    ? [val, 100 - val] 
                    : [val];
                chart.update();
                
                document.querySelector('#progressText span:first-child').textContent = val;
                
                if([25, 50, 75, 100].includes(val)) {
                    anime({
                        targets: '#progressText div',
                        scale: [1.1, 1],
                        duration: 300
                    });
                }
            },
            complete: () => {
                currentProgress = target;
                isAnimating = false;
            }
        });
    }

    function toggleProgress() {
        if(isAnimating) return;
        
        createParticles(event);
        const target = currentProgress === 0 ? originalProgress : 0;
        animateProgress(target);
    }

    function initChart() {
        const ctx = document.getElementById('progressChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 400, 0);
        gradient.addColorStop(0, currentColor);
        gradient.addColorStop(1, `${currentColor}DD`);

        if(chart) chart.destroy();

        chart = new Chart(ctx, {
            type: chartType,
            data: {
                labels: ['Progreso'],
                datasets: [{
                    label: 'Completado',
                    data: [0],
                    backgroundColor: [gradient],
                    borderColor: '#FFFFFF',
                    borderWidth: chartType === 'doughnut' ? 6 : 0,
                    borderRadius: 10,
                    hoverOffset: 15,
                    categoryPercentage: 0.8,
                    barPercentage: 0.9
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: chartType === 'doughnut' ? '75%' : '0%',
                rotation: chartType === 'doughnut' ? -90 : 0,
                circumference: chartType === 'doughnut' ? 360 : 0,
                scales: {
                    y: {
                        display: chartType === 'bar',
                        beginAtZero: true,
                        max: 100,
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        display: chartType === 'bar',
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            }
        });

        // Posicionamiento inicial del texto
        const progressText = document.getElementById('progressText');
        if(chartType === 'bar') {
            progressText.style.transform = 'translateX(120%)';
            progressText.style.justifyContent = 'flex-start';
        } else {
            progressText.style.transform = 'translateX(0)';
            progressText.style.justifyContent = 'center';
        }
    }
</script>

<style>
    #chartContainer {
        padding: 2rem;
        margin: 2rem auto;
        overflow: visible !important;
    }

    #progressChart {
        margin: -2rem;
        transition: transform 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }

    #progressText {
        width: 40%;
        right: auto;
        left: 50%;
        transform: translateX(-50%);
    }

    .chart-type button.active {
        background: #3B82F6 !important;
        color: white !important;
    }

    .chart-type button:hover {
        transform: translateY(-2px);
    }
</style>
@endpush