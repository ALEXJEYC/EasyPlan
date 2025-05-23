<div>
    <!-- Descripción del Proyecto -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Descripción del Proyecto</h2>
        <p class="text-gray-600 dark:text-gray-300">{{ $project->description }}</p>
    </div>

    <!-- Gráfico de Dona -->
    <div class="w-64 h-64 mx-auto">
        <canvas id="progressChart"></canvas>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            const ctx = document.getElementById('progressChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Completado', 'Pendiente'],
                    datasets: [{
                        data: [@this.progress, 100 - @this.progress],
                        backgroundColor: ['#3B82F6', '#E5E7EB'],
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                }
            });
        });
    </script>
    @endpush
</div>