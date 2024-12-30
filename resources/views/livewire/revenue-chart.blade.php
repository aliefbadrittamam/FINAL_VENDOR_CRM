<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Monthly Revenue</h3>
    <div class="h-64" wire:ignore>
        <canvas id="revenueChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const data = @json($data);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.month),
                datasets: [{
                    label: 'Revenue',
                    data: data.map(item => item.total),
                    borderColor: '#3B82F6',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush