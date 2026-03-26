<x-app-layout>
    <div class="py-6 bg-[#FDF2F4] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm flex items-center shadow-sm">
                    <span class="mr-2">✅</span> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-sm border border-pink-100 overflow-hidden">
                
                <div class="p-6 border-b border-gray-50 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4 flex-1">
                        <form action="{{ route('pacientes') }}" method="GET" class="flex items-center gap-3">
                            <input type="hidden" name="mes" value="{{ $month }}">
                            <input type="hidden" name="ano" value="{{ $year }}">
                            <input type="hidden" name="filter" value="{{ $filter ?? 'todos' }}">

                            <div class="relative group">
                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar paciente..." class="pl-12 pr-4 py-2.5 border-none bg-gray-50 rounded-full text-sm focus:ring-2 focus:ring-pink-200 w-64 shadow-inner transition-all">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-pink-400 transition-colors">🔍</span>
                            </div>
                            
                            @if(!empty($search))
                                <a href="{{ route('pacientes', ['mes' => $month, 'ano' => $year, 'filter' => $filter ?? 'todos']) }}" class="text-[10px] bg-pink-50 text-pink-500 px-3 py-1 rounded-full font-black hover:bg-pink-100 transition uppercase tracking-tighter">Limpar</a>
                            @endif
                            <button type="submit" class="hidden">Buscar</button>
                        </form>

                        <div class="flex gap-2 bg-gray-50 p-1 rounded-full border border-gray-100">
                            <a href="{{ route('pacientes', ['mes' => $month, 'ano' => $year, 'search' => $search, 'filter' => 'todos']) }}" class="px-4 py-1.5 rounded-full text-[10px] font-bold transition {{ ($filter ?? 'todos') !== 'agendados' ? 'bg-white text-pink-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">● Todos</a>
                            <a href="{{ route('pacientes', ['mes' => $month, 'ano' => $year, 'search' => $search, 'filter' => 'agendados']) }}" class="px-4 py-1.5 rounded-full text-[10px] font-bold transition {{ ($filter ?? '') === 'agendados' ? 'bg-pink-500 text-white shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">🗓️ Agendados no Mês</a>
                        </div>
                    </div>

                    <a href="{{ route('patients.create') }}" class="bg-pink-500 hover:bg-pink-600 text-white px-8 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-pink-100 transition transform hover:-translate-y-0.5 active:scale-95">+ Cadastrar Paciente</a>
                </div>

                <div class="flex items-center justify-start gap-1 px-6 overflow-x-auto py-4 bg-gray-50/30">
                    @foreach(range(1, 12) as $m)
                        @php $dataMes = \Carbon\Carbon::create(null, $m, 1); $isActive = ($month == $m); @endphp
                        <a href="{{ route('pacientes', ['mes' => $m, 'ano' => $year, 'search' => $search, 'filter' => $filter ?? 'todos']) }}" class="px-4 py-1.5 rounded-full text-[10px] font-bold transition whitespace-nowrap {{ $isActive ? 'bg-pink-500 text-white shadow-md' : 'bg-white text-gray-400 border border-gray-100 hover:bg-pink-50' }}">{{ $dataMes->translatedFormat('M') }}</a>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-white border-b border-gray-50">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Resumo Mensal: {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</h4>
                </div>

                <table class="min-w-full text-center">
                    <thead class="bg-pink-50/30">
                        <tr class="text-[10px] uppercase tracking-wider text-pink-700">
                            <th class="px-6 py-3 text-left text-xs font-bold text-pink-500 uppercase border-b border-pink-100">Nome</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase border-b border-pink-100">Sessões</th>
                            <th class="px-4 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-pink-100 italic">Serviço 1</th>
                            <th class="px-4 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-pink-100 italic">Serviço 2</th>
                            <th class="px-4 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-[0.15em] border-b border-pink-100 italic">Serviço 3</th>
                            <th class="px-4 py-3 text-xs font-bold text-pink-500 uppercase font-black italic border-b border-pink-100">Total a Pagar</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-pink-500 uppercase border-b border-pink-100">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($patients as $patient)
                        <tr class="hover:bg-pink-50/10 transition">
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-500 font-bold mr-3 border border-pink-200 shadow-sm">{{ substr($patient->name, 0, 1) }}</div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-700 leading-tight">{{ $patient->name }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $patient->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="inline-flex items-center justify-center bg-gray-50 border border-gray-100 rounded-lg px-3 py-1">
                                    <span class="text-sm font-black text-gray-700">{{ $patient->total_sessoes_mes ?? 0 }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="block text-sm font-medium text-gray-600">{{ $patient->s1_count ?? 0 }}x</span>
                                <span class="text-[10px] text-gray-400">R$ {{ number_format($patient->s1_sum ?? 0, 2, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="block text-sm font-medium text-gray-600">{{ $patient->s2_count ?? 0 }}x</span>
                                <span class="text-[10px] text-gray-400">R$ {{ number_format($patient->s2_sum ?? 0, 2, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="block text-sm font-medium text-gray-600">{{ $patient->s3_count ?? 0 }}x</span>
                                <span class="text-[10px] text-gray-400">R$ {{ number_format($patient->s3_sum ?? 0, 2, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-4"><span class="text-sm font-black text-pink-600 bg-pink-50 px-3 py-1 rounded-lg border border-pink-100">R$ {{ number_format($patient->total_mes ?? 0, 2, ',', '.') }}</span></td>
                            
                            <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                @if($patient->total_mes > 0 && $patient->pendentes_no_mes > 0)
                                    <form action="{{ route('patients.payMonth', $patient->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="mes" value="{{ $month }}">
                                        <input type="hidden" name="ano" value="{{ $year }}">
                                        <button type="submit" class="p-2 bg-pink-50 text-pink-500 rounded-xl hover:bg-pink-500 hover:text-white transition shadow-sm border border-pink-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                    </form>
                                @elseif($patient->total_mes > 0)
                                    <form action="{{ route('patients.refundMonth', $patient->id) }}" method="POST" onsubmit="return confirm('Deseja reverter os pagamentos deste mês?')">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="mes" value="{{ $month }}">
                                        <input type="hidden" name="ano" value="{{ $year }}">
                                        <button type="submit" class="p-2 text-green-500 bg-green-50 rounded-xl border border-green-100 hover:bg-red-50 hover:text-red-500 transition shadow-sm group">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 block group-hover:hidden" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden group-hover:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif

                                @php
                                    $keyMes = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                                    $jaEnviou = isset($patient->whatsapp_check_log[$keyMes]) && $patient->whatsapp_check_log[$keyMes];
                                    $mensagem = "Olá " . explode(' ', trim($patient->name))[0] . ", tudo bem? Aqui é da clínica da Lydia Sena. Passando para confirmar suas " . ($patient->total_sessoes_mes) . " sessões deste mês. O valor total é de R$ " . number_format($patient->total_mes, 2, ',', '.') . ". Podemos confirmar?";
                                    $urlZap = "https://wa.me/" . preg_replace('/\D/', '', $patient->phone) . "?text=" . urlencode($mensagem);
                                @endphp

                                <div class="relative flex items-center">
                                    <a href="{{ $urlZap }}" target="_blank" onclick="marcarComoEnviado(this, '{{ $patient->id }}', '{{ $month }}', '{{ $year }}')"
                                       class="p-2 {{ $jaEnviou ? 'bg-green-600 text-white' : 'bg-green-50 text-green-600' }} rounded-xl hover:scale-110 transition shadow-sm border border-green-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.237 3.483 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.652zm5.586-3.822c1.552.921 3.469 1.408 5.424 1.409 5.861 0 10.63-4.77 10.632-10.633.001-2.846-1.107-5.522-3.117-7.533-2.011-2.012-4.689-3.12-7.535-3.121-5.865 0-10.634 4.77-10.636 10.633-.001 2.035.534 4.021 1.549 5.79l-1.018 3.719 3.805-.998z"/>
                                        </svg>
                                    </a>
                                    @if($jaEnviou)
                                        <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-[7px] font-bold px-1 rounded-full border border-white shadow-sm">OK</span>
                                    @endif
                                </div>

                                <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center px-4 py-2 bg-white text-pink-500 border border-pink-100 rounded-full text-[10px] font-black uppercase hover:bg-pink-50 transition shadow-sm">Visualizar</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="py-20 text-center text-gray-300 italic text-sm">Nenhum paciente encontrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="bg-pink-50/50 p-6 border-t border-pink-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-8">
                        <div>
                            <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">Pacientes Pagos</p>
                            <p class="text-2xl font-serif text-gray-800">
                                @php
                                    $totalAgendados = $patients->where('total_mes', '>', 0)->count();
                                    $totalPagos = $patients->where('total_mes', '>', 0)->where('pendentes_no_mes', 0)->count();
                                @endphp
                                <span class="text-green-500">{{ $totalPagos }}</span><span class="text-gray-300 mx-1">/</span>{{ $totalAgendados }} <span class="text-[10px] font-sans text-gray-400 uppercase font-black tracking-tighter">Pacientes</span>
                            </p>
                        </div>
                        <div class="h-10 w-px bg-pink-200 hidden md:block"></div>
                        <div>
                            <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">Previsão de Faturamento</p>
                            <p class="text-2xl font-serif text-pink-600">R$ {{ number_format($patients->sum('total_mes'), 2, ',', '.') }}</p>
                        </div>
                    </div>
                    <button onclick="window.print()" class="inline-flex items-center gap-2 bg-white text-pink-500 border border-pink-200 px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase shadow-sm hover:bg-pink-50 transition tracking-widest">Gerar Relatório</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function marcarComoEnviado(element, patientId, mes, ano) {
        element.classList.remove('bg-green-50', 'text-green-600');
        element.classList.add('bg-green-600', 'text-white');
        
        fetch(`/pacientes/${patientId}/mark-whatsapp-sent?mes=${mes}&ano=${ano}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        });
    }
</script>