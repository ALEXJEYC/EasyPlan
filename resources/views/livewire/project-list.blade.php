<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-6">
    @foreach ($projects as $project)
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 group overflow-hidden relative isolate">
            <!-- Enlace principal para toda la tarjeta -->
            <a href="{{ route('projects.show', $project) }}" class="absolute inset-0 z-10"></a>

            <!-- Efecto PS3 -->
            <!-- Efecto PS3 - Limitado al encabezado -->
            <div
                class="ps3-hover-effect absolute top-0 h-40 w-full overflow-hidden opacity-0 group-hover:opacity-100 transition-opacity duration-500 bg-gradient-to-b from-white/5 via-black/70 to-black">
                <div class="particle-container absolute inset-0 pointer-events-none"></div>
            </div>

            <!-- Encabezado con efecto especial -->
            <div class="h-40 bg-gradient-to-br from-black via-gray-900 to-gray-800 relative z-5">
                <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2 z-30">

                    @if ($project->hasMedia('images'))
                        <div
                            class="w-24 h-24 rounded-full border-4 z-100 border-white dark:border-gray-800 shadow-xl overflow-hidden bg-white">
                            <img src="{{ $project->getFirstMediaUrl('images') }}" alt="{{ $project->name }}"
                                class="w-full h-full object-cover" loading="lazy">
                        </div>
                    @else
                        <div
                            class="w-24 h-24 rounded-full bg-white border-4 border-white shadow-xl flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

    <div class="pt-16 pb-6 px-6 text-center">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $project->name }}</h3>
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 leading-relaxed">
            {{ Str::limit($project->description ?? 'Descripción no disponible', 100) }}
        </p>

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>

        <!-- Acciones -->
        <div class="flex justify-center space-x-4">
            <a href="{{ route('projects.show', $project) }}" 
               class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors z-30 relative">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Detalles
            </a>

            <form action="{{ route('projects.archive', $project) }}" method="POST" class="z-30 relative">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="flex items-center text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 transition-colors"
                        onclick="event.stopPropagation()">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Archivar
                </button>
            </form>
        </div>
    </div>
    
    <!-- Enlace principal para el resto de la tarjeta -->
    <a href="{{ route('projects.show', $project) }}" class="absolute inset-0 z-20" style="pointer-events: none;"></a>
</div>
    @endforeach
</div>
@push('scripts')
    <style>
        .ps3-hover-effect {
            background: linear-gradient(45deg,
                    rgba(255, 255, 255, 0.15) 25%,
                    transparent 25%,
                    transparent 50%,
                    rgba(255, 255, 255, 0.15) 50%,
                    rgba(255, 255, 255, 0.1) 75%,
                    transparent 75%,
                    transparent);
            animation: ps3-move 20s linear infinite;
            z-index: 15;
            /* Capa intermedia para el efecto */
            mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
            /* Recorta el efecto */
            -webkit-mask-image: linear-gradient(to bottom, black 60%, transparent 100%);
        }

        @keyframes ps3-move {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 100px 100px;
            }
        }

        .particle-container {
            z-index: 20;
            /* Capa superior para partículas */
        }

        .relative.z-100 {
            z-index: 30;
            /* Asegura que el contenido esté sobre todo */
        }

        .ps3-particle {
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.8);
            filter: drop-shadow(0 0 2px rgba(255, 255, 255, 0.9));
        }

        .flex.justify-center.space-x-4 {
            position: relative;
            z-index: 40 !important;
        }

        .absolute.inset-0.z-20 {
            cursor: pointer;
            opacity: 0;
            /* Mantener invisible pero funcional */
            transition: opacity 0.3s;
        }

        .absolute.inset-0.z-20:hover {
            opacity: 0.1;
            /* Efecto sutil al hover */
        }

        .absolute.-bottom-12 {
            z-index: 35 !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.group').forEach(card => {
                let animation = null;
                const particles = [];

                const startEffect = () => {
                    const effectContainer = card.querySelector('.ps3-hover-effect');
                    if (!effectContainer) return;

                    // Crear partículas
                    for (let i = 0; i < 20; i++) {
                        const particle = document.createElement('div');
                        particle.className = 'ps3-particle';
                        effectContainer.appendChild(particle);
                        particles.push(particle);
                    }

                    // Animación principal
                    animation = anime({
                        targets: particles,
                        translateX: () => anime.random(-100, 100),
                        translateY: () => anime.random(-50, 50),
                        scale: [0.5, 2],
                        opacity: [0, 0.8, 0],
                        duration: 2000,
                        delay: () => anime.random(0, 1000),
                        easing: 'easeOutQuad',
                        loop: true,
                        update: () => {
                            particles.forEach(particle => {
                                anime.set(particle, {
                                    left: `${anime.random(10, 90)}%`,
                                    top: `${anime.random(10, 90)}%`
                                });
                            });
                        }
                    });
                };

                const stopEffect = () => {
                    if (animation) animation.pause();
                    particles.forEach(p => p.remove());
                    particles.length = 0;
                };

                // Escritorio
                card.addEventListener('mouseenter', startEffect);
                card.addEventListener('mouseleave', stopEffect);

                // Móviles
                card.addEventListener('touchstart', startEffect);
                card.addEventListener('touchend', stopEffect);
            });
        });
    </script>
@endpush
