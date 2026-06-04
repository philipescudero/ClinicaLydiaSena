<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Cabeçalho de Boas-vindas Reestilizado --}}
            <div class="flex justify-between items-end mb-10">
                <div class="text-left">
                    <p class="text-[#8C846C] font-black uppercase text-[10px] tracking-[0.3em] mb-2 opacity-60">Bem-vinda de volta</p>
                    <h2 class="text-4xl text-gray-800 italic">Olá, Lydia Sena</h2>
                </div>
                
                {{-- Mostrador de Mês Fixo Estilizado (Sem botões de navegação) --}}
                <div class="text-right flex flex-col items-end">
                    <span class="text-4xl font-serif text-[#8C846C] italic tracking-wide lowercase first-letter:uppercase select-none">
                        {{ $inicioMes->translatedFormat('F \d\e Y') }}
                    </span>
                </div>
            </div>

            {{-- GRID COMBINADO: Mini Calendário na Esquerda + Mapa Semanal na Direita --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-20 font-sans">
                
                {{-- COLUNA 1: Mini Calendário Estilo Google Agenda COMPACTO --}}
                {{-- ADICIONADO: max-w-[250px] e mx-auto para encolher a estrutura horizontalmente --}}
                <div class="lg:col-span-1 h-fit max-w-[250px] mx-auto w-full">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-[#E1D3C1] p-3.5 text-center">
                        
                        {{-- Cabeçalho do Mini Calendário --}}
                        <div class="flex justify-between items-center mb-2.5 px-0.5">
                            <h4 id="mini_cal_titulo" class="text-[10px] font-black text-[#8C846C] uppercase tracking-wider" data-mes="{{ $inicioMes->month }}" data-ano="{{ $inicioMes->year }}">
                                {{ $inicioMes->translatedFormat('F \d\e Y') }}
                            </h4>
                            <div class="flex items-center gap-0.5">
                                <button type="button" onclick="navegarMesMiniCalendario(-1)" class="p-1 hover:bg-[#F9F6F3] rounded-md text-[#8C846C] transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15 19l-7-7 7-7" /></svg>
                                </button>
                                <button type="button" onclick="navegarMesMiniCalendario(1)" class="p-1 hover:bg-[#F9F6F3] rounded-md text-[#8C846C] transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7" /></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Cabeçalho de Dias --}}
                        <div class="grid grid-cols-7 gap-y-1 text-center text-gray-400 font-bold text-[9px] uppercase mb-1">
                            <div>D</div><div>S</div><div>T</div><div>Q</div><div>Q</div><div>S</div><div>S</div>
                        </div>

                        {{-- Grid de Dias super encolhido (text-[10px] e padding reduzido) --}}
                        {{-- Grid de Dias super encolhido (text-[10px] e padding reduzido) --}}
                        <div id="mini_cal_dias" class="grid grid-cols-7 gap-y-0.5 text-center text-[10px]">
                            @php
                                // CORREÇÃO: Criamos clones isolados de mutabilidade
                                $gradeAtual = $inicioMes->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
                                $fimGrade = $inicioMes->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
                            @endphp

                            @while($gradeAtual <= $fimGrade)
                                @php
                                    $ehMesAtual = $gradeAtual->month == $inicioMes->month;
                                    $ehHoje = $gradeAtual->isToday();
                                    $rotaDia = route('dashboard', ['semana' => $gradeAtual->format('Y-m-d'), 'mes' => $gradeAtual->month, 'ano' => $gradeAtual->year]);
                                @endphp

                                {{-- h-6 w-6 força os círculos do calendário a ficarem pequenos e simétricos --}}
                                <a href="{{ $rotaDia }}" class="h-6 w-6 mx-auto flex items-center justify-center font-bold rounded-full transition-all dia-link
                                    {{ $ehHoje ? 'bg-[#8C846C] text-white shadow-sm scale-105' : ($ehMesAtual ? 'text-gray-700 hover:bg-[#F9F6F3]' : 'text-gray-300 hover:bg-[#F9F6F3]/50') }}"
                                    data-data="{{ $gradeAtual->format('Y-m-d') }}">
                                    {{ $gradeAtual->day }}
                                </a>

                                @php 
                                    // CORREÇÃO: Incrementa o loop de forma imutável sem alterar as variáveis do resto da página
                                    $gradeAtual = $gradeAtual->copy()->addDay(); 
                                @endphp
                            @endwhile
                        </div>
                    </div>
                </div>

                {{-- COLUNA 2 e 3: O Mapa Semanal de Horários (Ganhou 2 colunas esticadas) --}}
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-[3rem] shadow-sm border border-[#E1D3C1] overflow-hidden font-sans h-full">
                        <div class="p-6 border-b border-[#F9F6F3] bg-white flex justify-between items-center">
                            <h3 class="text-[11px] font-black text-[#8C846C] uppercase tracking-[0.25em]">🗓️ Mapa de Horários Semanal</h3>
                            <div class="flex items-center gap-2 bg-[#F9F6F3] p-1 rounded-xl border border-[#E1D3C1]/50">
                                <a href="{{ route('dashboard', ['semana' => $inicioSemana->copy()->subWeek()->format('Y-m-d'), 'mes' => $inicioMes->month, 'ano' => $inicioMes->year]) }}" 
                                class="p-2 hover:bg-white rounded-lg text-[#8C846C] transition shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15 19l-7-7 7-7" /></svg>
                                </a>
                                <span class="text-[10px] font-black text-[#8C846C] uppercase px-3">
                                    @php 
                                        $segunda = $inicioSemana->copy()->startOfWeek(\Carbon\Carbon::MONDAY); 
                                    @endphp
                                    {{ $segunda->translatedFormat('d M') }} — {{ $segunda->copy()->addDays(4)->translatedFormat('d M') }}
                                </span>
                                <a href="{{ route('dashboard', ['semana' => $inicioSemana->copy()->addWeek()->format('Y-m-d'), 'mes' => $inicioMes->month, 'ano' => $inicioMes->year]) }}" 
                                class="p-2 hover:bg-white rounded-lg text-[#8C846C] transition shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        </div>

                        <div class="overflow-x-auto p-4">
                            <table class="min-w-full table-fixed border-separate border-spacing-1.5">
                               <thead>
                                    <tr>
                                        <th class="w-16 py-4 text-[9px] font-black uppercase text-[#E1D3C1] text-center tracking-widest">Hora</th>
                                        @for($i = 0; $i < 5; $i++)
                                            @php 
                                                $dia = $inicioSemana->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays($i); 
                                            @endphp
                                            <th class="py-4 rounded-2xl {{ $dia->isToday() ? 'bg-[#8C846C]/10 border border-[#8C846C]/20' : 'bg-[#F9F6F3]/50' }}">
                                                <span class="block text-[11px] font-black text-[#8C846C] uppercase tracking-tighter">{{ $dia->translatedFormat('D') }}</span>
                                                <span class="text-[10px] font-bold text-[#8C846C]/40">{{ $dia->format('d/m') }}</span>
                                            </th>
                                        @endfor {{-- FECHAMENTO DO FOR DOS DIAS DA SEMANA --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($horariosPermitidos as $horaBruta)
                                        @php
                                            $hora = trim($horaBruta);
                                        @endphp
                                        <tr>
                                            <td class="py-2 text-center">
                                                <span class="text-[10px] font-black text-[#E1D3C1]">{{ $hora }}</span>
                                            </td>
                                            @for($i = 0; $i < 5; $i++)
                                                @php 
                                                    $diaObjeto = $inicioSemana->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->addDays($i);
                                                    $dataStr = $diaObjeto->format('Y-m-d');
                                                    $sessoesNoSlot = $gradeHorarios[$dataStr][$hora] ?? [];
                                                    $ocupado = $sessoesNoSlot[0] ?? null;
                                                    $temConflito = count($sessoesNoSlot) > 1;
                                                    $estaPago = ($ocupado && isset($ocupado->status) && $ocupado->status == 'pago') ? 1 : 0; 
                                                @endphp
                                                <td id="cell-slot-{{ $dataStr }}-{{ str_replace(':', '-', $hora) }}" class="relative h-14 transition-all duration-300">
                                                    @if($ocupado)
                                                        {{-- Card de Sessão --}}
                                                        <div id="map-card-{{ $ocupado->id }}" class="absolute inset-0.5 rounded-2xl shadow-sm flex flex-col items-center justify-center px-2 overflow-hidden group transition-all {{ $temConflito ? 'bg-red-500 animate-pulse' : ($ocupado->performed ? 'bg-green-600' : 'bg-[#8C846C]') }}">
                                                            <div class="group-hover:opacity-10 transition-opacity duration-300 flex flex-col items-center w-full">
                                                                <span class="text-[8px] font-black text-white uppercase leading-tight text-center break-words w-full px-1">{{ $ocupado->patient->name }}</span>
                                                                <span class="text-[7px] font-black text-white/50 uppercase mt-0.5 tracking-tighter text-center">
                                                                    {{ $ocupado->service_type == 1 ? 'Psi' : ($ocupado->service_type == 2 ? 'Ava' : 'Con') }}
                                                                </span>
                                                            </div>
                                                            {{-- Botões de Ação do Card --}}
                                                            <div class="absolute inset-0 rounded-2xl flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 bg-black/20 backdrop-blur-sm transition-all duration-300">
                                                                <button type="button" onclick="togglePerformed({{ $ocupado->id }})" class="p-1.5 rounded-lg hover:scale-110 transition shadow-lg {{ $ocupado->performed ? 'bg-orange-500 text-white' : 'bg-green-500 text-white' }}">
                                                                    {!! $ocupado->performed ? '<svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>' : '<svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4"><path d="M5 13l4 4L19 7" /></svg>' !!}
                                                                </button>
                                                                <button type="button" onclick="confirmarExclusaoMapa({{ $ocupado->id }}, {{ $estaPago }}, '{{ $dataStr }}', '{{ $hora }}')" class="p-1.5 bg-white text-red-500 rounded-lg hover:scale-110 transition shadow-lg">
                                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        {{-- Slot Vazio --}}
                                                        <div onclick="abrirModalAgendamento('{{ $dataStr }}', '{{ $hora }}')" class="absolute inset-0.5 border-2 border-dashed border-[#E1D3C1]/30 rounded-2xl flex items-center justify-center hover:border-[#8C846C]/30 hover:bg-[#F9F6F3] transition-all group cursor-pointer">
                                                            <span class="text-[#E1D3C1] group-hover:text-[#8C846C]/40 font-black text-lg">+</span>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endfor {{-- FECHAMENTO DO FOR DAS COLUNAS (DIAS) --}}
                                        </tr>
                                    @endforeach {{-- FECHAMENTO DO FOREACH DOS HORÁRIOS --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            

            {{-- Operações e GPS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-12">
                {{-- GPS: Próximos 7 dias --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[3rem] shadow-sm border border-[#E1D3C1] overflow-hidden h-full">
                        <div class="p-6 border-b border-[#F9F6F3] text-left">
                            <h3 class="text-[10px] font-black text-[#8C846C] uppercase tracking-[0.2em] flex items-center gap-2">
                                <span class="p-1.5 bg-[#8C846C]/10 rounded-lg text-[#8C846C]">📍</span> GPS: Próximos 7 dias
                            </h3>
                        </div>
                        <div class="p-6 space-y-4 font-sans">
                            @forelse($sessoesSemana as $sessao)
                            {{-- 1. Adicionamos o ID no container principal: gps-row-ID --}}
                            <div id="gps-row-{{ $sessao->id }}" class="flex items-center gap-4 p-4 rounded-[2rem] border transition-all {{ $sessao->performed ? 'bg-green-50/40 border-green-100 opacity-60' : 'bg-[#F9F6F3]/50 border-[#E1D3C1]/30 hover:bg-white hover:shadow-md' }}">
                                <div class="text-center min-w-[45px] border-r border-[#E1D3C1]/50 pr-4">
                                    <span class="block text-sm font-black text-[#8C846C]">{{ \Carbon\Carbon::parse($sessao->session_date)->format('d') }}</span>
                                    <span class="text-[9px] uppercase font-bold text-[#8C846C]/40">{{ \Carbon\Carbon::parse($sessao->session_date)->translatedFormat('D') }}</span>
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <p class="text-xs font-bold text-gray-700 truncate text-left">{{ $sessao->patient->name }}</p>
                                    {{-- 2. Adicionamos a classe 'gps-label' para o JS trocar o texto e a cor --}}
                                    <p class="gps-label text-[10px] font-bold text-left {{ $sessao->performed ? 'text-green-600' : 'text-[#8C846C]/40' }} mt-0.5">
                                        {{ \Carbon\Carbon::parse($sessao->session_date)->format('H:i') }} • {{ $sessao->performed ? 'REALIZADO' : 'AGENDADO' }}
                                    </p>
                                </div>
                            </div>
                            @empty
                                <div class="py-12 text-center">
                                    <div class="bg-[#F9F6F3] w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#E1D3C1]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <p class="text-[11px] text-[#8C846C]/40 font-bold uppercase tracking-widest">Sem agendamentos</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Operações da Semana --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[3rem] shadow-sm border border-[#E1D3C1] overflow-hidden relative min-h-[550px]">
                        <div class="p-8 border-b border-[#F9F6F3] flex justify-between items-center font-sans">
                            <h3 class="text-[10px] font-black text-[#8C846C] uppercase tracking-[0.2em] flex items-center gap-2">
                                <span class="p-1.5 bg-[#8C846C]/10 rounded-lg text-[#8C846C]">🎛️</span> Operações da Semana
                            </h3>
                            
                            {{-- Passador de Semana --}}
                            <div class="flex items-center gap-2 bg-[#F9F6F3] p-1 rounded-xl border border-[#E1D3C1]/50">
                                <a href="{{ route('dashboard', ['semana' => $inicioSemana->copy()->subWeek()->format('Y-m-d'), 'mes' => $inicioMes->month, 'ano' => $inicioMes->year]) }}" 
                                class="p-2 hover:bg-white rounded-lg text-[#8C846C] transition shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M15 19l-7-7 7-7" /></svg>
                                </a>
                                <span class="text-[9px] font-black text-[#8C846C] uppercase px-2 tracking-tighter">
                                    @php $segunda = $inicioSemana->copy()->startOfWeek(Carbon\Carbon::MONDAY); @endphp
                                    {{ $segunda->format('d/m') }} — {{ $segunda->copy()->addDays(4)->format('d/m') }}
                                </span>
                                <a href="{{ route('dashboard', ['semana' => $inicioSemana->copy()->addWeek()->format('Y-m-d'), 'mes' => $inicioMes->month, 'ano' => $inicioMes->year]) }}" 
                                class="p-2 hover:bg-white rounded-lg text-[#8C846C] transition shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        </div>

                        <div class="overflow-x-auto font-sans">
                            <table class="min-w-full text-left">
                                <thead class="bg-[#F9F6F3]/50 font-black text-[9px] uppercase tracking-[0.15em] text-[#8C846C]/60">
                                    <tr>
                                        <th class="px-8 py-5">Data / Paciente</th>
                                        <th class="px-8 py-5 text-center">Status / Serviço</th>
                                        <th class="px-8 py-5 text-right">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#F9F6F3]">
                                    @forelse($sessoesOperacoes as $sessao)
                                        @php
                                            // Extraímos a data pura e a hora formatada para o modal ler sem quebras
                                            $dataSessaoStr = \Carbon\Carbon::parse($sessao->session_date)->format('Y-m-d');
                                            $horaSessaoStr = \Carbon\Carbon::parse($sessao->session_date)->format('H:i');
                                            $estaSessaoPaga = $sessao->status == 'pago' ? 1 : 0;
                                        @endphp
                                        
                                        {{-- Adicionamos um ID único na linha para o JavaScript conseguir capturar o nome do paciente --}}
                                        <tr id="sessao-row-{{ $sessao->id }}" class="transition group {{ $sessao->performed ? 'bg-green-50/20' : 'hover:bg-[#F9F6F3]/30' }}">
                                            
                                            <td class="px-8 py-5 border-indicador {{ $sessao->performed ? 'border-l-4 border-green-500' : '' }}">
                                                <div class="flex flex-col text-left">
                                                    <span class="text-data text-[11px] font-black {{ $sessao->performed ? 'text-green-600' : 'text-[#8C846C]' }}">
                                                        {{ \Carbon\Carbon::parse($sessao->session_date)->translatedFormat('D, d/m') }} às {{ $horaSessaoStr }}
                                                    </span>
                                                    {{-- Esta classe 'nome-paciente-tabela' garante o mapeamento correto do nome --}}
                                                    <span class="text-sm font-bold text-gray-700 mt-0.5 nome-paciente-tabela">{{ $sessao->patient->name }}</span>
                                                </div>
                                            </td>

                                            <td class="px-8 py-5 text-center">
                                                <div class="flex flex-col items-center gap-2">
                                                    <span class="text-[9px] px-3 py-1 bg-white border border-[#E1D3C1]/50 rounded-full font-black text-[#8C846C]/60 uppercase tracking-tighter shadow-sm">
                                                        {{ $sessao->service_type == 1 ? 'Psicoterapia' : ($sessao->service_type == 2 ? 'Avaliação' : 'Consultoria') }}
                                                    </span>
                                                    <div class="flex items-center gap-1.5">
                                                        <div class="dot h-1.5 w-1.5 rounded-full {{ $sessao->performed ? 'bg-green-500' : 'bg-[#E1D3C1]' }}"></div>
                                                        <span class="label text-[9px] font-black uppercase {{ $sessao->performed ? 'text-green-600' : 'text-[#8C846C]/30' }}">
                                                            {{ $sessao->performed ? 'REALIZADA' : 'PENDENTE' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-8 py-5 text-right">
                                                <div class="flex justify-end items-center gap-2">
                                                    {{-- BOTÃO AJAX (Concluir/Reverter) --}}
                                                    <button type="button" 
                                                            id="btn-perform-{{ $sessao->id }}"
                                                            onclick="togglePerformed({{ $sessao->id }})"
                                                            class="p-2.5 rounded-xl transition-all shadow-sm border {{ $sessao->performed ? 'bg-green-500 text-white border-green-600' : 'bg-white text-green-600 border-green-100 hover:bg-green-600 hover:text-white' }}">
                                                        @if($sessao->performed)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                                        @endif
                                                    </button>

                                                    {{-- Link para o Prontuário --}}
                                                    <a href="{{ route('patients.show', $sessao->patient_id) }}" class="p-2.5 bg-white text-[#8C846C] border border-[#E1D3C1] rounded-xl hover:bg-[#8C846C] hover:text-white transition-all shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                    </a>

                                                    {{-- BOTÃO ADICIONADO: Editar Consulta --}}
                                                    <button type="button" 
                                                            onclick="abrirModalEdicao({{ $sessao->id }}, '{{ $dataSessaoStr }}', '{{ $horaSessaoStr }}', {{ $sessao->service_type }}, '{{ $sessao->value }}', {{ $sessao->patient_id }}, 'tabela')" 
                                                            class="p-2.5 bg-white text-blue-500 border border-[#E1D3C1]/60 rounded-xl hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                                    </button>

                                                    {{-- Formulário de Exclusão Blindado com Alerta Toast --}}
                                                    <form id="delete-form-{{ $sessao->id }}" action="{{ route('sessions.destroy', $sessao->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        {{-- Formulário de Exclusão Blindado com Alerta Toast --}}
                                                    <form id="delete-form-{{ $sessao->id }}" action="{{ route('sessions.destroy', $sessao->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        {{-- Usamos as variáveis nativas da tabela superior: $sessao e $estaSessaoPaga --}}
                                                        <button type="button" onclick="confirmarExclusaoMapa({{ $sessao->id }}, {{ $estaSessaoPaga }}, '{{ $dataSessaoStr }}', '{{ $horaSessaoStr }}')" class="p-2.5 bg-white text-red-400 border border-[#E1D3C1]/50 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="py-24 text-center text-[#8C846C]/30 italic text-sm font-sans">Nenhum agendamento encontrado para este mês.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    {{-- Modal de Agendamento Rápido Refinado --}}
    <div id="modalAgendamentoRapido" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center z-[100] px-4">
        <div class="bg-white rounded-[3rem] p-10 max-w-md w-full shadow-2xl border border-[#E1D3C1] transition-all transform scale-95 font-sans">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-serif italic text-[#8C846C]">Agendamento Rápido</h3>
                <button onclick="fecharModalRapido()" class="text-[#E1D3C1] hover:text-[#8C846C] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="form_agendamento_rapido" action="{{ route('sessions.store_fast') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest text-left">Paciente</label>
                    <div class="relative search-container">
                        <select id="patient_select_fast" name="patient_id" required placeholder="Buscar por nome ou CPF..." class="w-full">
                            <option value=""></option>
                            @foreach($todosPacientes as $p)
                                <option value="{{ $p->id }}" 
                                        data-cpf="{{ $p->cpf }}" 
                                        data-initials="{{ collect(explode(' ', $p->name))->map(fn($n) => mb_substr($n, 0, 1))->take(2)->implode('') }}">
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="absolute right-4 top-[14px] text-[#8C846C]/40 pointer-events-none z-10 lupa-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest text-left">Data</label>
                        <input type="date" name="session_date" id="input_data_rapida" readonly class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3] text-sm font-bold text-gray-500">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest text-left">Hora</label>
                        <input type="time" name="session_time" id="input_hora_rapida" readonly class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3] text-sm font-bold text-gray-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest text-left">Serviço</label>
                        <select name="service_type" id="service_selector_rapido" onchange="atualizarPrecoRapido(this.value)" class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3]/50 text-sm">
                            <option value="1">Psicoterapia</option>
                            <option value="2">Avaliação</option>
                            <option value="3">Consultoria</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest text-left">Valor (R$)</label>
                        <input type="number" name="value" id="price_rapido" value="120.00" step="0.01" class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3] font-bold text-[#8C846C]">
                    </div>
                </div>

                <div class="space-y-4 p-5 bg-[#F9F6F3] rounded-[2rem] border border-[#E1D3C1]/50 text-left">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_recurrent" id="is_recurrent_rapido" onchange="toggleFrequencia(this.checked)" class="rounded text-[#8C846C] focus:ring-[#8C846C] h-5 w-5 cursor-pointer">
                        <label for="is_recurrent_rapido" class="text-[11px] font-bold text-[#8C846C] italic cursor-pointer select-none">
                            Este agendamento se repete?
                        </label>
                    </div>

                    <div id="container_frequencia" class="hidden transition-all duration-300 pt-2 border-t border-[#E1D3C1]/30">
                        <label class="block text-[9px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Frequência da Repetição</label>
                        <select name="recurrence_period" class="w-full rounded-xl border-[#E1D3C1] bg-white text-xs font-bold text-gray-700 focus:ring-[#8C846C] focus:border-[#8C846C]">
                            <option value="weekly">Semanalmente (Toda semana)</option>
                            <option value="biweekly">Quinzenalmente (De 15 em 15 dias)</option>
                        </select>
                        <p class="text-[9px] text-gray-400 italic mt-2 leading-tight">Os agendamentos serão replicados automaticamente até o último dia do ano corrente.</p>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#8C846C] text-white py-5 rounded-[2rem] font-bold uppercase text-xs tracking-widest shadow-xl hover:bg-[#766f5a] transition duration-300">
                    Confirmar Agendamento
                </button>
            </form>
        </div>
    </div>

    {{-- Modal de Edição de Agendamento --}}
    <div id="modalEdicaoConsulta" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center z-[100] px-4">
        <div class="bg-white rounded-[3rem] p-10 max-w-md w-full shadow-2xl border border-[#E1D3C1] transition-all transform scale-95 font-sans">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-serif italic text-[#8C846C]">Editar Agendamento</h3>
                <button type="button" onclick="fecharModalEdicao()" class="text-[#E1D3C1] hover:text-[#8C846C] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="form_edicao_consulta" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <input type="hidden" name="patient_id" id="edit_patient_id">

                <div>
                    <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest text-left">Paciente (Não alterável)</label>
                    <input type="text" id="edit_patient_name" readonly class="w-full rounded-2xl border-[#E1D3C1] bg-[#F9F6F3] text-sm font-bold text-gray-400">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest text-left">Nova Data</label>
                        <input type="date" name="session_date" id="input_edit_data" required class="w-full rounded-2xl border-[#E1D3C1] bg-white text-sm font-bold text-gray-700">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest text-left">Nova Hora</label>
                        <input type="time" name="session_time" id="input_edit_hora" required class="w-full rounded-2xl border-[#E1D3C1] bg-white text-sm font-bold text-gray-700">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest text-left">Serviço</label>
                        <select name="service_type" id="service_selector_edit" class="w-full rounded-2xl border-[#E1D3C1] bg-white text-sm">
                            <option value="1">Psicoterapia</option>
                            <option value="2">Avaliação</option>
                            <option value="3">Consultoria</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest text-left">Valor (R$)</label>
                        <input type="number" name="value" id="price_edit" step="0.01" required class="w-full rounded-2xl border-[#E1D3C1] bg-white font-bold text-[#8C846C]">
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#8C846C] text-white py-5 rounded-[2rem] font-bold uppercase text-xs tracking-widest shadow-xl hover:bg-[#766f5a] transition duration-300">
                    Salvar Alterações
                </button>
            </form>
        </div>
    </div>

    {{-- SCRIPTS DO SISTEMA --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function navegarMesMiniCalendario(direcao) {
        const titulo = document.getElementById('mini_cal_titulo');
        let mesAtual = parseInt(titulo.getAttribute('data-mes'));
        let anoAtual = parseInt(titulo.getAttribute('data-ano'));

        // Calcula a mudança do mês
        mesAtual += direcao;
        if (mesAtual > 12) { mesAtual = 1; anoAtual += 1; }
        if (mesAtual < 1) { mesAtual = 12; anoAtual -= 1; }

        // Grava as novas referências nos atributos de estado
        titulo.setAttribute('data-mes', mesAtual);
        titulo.setAttribute('data-ano', anoAtual);

        // Array de tradução dos meses
        const mesesNomes = ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"];
        titulo.innerText = `${mesesNomes[mesAtual - 1]} de ${anoAtual}`;

        const gridDias = document.getElementById('mini_cal_dias');
        gridDias.innerHTML = '';

        // Monta a grade inicial retrocedendo até o Domingo correspondente
        const primeiraDataMes = new Date(anoAtual, mesAtual - 1, 1);
        const diaSemanaInicio = primeiraDataMes.getDay(); 
        
        const dataCorrente = new Date(primeiraDataMes);
        dataCorrente.setDate(dataCorrente.getDate() - diaSemanaInicio); 

        const hoje = new Date();
        hoje.setHours(0,0,0,0);

        // Imprime o loop fixo de 42 posições (Grade Padrão de Calendário)
        for (let i = 0; i < 42; i++) {
            const diaNum = dataCorrente.getDate();
            const mesNum = dataCorrente.getMonth() + 1;
            const anoNum = dataCorrente.getFullYear();
            
            const ehMesAtual = mesNum === mesAtual;
            const ehHoje = dataCorrente.getTime() === hoje.getTime();
            
            const mesPad = String(mesNum).padStart(2, '0');
            const diaPad = String(diaNum).padStart(2, '0');
            const dataFormatada = `${anoNum}-${mesPad}-${diaPad}`;
            
            // CORREÇÃO: O link do dia passa para a URL a semana, o mês e o ano correspondentes àquele dia clicado
            // Procure por essa linha de linkHref dentro do loop 'for' da função navegarMesMiniCalendario:
            const linkHref = `/dashboard?semana=${dataFormatada}&mes=${mesNum}&ano=${anoNum}`;

            let classesCSS = "h-6 w-6 mx-auto flex items-center justify-center font-bold rounded-full transition-all dia-link ";
            if (ehHoje) {
                classesCSS += "bg-[#8C846C] text-white shadow-sm scale-105";
            } else if (ehMesAtual) {
                classesCSS += "text-gray-700 hover:bg-[#F9F6F3]";
            } else {
                classesCSS += "text-gray-300 hover:bg-[#F9F6F3]/50";
            }

            gridDias.innerHTML += `<a href="${linkHref}" class="${classesCSS}" data-data="${dataFormatada}">${diaNum}</a>`;
            
            dataCorrente.setDate(dataCorrente.getDate() + 1);
        }
    }

    // 1. EVENTOS AJAX DO SISTEMA
    function togglePerformed(id) {
        const url = `/sessoes/${id}/realizado`; 

        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) { throw new Error('Erro na rede ou rota não encontrada'); }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateRowUI(id, data.performed);
                
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon: 'success',
                    title: data.message
                });
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Ops!',
                text: 'Não foi possível atualizar o status do atendimento.',
                confirmButtonColor: '#8C846C'
            });
        });
    }

    function updateRowUI(id, isPerformed) {
        const row = document.getElementById(`sessao-row-${id}`);
        if (row) {
            const btn = document.getElementById(`btn-perform-${id}`);
            const label = row.querySelector('.label');

            if (isPerformed) {
                row.classList.add('bg-green-50/20');
                if(btn) btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>';
                if(btn) btn.className = 'p-2.5 rounded-xl transition-all shadow-sm border bg-green-500 text-white border-green-600';
                if(label) label.innerText = 'REALIZADA';
                if(label) label.className = 'label text-[9px] font-black uppercase text-green-600';
            } else {
                row.classList.remove('bg-green-50/20');
                if(btn) btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>';
                if(btn) btn.className = 'p-2.5 rounded-xl transition-all shadow-sm border bg-white text-green-600 border-green-100 hover:bg-green-600 hover:text-white';
                if(label) label.innerText = 'PENDENTE';
                if(label) label.className = 'label text-[9px] font-black uppercase text-[#8C846C]/30';
            }
        }

        const mapCard = document.getElementById(`map-card-${id}`);
        if (mapCard) {
            const mapBtn = document.getElementById(`btn-map-perform-${id}`);
            if (isPerformed) {
                mapCard.classList.replace('bg-[#8C846C]', 'bg-green-600');
                if(mapBtn) mapBtn.className = 'p-1.5 rounded-lg hover:scale-110 transition shadow-lg bg-orange-500 text-white';
            } else {
                mapCard.classList.replace('bg-green-600', 'bg-[#8C846C]');
                if(mapBtn) mapBtn.className = 'p-1.5 rounded-lg hover:scale-110 transition shadow-lg bg-green-500 text-white';
            }
        }
    }

    // 2. CONTROLES DO MODAL DE AGENDAMENTO
    function abrirModalAgendamento(data, hora) {
        const horaFormatada = hora.substring(0, 5);
        document.getElementById('input_data_rapida').value = data;
        document.getElementById('input_hora_rapida').value = horaFormatada;
        const modal = document.getElementById('modalAgendamentoRapido');
        modal.classList.remove('hidden');
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

    function toggleFrequencia(isCheck) {
        const container = document.getElementById('container_frequencia');
        if (isCheck) { container.classList.remove('hidden'); } else { container.classList.add('hidden'); }
    }

    // 3. SEÇÃO DE VALIDAÇÕES DO SWEETALERT
    function confirmarExclusao(id) {
        Swal.fire({
            title: 'Excluir agendamento?',
            text: "Essa ação não poderá ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#8C846C',
            cancelButtonColor: '#E1D3C1',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-[3rem] font-sans' }
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('delete-form-' + id).submit(); }
        })
    }

    function confirmarExclusaoMapa(id, estaPago, dataStr = null, hora = null) {
        if (estaPago == 1 || estaPago == '1') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'warning',
                title: 'Esta consulta já está paga e não pode ser excluída.'
            });
            return; 
        }

        Swal.fire({
            title: 'Excluir agendamento?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#E1D3C1',
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            customClass: { popup: 'rounded-[3rem] font-sans' }
        }).then((result) => {
            if (result.isConfirmed) { 
                const url = `/sessoes/${id}`;

                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) { throw new Error('Erro na resposta do servidor'); }
                    return response.json();
                })
                .then(data => {
                    // Remove a linha da tabela superior se existir
                    const linhaTabela = document.getElementById(`sessao-row-${id}`);
                    if (linhaTabela) {
                        linhaTabela.style.opacity = '0';
                        setTimeout(() => linhaTabela.remove(), 300);
                    }

                    // Remove do Mapa Semanal e reconstrói o botão "+" pontilhado
                    if (dataStr && hora) {
                        // Remove espaços em branco indesejados da string de hora
                        const horaFormatada = hora.trim();
                        const slotCell = document.getElementById(`cell-slot-${dataStr}-${horaFormatada.replace(':', '-')}`);
                        if (slotCell) {
                            slotCell.innerHTML = `
                                <div onclick="abrirModalAgendamento('${dataStr}', '${horaFormatada}')" class="absolute inset-0.5 border-2 border-dashed border-[#E1D3C1]/30 rounded-2xl flex items-center justify-center hover:border-[#8C846C]/30 hover:bg-[#F9F6F3] transition-all group cursor-pointer">
                                    <span class="text-[#E1D3C1] group-hover:text-[#8C846C]/40 font-black text-lg">+</span>
                                </div>
                            `;
                        }
                    }

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: data.message || 'Sessão excluída com sucesso!'
                    });
                })
                .catch(error => {
                    console.error('Erro AJAX:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops!',
                        text: 'Ocorreu um erro ao tentar excluir esta sessão, verifique se esta paga.',
                        confirmButtonColor: '#8C846C'
                    });
                });
            }
        });
    }

    function confirmarRealizacaoMapa(id, nome) {
        Swal.fire({
            title: 'Concluir atendimento?',
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#166534',
            cancelButtonColor: '#E1D3C1',
            confirmButtonText: 'Sim, concluído',
            customClass: { popup: 'rounded-[3rem] font-sans' }
        }).then((result) => {
            if (result.isConfirmed) { document.getElementById('perform-mapa-' + id).submit(); }
        })
    }

    // --- CONTROLES DO MODAL DE EDIÇÃO ---
    // --- CONTROLES DO MODAL DE EDIÇÃO ---
    function abrirModalEdicao(id, data, hora, tipoServico, valor, pacienteId, origem = 'mapa') {
        // Altera a rota do form para bater no método update dinamicamente
        document.getElementById('form_edicao_consulta').action = `/sessoes/${id}`;
        
        // Preenche os inputs do formulário com os valores atuais recebidos
        document.getElementById('input_edit_data').value = data;
        document.getElementById('input_edit_hora').value = hora;
        document.getElementById('service_selector_edit').value = tipoServico;
        document.getElementById('price_edit').value = parseFloat(valor).toFixed(2);
        document.getElementById('edit_patient_id').value = pacienteId;

        // Identifica de onde veio o clique para ler o elemento correto do HTML
        let nomePaciente = '';
        if (origem === 'tabela') {
            const linhaTabela = document.getElementById(`sessao-row-${id}`);
            if (linhaTabela) {
                nomePaciente = linhaTabela.querySelector('.nome-paciente-tabela').innerText;
            }
        } else {
            const cardContainer = document.getElementById(`map-card-${id}`);
            if (cardContainer) {
                nomePaciente = cardContainer.querySelector('span').innerText;
            }
        }
        
        document.getElementById('edit_patient_name').value = nomePaciente;

        const modal = document.getElementById('modalEdicaoConsulta');
        modal.classList.remove('hidden');
        setTimeout(() => modal.firstElementChild.classList.remove('scale-95'), 10);
    }

    function fecharModalEdicao() {
        const modal = document.getElementById('modalEdicaoConsulta');
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 100);
    }

document.addEventListener("DOMContentLoaded", function() {
    // --- MEMÓRIA DO SCROLL: Devolve a tela para o Mapa Semanal após atualizar ---
    const posAnteriorScroll = sessionStorage.getItem("scrollPositionLydia");
    if (posAnteriorScroll) {
        window.scrollTo(0, parseInt(posAnteriorScroll));
        sessionStorage.removeItem("scrollPositionLydia"); // Limpa a memória
    }

    // Função interna para salvar a rolagem e recarregar de forma limpa
    function atualizarMantendoScroll() {
        sessionStorage.setItem("scrollPositionLydia", window.scrollY);
        window.location.reload();
    }

    // ==========================================
    // INTERCEPTADOR: SALVAR NOVO AGENDAMENTO
    // ==========================================
    const formCadastro = document.getElementById('form_agendamento_rapido');
    if (formCadastro) {
        formCadastro.addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o refresh convencional

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                fecharModalRapido();
                // Grava a rolagem atual e recarrega trazendo o novo card na tela
                atualizarMantendoScroll();
            })
            .catch(error => {
                console.error('Erro no cadastro:', error);
                // Exibe a mensagem de conflito de agenda estilizada que criamos
                Swal.fire({
                    title: 'Não permitido!',
                    text: error.message || 'Já existe um atendimento marcado para este dia e horário.',
                    icon: 'error',
                    confirmButtonColor: '#8C846C',
                    customClass: { popup: 'rounded-[3rem] font-sans' }
                });
            });
        });
    }

    // ==========================================
    // INTERCEPTADOR: SALVAR ALTERAÇÕES (EDIÇÃO)
    // ==========================================
    const formEdicao = document.getElementById('form_edicao_consulta');
    if (formEdicao) {
        formEdicao.addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o refresh convencional

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST', // O Laravel lê via POST com o @method('PUT') embutido
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                fecharModalEdicao();
                // Grava a rolagem atual e recarrega movendo o card de horário
                atualizarMantendoScroll();
            })
            .catch(error => {
                console.error('Erro na edição:', error);
                Swal.fire({
                    title: 'Não permitido!',
                    text: error.message || 'Já existe um atendimento marcado para este dia e horário.',
                    icon: 'error',
                    confirmButtonColor: '#8C846C',
                    customClass: { popup: 'rounded-[3rem] font-sans' }
                });
            });
        });
    }
});                   
    
    </script>

    {{-- DEPENDÊNCIAS DO TOMSELECT --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializador do Buscador de Pacientes
            new TomSelect("#patient_select_fast", {
                create: false,
                sortField: { field: "text", order: "asc" },
                allowEmptyOption: true,
                render: {
                    option: function(data, escape) {
                        if (!data.initials) {
                            return `<div class="py-2 px-3 text-[#8C846C]/50 italic text-xs">Comece a digitar para buscar...</div>`;
                        }
                        return `
                            <div class="flex items-center">
                                <div class="patient-avatar">${escape(data.initials)}</div>
                                <div class="patient-info">
                                    <span class="font-bold text-gray-700 text-sm">${escape(data.text)}</span>
                                    <span class="patient-cpf">CPF: ${escape(data.cpf)}</span>
                                </div>
                            </div>`;
                    },
                    item: function(data, escape) {
                        return `<div class="text-gray-700 font-bold">${escape(data.text)}</div>`;
                    }
                }
            });

            // --- INTERCEPTADOR DE MENSAGEM DE ERRO DO SERVIDOR ---
            @if(session('error'))
                Swal.fire({
                    title: 'Não permitido!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonColor: '#8C846C',
                    customClass: { popup: 'rounded-[3rem] font-sans' }
                });
            @endif

            // --- INTERCEPTADOR DE MENSAGEM DE SUCESSO DO SERVIDOR ---
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif
        });
    </script>

