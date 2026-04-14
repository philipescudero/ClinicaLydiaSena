<x-app-layout>
    <div class="py-12 bg-[#FDF2F4] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-end mb-8">
                <div>
                    <p class="text-pink-500 font-bold uppercase text-[10px] tracking-[0.2em] mb-1">Bem-vinda de volta</p>
                    <h2 class="text-3xl font-serif text-gray-800 italic">Olá, Lydia Sena</h2>
                </div>
                <div class="text-right flex flex-col items-end gap-2">
                    <div class="flex gap-1">
                        {{-- Link para o mês anterior --}}
                        <a href="{{ route('dashboard', ['mes' => $inicioMes->copy()->subMonth()->month, 'ano' => $inicioMes->copy()->subMonth()->year]) }}" class="bg-white p-1.5 rounded-lg border border-pink-100 text-pink-500 hover:bg-pink-50 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15 19l-7-7 7-7" /></svg>
                        </a>
                        
                        <span class="bg-white px-4 py-2 rounded-2xl shadow-sm border border-pink-100 text-pink-500 font-bold text-[10px] uppercase tracking-wider">
                            Referência: {{ $inicioMes->translatedFormat('F Y') }}
                        </span>

                        {{-- Link para o próximo mês --}}
                        <a href="{{ route('dashboard', ['mes' => $inicioMes->copy()->addMonth()->month, 'ano' => $inicioMes->copy()->addMonth()->year]) }}" class="bg-white p-1.5 rounded-lg border border-pink-100 text-pink-500 hover:bg-pink-50 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-pink-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-2">Faturamento Realizado</p>
                    <p class="text-3xl font-serif text-green-600">R$ {{ number_format($faturamentoRealizado, 2, ',', '.') }}</p>
                    <div class="mt-4 h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                        @php $progresso = $faturamentoPrevisto > 0 ? ($faturamentoRealizado / $faturamentoPrevisto) * 100 : 0; @endphp
                        <div class="h-full bg-green-500 transition-all duration-500" style="width: {{ $progresso }}%"></div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-pink-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10 text-pink-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-2">Previsão Mensal (Bruto)</p>
                    <p class="text-3xl font-serif text-pink-600">R$ {{ number_format($faturamentoPrevisto, 2, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-400 mt-4 italic">* Total agendado para o mês</p>
                </div>

                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-pink-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5 text-pink-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    </div>

                    <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-4">Aproveitamento das Sessões</p>
                    
                    <div class="flex items-end justify-between mb-4">
                        <div>
                            <div class="flex items-baseline gap-1">
                                <p class="text-3xl font-serif text-pink-600">{{ $sessoesRealizadasDoMes }}</p>
                                <p class="text-lg font-serif text-gray-300">/ {{ $totalSessoesDoMes }}</p>
                            </div>
                            <p class="text-[9px] text-gray-400 uppercase font-black tracking-tighter">Realizadas no mês</p>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-xl font-serif text-gray-800">{{ number_format($porcentagemConclusao, 0) }}%</p>
                            <p class="text-[9px] text-gray-400 uppercase font-black tracking-tighter">Concluído</p>
                        </div>
                    </div>

                    <div class="h-2 w-full bg-pink-50 rounded-full overflow-hidden border border-pink-100/50">
                        <div class="h-full bg-gradient-to-r from-pink-400 to-pink-500 transition-all duration-1000 shadow-[0_0_8px_rgba(236,72,153,0.3)]" 
                            style="width: {{ $porcentagemConclusao }}%"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <div class="lg:col-span-1">
                    <div class="bg-white/60 backdrop-blur-sm rounded-[2rem] shadow-sm border border-pink-100 overflow-hidden h-full">
                        <div class="p-5 border-b border-pink-50 bg-white/80 text-left">
                            <h3 class="text-xs font-bold text-gray-800 flex items-center gap-2 text-[10px] uppercase tracking-wider">📍 GPS: Próximos 7 dias</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            @forelse($sessoesSemana as $sessao)
                                <div class="flex items-center gap-3 p-3 rounded-2xl border transition-all {{ $sessao->performed ? 'bg-green-50 border-green-200 opacity-70' : 'bg-white border-pink-50 shadow-sm' }}">
                                    <div class="text-center min-w-[42px] border-r border-pink-50 pr-2">
                                        <span class="block text-xs font-black text-pink-500">{{ \Carbon\Carbon::parse($sessao->session_date)->format('d') }}</span>
                                        <span class="text-[8px] uppercase font-bold text-gray-400">{{ \Carbon\Carbon::parse($sessao->session_date)->translatedFormat('D') }}</span>
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <p class="text-xs font-bold text-gray-700 truncate text-left">{{ $sessao->patient->name }}</p>
                                        <p class="text-[9px] font-bold text-left {{ $sessao->performed ? 'text-green-500' : 'text-gray-400' }}">
                                            {{ \Carbon\Carbon::parse($sessao->session_date)->format('H:i') }} • {{ $sessao->performed ? 'REALIZADO' : 'AGENDADO' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-10 text-center"><p class="text-[10px] text-gray-400 italic">Sem consultas agendadas.</p></div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-pink-100 overflow-hidden relative min-h-[500px]">
                        <div class="relative z-10">
                            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white/90 backdrop-blur-sm">
                                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">🎛️ Operações do Mês</h3>
                                <a href="{{ route('pacientes') }}" class="text-[10px] font-black text-pink-500 uppercase hover:underline tracking-widest">Painel Financeiro →</a>
                            </div>
                            <div class="p-0 overflow-x-auto">
                                <table class="min-w-full bg-transparent text-left">
                                    <thead class="bg-pink-50/30 font-black text-[9px] uppercase tracking-[0.15em] text-pink-700">
                                        <tr><th class="px-6 py-4">Data / Paciente</th><th class="px-6 py-4 text-center">Status / Serviço</th><th class="px-6 py-4 text-right">Ações</th></tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse($sessoesMes as $sessao)
                                        <tr class="transition {{ $sessao->performed ? 'bg-green-50/50' : 'hover:bg-pink-50/10' }}">
                                            {{-- BARRA LATERAL VERDE CORRIGIDA --}}
                                            <td class="px-6 py-4 {{ $sessao->performed ? 'border-l-4 border-green-500' : '' }}">
                                                <div class="flex flex-col text-left">
                                                    <span class="text-[11px] font-black {{ $sessao->performed ? 'text-green-600' : 'text-pink-500' }}">{{ \Carbon\Carbon::parse($sessao->session_date)->format('d/m') }} às {{ \Carbon\Carbon::parse($sessao->session_date)->format('H:i') }}</span>
                                                    <span class="text-sm font-bold text-gray-700">{{ $sessao->patient->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="text-[8px] px-2 py-0.5 bg-white border border-gray-100 rounded-full font-black text-gray-400 uppercase">
                                                        {{ $sessao->service_type == 1 ? 'Psicoterapia' : ($sessao->service_type == 2 ? 'Avaliação' : 'Consultoria') }}
                                                    </span>
                                                    <div class="flex items-center gap-1">
                                                        <div class="h-1.5 w-1.5 rounded-full {{ $sessao->performed ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]' : 'bg-gray-300' }}"></div>
                                                        <span class="text-[9px] font-black uppercase {{ $sessao->performed ? 'text-green-600' : 'text-gray-400' }}">{{ $sessao->performed ? 'REALIZADA' : 'PENDENTE' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end items-center gap-2">
                                                    @if(!$sessao->performed)
                                                        <form id="perform-form-{{ $sessao->id }}" action="{{ route('sessions.markPerformed', $sessao->id) }}" method="POST">
                                                            @csrf @method('PATCH')
                                                            <button type="button" onclick="confirmarRealizacao({{ $sessao->id }}, '{{ $sessao->patient->name }}')" class="p-2 bg-white text-green-500 border border-green-100 rounded-xl hover:bg-green-500 hover:text-white transition shadow-sm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('sessions.reversePerformed', $sessao->id) }}" method="POST">@csrf @method('PATCH')
                                                            <button type="submit" class="p-2 bg-green-500 text-white border border-green-600 rounded-xl hover:bg-red-500 transition shadow-sm group" title="Reverter Status">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form id="delete-form-{{ $sessao->id }}" action="{{ route('sessions.destroy', $sessao->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="button" onclick="confirmarExclusao({{ $sessao->id }})" class="p-2 bg-white text-pink-300 border border-pink-100 rounded-xl hover:bg-pink-500 hover:text-white transition shadow-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('patients.show', $sessao->patient_id) }}" class="p-2 bg-white text-gray-400 border border-gray-100 rounded-xl hover:bg-pink-50 hover:text-pink-500 transition shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="3" class="py-16 text-center text-gray-300 italic text-sm">Nenhum agendamento encontrado para este mês.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 bg-white rounded-[2rem] shadow-sm border border-pink-100 overflow-hidden max-w-5xl mx-auto">
                <div class="p-4 border-b border-gray-50 bg-white flex justify-between items-center">
                    <h3 class="text-[11px] font-black text-gray-700 uppercase tracking-widest">🗓️ Mapa de Horários Semanal</h3>
                    <div class="flex items-center gap-2 bg-pink-50/50 p-1 rounded-xl border border-pink-100">
                        {{-- Navegação de Semana --}}
                        <a href="{{ route('dashboard', ['semana' => $inicioSemana->copy()->subWeek()->format('Y-m-d'), 'mes' => $inicioMes->month, 'ano' => $inicioMes->year]) }}" class="p-1.5 hover:bg-white rounded-lg text-pink-500 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15 19l-7-7 7-7" /></svg>
                        </a>
                        <span class="text-[9px] font-black text-pink-600 uppercase px-1">
                            {{ $inicioSemana->translatedFormat('d M') }} — {{ $inicioSemana->copy()->addDays(4)->translatedFormat('d M') }}
                        </span>
                        <a href="{{ route('dashboard', ['semana' => $inicioSemana->copy()->addWeek()->format('Y-m-d'), 'mes' => $inicioMes->month, 'ano' => $inicioMes->year]) }}" class="p-1.5 hover:bg-white rounded-lg text-pink-500 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full table-fixed border-separate border-spacing-1 px-2 pb-2">
                        <thead>
                            <tr>
                                <th class="w-16 py-2 text-[9px] font-black uppercase text-gray-300 text-center">Hora</th>
                                @for($i = 0; $i < 5; $i++)
                                    @php $dia = $inicioSemana->copy()->addDays($i); @endphp
                                    <th class="py-2 {{ $dia->isToday() ? 'bg-pink-50 rounded-t-xl border border-pink-100 border-b-0' : '' }}">
                                        <span class="block text-[10px] font-black text-gray-500 uppercase">{{ $dia->translatedFormat('D') }}</span>
                                        <span class="text-[9px] font-bold text-pink-300">{{ $dia->format('d/m') }}</span>
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($horariosPermitidos as $hora)
                                <tr>
                                    <td class="py-1 text-center">
                                        <span class="text-[9px] font-bold text-gray-300">{{ $hora }}</span>
                                    </td>
                                    @for($i = 0; $i < 5; $i++)
                                        @php 
                                            $dataStr = $inicioSemana->copy()->addDays($i)->format('Y-m-d');
                                            $sessoesNoSlot = $gradeHorarios[$dataStr][$hora] ?? [];
                                            $ocupado = $sessoesNoSlot[0] ?? null;
                                            $temConflito = count($sessoesNoSlot) > 1;
                                        @endphp
                                        <td class="relative h-12 transition-all duration-300 {{ $inicioSemana->copy()->addDays($i)->isToday() ? 'bg-pink-50/20' : '' }}">
                                            @if($ocupado)
                                                {{-- CARD DO PACIENTE (OCUPADO) --}}
                                                <div class="absolute inset-0.5 rounded-xl shadow-sm flex flex-col items-center justify-center px-1 overflow-hidden group transition-all
                                                    {{ $temConflito ? 'bg-red-500 animate-pulse shadow-red-200' : ($ocupado->performed ? 'bg-gradient-to-br from-green-500 to-green-600 shadow-green-100' : 'bg-gradient-to-br from-pink-500 to-pink-600 shadow-pink-100') }}">
                                                    
                                                    {{-- CONTEÚDO NORMAL --}}
                                                    <div class="group-hover:opacity-20 transition-opacity duration-300 flex flex-col items-center">
                                                        @if($ocupado->performed)
                                                            <div class="absolute top-1 right-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-2 w-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg></div>
                                                        @endif
                                                        <span class="text-[8px] font-black text-white uppercase leading-none text-center truncate w-full">{{ $ocupado->patient->name }}</span>
                                                        <span class="text-[7px] font-medium {{ $ocupado->performed ? 'text-green-100' : 'text-pink-100' }} uppercase mt-0.5 tracking-tighter text-center">
                                                            {{ $ocupado->service_type == 1 ? 'Psicoterapia' : ($ocupado->service_type == 2 ? 'Avaliação' : 'Consultoria') }}
                                                        </span>
                                                    </div>

                                                    {{-- BOTÕES DE AÇÃO NO HOVER (DESIGN REFINADO) --}}
                                                    <div class="absolute inset-0 flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 bg-white/40 backdrop-blur-[2px] transition-all duration-300 rounded-xl">
                                                        @if(!$ocupado->performed)
                                                            <form id="perform-mapa-{{ $ocupado->id }}" action="{{ route('sessions.markPerformed', $ocupado->id) }}" method="POST">
                                                                @csrf @method('PATCH')
                                                                <button type="button" onclick="confirmarRealizacaoMapa({{ $ocupado->id }}, '{{ $ocupado->patient->name }}')" class="p-1 bg-green-500/90 text-white rounded-lg hover:bg-green-600 transition shadow-sm">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form action="{{ route('sessions.reversePerformed', $ocupado->id) }}" method="POST">
                                                                @csrf @method('PATCH')
                                                                <button type="submit" class="p-1 bg-orange-500/90 text-white rounded-lg hover:bg-orange-600 transition shadow-sm">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        <form id="delete-mapa-{{ $ocupado->id }}" action="{{ route('sessions.destroy', $ocupado->id) }}" method="POST">
                                                            @csrf @method('DELETE')
                                                            <button type="button" onclick="confirmarExclusaoMapa({{ $ocupado->id }})" class="p-1 bg-pink-500/90 text-white rounded-lg hover:bg-pink-600 transition shadow-sm">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                            </button>
                                                        </form>

                                                        <a href="{{ route('patients.show', $ocupado->patient_id) }}" class="p-1 bg-gray-600/90 text-white rounded-lg hover:bg-gray-800 transition shadow-sm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                        </a>
                                                    </div>
                                                </div>
                                                
                                            @else
                                                {{-- BOTÃO DE ADICIONAR (LÓGICA DO HOVER RECURSIVO CORRIGIDA) --}}
                                                <div onclick="abrirModalAgendamento('{{ $dataStr }}', '{{ $hora }}')" 
                                                    class="absolute inset-0.5 border border-dashed border-gray-100 rounded-xl flex items-center justify-center hover:border-pink-200 hover:bg-pink-50 transition-all group cursor-pointer">
                                                    <span class="text-gray-100 group-hover:text-pink-300 font-black text-sm transition-colors">+</span>
                                                </div>
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div id="modalAgendamentoRapido" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center z-[100] px-4">
            <div class="bg-white rounded-[2rem] p-8 max-w-md w-full shadow-2xl border border-pink-100 transition-all transform scale-95">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800 italic font-serif">Agendamento Rápido</h3>
                    <button onclick="fecharModalRapido()" class="text-gray-400 hover:text-pink-500 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('sessions.store_fast') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] uppercase font-black text-gray-400 mb-2 tracking-widest text-left">Paciente</label>
                        <select name="patient_id" required class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm focus:ring-pink-500">
                            <option value="">Selecione um paciente...</option>
                            @foreach($todosPacientes as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase font-black text-gray-400 mb-2 tracking-widest text-left">Data Selecionada</label>
                            <input type="date" name="session_date" id="input_data_rapida" readonly class="w-full rounded-xl border-gray-100 bg-gray-100 text-sm font-bold text-gray-500">
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-black text-gray-400 mb-2 tracking-widest text-left">Horário</label>
                            <input type="time" name="session_time" id="input_hora_rapida" readonly class="w-full rounded-xl border-gray-100 bg-gray-100 text-sm font-bold text-gray-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] uppercase font-black text-gray-400 mb-2 tracking-widest text-left">Serviço</label>
                            <select name="service_type" id="service_selector_rapido" onchange="atualizarPrecoRapido(this.value)" class="w-full rounded-xl border-gray-100 bg-gray-50 text-sm">
                                <option value="1">Psicoterapia</option>
                                <option value="2">Avaliação</option>
                                <option value="3">Consultoria</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase font-black text-gray-400 mb-2 tracking-widest text-left">Valor (R$)</label>
                            <input type="number" name="value" id="price_rapido" value="120.00" step="0.01" class="w-full rounded-xl border-gray-100 bg-gray-50 font-bold text-pink-600">
                        </div>
                    </div>
                    <div class="flex items-center gap-2 p-3 bg-pink-50/50 rounded-2xl border border-pink-100 transition-all hover:bg-pink-50">
                        <input type="checkbox" name="is_recurrent" id="is_recurrent_rapido" class="rounded text-pink-500 focus:ring-pink-500 h-4 w-4">
                        <label for="is_recurrent_rapido" class="text-[11px] font-bold text-pink-700 italic cursor-pointer">
                            Repetir semanalmente até fim do ano
                        </label>
                    </div>
                    <button type="submit" class="w-full bg-pink-500 text-white py-4 rounded-2xl font-bold shadow-lg shadow-pink-200 hover:bg-pink-600 transition duration-300">
                        Confirmar Agendamento
                    </button>
                </form>
            </div>
        </div>
            <div id="modalAcoesMapa" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center z-[110] px-4">
        <div class="bg-white rounded-[2rem] p-6 max-w-xs w-full shadow-2xl border border-pink-100 text-center">
            <h3 id="nomePacienteAcao" class="text-lg font-serif italic text-gray-800 mb-1">Paciente</h3>
            <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-6">O que deseja fazer?</p>

            <div class="grid grid-cols-1 gap-3">
                <form id="formStatusMapa" method="POST" action="">
                    @csrf @method('PATCH')
                    <button type="submit" id="btnStatusMapa" class="w-full py-3 rounded-2xl font-bold text-sm transition shadow-sm flex items-center justify-center gap-2">
                        <span id="iconStatusMapa"></span>
                        <span id="textStatusMapa">Marcar Realizada</span>
                    </button>
                </form>

                <a id="linkProntuarioMapa" href="#" class="w-full py-3 bg-gray-50 text-gray-600 rounded-2xl font-bold text-sm hover:bg-gray-100 transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Ver Prontuário
                </a>

                <button id="btnExcluirMapa" onclick="" class="w-full py-3 bg-pink-50 text-pink-500 rounded-2xl font-bold text-sm hover:bg-pink-500 hover:text-white transition flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Excluir Agendamento
                </button>
            </div>

            <button onclick="fecharModalAcoes()" class="mt-4 text-xs font-bold text-gray-400 hover:text-gray-600 uppercase tracking-tighter">Fechar</button>
        </div>
    </div>
    </div>
    <script>
        function abrirModalAgendamento(data, hora) {
            // Formata a hora para garantir que o input time aceite (HH:mm)
            const horaFormatada = hora.substring(0, 5);
            
            document.getElementById('input_data_rapida').value = data;
            document.getElementById('input_hora_rapida').value = horaFormatada;
            document.getElementById('is_recurrent_rapido').checked = false;
            document.getElementById('modalAgendamentoRapido').classList.remove('hidden');
            // Exibe o modal
            const modal = document.getElementById('modalAgendamentoRapido');
            modal.classList.remove('hidden');
            // Pequeno delay para animação de scale
            setTimeout(() => modal.firstElementChild.classList.remove('scale-95'), 10);
        }

        function fecharModalRapido() {
            const modal = document.getElementById('modalAgendamentoRapido');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 100);
        }

        function atualizarPrecoRapido(type) {
            const prices = { '1': '120.00', '2': '250.00', '3': '140.00' };
            document.getElementById('price_rapido').value = prices[type];
        }
    </script>
    <script>
    function confirmarExclusao(id) {
        Swal.fire({
            title: 'Excluir agendamento?',
            text: "Essa ação não poderá ser desfeita!",
            icon: 'warning',
            width: '22rem', // Reduz a largura da caixa
            padding: '1rem', // Diminui o respiro interno
            showCancelButton: true,
            confirmButtonColor: '#ec4899',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'rounded-[2rem] font-sans',
                title: 'text-lg font-serif italic', // Diminui o título
                confirmButton: 'text-xs px-4 py-2 rounded-xl', // Botões menores
                cancelButton: 'text-xs px-4 py-2 rounded-xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    function confirmarRealizacao(id, nome) {
        Swal.fire({
            title: 'Concluir atendimento?',
            text: "Confirmar sessão de " + nome + "?",
            icon: 'success',
            width: '22rem', // Largura menor
            padding: '1rem',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Sim, concluído',
            cancelButtonText: 'Agora não',
            customClass: {
                popup: 'rounded-[2rem] font-sans',
                title: 'text-lg font-serif italic',
                confirmButton: 'text-xs px-4 py-2 rounded-xl',
                cancelButton: 'text-xs px-4 py-2 rounded-xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('perform-form-' + id).submit();
            }
        })
    }

    // Toast de Sucesso (Aparece no canto superior)
    @if(session('success'))
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            width: '18rem', // Toast também mais compacto
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        })

        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        })
    @endif
    function abrirAcoesRapidas(data) {
        document.getElementById('nomePacienteAcao').innerText = data.nome;
        
        // Configura o formulário de Status (Realizado/Pendente)
        const formStatus = document.getElementById('formStatusMapa');
        const btnStatus = document.getElementById('btnStatusMapa');
        const textStatus = document.getElementById('textStatusMapa');
        
        if (data.performed) {
            formStatus.action = `/sessions/${data.id}/reverse-performed`;
            btnStatus.className = "w-full py-3 bg-orange-100 text-orange-600 rounded-2xl font-bold text-sm hover:bg-orange-600 hover:text-white transition";
            textStatus.innerText = "Reverter para Pendente";
        } else {
            formStatus.action = `/sessions/${data.id}/mark-performed`;
            btnStatus.className = "w-full py-3 bg-green-100 text-green-600 rounded-2xl font-bold text-sm hover:bg-green-600 hover:text-white transition";
            textStatus.innerText = "Marcar como Realizada";
        }

        // Configura o link do Prontuário
        document.getElementById('linkProntuarioMapa').href = `/patients/${data.patient_id}`;

        // Configura o botão de exclusão (aproveitando o SweetAlert que você já tem)
        document.getElementById('btnExcluirMapa').onclick = function() {
            fecharModalAcoes();
            confirmarExclusao(data.id);
        };

        document.getElementById('modalAcoesMapa').classList.remove('hidden');
    }

    function fecharModalAcoes() {
        document.getElementById('modalAcoesMapa').classList.add('hidden');
    }
    function confirmarExclusaoMapa(id) {
    Swal.fire({
        title: 'Excluir agendamento?',
        text: "Essa ação não poderá ser desfeita!",
        icon: 'warning',
        width: '22rem', // Reduz a largura da caixa
        padding: '1rem', // Diminui o respiro interno
        showCancelButton: true,
        confirmButtonColor: '#ec4899',
        confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            customClass: {
                popup: 'rounded-[2rem]',
                title: 'text-lg font-serif italic', // Diminui o título
                confirmButton: 'text-xs px-4 py-2 rounded-xl', // Botões menores
                cancelButton: 'text-xs px-4 py-2 rounded-xl'
            }
    }).then((result) => {
        if (result.isConfirmed) { document.getElementById('delete-mapa-' + id).submit(); }
    })
}

    function confirmarRealizacaoMapa(id, nome) {
        Swal.fire({
            title: 'Concluir atendimento?',
            text: "Confirmar sessão de " + nome + "?",
            icon: 'success',
            width: '22rem', // Reduz a largura da caixa
            padding: '1rem', // Diminui o respiro interno
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            confirmButtonText: 'Sim, concluído',
            cancelButtonText: 'Agora não',
            customClass: {
                popup: 'rounded-[2rem] font-sans',
                title: 'text-lg font-serif italic', // Diminui o título
                confirmButton: 'text-xs px-4 py-2 rounded-xl', // Botões menores
                cancelButton: 'text-xs px-4 py-2 rounded-xl'
            }
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('perform-mapa-' + id).submit(); }
        })
    }
    </script>
</x-app-layout>