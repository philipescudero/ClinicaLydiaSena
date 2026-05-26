<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Card Principal --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-[#E1D3C1] overflow-hidden">
                
                {{-- Barra de Ferramentas Refinada --}}
                <div class="p-8 border-b border-[#F9F6F3] flex flex-wrap items-center justify-between gap-6 font-sans">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6 flex-1">
                        <form action="{{ route('pacientes') }}" method="GET" class="flex items-center gap-3">
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <input type="hidden" name="filter" value="{{ $filter ?? 'todos' }}">

                            <div class="relative group">
                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar paciente..."  
                                    class="pl-12 pr-14 py-3.5 border border-[#E1D3C1] bg-[#F9F6F3]/50 rounded-2xl text-sm focus:ring-2 focus:ring-[#8C846C] focus:border-[#8C846C] w-80 shadow-inner transition-all outline-none text-gray-700 placeholder:text-[#8C846C]/30 placeholder:italic">
                                
                                {{-- Ícone de Lupa Profissional --}}
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#8C846C]/40 group-focus-within:text-[#8C846C] transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </span>

                                {{-- Botão "Enter" --}}
                                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 rounded-lg bg-[#E1D3C1]/20 text-[#8C846C]/40 hover:bg-[#8C846C] hover:text-white transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </button>
                            </div>
                            
                            @if(!empty($search))
                                <a href="{{ route('pacientes', ['month' => $month, 'year' => $year, 'filter' => $filter ?? 'todos']) }}" 
                                    class="text-[9px] bg-white border border-[#E1D3C1] text-[#8C846C]/60 px-4 py-2 rounded-full font-black hover:bg-red-50 hover:text-red-400 hover:border-red-100 transition uppercase tracking-widest shadow-sm">Limpar Busca</a>
                            @endif
                        </form>

                        {{-- Filtros de Status --}}
                        <div class="flex gap-2 bg-[#F9F6F3] p-1.5 rounded-full border border-[#E1D3C1]/50 shadow-inner">
                            <a href="{{ route('pacientes', ['month' => $month, 'year' => $year, 'search' => $search, 'filter' => 'todos']) }}" 
                                class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition {{ ($filter ?? 'todos') !== 'agendados' ? 'bg-white text-[#8C846C] shadow-md scale-105' : 'text-[#8C846C]/40 hover:text-[#8C846C]' }}">● Todos</a>
                            <a href="{{ route('pacientes', ['month' => $month, 'year' => $year, 'search' => $search, 'filter' => 'agendados']) }}" 
                                class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition {{ ($filter ?? '') === 'agendados' ? 'bg-[#8C846C] text-white shadow-md scale-105' : 'text-[#8C846C]/40 hover:text-[#8C846C]' }}">🗓️ Agendados no Mês</a>
                        </div>
                    </div>

                    <a href="{{ route('patients.create') }}" class="bg-[#8C846C] hover:bg-[#766f5a] text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.15em] shadow-xl shadow-[#8C846C]/20 transition transform hover:-translate-y-0.5 active:scale-95 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                        Cadastrar Paciente
                    </a>
                </div>

                {{-- Barra de Navegação Temporal (Ano e Meses) --}}
                <div class="flex items-center gap-4 px-8 py-5 bg-[#F9F6F3]/30 border-b border-[#F9F6F3] overflow-x-auto">
                    {{-- Seletor de Ano --}}
                    <div class="flex items-center gap-2 pr-4 border-r border-[#E1D3C1]/50">
                        @php $currentYear = now()->year; @endphp
                        <select onchange="window.location.href = this.value" 
                            class="bg-white border border-[#E1D3C1] text-[#8C846C] text-[10px] font-black uppercase tracking-widest rounded-xl focus:ring-[#8C846C] focus:border-[#8C846C] py-2 px-4 shadow-sm cursor-pointer">
                            @for($y = $currentYear - 2; $y <= $currentYear + 2; $y++)
                                <option value="{{ route('pacientes', ['month' => $month, 'year' => $y, 'search' => $search, 'filter' => $filter ?? 'todos']) }}" 
                                    {{ $year == $y ? 'selected' : '' }}>
                                    Ano {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Seletor de Meses --}}
                    <div class="flex items-center gap-2">
                        @foreach(range(1, 12) as $m)
                            @php 
                                $dataMes = \Carbon\Carbon::create(null, $m, 1); 
                                $isActive = ($month == $m); 
                            @endphp
                            <a href="{{ route('pacientes', ['month' => $m, 'year' => $year, 'search' => $search, 'filter' => $filter ?? 'todos']) }}" 
                                class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition whitespace-nowrap 
                                {{ $isActive 
                                    ? 'bg-[#8C846C] text-white shadow-md scale-105' 
                                    : 'bg-white text-[#8C846C]/40 border border-[#E1D3C1]/50 hover:bg-[#E1D3C1]/20' 
                                }}">
                                {{ $dataMes->translatedFormat('M') }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Resumo Mensal --}}
                <div class="px-8 py-5 bg-white border-b border-[#F9F6F3]">
                    <h4 class="text-[10px] font-black text-[#8C846C]/40 uppercase tracking-[0.3em]">Resumo Mensal: {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}</h4>
                </div>

                {{-- Tabela --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-center font-sans">
                        <thead>
                            <tr class="bg-[#F9F6F3]/50 text-[10px] uppercase tracking-[0.15em] text-[#8C846C]">
                                <th class="px-8 py-4 text-left font-black border-b border-[#E1D3C1]/50 uppercase">Nome</th>
                                <th class="px-4 py-4 font-black border-b border-[#E1D3C1]/50 uppercase">Sessões</th>
                                <th class="px-4 py-4 font-black border-b border-[#E1D3C1]/50 uppercase italic text-[#8C846C]/60">S1</th>
                                <th class="px-4 py-4 font-black border-b border-[#E1D3C1]/50 uppercase italic text-[#8C846C]/60">S2</th>
                                <th class="px-4 py-4 font-black border-b border-[#E1D3C1]/50 uppercase italic text-[#8C846C]/60">S3</th>
                                <th class="px-4 py-4 font-black border-b border-[#E1D3C1]/50 uppercase italic">Total</th>
                                <th class="px-4 py-4 font-black border-b border-[#E1D3C1]/50 uppercase">Pagamento / WhatsApp</th>
                                <th class="px-8 py-4 text-right font-black border-b border-[#E1D3C1]/50 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F9F6F3]">
                            @forelse($patients as $patient)
                            <tr class="hover:bg-[#F9F6F3]/30 transition group">
                                <td class="px-8 py-5 text-left">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-[#E1D3C1]/40 flex items-center justify-center text-[#8C846C] font-black mr-4 border border-[#E1D3C1]/50 shadow-sm">{{ substr($patient->name, 0, 1) }}</div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-700 leading-tight">{{ $patient->name }}</div>
                                            <div class="text-[10px] text-[#8C846C]/50 font-medium">{{ $patient->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-5">
                                    <span class="inline-flex items-center justify-center bg-[#F9F6F3] border border-[#E1D3C1] rounded-xl w-10 h-10 text-sm font-black text-[#8C846C] shadow-inner">{{ $patient->total_sessoes_mes ?? 0 }}</span>
                                </td>
                                <td class="px-4 py-5 text-center">
                                    <span class="block text-xs font-bold text-gray-700">{{ $patient->s1_count ?? 0 }}x</span>
                                    <span class="text-[9px] text-[#8C846C]/40 font-black">R$ {{ number_format($patient->s1_sum ?? 0, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-5 text-center">
                                    <span class="block text-xs font-bold text-gray-700">{{ $patient->s2_count ?? 0 }}x</span>
                                    <span class="text-[9px] text-[#8C846C]/40 font-black">R$ {{ number_format($patient->s2_sum ?? 0, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-5 text-center">
                                    <span class="block text-xs font-bold text-gray-700">{{ $patient->s3_count ?? 0 }}x</span>
                                    <span class="text-[9px] text-[#8C846C]/40 font-black">R$ {{ number_format($patient->s3_sum ?? 0, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-4 py-5">
                                    <span class="text-xs font-black text-[#8C846C] bg-[#E1D3C1]/30 px-4 py-2 rounded-xl border border-[#E1D3C1]">R$ {{ number_format($patient->total_mes ?? 0, 2, ',', '.') }}</span>
                                </td>
                                
                               <td class="px-4 py-5">
                                    <div class="flex items-center justify-center gap-3" id="payment-container-{{ $patient->id }}">
                                        @php
                                            $temPendencia = $patient->pendentes_no_mes > 0;
                                            $temSessao = ($patient->total_sessoes_mes ?? 0) > 0;
                                        @endphp

                                        {{-- Caso 1: Tem sessões e há pendências --}}
                                        @if($temSessao && $temPendencia)
                                            <button type="button" 
                                                    onclick="ajaxTogglePayment('{{ $patient->id }}', 'pay')" 
                                                    title="Registrar Pagamento Total" 
                                                    class="p-2.5 bg-white text-[#8C846C]/30 hover:text-green-600 rounded-xl hover:bg-green-50 transition shadow-sm border border-[#E1D3C1]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                                            </button>

                                        {{-- Caso 2: Tem sessões e tudo está pago --}}
                                        @elseif($temSessao)
                                            <button type="button" 
                                                    onclick="ajaxTogglePayment('{{ $patient->id }}', 'refund', '{{ $patient->name }}')"
                                                    title="Estornar Pagamento"
                                                    class="p-2.5 bg-green-600 text-white rounded-xl shadow-md border border-green-700 transition transform hover:scale-105">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                                            </button>
                                        @else
                                            <div class="p-2.5 opacity-10"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>
                                        @endif

                                        {{-- WhatsApp com Menu de Mensagens Inteligente Dinâmico por CLIQUE --}}
                                        @php
                                            $keyMes = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                                            $logs = $patient->whatsapp_check_log ?? [];
                                            $jaEnviou = isset($logs[$keyMes]) && $logs[$keyMes];
                                            $primeiroNome = explode(' ', trim($patient->name))[0];
                                            $phoneLimpo = preg_replace('/\D/', '', $patient->phone);

                                            if (!empty($patient->sessoes_pendentes)) {
                                                $listaSessoes = explode('|', $patient->sessoes_pendentes);
                                                $textoSessoes = "\n- " . implode("\n- ", $listaSessoes);
                                                $msgLembrete = "Olá " . $primeiroNome . ", tudo bem? Passando para lembrar das nossas próximas sessões agendadas deste mês:" . $textoSessoes . "\n\nAguardo você nos nossos horários combinados!";
                                            } else {
                                                $msgLembrete = "Olá " . $primeiroNome . ", tudo bem? Passando para lembrar da nossa próxima sessão agendada. Aguardo você no nosso horário combinado!";
                                            }
                                            $urlLembrete = "https://wa.me/" . $phoneLimpo . "?text=" . urlencode($msgLembrete);

                                            $msgCobranca = "Olá " . $primeiroNome . ", tudo bem? Aqui é da clínica da Lydia Sena. Passando para confirmar suas " . ($patient->total_sessoes_mes ?? 0) . " sessões deste mês. O valor total é de R$ " . number_format(($patient->total_mes ?? 0), 2, ',', '.') . ". Podemos confirmar?";
                                            $urlCobranca = "https://wa.me/" . $phoneLimpo . "?text=" . urlencode($msgCobranca);

                                            // Puxa o valor que está gravado na coluna CPF do banco
                                        $matriculaPaciente = $patient->cpf; 
                                        
                                            $msgAcesso = "Olá " . $primeiroNome . ", criei seu perfil no nosso sistema! Você pode acompanhar sua agenda e histórico financeiro acessando o link: " . url('/') . "/login \n\nSua Matrícula de Acesso é: " . $matriculaPaciente . "\nSua senha provisória é: clinicalydiasena";
                                        
                                        $urlAcesso = "https://wa.me/" . $phoneLimpo . "?text=" . urlencode($msgAcesso);
                                        @endphp

                                        {{-- Container Mestre Isolado (Garante que nada saia do lugar) --}}
                                        <div class="relative inline-block text-left zap-dropdown-container">
                                            
                                            {{-- Botão Principal do WhatsApp --}}
                                            <button type="button" onclick="toggleZapMenu(event, '{{ $patient->id }}')" 
                                                    class="p-2.5 {{ $jaEnviou ? 'bg-green-600 text-white shadow-md border-green-700' : 'bg-white text-green-500 hover:bg-green-50 border-[#E1D3C1]' }} rounded-xl transition shadow-sm border flex items-center outline-none select-none">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.237 3.483 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.652zm5.586-3.822c1.552.921 3.469 1.408 5.424 1.409 5.861 0 10.63-4.77 10.632-10.633.001-2.846-1.107-5.522-3.117-7.533-2.011-2.012-4.689-3.12-7.535-3.121-5.865 0-10.634 4.77-10.636 10.633-.001 2.035.534 4.021 1.549 5.79l-1.018 3.719 3.805-.998z"/>
                                                </svg>
                                                <span class="text-[8px] font-black uppercase tracking-widest ml-1 md:inline hidden">Mensagem</span>
                                            </button>

                                            @if($jaEnviou)
                                                <span class="badge-ok absolute -top-1 -right-1 bg-blue-500 text-white text-[7px] font-black px-1.5 rounded-full border border-white shadow-sm z-10">OK</span>
                                            @endif

                                            {{-- Menu Flutuante Blindado: Adicionado 'top-full right-0 origin-top-right' para forçar a flutuação absoluta real --}}
                                            {{-- Mudamos para top-0 e aumentamos o mr para afastar perfeitamente do botão mensagem --}}
                                            {{-- Removemos o top-0 e adicionamos a classe 'js-zap-menu' para o script gerenciar --}}
                                            <div id="zap-menu-{{ $patient->id }}" 
                                                class="absolute right-0 mr-36 w-64 bg-white rounded-2xl shadow-2xl border border-[#E1D3C1] py-2 z-[100] hidden text-left popup-zap-menu pointer-events-auto js-zap-menu">                    
                                                
                                                <div class="px-4 py-2 border-b border-[#F9F6F3]">
                                                    <p class="text-[9px] font-black uppercase tracking-widest text-[#8C846C]/60">Enviar Notificação</p>
                                                </div>
                                                
                                                <a href="{{ $urlLembrete }}" target="_blank" class="block px-4 py-3 text-xs text-gray-700 hover:bg-[#F9F6F3] transition flex flex-col gap-0.5">
                                                    <span class="font-bold text-gray-800">⏰ Lembrete de Consulta</span>
                                                    @if(!empty($patient->sessoes_pendentes))
                                                        <span class="text-[9px] text-green-600 font-medium font-sans leading-tight">Inclui as datas pendentes do mês.</span>
                                                    @else
                                                        <span class="text-[10px] text-gray-400 italic font-sans leading-tight">Aviso simples de retorno ou confirmation de horário.</span>
                                                    @endif
                                                </a>

                                                <a href="{{ $urlCobranca }}" target="_blank" onclick="marcarComoEnviado(this, '{{ $patient->id }}', '{{ $month }}', '{{ $year }}')"
                                                   class="block px-4 py-3 text-xs text-gray-700 hover:bg-[#F9F6F3] transition border-t border-[#F9F6F3] flex flex-col gap-0.5">
                                                    <span class="font-bold text-gray-800">💰 Fechamento do Mês</span>
                                                    <span class="text-[10px] text-gray-400 italic font-sans leading-tight">Envia o resumo de sessões e o valor Pix de R$ {{ number_format(($patient->total_mes ?? 0), 2, ',', '.') }}.</span>
                                                </a>

                                                <a href="{{ $urlAcesso }}" target="_blank" class="block px-4 py-3 text-xs text-gray-700 hover:bg-[#F9F6F3] transition border-t border-[#F9F6F3] flex flex-col gap-0.5">
                                                    <span class="font-bold text-gray-800">🔑 Credenciais do Espaço</span>
                                                    <span class="text-[10px] text-gray-400 italic font-sans leading-tight">Envia o link de login e dados de primeiro acesso do paciente.</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center px-6 py-2.5 bg-white text-[#8C846C] border border-[#E1D3C1] rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-[#8C846C] hover:text-white transition shadow-sm">Prontuário</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="py-24 text-center text-[#8C846C]/30 italic text-sm">Nenhum paciente encontrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Rodapé --}}
                <div class="bg-[#F9F6F3]/50 p-8 border-t border-[#E1D3C1] flex flex-wrap items-center justify-between gap-8 font-sans">
                    <div class="flex items-center gap-12">
                        <div>
                            <p class="text-[10px] uppercase font-black text-[#8C846C]/40 tracking-widest mb-2">Pacientes Pagos</p>
                            <p class="text-3xl font-serif text-gray-800">
                                @php
                                    $agendadosNoMes = $patients->filter(fn($p) => ($p->total_mes ?? 0) > 0);
                                    $totalAgendados = $agendadosNoMes->count();
                                    $totalPagos = $agendadosNoMes->filter(fn($p) => ($p->pendentes_no_mes ?? 0) == 0)->count();
                                @endphp
                                <span class="text-green-600 font-bold">{{ $totalPagos }}</span>
                                <span class="text-[#E1D3C1] mx-2">/</span>{{ $totalAgendados }} 
                                <span class="text-[10px] text-[#8C846C]/40 uppercase font-black tracking-widest ml-1">Pacientes</span>
                            </p>
                        </div>
                        <div class="h-12 w-px bg-[#E1D3C1] hidden md:block"></div>
                        <div>
                            <p class="text-[10px] uppercase font-black text-[#8C846C]/40 tracking-widest mb-2">Previsão de Faturamento</p>
                            <p class="text-3xl font-serif text-[#8C846C] font-black">R$ {{ number_format($patients->sum('total_mes'), 2, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('patients.relatorioPdf', ['month' => $month, 'year' => $year]) }}" 
                       class="inline-flex items-center gap-3 bg-white text-[#8C846C] border border-[#E1D3C1] px-8 py-3 rounded-2xl text-[10px] font-black uppercase shadow-sm hover:bg-[#8C846C] hover:text-white transition tracking-[0.2em]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Gerar Relatório PDF
                    </a>
                </div>
            </div> {{-- Fim do Card Principal --}}
        </div>
    </div>
</x-app-layout>

<style>
    @media print {
        nav, .Barra-de-Ferramentas, button, form, .no-print, .Seletor-Meses {
            display: none !important;
        }
        body { background-color: white !important; font-family: 'serif' !important; }
        .max-w-7xl { max-width: 100% !important; padding: 0 !important; }
        .shadow-sm, .rounded-[2.5rem] { border: none !important; shadow: none !important; }
        .header-relatorio {
            display: block !important;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #8C846C;
            padding-bottom: 20px;
        }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #F9F6F3 !important; color: #8C846C !important; -webkit-print-color-adjust: exact; }
        td, th { border: 1px solid #E1D3C1 !important; padding: 12px !important; font-size: 10pt !important; }
        .text-green-600 { color: #166534 !important; font-weight: bold !important; }
        .bg-green-600 { background-color: #166534 !important; color: white !important; -webkit-print-color-adjust: exact; }
    }
    .header-relatorio { display: none; }
</style>

<div class="header-relatorio">
    <h1 style="font-size: 24pt; color: #8C846C; font-style: italic; margin-bottom: 5px;">Lydia Sena</h1>
    <p style="text-transform: uppercase; letter-spacing: 3px; font-size: 9pt; color: #8C846C;">Psicologia Clínica e Neuropsicologia</p>
    <div style="margin-top: 20px; font-weight: bold; color: #766f5a;">
        RELATÓRIO MENSAL DE ATENDIMENTOS - {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F / Y') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmarEstorno(patientId, patientName) {
        Swal.fire({
            title: 'Reverter Pagamento?',
            text: `Deseja retornar o status financeiro de ${patientName} para pendente?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, Reverter',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#8C846C',
            cancelButtonColor: '#E1D3C1',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[2.5rem] border border-[#E1D3C1] p-8 font-sans',
                title: 'font-serif italic text-gray-800 text-2xl',
                confirmButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-6 py-3',
                cancelButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`form-estorno-${patientId}`).submit();
            }
        });
    }

    function marcarComoEnviado(element, patientId, month, year) {
        const container = element.closest('.relative');
        const botao = container.querySelector('button');
        
        botao.classList.remove('bg-white', 'text-green-500');
        botao.classList.add('bg-green-600', 'text-white', 'shadow-md');
        
        if (!container.querySelector('.badge-ok')) {
            const badge = document.createElement('span');
            badge.className = "badge-ok absolute -top-1 -right-1 bg-blue-500 text-white text-[7px] font-black px-1.5 rounded-full border border-white shadow-sm z-10";
            badge.innerText = "OK";
            container.appendChild(badge);
        }

        fetch(`/pacientes/${patientId}/mark-whatsapp-sent`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ month: month, year: year })
        });
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#8C846C',
            background: '#ffffff',
            customClass: {
                popup: 'rounded-[2rem] border border-[#E1D3C1]',
                confirmButton: 'rounded-xl uppercase font-black text-xs tracking-widest px-8 py-3'
            }
        });
    @endif

    function ajaxTogglePayment(patientId, action, patientName = '') {
        const isRefund = (action === 'refund');
        if (isRefund) {
            Swal.fire({
                title: 'Reverter Pagamento?',
                text: `Deseja retornar o status de ${patientName} para pendente?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, Reverter',
                confirmButtonColor: '#8C846C',
                cancelButtonColor: '#E1D3C1',
            }).then((result) => {
                if (result.isConfirmed) {
                    executePaymentRequest(patientId, action);
                }
            });
        } else {
            executePaymentRequest(patientId, action);
        }
    }

    function executePaymentRequest(patientId, action) {
        const url = action === 'pay' ? `/pacientes/${patientId}/baixar-mes` : `/pacientes/${patientId}/estornar-mes`;
        
        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ month: '{{ $month }}', year: '{{ $year }}' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById(`payment-container-${patientId}`);
                const zapHtml = container.querySelector('.zap-dropdown-container').outerHTML;

                if (action === 'pay') {
                    container.innerHTML = `
                        <button type="button" onclick="ajaxTogglePayment('${patientId}', 'refund')" class="p-2.5 bg-green-600 text-white rounded-xl shadow-md border border-green-700 transition transform hover:scale-105">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                        </button>
                        ${zapHtml}
                    `;
                } else {
                    container.innerHTML = `
                        <button type="button" onclick="ajaxTogglePayment('${patientId}', 'pay')" class="p-2.5 bg-white text-[#8C846C]/30 hover:text-green-600 rounded-xl hover:bg-green-50 transition shadow-sm border border-[#E1D3C1]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7" /></svg>
                        </button>
                        ${zapHtml}
                    `;
                }

                const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                Toast.fire({ icon: 'success', title: data.message });
            }
        })
        .catch(error => console.error('Erro:', error));
    }

    // --- LOGICA DE CLIQUE MANUAL PARA O MENU DO WHATSAPP ---
    function toggleZapMenu(event, patientId) {
        event.stopPropagation(); // Impede o fechamento imediato
        
        const menuId = `zap-menu-${patientId}`;
        const menuAlvo = document.getElementById(menuId);
        const linhaAtual = event.currentTarget.closest('tr'); // Pega a linha (tr) atual
        const todasAsLinhas = Array.from(linhaAtual.parentElement.querySelectorAll('tr')); // Todas as linhas válidas
        
        const totalPacientes = todasAsLinhas.length;
        const indiceAtual = todasAsLinhas.indexOf(linhaAtual); // Posição atual (0, 1, 2...)

        // Fecha qualquer outro menu aberto na tela
        document.querySelectorAll('.popup-zap-menu').forEach(menu => {
            if (menu.id !== menuId) {
                menu.classList.add('hidden');
            }
        });

        // Se o menu já estava aberto e vai fechar, apenas esconde e limpa as classes
        if (!menuAlvo.classList.contains('hidden')) {
            menuAlvo.classList.add('hidden');
            return;
        }

        // --- REGRA DINÂMICA PARA OS 3 ÚLTIMOS ---
        // Exemplo: Se tiver 16 pacientes, o índice vai de 0 a 15. Os 3 últimos são 13, 14 e 15.
        // Portanto: 15 >= (16 - 3) -> 15 >= 13 (Verdadeiro!)
        if (indiceAtual >= (totalPacientes - 3)) {
            // Alinha a base do menu com a base da linha e joga para cima
            menuAlvo.classList.remove('top-0', 'mt-0');
            menuAlvo.classList.add('bottom-0', 'mb-0');
        } else {
            // Alinha o topo do menu com o topo da linha e joga para baixo
            menuAlvo.classList.remove('bottom-0', 'mb-0');
            menuAlvo.classList.add('top-0', 'mt-0');
        }

        // Exibe o menu posicionado com precisão cirúrgica
        menuAlvo.classList.remove('hidden');
    }

    // Fecha qualquer menu se a Lydia clicar em qualquer outra parte da tela
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.zap-dropdown-container')) {
            document.querySelectorAll('.popup-zap-menu').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .font-sans { font-style: normal !important; }
    .historico-card { background-color: white !important; }
    .indicador-pago { position: absolute; left: 0; top: 0; bottom: 0; width: 6px; background-color: #22c55e; opacity: 0.5; z-index: 20; }
</style>