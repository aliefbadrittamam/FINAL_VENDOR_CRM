<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(Auth::user()->role === 'Admin')
                <livewire:dashboard.main />
            @elseif(Auth::user()->role === 'Vendor')
                <livewire:dashboard.vendor-dashboard />
            @elseif(Auth::user()->role === 'Customers')
                <livewire:dashboard.customer-dashboard />
            @endif
        </div>
    </div>
    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', function() {
    Livewire.on('chartDataReady', function(data) {
        initCharts(data);
    });

    function initCharts(chartData) {
        // Fungsi untuk membuat chart
        function createChart(elementId, type, chartData, options = {}) {
            const ctx = document.getElementById(elementId);
            if (ctx && chartData.labels.length > 0) {
                new Chart(ctx.getContext('2d'), {
                    type: type,
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            ...options.dataset,
                            data: chartData.data
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' }
                        },
                        ...options.chartOptions
                    }
                });
            } else {
                ctx.innerHTML = `<div class="text-center text-gray-500 py-4">No ${elementId} data available</div>`;
            }
        }

        // Revenue Trend Chart
        createChart('revenueTrendChart', 'line', chartData.revenueTrend, {
            dataset: {
                label: 'Revenue',
                borderColor: '#10B981',
                tension: 0.4,
                fill: false
            },
            chartOptions: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString()
                        }
                    }
                }
            }
        });

        // Project Status Chart
        createChart('projectStatusChart', 'doughnut', chartData.projectStatus, {
            dataset: {
                backgroundColor: ['#3B82F6', '#10B981', '#F59E0B']
            },
            chartOptions: {
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Customer Growth Chart
        createChart('customerGrowthChart', 'bar', chartData.customerGrowth, {
            dataset: {
                label: 'New Customers',
                backgroundColor: '#6366F1'
            },
            chartOptions: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Lead Conversion Chart
        createChart('leadConversionChart', 'pie', chartData.leadConversion, {
            dataset: {
                backgroundColor: ['#EF4444', '#F59E0B', '#10B981']
            },
            chartOptions: {
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        // Project Timeline Chart (ApexCharts)
        const timelineChart = document.getElementById('projectTimelineChart');
        if (timelineChart && chartData.timelineData.length > 0) {
            const timelineOptions = {
                series: [{
                    data: chartData.timelineData
                }],
                chart: {
                    height: 350,
                    type: 'rangeBar',
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        distributed: true,
                        dataLabels: {
                            hideOverflowingLabels: false
                        }
                    }
                },
                xaxis: {
                    type: 'datetime'
                },
                tooltip: {
                    custom: function({series, seriesIndex, dataPointIndex, w}) {
                        const project = w.config.series[seriesIndex].data[dataPointIndex];
                        const startDate = new Date(project.y[0]).toLocaleDateString();
                        const endDate = new Date(project.y[1]).toLocaleDateString();
                        
                        return `
                            <div class="p-2">
                                <strong>${project.x}</strong><br>
                                Start: ${startDate}<br>
                                End: ${endDate}
                            </div>
                        `;
                    }
                }
            };

            const projectTimelineChart = new ApexCharts(timelineChart, timelineOptions);
            projectTimelineChart.render();
        } else {
            timelineChart.innerHTML = '<div class="text-center text-gray-500 py-4">No project timeline data available</div>';
        }
    }
});
</script>
@endpush
</x-app-layout>