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
                                <input type="text" 
                                       name="search" 
                                       value="{{ $search ?? '' }}" 
                                       placeholder="Buscar paciente..." 
                                       class="pl-12 pr-4 py-2.5 border-none bg-gray-50 rounded-full text-sm focus:ring-2 focus:ring-pink-200 w-64 shadow-inner transition-all"
                                >
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-pink-400 transition-colors">
                                    🔍
                                </span>
                            </div>
                            
                            @if(!empty($search))
                                <a href="{{ route('pacientes', ['mes' => $month, 'ano' => $year, 'filter' => $filter ?? 'todos']) }}" class="text-[10px] bg-pink-50 text-pink-500 px-3 py-1 rounded-full font-black hover:bg-pink-100 transition uppercase tracking-tighter">
                                    Limpar
                                </a>
                            @endif
                            <button type="submit" class="hidden">Buscar</button>
                        </form>

                        <div class="flex gap-2 bg-gray-50 p-1 rounded-full border border-gray-100">
                            <a href="{{ route('pacientes', ['mes' => $month, 'ano' => $year, 'search' => $search, 'filter' => 'todos']) }}" 
                               class="px-4 py-1.5 rounded-full text-[10px] font-bold transition {{ ($filter ?? 'todos') !== 'agendados' ? 'bg-white text-pink-600 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                                ● Todos
                            </a>
                            <a href="{{ route('pacientes', ['mes' => $month, 'ano' => $year, 'search' => $search, 'filter' => 'agendados']) }}" 
                               class="px-4 py-1.5 rounded-full text-[10px] font-bold transition {{ ($filter ?? '') === 'agendados' ? 'bg-pink-500 text-white shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                                🗓️ Agendados no Mês
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('patients.create') }}" class="bg-pink-500 hover:bg-pink-600 text-white px-8 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-pink-100 transition transform hover:-translate-y-0.5 active:scale-95">
                        + Cadastrar Paciente
                    </a>
                </div>

                <div class="flex items-center justify-start gap-1 px-6 overflow-x-auto py-4 bg-gray-50/30">
                    @foreach(range(1, 12) as $m)
                        @php
                            $dataMes = \Carbon\Carbon::create(null, $m, 1);
                            $isActive = ($month == $m);
                        @endphp
                        <a href="{{ route('pacientes', ['mes' => $m, 'ano' => $year, 'search' => $search, 'filter' => $filter ?? 'todos']) }}" 
                        class="px-4 py-1.5 rounded-full text-[10px] font-bold transition whitespace-nowrap {{ $isActive ? 'bg-pink-500 text-white shadow-md' : 'bg-white text-gray-400 border border-gray-100 hover:bg-pink-50' }}">
                            {{ $dataMes->translatedFormat('M') }}
                        </a>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-white border-b border-gray-50">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                        Resumo Mensal: {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                    </h4>
                </div>

                <table class="min-w-full text-center">
                    <thead class="bg-pink-50/30">
                        <tr class="text-[10px] uppercase tracking-wider text-pink-700">
                            <th class="px-6 py-3 text-left text-xs font-bold text-pink-500 uppercase border-b border-pink-100">Nome</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase border-b border-pink-100">Serviço 1</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase border-b border-pink-100">Serviço 2</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase border-b border-pink-100">Serviço 3</th>
                            <th class="px-4 py-3 text-xs font-bold text-pink-500 uppercase font-black italic border-b border-pink-100">Total a Pagar</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-pink-500 uppercase border-b border-pink-100">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($patients as $patient)
                        <tr class="hover:bg-pink-50/10 transition">
                            <td class="px-6 py-4 text-left">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-500 font-bold mr-3 border border-pink-200 shadow-sm">
                                        {{ substr($patient->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-700 leading-tight">{{ $patient->name }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $patient->email }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-4 py-4">
                                <span class="block text-sm font-bold text-gray-700">{{ $patient->s1_count ?? 0 }}x</span>
                                <span class="text-[11px] text-gray-400 font-medium">R$ {{ number_format($patient->s1_sum ?? 0, 2, ',', '.') }}</span>
                            </td>

                            <td class="px-4 py-4">
                                <span class="block text-sm font-bold text-gray-700">{{ $patient->s2_count ?? 0 }}x</span>
                                <span class="text-[11px] text-gray-400 font-medium">R$ {{ number_format($patient->s2_sum ?? 0, 2, ',', '.') }}</span>
                            </td>

                            <td class="px-4 py-4">
                                <span class="block text-sm font-bold text-gray-700">{{ $patient->s3_count ?? 0 }}x</span>
                                <span class="text-[11px] text-gray-400 font-medium">R$ {{ number_format($patient->s3_sum ?? 0, 2, ',', '.') }}</span>
                            </td>

                            <td class="px-4 py-4">
                                <span class="text-sm font-black text-pink-600 bg-pink-50 px-3 py-1 rounded-lg border border-pink-100">
                                    R$ {{ number_format($patient->total_mes ?? 0, 2, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center px-3 py-1.5 bg-white text-pink-500 border border-pink-200 rounded-lg text-[10px] font-black uppercase hover:bg-pink-50 transition shadow-sm">
                                    Visualizar
                                </a>
                                <a href="{{ route('patients.edit', $patient->id) }}" class="inline-flex items-center px-3 py-1.5 bg-gray-50 text-gray-400 border border-gray-200 rounded-lg text-[10px] font-bold uppercase hover:bg-gray-200 transition">
                                    Editar
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center">
                                <p class="text-gray-300 italic text-sm">Nenhum paciente encontrado com esses filtros.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="bg-pink-50/50 p-6 border-t border-pink-100 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-8">
                        <div>
                            <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">Pacientes no período</p>
                            <p class="text-2xl font-serif text-gray-800">{{ $patients->where('total_mes', '>', 0)->count() }}</p>
                        </div>
                        
                        <div class="h-10 w-px bg-pink-200 hidden md:block"></div>

                        <div>
                            <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">Previsão de Faturamento</p>
                            <p class="text-2xl font-serif text-pink-600">
                                R$ {{ number_format($patients->sum('total_mes'), 2, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <button onclick="window.print()" class="inline-flex items-center gap-2 bg-white text-pink-500 border border-pink-200 px-6 py-2.5 rounded-2xl text-[10px] font-black uppercase shadow-sm hover:bg-pink-50 transition tracking-widest">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Gerar Relatório
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>