<style>
.ts-wrapper.single .ts-control {
    display: flex !important;
    align-items: center !important;
    min-height: 48px !important;
    border-radius: 1rem !important;
    border: 1px solid #E1D3C1 !important;
    background-color: rgba(249, 246, 243, 0.5) !important;
    padding: 0 16px !important;
    font-family: sans-serif !important;
    box-shadow: none !important;
    cursor: text;
}
.ts-wrapper.single .ts-control .item {
    font-size: 14px !important;
    font-weight: 700 !important;
    color: #374151 !important;
    background: none !important;
    border: none !important;
    padding: 0 !important;
    margin: 0 !important;
    display: flex !important;
    align-items: center !important;
}
.ts-wrapper.single .ts-control input {
    font-size: 14px !important;
    font-family: sans-serif !important;
    display: block !important;
    width: 100% !important;
    background: none !important;
    margin: 0 !important;
    padding: 0 !important;
}
.ts-control input::placeholder {
    color: rgba(140, 132, 108, 0.4) !important;
    font-style: italic;
}
.ts-dropdown .option {
    display: flex !important;
    align-items: center;
    padding: 10px 15px !important;
}
.patient-avatar {
    width: 32px;
    height: 32px;
    background: #E1D3C1;
    color: #8C846C;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 900;
    margin-right: 12px;
    flex-shrink: 0;
}
.patient-info { display: flex; flex-direction: column; }
.patient-cpf { font-size: 10px; color: #8C846C; opacity: 0.6; }
.lupa-icon { 
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(140, 132, 108, 0.4);
    pointer-events: none;
    z-index: 10;
}
.ts-wrapper.focus .ts-control {
    border-color: #8C846C !important;
    background-color: #fff !important;
}
.ts-wrapper.has-items .lupa-icon { display: none; }
</style>
</x-app-layout>