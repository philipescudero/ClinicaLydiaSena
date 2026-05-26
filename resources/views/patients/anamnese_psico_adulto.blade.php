<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif text-left">
        
        <div class="max-w-6xl mx-auto mb-6 no-print">
            <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center text-[#8C846C] font-bold text-sm hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Voltar ao Prontuário
            </a>
        </div>

        <div id="conteudo-imprimivel" class="max-w-6xl mx-auto bg-white px-10 py-10 shadow-2xl rounded-[1rem] border border-[#E1D3C1] relative">
            
            <div class="text-center mb-10">
                <span class="text-4xl text-[#8C846C]">Ψ</span>
                <h1 class="text-xl text-[#8C846C] font-bold uppercase">Anamnese Psicológica - Adulto</h1>
                <p class="text-xs text-[#8C846C]/60 font-black tracking-widest mt-1">MSc. LYDIA MARIA SENA LIMA E SANTOS - CRP 04/41542</p>
            </div>

            <div class="bg-[#F9F6F3]/50 p-6 rounded-2xl border border-[#E1D3C1]/50 mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-[10px] uppercase font-black text-[#8C846C]/50 block mb-1">Paciente</label>
                    <p class="font-bold text-[#8C846C] uppercase">{{ $patient->name }}</p>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-black text-[#8C846C]/50 block mb-1">Matrícula de Acesso ao Sistema</label>
                    <p class="font-bold text-[#8C846C]">{{ $patient->cpf ?? 'Não informado' }}</p>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-black text-[#8C846C]/50 block mb-1">Idade Atual</label>
                    <p class="font-bold text-[#8C846C]">{{ \Carbon\Carbon::parse($patient->birth_date)->age }} anos</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-8 no-print border-b border-[#E1D3C1] pb-4">
                @php
                    $abasAdulto = [
                        'identificacao' => '1. Identificação',
                        'familiar' => '2. Constelação Familiar',
                        'clinico' => '3. Histórico e Queixa',
                        'parecer' => '4. Parecer e Obs'
                    ];
                @endphp
                @foreach($abasAdulto as $id => $titulo)
                    <button onclick="switchTab('tab-{{$id}}')" id="btn-tab-{{$id}}" class="tab-btn px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-tighter transition-all border border-[#E1D3C1] text-[#8C846C] hover:bg-[#E1D3C1]/30">
                        {{ $titulo }}
                    </button>
                @endforeach
            </div>

            <form action="{{ route('anamnese.psico.adulto.store', $patient->id) }}" method="POST">
                @csrf
                
                <div id="tab-identificacao" class="tab-content space-y-6">
                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Dados Pessoais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Sexo</label>
                            <select name="sections[identificacao][sexo]" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                                <option value="Masculino" {{ ($anamnese->sections['identificacao']['sexo'] ?? '') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="Feminino" {{ ($anamnese->sections['identificacao']['sexo'] ?? '') == 'Feminino' ? 'selected' : '' }}>Feminino</option>
                                <option value="Outro" {{ ($anamnese->sections['identificacao']['sexo'] ?? '') == 'Outro' ? 'selected' : '' }}>Outro</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Estado Civil</label>
                            <input type="text" name="sections[identificacao][estado_civil]" value="{{ $anamnese->sections['identificacao']['estado_civil'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Escolaridade</label>
                            <input type="text" name="sections[identificacao][escolaridade]" value="{{ $anamnese->sections['identificacao']['escolaridade'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Atividade Laboral (Profissão)</label>
                        <input type="text" name="sections[identificacao][profissao]" value="{{ $anamnese->sections['identificacao']['profissao'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                    </div>
                </div>

                <div id="tab-familiar" class="tab-content hidden space-y-6">
                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Contexto Familiar</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Dados do Pai (Nome/Idade)</label>
                            <textarea name="sections[familiar][pai]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['familiar']['pai'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Dados da Mãe (Nome/Idade)</label>
                            <textarea name="sections[familiar][mae]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['familiar']['mae'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Irmãos (Sim/Não - Quantos?)</label>
                            <textarea name="sections[familiar][irmaos]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['familiar']['irmaos'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Cônjuge / Filhos</label>
                            <textarea name="sections[familiar][conjuge_filhos]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['familiar']['conjuge_filhos'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Histórico Familiar (Doenças/Comportamentos)</label>
                        <textarea name="sections[familiar][historico]" rows="4" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['familiar']['historico'] ?? '' }}</textarea>
                    </div>
                </div>

                <div id="tab-clinico" class="tab-content hidden space-y-6">
                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Queixa e Saúde</h3>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Queixa Principal (Por que procurou ajuda?)</label>
                        <textarea name="sections[clinico][queixa]" rows="4" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['clinico']['queixa'] ?? '' }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Acompanhamento Psicológico Anterior?</label>
                            <textarea name="sections[clinico][psicologico_anterior]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['clinico']['psicologico_anterior'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Faz uso de medicação controlada?</label>
                            <textarea name="sections[clinico][medicacao]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['clinico']['medicacao'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div id="tab-parecer" class="tab-content hidden space-y-6">
                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Análise Clínica</h3>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Parecer Psicológico</label>
                        <textarea name="sections[parecer][analise]" rows="4" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['parecer']['analise'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Observações Adicionais</label>
                        <textarea name="sections[parecer][observacoes]" rows="4" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['parecer']['observacoes'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="mt-12 pt-6 border-t border-[#F9F6F3] flex justify-end no-print">
                    <button type="submit" class="bg-[#8C846C] text-white px-10 py-3 rounded-full font-bold shadow-lg hover:bg-[#766f5a] transition transform hover:scale-105">
                        Salvar Anamnese Adulto
                    </button>
                </div>
            </form>

            <div class="mt-16 text-center text-[10px] text-[#8C846C]/60 font-black uppercase tracking-widest">
                <p>Psicóloga Lydia Maria Sena Lima e Santos | CRP MG/41542</p>
                <p>Rua: Sete de setembro, 923, Centro, Muzambinho – MG</p>
            </div>
        </div>
    </div>

    <button onclick="gerarPDFAdulto()" 
            class="fixed bottom-10 right-10 bg-[#8C846C] text-white p-4 rounded-full shadow-2xl no-print hover:scale-110 transition flex items-center justify-center z-50"
            title="Gerar PDF Profissional">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-[#8C846C]', 'text-white');
                el.classList.add('text-[#8C846C]');
            });
            
            const targetTab = document.getElementById(tabId);
            const targetBtn = document.getElementById('btn-' + tabId);
            
            if(targetTab) targetTab.classList.remove('hidden');
            if(targetBtn) targetBtn.classList.add('bg-[#8C846C]', 'text-white');
        }

        function gerarPDFAdulto() {
            console.log("Botão clicado!");

            // Verifica se o SweetAlert carregou corretamente
            if (typeof Swal === 'undefined') {
                window.open("{{ route('anamnese.psico.adulto.pdf', $patient->id) }}", '_blank');
                return;
            }

            Swal.fire({
                title: 'Gerar PDF?',
                text: "Certifique-se de ter clicado em 'Salvar Anamnese' para que as alterações recentes apareçam no documento.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Sim, gerar PDF',
                cancelButtonText: 'Vou salvar primeiro',
                confirmButtonColor: '#8C846C',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("{{ route('anamnese.psico.adulto.pdf', $patient->id) }}", '_blank');
                }
            });
        }

        // Inicialização
        document.addEventListener('DOMContentLoaded', () => {
            switchTab('tab-identificacao');
        });
    </script>
</x-app-layout>