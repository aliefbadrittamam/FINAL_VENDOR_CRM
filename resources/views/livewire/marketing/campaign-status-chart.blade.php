<div width="600" height="400">
    <canvas id="myChart"></canvas>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('myChart').getContext('2d');
        const campaignChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sent', 'Delivered'],
                datasets: [{
                    label: 'Campaign Status',
                    data: [],
                    backgroundColor: ['blue', 'green']
                }]
            },
            options: { responsive: true }
        });

        window.addEventListener('updateCampaignChart', event => {
            campaignChart.data.datasets[0].data = [event.detail.total_send, event.detail.total_delivered];
            campaignChart.update();
        });
    });
</script>

