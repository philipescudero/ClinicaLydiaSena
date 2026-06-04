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
                                {{-- COLUNA 1: NOME --}}
                                <td class="px-8 py-5 text-left">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-[#E1D3C1]/40 flex items-center justify-center text-[#8C846C] font-black mr-4 border border-[#E1D3C1]/50 shadow-sm">{{ substr($patient->name, 0, 1) }}</div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-700 leading-tight">{{ $patient->name }}</div>
                                            <div class="text-[10px] text-[#8C846C]/50 font-medium">{{ $patient->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- COLUNA 2: QUANTIDADE SESSÕES --}}
                                <td class="px-4 py-5">
                                    <span class="inline-flex items-center justify-center bg-[#F9F6F3] border border-[#E1D3C1] rounded-xl w-10 h-10 text-sm font-black text-[#8C846C] shadow-inner">{{ $patient->total_sessoes_mes ?? 0 }}</span>
                                </td>

                                {{-- COLUNA 3: S1 --}}
                                <td class="px-4 py-5 text-center">
                                    <span class="block text-xs font-bold text-gray-700">{{ $patient->s1_count ?? 0 }}x</span>
                                    <span class="text-[9px] text-[#8C846C]/40 font-black">R$ {{ number_format($patient->s1_sum ?? 0, 2, ',', '.') }}</span>
                                </td>

                                {{-- COLUNA 4: S2 --}}
                                <td class="px-4 py-5 text-center">
                                    <span class="block text-xs font-bold text-gray-700">{{ $patient->s2_count ?? 0 }}x</span>
                                    <span class="text-[9px] text-[#8C846C]/40 font-black">R$ {{ number_format($patient->s2_sum ?? 0, 2, ',', '.') }}</span>
                                </td>

                                {{-- COLUNA 5: S3 --}}
                                <td class="px-4 py-5 text-center">
                                    <span class="block text-xs font-bold text-gray-700">{{ $patient->s3_count ?? 0 }}x</span>
                                    <span class="text-[9px] text-[#8C846C]/40 font-black">R$ {{ number_format($patient->s3_sum ?? 0, 2, ',', '.') }}</span>
                                </td>

                                {{-- COLUNA 6: TOTAL MÊS + BALANÇO DEVEDOR EM AMARELO RECALCULADO --}}
                                <td class="px-4 py-5">
                                    <div class="flex flex-col items-center justify-center gap-1 font-sans">
                                        <span class="text-xs font-black text-[#8C846C] bg-[#E1D3C1]/30 px-4 py-1.5 rounded-xl border border-[#E1D3C1]">
                                            R$ {{ number_format($patient->total_mes ?? 0, 2, ',', '.') }}
                                        </span>

                                        @if(($patient->pendentes_no_mes ?? 0) > 0)
                                            <span class="text-[9px] font-black text-amber-500 uppercase tracking-wider animate-pulse">
                                                Pendente: R$ {{ number_format($patient->pendentes_no_mes, 2, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                
                                {{-- COLUNA 7: PAGAMENTO E WHATSAPP (CORRIGIDA COM FECHAMENTO DE TAGS) --}}
                                <td class="px-4 py-5">
                                    <div id="payment-container-{{ $patient->id }}" class="relative inline-block text-left zap-dropdown-container">
                                        @php
                                            $totalSessoes = $patient->total_sessoes_mes ?? 0;
                                            $temSessao = $totalSessoes > 0;
                                            $totalMesBruto = (float) ($patient->total_mes ?? 0);
                                            $valorPendenteReais = (float) ($patient->pendentes_no_mes ?? 0);
                                            $parcial = ($valorPendenteReais > 0 && $valorPendenteReais < $totalMesBruto);
                                        @endphp

                                        @if($temSessao)
                                            @if(($patient->pendentes_no_mes ?? 0) > 0)
                                                {{-- Botão ativo se houver pendência --}}
                                                <button type="button" onclick="togglePayMenu(event, '{{ $patient->id }}')" 
                                                        class="px-4 py-2 rounded-2xl font-sans text-[10px] font-black uppercase tracking-widest transition shadow-sm border flex items-center gap-1.5 outline-none select-none cursor-pointer
                                                        {{ $parcial ? 'bg-white text-amber-500 border-amber-200 hover:bg-amber-50' : 'bg-white text-[#8C846C]/40 border-[#E1D3C1] hover:bg-[#F9F6F3]' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    <span>{{ $parcial ? 'Parcial' : 'Pagamento' }}</span>
                                                </button>

                                                {{-- MENU FLUTUANTE FINANÇAS --}}
                                                {{-- MENU FLUTUANTE FINANÇAS NA LISTAGEM GERAL --}}
                                                    <div id="pay-menu-{{ $patient->id }}" class="absolute right-0 mr-36 w-64 bg-white rounded-2xl shadow-2xl border border-[#E1D3C1] py-2 z-[110] hidden text-left popup-zap-menu js-pay-menu">    
                                                        <div class="px-4 py-2 border-b border-[#F9F6F3]">
                                                            <p class="text-[9px] font-black uppercase tracking-widest text-[#8C846C]/60">Gestão Financeira</p>
                                                        </div>
                                                        
                                                        <a href="javascript:void(0);" onclick="executePaymentRequest('{{ $patient->id }}', 'pay')" class="block px-4 py-3 text-xs text-gray-700 hover:bg-green-50 transition flex flex-col gap-0.5">
                                                            <span class="font-bold text-green-600">⚡ Pagamento Integral</span>
                                                            <span class="text-[9px] text-gray-400 font-medium leading-tight">Quita todo o valor de R$ {{ number_format($patient->pendentes_no_mes, 2, ',', '.') }}</span>
                                                        </a>

                                                        <a href="javascript:void(0);" onclick="abrirModalParcialReais('{{ $patient->id }}', '{{ $patient->name }}', '{{ number_format($patient->pendentes_no_mes, 2, ',', '.') }}')" class="block px-4 py-3 text-xs text-gray-700 hover:bg-amber-50 transition border-t border-[#F9F6F3] flex flex-col gap-0.5">
                                                            <span class="font-bold text-amber-600">📝 Pagamento Parcial</span>
                                                            <span class="text-[9px] text-gray-400 font-medium leading-tight">Informa um valor recebido em Reais (R$).</span>
                                                        </a>

                                                        <a href="javascript:void(0);" onclick="ajaxTogglePayment('{{ $patient->id }}', 'refund', '{{ $patient->name }}')" class="block px-4 py-3 text-xs text-gray-700 hover:bg-red-50 transition border-t border-[#F9F6F3] flex flex-col gap-0.5">
                                                            <span class="font-bold text-red-500">🔄 Estornar Tudo</span>
                                                            <span class="text-[10px] text-gray-400 italic leading-tight">Retorna o mês para pendente.</span>
                                                        </a>
                                                    </div>
                                            @else
                                                {{-- Botão estático se o mês já estiver pago --}}
                                                <button type="button" 
                                                        onclick="ajaxTogglePayment('{{ $patient->id }}', 'refund', '{{ $patient->name }}')"
                                                        title="Clique para estornar o pagamento deste mês"
                                                        class="px-4 py-2 bg-white text-green-600 border-green-200 hover:bg-green-50 rounded-2xl font-sans text-[10px] font-black uppercase tracking-widest transition shadow-sm border flex items-center gap-1.5 outline-none select-none cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    <span>Pago</span>
                                                </button>
                                            @endif
                                        @else
                                            <div class="p-2.5 opacity-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        @endif {{-- Fecha @if($temSessao) --}}
                                    </div> {{-- Fecha o payment-container --}}

                                    {{-- BLOCALIZAÇÃO DO COGNITIVO DO WHATSAPP (MANTIDO EXATAMENTE IGUAL) --}}
                                    @php
                                        $keyMes = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
                                        $logs = $patient->whatsapp_check_log ?? [];
                                        $jaEnviou = isset($logs[$keyMes]) && $logs[$keyMes];
                                        $primeiroNome = explode(' ', trim($patient->name))[0];
                                        $phoneLimpo = preg_replace('/\D/', '', $patient->phone);

                                        if (!empty($patient->sessoes_pendentes)) {
                                            $listaSessoes = explode('|', $patient->sessoes_pendentes);
                                            $textoSessoes = "";
                                            foreach ($listaSessoes as $sessao) {
                                                $textoSessoes .= "\n• " . trim($sessao);
                                            }
                                            $msgLembrete = "Olá, {$primeiroNome}! Tudo bem?\n\nGostaria de confirmar nossos atendimentos agendados:" . $textoSessoes . "\n\nEstarei te aguardando. Até lá!";
                                        } else {
                                            $msgLembrete = "Olá, {$primeiroNome}! Tudo bem?\n\nGostaria de confirmar nosso próximo atendimento agendado.\n\nEstarei te aguardando. Até lá!";
                                        }
                                        $urlLembrete = "https://wa.me/" . $phoneLimpo . "?text=" . urlencode($msgLembrete);

                                        $msgCobranca = "Olá, {$primeiroNome}! Tudo bem?\n\nAqui é do Consultório da Psicóloga Lydia Sena.\n\nPassando para confirmar suas " . ($patient->total_sessoes_mes ?? 0) . " sessões deste mês.\nO valor total é de R$ " . number_format(($patient->total_mes ?? 0), 2, ',', '.') . ".\n\nÉ isso mesmo?";
                                        $urlCobranca = "https://wa.me/" . $phoneLimpo . "?text=" . urlencode($msgCobranca);

                                        $matriculaPaciente = $patient->cpf;
                                        $msgAcesso = "Olá, {$primeiroNome}!\n\nCriei seu perfil no nosso sistema!\n\nVocê pode acompanhar sua agenda de atendimentos e histórico financeiro acessando o link:\n" . url('/') . "\n\nSua Matrícula de Acesso é: {$matriculaPaciente}\nSua senha provisória é: clinicalydiasena";
                                        $urlAcesso = "https://wa.me/" . $phoneLimpo . "?text=" . urlencode($msgAcesso);
                                    @endphp

                                    <div class="relative inline-block text-left zap-dropdown-container">
                                        <button type="button" onclick="toggleZapMenu(event, '{{ $patient->id }}')" 
                                                class="px-4 py-2 {{ $jaEnviou ? 'bg-green-600 text-white shadow-md border-green-700' : 'bg-white text-green-500 hover:bg-green-50 border-[#E1D3C1]' }} rounded-2xl font-sans text-[10px] font-black uppercase tracking-widest transition shadow-sm border flex items-center gap-1.5 outline-none select-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.237 3.483 8.417-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.652zm5.586-3.822c1.552.921 3.469 1.408 5.424 1.409 5.861 0 10.63-4.77 10.632-10.633.001-2.846-1.107-5.522-3.117-7.533-2.011-2.012-4.689-3.12-7.535-3.121-5.865 0-10.634 4.77-10.636 10.633-.001 2.035.534 4.021 1.549 5.79l-1.018 3.719 3.805-.998z"/>
                                            </svg>
                                            <span>Mensagem</span>
                                        </button>
                                        @if($jaEnviou)
                                            <span class="badge-ok absolute -top-1 -right-1 bg-blue-500 text-white text-[7px] font-black px-1.5 rounded-full border border-white shadow-sm z-10">OK</span>
                                        @endif

                                        <div id="zap-menu-{{ $patient->id }}" class="absolute right-0 mr-36 w-64 bg-white rounded-2xl shadow-2xl border border-[#E1D3C1] py-2 z-[100] hidden text-left popup-zap-menu pointer-events-auto js-zap-menu">    
                                            <div class="px-4 py-2 border-b border-[#F9F6F3]">
                                                <p class="text-[9px] font-black uppercase tracking-widest text-[#8C846C]/60">Enviar Notificação</p>
                                            </div>
                                            <a href="{{ $urlLembrete }}" target="_blank" class="block px-4 py-3 text-xs text-gray-700 hover:bg-[#F9F6F3] transition flex flex-col gap-0.5">
                                                <span class="font-bold text-gray-800">⏰ Lembrete de Consulta</span>
                                                @if(!empty($patient->sessoes_pendentes))
                                                    <span class="text-[9px] text-green-600 font-medium font-sans leading-tight">Inclui as datas pendentes do mês.</span>
                                                @else
                                                    <span class="text-[10px] text-gray-400 italic font-sans leading-tight">Aviso simples de confirmação de horário.</span>
                                                @endif
                                            </a>
                                            <a href="{{ $urlCobranca }}" target="_blank" onclick="marcarComoEnviado(this, '{{ $patient->id }}', '{{ $month }}', '{{ $year }}')" class="block px-4 py-3 text-xs text-gray-700 hover:bg-[#F9F6F3] transition border-t border-[#F9F6F3] flex flex-col gap-0.5">
                                                <span class="font-bold text-gray-800">💰 Fechamento do Mês</span>
                                                <span class="text-[10px] text-gray-400 italic font-sans leading-tight">Envia o resumo de sessões e o valor total de R$ {{ number_format(($patient->total_mes ?? 0), 2, ',', '.') }}.</span>
                                            </a>
                                            <a href="{{ $urlAcesso }}" target="_blank" class="block px-4 py-3 text-xs text-gray-700 hover:bg-[#F9F6F3] transition border-t border-[#F9F6F3] flex flex-col gap-0.5">
                                                <span class="font-bold text-gray-800">🔑 Credenciais do Espaço</span>
                                                <span class="text-[10px] text-gray-400 italic font-sans leading-tight">Envia o link de login e dados de primeiro acesso do paciente.</span>
                                            </a>
                                        </div>
                                    </div>
                                </td>

                                {{-- COLUNA 8: AÇÕES --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- UTILS BASE ---
    const swalConfigBase = {
        confirmButtonColor: '#8C846C',
        cancelButtonColor: '#E1D3C1',
        customClass: {
            popup: 'rounded-[2.5rem] border border-[#E1D3C1] p-8 font-sans',
            title: 'font-serif italic text-gray-800 text-2xl'
        }
    };

    const hojeDataLocal = new Date().toISOString().split('T')[0];

    // --- WHATSAPP: MARCAR COMO ENVIADO ---
    function marcarComoEnviado(element, patientId, month, year) {
        const container = element.closest('.zap-dropdown-container');
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ month: month, year: year })
        });
    }

    // --- SESSION FLASH TOAST ---
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#8C846C',
            customClass: { popup: 'rounded-[2rem] border border-[#E1D3C1]' }
        });
    @endif

    // --- CONTROLADOR DROPDOWNS ---
    function togglePayMenu(event, patientId) {
        event.stopPropagation();
        const menu = document.getElementById(`pay-menu-${patientId}`);
        
        document.querySelectorAll('.js-pay-menu').forEach(m => { 
            if(m !== menu) m.classList.add('hidden'); 
        });
        
        if (menu) {
            menu.classList.toggle('hidden');
            const row = event.currentTarget.closest('tr');
            const allRows = Array.from(row.parentElement.querySelectorAll('tr'));
            if (allRows.indexOf(row) >= (allRows.length - 3)) {
                menu.classList.add('bottom-0', 'mb-0'); 
                menu.classList.remove('top-0', 'mt-0');
            } else {
                menu.classList.add('top-0', 'mt-0'); 
                menu.classList.remove('bottom-0', 'mb-0');
            }
        }
    }

    function toggleZapMenu(event, patientId) {
        event.stopPropagation();
        const menu = document.getElementById(`zap-menu-${patientId}`);
        
        document.querySelectorAll('.js-zap-menu').forEach(m => { 
            if(m !== menu) m.classList.add('hidden'); 
        });
        
        if (menu) menu.classList.toggle('hidden');
    }

    // --- CONTROLADOR MODAL PARCIAL REAIS ---
    // --- CONTROLADOR MODAL PARCIAL REAIS CORRIGIDO ---
    function abrirModalParcialReais(patientId, name, pendente) {
        const menu = document.getElementById(`pay-menu-${patientId}`);
        if(menu) menu.classList.add('hidden');

        Swal.fire({
            title: 'Lançar Recebimento Parcial',
            html: `
                <p class="text-xs text-gray-500 mb-4 text-center">Total Restante Pendente: <b class="text-amber-600">R$ ${pendente}</b></p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-1 text-left pl-2">Valor Pago</label>
                        <input id="swal_valor" type="text" placeholder="R$ 0,00" class="w-full rounded-xl border-[#E1D3C1] bg-[#F9F6F3] text-center font-bold p-3 outline-none text-[#8C846C] text-lg">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-1 text-left pl-2">Data do Recebimento</label>
                        <input id="swal_data_parcial" type="date" value="${hojeDataLocal}" class="w-full rounded-xl border-[#E1D3C1] bg-[#F9F6F3] text-center font-bold p-3 outline-none text-[#8C846C] text-sm">
                    </div>
                </div>
            `,
            showCancelButton: true, 
            confirmButtonText: 'Confirmar Recebimento',
            cancelButtonText: 'Voltar',
            confirmButtonColor: '#8C846C',
            cancelButtonColor: '#E1D3C1',
            customClass: { popup: 'rounded-[2.5rem] border border-[#E1D3C1] p-8 font-sans', title: 'font-serif italic text-gray-800 text-xl' },
            didOpen: () => {
                const input = document.getElementById('swal_valor');
                input.addEventListener('input', (e) => {
                    let v = e.target.value.replace(/\D/g, "");
                    v = (v/100).toFixed(2).replace(".", ",");
                    v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
                    e.target.value = v ? "R$ " + v : "";
                });
            },
            preConfirm: () => {
                const val = document.getElementById('swal_valor').value;
                const dataEscolhida = document.getElementById('swal_data_parcial').value;
                if(!val) return Swal.showValidationMessage('Por favor, digite o valor recebido.');
                if(!dataEscolhida) return Swal.showValidationMessage('Por favor, selecione uma data válida.'); // <-- CORREÇÃO: Variável mapeada corretamente
                return { valor: val, data: dataEscolhida };
            }
        }).then((res) => {
            if(res.isConfirmed) {
                let valorLimpo = res.value.valor.replace("R$ ", "").replaceAll(".", "").replace(",", ".");
                executePartialPaymentReaisRequest(patientId, parseFloat(valorLimpo), res.value.data);
            }
        });
    }

    function executePartialPaymentReaisRequest(patientId, valorPagoInReais, dataPagamento) {
        fetch(`/pacientes/${patientId}/baixar-parcial-reais`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                month: '{{ $month }}', 
                year: '{{ $year }}',
                valor_pago: valorPagoInReais,
                paid_at: dataPagamento
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Sucesso!', text: data.message, confirmButtonColor: '#8C846C' })
                .then(() => window.location.reload());
            }
        });
    }

    // --- REQUISIÇÃO AJAX: INTEGRAL OU REVERSÃO ---
    function executePaymentRequest(patientId, action, dataPagamento = null) {
        const menu = document.getElementById(`pay-menu-${patientId}`);
        if(menu) menu.classList.add('hidden');

        if (action === 'pay' && !dataPagamento) {
            Swal.fire({
                title: 'Data do Pagamento',
                html: `
                    <p class="text-xs text-gray-500 mb-4">Selecione o dia do recebimento do pagamento integral:</p>
                    <input id="swal_data_integral" type="date" value="${hojeDataLocal}" class="w-full rounded-xl border-[#E1D3C1] bg-[#F9F6F3] text-center font-bold p-3 outline-none text-[#8C846C]">
                `,
                showCancelButton: true, confirmButtonText: 'Confirmar Pagamento', cancelButtonText: 'Voltar',
                confirmButtonColor: '#8C846C', cancelButtonColor: '#E1D3C1',
                customClass: { popup: 'rounded-[2.5rem] border border-[#E1D3C1] p-8 font-sans' },
                preConfirm: () => document.getElementById('swal_data_integral').value
            }).then((dateResult) => {
                if (dateResult.isConfirmed) {
                    executePaymentRequest(patientId, 'pay', dateResult.value);
                }
            });
            return;
        }

        const url = action === 'pay' ? `/pacientes/${patientId}/baixar-mes` : `/pacientes/${patientId}/estornar-mes`;
        
        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                month: '{{ $month }}', 
                year: '{{ $year }}', 
                paid_at: dataPagamento 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Sucesso!', text: data.message, confirmButtonColor: '#8C846C' })
                .then(() => {
                    // CORREÇÃO CRÍTICA: Força o reload físico do banco de dados na listagem mestre
                    window.location.reload();
                });
            }
        })
        .catch(error => console.error('Erro:', error));
    }

    function ajaxTogglePayment(patientId, action, patientName = '') {
        if (action === 'refund') {
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
                customClass: { popup: 'rounded-[2.5rem] border border-[#E1D3C1]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    executePaymentRequest(patientId, action);
                }
            });
        } else {
            executePaymentRequest(patientId, action);
        }
    }

    // Fechamento automático global ao clicar fora
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