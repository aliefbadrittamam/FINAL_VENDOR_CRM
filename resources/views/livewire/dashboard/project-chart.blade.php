<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Project Status</h3>
    <div class="h-64" wire:ignore>
        <canvas id="projectStatusChart"></canvas>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        const ctx = document.getElementById('projectStatusChart').getContext('2d');
        const data = @json($data);
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.map(item => item.status),
                datasets: [{
                    data: data.map(item => item.total),
                    backgroundColor: ['#3B82F6', '#10B981', '#F59E0B']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });
</script>
@endpush