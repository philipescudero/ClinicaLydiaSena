<x-app-layout>
    <div class="py-12 bg-[#FDF2F4] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-3xl font-serif text-gray-800 italic mb-8 text-center md:text-left">Relatórios de Performance</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-pink-100">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 text-center md:text-left">Faturamento Mensal (Pago)</h3>
                    <div class="h-72">
                        <canvas id="revenueChart"></canvas>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-4 italic text-center">Evolução do faturamento recebido nos últimos meses.</p>
                </div>

                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-pink-100">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 text-center md:text-left">Assiduidade Mensal (Presenças)</h3>
                    <div class="h-72">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-4 italic text-center">Comparativo entre sessões agendadas e confirmadas como realizadas.</p>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#9ca3af';

        // 1. Gráfico de Receita
        const ctxRevenue = document.getElementById('revenueChart');
        new Chart(ctxRevenue, {
            type: 'line',
            plugins: [ChartDataLabels],
            data: {
                labels: {!! json_encode($faturamentoMensal->pluck('mes')) !!},
                datasets: [{
                    label: 'R$ Recebido',
                    data: {!! json_encode($faturamentoMensal->pluck('total')) !!},
                    borderColor: '#ec4899',
                    backgroundColor: 'rgba(236, 72, 153, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointBackgroundColor: '#ec4899',
                    pointRadius: 6,
                    datalabels: {
                        align: 'top',
                        offset: 8,
                        color: '#ec4899',
                        font: { weight: 'bold', size: 11 },
                        formatter: (value) => 'R$ ' + value.toLocaleString('pt-BR')
                    }
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: { padding: { top: 30, left: 10, right: 20 } },
                plugins: { 
                    legend: { display: false },
                    datalabels: { display: true }
                },
                scales: { 
                    y: { display: false, beginAtZero: true },
                    x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                }
            }
        });

        // 2. Gráfico de Assiduidade (CORRIGIDO PARA NÃO SOBREPOR)
        const ctxAttendance = document.getElementById('attendanceChart');
        new Chart(ctxAttendance, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
                labels: {!! json_encode($assiduidadeMensal->pluck('mes')) !!},
                datasets: [
                    {
                        label: 'Agendadas',
                        data: {!! json_encode($assiduidadeMensal->pluck('agendadas')) !!},
                        backgroundColor: '#f3f4f6',
                        borderRadius: 6,
                        datalabels: {
                            color: '#9ca3af',
                            anchor: 'end',
                            align: 'top',
                            offset: 4
                        }
                    },
                    {
                        label: 'Realizadas',
                        data: {!! json_encode($assiduidadeMensal->pluck('realizadas')) !!},
                        backgroundColor: '#ec4899',
                        borderRadius: 6,
                        datalabels: {
                            color: '#ec4899',
                            anchor: 'end',
                            align: 'top',
                            offset: 4
                        }
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 40, // Espaço para os números respirarem
                        bottom: 10
                    }
                },
                plugins: {
                    legend: { 
                        position: 'top',
                        align: 'start', // LEGENDA PARA A ESQUERDA: libera o espaço acima das barras
                        labels: { 
                            boxWidth: 12, 
                            padding: 20,
                            font: { size: 11, weight: '800' } 
                        }
                    },
                    datalabels: {
                        font: { weight: 'bold', size: 12 },
                        formatter: (value) => value > 0 ? value : '0'
                    }
                },
                scales: {
                    y: { 
                        display: false, 
                        beginAtZero: true,
                        suggestedMax: function(context) {
                            // Dinamicamente aumenta o topo do gráfico em 20% para o número não bater no teto
                            return Math.max(...context.chart.data.datasets[0].data) * 1.2;
                        }
                    },
                    x: { grid: { display: false }, ticks: { font: { weight: 'bold' } } }
                }
            }
        });
    </script>
</x-app-layout>