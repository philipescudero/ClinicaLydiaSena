<x-app-layout>
    <div class="py-12 bg-[#FDF2F4] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-end mb-8">
                <div>
                    <p class="text-pink-500 font-bold uppercase text-[10px] tracking-[0.2em] mb-1">Bem-vinda de volta</p>
                    <h2 class="text-3xl font-serif text-gray-800 italic">Olá, Lydia Sena</h2>
                </div>
                <div class="text-right">
                    <span class="bg-white px-4 py-2 rounded-2xl shadow-sm border border-pink-100 text-pink-500 font-bold text-[10px] uppercase tracking-wider">
                        Referência: {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                    </span>
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
                    <div class="absolute top-0 right-0 p-4 opacity-10 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-2">Movimentação do Mês</p>
                    <div class="flex items-end gap-6 mt-1">
                        <div><p class="text-3xl font-serif text-gray-800">{{ $pacientesNoMes }}</p><p class="text-[9px] text-gray-400 uppercase font-black tracking-tighter">Pacientes Ativos</p></div>
                        <div class="h-10 w-px bg-pink-50"></div>
                        <div><p class="text-3xl font-serif text-pink-600">{{ $totalSessoesDoMes ?? 0 }}</p><p class="text-[9px] text-gray-400 uppercase font-black tracking-tighter">Sessões Agendadas</p></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white/60 backdrop-blur-sm rounded-[2rem] shadow-sm border border-pink-100 overflow-hidden h-full">
                        <div class="p-5 border-b border-pink-50 bg-white/80">
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
                                        <p class="text-xs font-bold text-gray-700 truncate">{{ $sessao->patient->name }}</p>
                                        <p class="text-[9px] font-bold {{ $sessao->performed ? 'text-green-500' : 'text-gray-400' }}">
                                            {{ \Carbon\Carbon::parse($sessao->session_date)->format('H:i') }} • {{ $sessao->performed ? 'REALIZADO' : 'AGENDADO' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-10 text-center"><p class="text-[10px] text-gray-400 italic">Sem consultas na semana.</p></div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-pink-100 overflow-hidden relative min-h-[500px]">
                        <div class="absolute -bottom-12 -right-12 p-4 opacity-[0.03] text-pink-500 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-80 w-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
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
                                        <tr class="transition {{ $sessao->performed ? 'bg-green-50 border-l-4 border-green-400' : 'hover:bg-pink-50/10' }}">
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
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
                                                        <div class="h-1.5 w-1.5 rounded-full {{ $sessao->performed ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                                                        <span class="text-[9px] font-black uppercase {{ $sessao->performed ? 'text-green-600' : 'text-gray-400' }}">{{ $sessao->performed ? 'REALIZADA' : 'PENDENTE' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end items-center gap-2">
                                                    @if(!$sessao->performed)
                                                        <form action="{{ route('sessions.markPerformed', $sessao->id) }}" method="POST">@csrf @method('PATCH')
                                                            <button type="submit" class="p-2 bg-white text-green-500 border border-green-100 rounded-xl hover:bg-green-500 hover:text-white transition shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg></button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('sessions.reversePerformed', $sessao->id) }}" method="POST" onsubmit="return confirm('Reverter status?')">@csrf @method('PATCH')
                                                            <button type="submit" class="p-2 bg-green-500 text-white border border-green-600 rounded-xl hover:bg-red-500 hover:border-red-600 transition shadow-sm group">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 block group-hover:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 hidden group-hover:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('sessions.destroy', $sessao->id) }}" method="POST" onsubmit="return confirm('Excluir?')">@csrf @method('DELETE')
                                                        <button type="submit" class="p-2 bg-white text-pink-300 border border-pink-100 rounded-xl hover:bg-pink-500 hover:text-white transition shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                                    </form>
                                                    <a href="{{ route('patients.show', $sessao->patient_id) }}" class="p-2 bg-white text-gray-400 border border-gray-100 rounded-xl hover:bg-pink-50 hover:text-pink-500 transition shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg></a>
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

            <div class="mt-8 bg-white rounded-[1.5rem] shadow-sm border border-pink-100 overflow-hidden">
                <div class="p-4 border-b border-gray-50 bg-white flex justify-between items-center">
                    <h3 class="text-[11px] font-black text-gray-500 uppercase tracking-widest flex items-center gap-2">🗓️ Mapa de Horários</h3>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('dashboard', ['semana' => $inicioSemana->copy()->subWeek()->format('Y-m-d')]) }}" class="p-1 hover:bg-pink-50 rounded text-pink-500 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15 19l-7-7 7-7" /></svg></a>
                        <span class="text-[10px] font-black text-gray-400 uppercase">{{ $inicioSemana->translatedFormat('d M') }} - {{ $inicioSemana->copy()->addDays(4)->translatedFormat('d M') }}</span>
                        <a href="{{ route('dashboard', ['semana' => $inicioSemana->copy()->addWeek()->format('Y-m-d')]) }}" class="p-1 hover:bg-pink-50 rounded text-pink-500 transition"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7" /></svg></a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full table-fixed border-collapse">
                        <thead class="bg-gray-50/50 text-[8px] font-black uppercase text-gray-400">
                            <tr><th class="w-16 py-2">Hora</th>@for($i = 0; $i < 5; $i++) @php $dia = $inicioSemana->copy()->addDays($i); @endphp <th class="py-2 border-l border-gray-100 {{ $dia->isToday() ? 'bg-pink-50/50' : '' }}"><span class="block text-[10px] font-black text-gray-600">{{ $dia->translatedFormat('D') }}</span></th> @endfor</tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($horariosPermitidos as $hora)
                                <tr class="h-10">
                                    <td class="text-center text-[9px] font-bold text-gray-400 bg-gray-50/20 border-r border-gray-100">{{ $hora }}</td>
                                    @for($i = 0; $i < 5; $i++)
                                        @php 
                                            $dataStr = $inicioSemana->copy()->addDays($i)->format('Y-m-d');
                                            $ocupado = $gradeHorarios[$dataStr][$hora] ?? null;
                                        @endphp
                                        <td class="p-1 border-r border-gray-100 relative group">
                                            @if($ocupado)
                                                <div class="h-full w-full bg-pink-500 rounded-lg flex items-center justify-center shadow-sm px-1" title="{{ $ocupado->patient->name }}">
                                                    <span class="text-[8px] font-black text-white uppercase truncate">{{ explode(' ', $ocupado->patient->name)[0] }}</span>
                                                </div>
                                            @else
                                                <div class="h-full w-full rounded-lg border border-transparent group-hover:bg-pink-50/30 flex items-center justify-center transition-all"><span class="text-[10px] text-transparent group-hover:text-pink-200">+</span></div>
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
    </div>
</x-app-layout>