<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Cabeçalho com Seletor de Ano --}}
            <div class="mb-12 flex flex-col md:flex-row justify-between items-end border-b border-[#E1D3C1] pb-8 gap-4 font-sans">
                <div>
                    <h2 class="text-4xl text-gray-800 italic font-serif">Relatórios de Performance</h2>
                    <p class="text-[#8C846C]/60 text-[10px] font-black uppercase tracking-[0.3em] mt-3">Análise estratégica de faturamento e engajamento</p>
                </div>

                {{-- Passador de Anos --}}
                <div class="flex items-center gap-2 bg-white p-1.5 rounded-2xl border border-[#E1D3C1] shadow-sm">
                    <span class="text-[9px] font-black text-[#8C846C]/40 uppercase px-3 tracking-widest">Ano de Referência</span>
                    <div class="flex gap-1">
                        @php $anoAtual = \Carbon\Carbon::now()->year; @endphp
                        @foreach(range($anoAtual - 2, $anoAtual + 1) as $ano)
                            <a href="{{ route('reports.index', ['ano' => $ano]) }}" 
                               class="px-4 py-2 rounded-xl text-[10px] font-black transition-all {{ $anoFoco == $ano ? 'bg-[#8C846C] text-white shadow-md' : 'text-[#8C846C] hover:bg-[#F9F6F3]' }}">
                                {{ $ano }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                
                {{-- Card: Faturamento Mensal --}}
                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-[#E1D3C1] flex flex-col h-full">
                    <div class="mb-8">
                        <h3 class="text-[10px] font-black text-[#8C846C]/60 uppercase tracking-widest font-sans">Faturamento Mensal (Líquido)</h3>
                        <p class="text-[11px] text-gray-400 mt-1 italic">Evolução dos valores recebidos no período</p>
                    </div>
                    
                    <div class="flex-grow h-72">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- Card: Engajamento e Presenças --}}
                <div class="bg-white p-10 rounded-[3rem] shadow-sm border border-[#E1D3C1] flex flex-col h-full">
                    <div class="mb-8">
                        <h3 class="text-[10px] font-black text-[#8C846C]/60 uppercase tracking-widest font-sans">Engajamento e Presenças</h3>
                        <p class="text-[11px] text-gray-400 mt-1 italic">Relação entre sessões agendadas e realizadas</p>
                    </div>

                    <div class="flex-grow h-72">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        // Configurações Globais
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#8C846C';
        
        // 1. Gráfico de Receita (Linhas Orgânicas)
        const ctxRevenue = document.getElementById('revenueChart');
        new Chart(ctxRevenue, {
            type: 'line',
            plugins: [ChartDataLabels],
            data: {
                labels: {!! json_encode($faturamentoMensal->pluck('mes')) !!},
                datasets: [{
                    label: 'R$ Recebido',
                    data: {!! json_encode($faturamentoMensal->pluck('total')) !!},
                    borderColor: '#8C846C',
                    backgroundColor: 'rgba(140, 132, 108, 0.05)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: '#8C846C',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    datalabels: {
                        align: 'top',
                        offset: 8,
                        color: '#8C846C',
                        font: { weight: '800', size: 10 },
                        formatter: (value) => 'R$ ' + Number(value).toLocaleString('pt-BR', { minimumFractionDigits: 2 }),
                        // SOLUÇÃO PARA NÃO CORTAR:
                        clip: false, // Impede que o texto seja cortado se sair da área do gráfico
                        clamp: true  // Tenta manter o rótulo dentro do canvas se possível
                    }
                }]
            },
            options: {
                maintainAspectRatio: false,
                // SOLUÇÃO PARA NÃO CORTAR: Aumentamos o padding lateral e superior
                layout: {
                    padding: {
                        top: 40,
                        left: 35,  // Espaço para o "R$" inicial não bater na borda
                        right: 35, // Espaço para o final do número
                        bottom: 10
                    }
                },
                plugins: { 
                    legend: { display: false },
                    datalabels: { display: true }
                },
                scales: { 
                    y: { 
                        display: false, 
                        beginAtZero: true,
                        // Garantimos que o topo do gráfico sempre tenha fôlego para o texto
                        grace: '15%' 
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { font: { weight: 'bold', size: 9 } } 
                    }
                }
            }
        });

        // 2. Gráfico de Assiduidade (Barras)
        const ctxAttendance = document.getElementById('attendanceChart');
        new Chart(ctxAttendance, {
            type: 'bar',
            plugins: [ChartDataLabels],
            data: {
                // CORREÇÃO: Agora usa explicitamente as labels da coleção de assiduidade
                labels: {!! json_encode($assiduidadeMensal->pluck('mes')) !!},
                datasets: [
                    {
                        label: 'Agendadas',
                        data: {!! json_encode($assiduidadeMensal->pluck('agendadas')) !!},
                        backgroundColor: '#E1D3C1',
                        borderRadius: 8,
                        maxBarThickness: 30,
                        datalabels: { color: '#8C846C', anchor: 'end', align: 'top', offset: 4 }
                    },
                    {
                        label: 'Realizadas',
                        data: {!! json_encode($assiduidadeMensal->pluck('realizadas')) !!},
                        backgroundColor: '#8C846C',
                        borderRadius: 8,
                        maxBarThickness: 30,
                        datalabels: { color: '#fff', anchor: 'center', align: 'center' }
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                layout: { padding: { top: 40 } },
                plugins: {
                    legend: { 
                        position: 'top',
                        align: 'end',
                        labels: { 
                            boxWidth: 8, 
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 10, weight: 'bold' } 
                        }
                    },
                    datalabels: {
                        font: { weight: '800', size: 10 }
                    }
                },
                scales: {
                    y: { 
                        display: false, 
                        beginAtZero: true,
                        suggestedMax: function(context) {
                            const max = Math.max(...context.chart.data.datasets[0].data);
                            return max + (max * 0.3);
                        }
                    },
                    x: { 
                        grid: { display: false }, 
                        ticks: { font: { weight: 'bold', size: 9 } } 
                    }
                }
            }
        });
    </script>
</x-app-layout>