<div width="600" height="400">
    <canvas id="orderChart"></canvas>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('orderChart').getContext('2d');
        const orderChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Orders',
                    data: [],
                    borderColor: 'red',
                    fill: false
                }]
            },
            options: { responsive: true }
        });

        Livewire.on('updateChart', data => {
            chart.data = data;
            chart.update();
        });
    });
</script>
