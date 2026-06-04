<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif">
        <div class="max-w-6xl mx-auto px-4">
            
            {{-- Cabeçalho --}}
            <div class="mb-12 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-left">
                    <h2 class="text-4xl text-gray-800 italic">Olá, {{ Auth::user()->name }}</h2>
                    <p class="text-[#8C846C]/60 text-[10px] font-black uppercase tracking-[0.3em] mt-3 font-sans">Seu Espaço de Cuidado e Acompanhamento</p>
                </div>

                {{-- Seletor de Período --}}
                <div class="flex items-center bg-white border border-[#E1D3C1] rounded-2xl p-1 shadow-sm font-sans">
                    <a href="{{ route('patient.area', ['month' => $currentDate->copy()->subMonth()->month, 'year' => $currentDate->copy()->subMonth()->year]) }}" 
                       class="p-3 hover:bg-[#F9F6F3] rounded-xl transition-all text-[#8C846C] group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    
                    <div class="px-8 text-center min-w-[180px]">
                        <div class="text-[9px] font-black uppercase tracking-widest text-[#8C846C]/50 mb-0.5">Período de</div>
                        <div class="text-sm font-bold text-gray-800 capitalize">{{ $currentDate->translatedFormat('F Y') }}</div>
                    </div>

                    <a href="{{ route('patient.area', ['month' => $currentDate->copy()->addMonth()->month, 'year' => $currentDate->copy()->addMonth()->year]) }}" 
                       class="p-3 hover:bg-[#F9F6F3] rounded-xl transition-all text-[#8C846C] group">
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                
                {{-- Coluna 1: Financeiro --}}
                {{-- Coluna 1: Financeiro --}}
                {{-- Coluna 1: Financeiro Refinado com Extrato Transparente --}}
                <div class="bg-white p-10 rounded-[3rem] border border-[#E1D3C1] shadow-sm flex flex-col justify-between h-full min-h-[460px]">
                    
                    <div>
                        <h3 class="text-[10px] uppercase font-black text-[#8C846C]/60 tracking-widest mb-6 font-sans">Financeiro do Mês</h3>
                        
                        {{-- Métrica Dinâmica de Saldos Cruzados --}}
                        <div class="grid grid-cols-2 gap-4 border-b border-[#F9F6F3] pb-6 mb-6">
                            <div>
                                <p class="text-[#8C846C]/40 uppercase text-[8px] font-black tracking-widest mb-1">Total Consultas</p>
                                <p class="text-xl font-bold text-gray-700 font-sans">R$ {{ number_format($totalAmount, 2, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-[#8C846C]/40 uppercase text-[8px] font-black tracking-widest mb-1">Valor Pendente</p>
                                <p class="text-xl font-bold {{ $restantePendente > 0 ? 'text-amber-500 animate-pulse' : 'text-green-600' }} font-sans">
                                    R$ {{ number_format($restantePendente, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        {{-- Extrato de Entradas Transparente para o Paciente --}}
                        <div class="mb-6">
                            <p class="text-[#8C846C]/50 uppercase text-[8px] font-black tracking-widest mb-2 pl-1">Seus Pagamentos Registrados</p>
                            <div class="space-y-1.5 max-h-28 overflow-y-auto pr-1 custom-scrollbar">
                                @forelse($historicoPagamentos ?? [] as $pagamento)
                                    <div class="flex items-center justify-between p-2 bg-green-50/30 border border-green-100/50 rounded-xl text-[11px] font-sans">
                                        <div class="flex items-center gap-1.5 text-gray-600">
                                            <div class="w-1 h-1 rounded-full bg-green-500"></div>
                                            <span class="font-medium">{{ $pagamento->type === 'integral' ? 'Baixa Integral' : 'Abatimento Parcial' }}</span>
                                        </div>
                                        <div class="text-right flex items-center gap-2">
                                            <span class="font-black text-green-700">R$ {{ number_format($pagamento->amount, 2, ',', '.') }}</span>
                                            <span class="text-[9px] text-gray-400 font-medium">({{ \Carbon\Carbon::parse($pagamento->payment_date)->format('d/m/y') }})</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-[10px] text-[#8C846C]/40 italic bg-[#F9F6F3]/30 rounded-xl border border-dashed border-[#E1D3C1]/30">
                                        Nenhum pagamento computado para este período.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        {{-- Botão de Pix Dinâmico: Cobra estritamente o $restantePendente devido --}}
                        @if(!$isPaid && $restantePendente > 0)
                            <button onclick="gerarPagamentoPix('{{ $restantePendente }}')" 
                                    class="w-full mb-6 bg-[#8C846C] text-white py-4 rounded-2xl font-bold text-[11px] uppercase tracking-widest hover:bg-gray-800 transition-all shadow-lg flex items-center justify-center gap-2 font-sans cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4"></path></svg>
                                Pagar com pix
                            </button>
                        @endif

                        <div class="pt-6 border-t border-[#F9F6F3] flex justify-between items-center">
                            @if($isPaid)
                                <span class="px-5 py-2 bg-green-50 text-green-600 text-[9px] font-black uppercase tracking-widest rounded-full font-sans border border-green-100 shadow-sm">
                                    Mês Quitado
                                </span>
                            @else
                                <span class="px-5 py-2 bg-amber-50/60 text-amber-600 border border-amber-100 text-[9px] font-black uppercase tracking-widest rounded-full font-sans shadow-sm">
                                    Aguardando Acerto
                                </span>
                            @endif

                            <a href="https://wa.me/553587042011" target="_blank" class="text-[10px] font-black uppercase text-[#8C846C] hover:text-gray-800 tracking-widest font-sans transition-colors">
                                Enviar Comprovante →
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Coluna 2: Agenda --}}
                <div class="bg-[#8C846C] p-10 rounded-[3rem] shadow-xl shadow-[#8C846C]/20 text-white h-full min-h-[420px] flex flex-col">
                    <h3 class="text-[10px] uppercase font-black text-white/60 tracking-widest mb-8 font-sans">Sua Agenda em {{ $currentDate->translatedFormat('F') }}</h3>
                    
                    <div class="space-y-4 overflow-y-auto pr-2 custom-scrollbar flex-grow">
                        @forelse($allSessions as $session)
                            <div class="bg-white/5 p-5 rounded-2xl flex justify-between items-center border border-white/10 transition-all hover:bg-white/10">
                                <div class="font-sans">
                                    <div class="text-[10px] font-black uppercase opacity-50 tracking-wider mb-1">
                                        {{ \Carbon\Carbon::parse($session->session_date)->translatedFormat('l, d/m') }}
                                    </div>
                                    <div class="text-xl font-light tracking-tight">
                                        {{ \Carbon\Carbon::parse($session->session_date)->format('H:i') }}
                                    </div>
                                </div>

                                <div class="text-right font-sans">
                                    <span class="px-4 py-1.5 {{ $session->performed ? 'bg-green-500/20 text-green-300 border-green-500/30' : 'bg-white/10 text-white/70 border-white/10' }} rounded-full text-[8px] font-black uppercase tracking-widest border shadow-sm">
                                        {{ $session->performed ? 'Realizada' : 'Agendada' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full opacity-40 italic font-sans py-20">
                                <p class="text-sm">Nenhuma sessão registrada para este período.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/10">
                        <a href="https://www.google.com/maps/search/?api=1&query=R.+Sete+de+Setembro,+923+Muzambinho+MG" target="_blank" 
                           class="flex justify-center items-center gap-3 text-[10px] font-black uppercase tracking-[0.2em] bg-white text-[#8C846C] py-4 rounded-2xl hover:bg-[#F9F6F3] transition-all font-sans shadow-lg">
                            Localização da Clínica
                        </a>
                    </div>
                </div>

            </div> {{-- Fim do Grid --}}
            
            {{-- Rodapé --}}
            <div class="mt-16 text-center border-t border-[#E1D3C1] pt-10 font-sans">
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-[#8C846C]/40 mb-6">Dúvidas ou Reagendamentos?</p>
                <div class="flex flex-col md:flex-row justify-center items-center gap-6 md:gap-12 text-[#8C846C] text-[11px] font-bold tracking-wider">
                    <span> (35) 98704-2011 </span>
                    <span class="uppercase"> R. Sete de Setembro, 923 - Muzambinho </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Script do Pix Corrigido --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function gerarPagamentoPix(valor) {
        // --- CONFIGURAÇÕES DO BENEFICIÁRIO ---
        let chavePix = "35987042011"; // COLOQUE SUA CHAVE AQUI
        const nomeBeneficiario = "LYDIA SENA";   // SEM ACENTOS
        const cidadeBeneficiario = "MUZAMBINHO"; // SEM ACENTOS
        const valorFormatado = parseFloat(valor).toFixed(2);

        // O PULO DO GATO: Se for chave de celular (apenas números com 10 ou 11 dígitos), injeta o +55 obrigatório do BCB
        if (/^\d+$/.test(chavePix) && (chavePix.length === 10 || chavePix.length === 11)) {
            chavePix = "+55" + chavePix;
        }

        // Função auxiliar para formatar os campos (ID + Tamanho + Conteúdo)
        const f = (id, conteudo) => {
            const tam = String(conteudo.length).padStart(2, '0');
            return id + tam + conteudo;
        };

        // Montagem do Payload conforme padrão BCB
        const infoPix = f("00", "br.gov.bcb.pix") + f("01", chavePix);
        
        let payload = "000201"; // Versão
        payload += f("26", infoPix); // Informações da conta
        payload += "52040000"; // Categoria
        payload += "5303986";  // Moeda (BRL)
        payload += f("54", valorFormatado); // Valor
        payload += "5802BR";   // País
        payload += f("59", nomeBeneficiario);
        payload += f("60", cidadeBeneficiario);
        payload += "62070503***"; // Campo livre
        payload += "6304"; // Início do Checksum

        // Função de Cálculo CRC16 (Validação do Banco)
        function calcularCRC16(data) {
            let crc = 0xFFFF;
            for (let i = 0; i < data.length; i++) {
                crc ^= data.charCodeAt(i) << 8;
                for (let j = 0; j < 8; j++) {
                    if ((crc & 0x8000) !== 0) crc = (crc << 1) ^ 0x1021;
                    else crc <<= 1;
                }
            }
            return (crc & 0xFFFF).toString(16).toUpperCase().padStart(4, '0');
        }

        const payloadCompleto = payload + calcularCRC16(payload);
        const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(payloadCompleto)}`;

        Swal.fire({
            title: 'Pagamento via Pix',
            html: `
                <div class="flex flex-col items-center">
                    <div class="bg-white p-2 rounded-2xl mb-4 shadow-inner">
                        <img src="${qrCodeUrl}" class="w-52 h-56" alt="QR Code Pix">
                    </div>
                    <p class="text-[11px] text-gray-500 mb-4 font-sans px-6 text-center">
                        Aponte o App do banco para o QR Code ou copie o código abaixo.
                    </p>
                    <div class="bg-[#F9F6F3] p-4 rounded-2xl border border-[#E1D3C1] w-full text-left font-sans overflow-hidden">
                        <p class="text-[9px] font-black uppercase text-[#8C846C]/60 mb-1">Pix Copia e Cola</p>
                        <div class="flex items-center gap-2">
                            <p class="text-[9px] font-mono text-gray-500 break-all leading-tight flex-1 select-all">${payloadCompleto}</p>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Copiar Código',
            cancelButtonText: 'Fechar',
            confirmButtonColor: '#8C846C',
            cancelButtonColor: '#E1D3C1',
            customClass: {
                popup: 'rounded-[3rem] p-10',
                title: 'font-serif italic text-2xl text-gray-800',
                confirmButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-6 py-3',
                cancelButton: 'rounded-xl uppercase font-black text-[10px] tracking-widest px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Copia o código para a área de transferência
                const el = document.createElement('textarea');
                el.value = payloadCompleto;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    title: 'Código copiado!',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    }
</script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .font-sans { font-style: normal !important; }
    </style>
</x-app-layout>