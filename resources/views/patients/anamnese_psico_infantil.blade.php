<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif text-left">
        
        <!-- Botão Voltar -->
        <div class="max-w-6xl mx-auto mb-6 no-print">
            <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center text-[#8C846C] font-bold text-sm hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Voltar ao Prontuário
            </a>
        </div>

        <div class="max-w-6xl mx-auto bg-white px-10 py-10 shadow-2xl rounded-[1rem] border border-[#E1D3C1] relative">
            
            <!-- Cabeçalho -->
            <div class="text-center mb-10">
                <span class="text-4xl text-[#8C846C]">Ψ</span>
                <h1 class="text-xl text-[#8C846C] font-bold uppercase tracking-widest">Anamnese Psicológica Infantil</h1>
                <p class="text-xs text-[#8C846C]/60 font-black mt-1 uppercase">MSc. LYDIA MARIA SENA LIMA E SANTOS - CRP 04/41542</p>
            </div>

            <!-- Dados Fixos do Paciente -->
            <div class="bg-[#F9F6F3]/50 p-6 rounded-2xl border border-[#E1D3C1]/50 mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-[10px] uppercase font-black text-[#8C846C]/50 block mb-1">Criança</label>
                    <p class="font-bold text-[#8C846C] uppercase">{{ $patient->name }}</p>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-black text-[#8C846C]/50 block mb-1">Data de Nascimento</label>
                    <p class="font-bold text-[#8C846C]">{{ \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-black text-[#8C846C]/50 block mb-1">Idade Atual</label>
                    <p class="font-bold text-[#8C846C]">{{ \Carbon\Carbon::parse($patient->birth_date)->age }} anos</p>
                </div>
            </div>

            <!-- Menu de Abas -->
            <div class="flex flex-wrap gap-2 mb-8 no-print border-b border-[#E1D3C1] pb-4">
                @php
                    $abas = [
                        'escolar' => '1. Dados Escolares',
                        'filiacao' => '2. Filiação/Constelação',
                        'queixa' => '3. Queixa Principal',
                        'primeiros_dias' => '4. Primeiros Dias',
                        'desenvolvimento' => '5. Desenvolvimento',
                        'socializacao' => '6. Socialização',
                        'psicomotor' => '7. Psicomotricidade',
                        'cognitivo' => '8. Cognitivo/Final'
                    ];
                @endphp
                @foreach($abas as $id => $titulo)
                    <button onclick="switchTab('{{$id}}')" id="btn-{{$id}}" class="tab-btn px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all border border-[#E1D3C1] text-[#8C846C]">
                        {{ $titulo }}
                    </button>
                @endforeach
            </div>

            <form action="{{ route('anamnese.psico.infantil.store', $patient->id) }}" method="POST">
                @csrf
                
                <!-- ABA 1: DADOS ESCOLARES -->
                <div id="tab-escolar" class="tab-content space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Escola</label>
                            <input type="text" name="sections[escolar][escola]" value="{{ $anamnese->sections['escolar']['escola'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Ano</label>
                                <input type="text" name="sections[escolar][ano]" value="{{ $anamnese->sections['escolar']['ano'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Série</label>
                                <input type="text" name="sections[escolar][serie]" value="{{ $anamnese->sections['escolar']['serie'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Endereço da Escola / Fone</label>
                            <input type="text" name="sections[escolar][endereco_fone]" value="{{ $anamnese->sections['escolar']['endereco_fone'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Nome da Professora</label>
                            <input type="text" name="sections[escolar][professora]" value="{{ $anamnese->sections['escolar']['professora'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Horário que frequenta a escola</label>
                        <input type="text" name="sections[escolar][horario]" value="{{ $anamnese->sections['escolar']['horario'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                    </div>
                </div>

                <!-- ABA 2: FILIAÇÃO E CONSTELAÇÃO -->
                    <div id="tab-filiacao" class="tab-content hidden space-y-6">
                        @foreach(['Pai', 'Mãe', 'Responsável'] as $f)
                        <div class="p-4 bg-[#F9F6F3]/50 rounded-2xl border border-[#E1D3C1]/50 space-y-4">
                            <h4 class="text-[10px] font-black uppercase text-[#8C846C] border-b border-[#E1D3C1] pb-1">{{ $f }}</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nome -->
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Nome do {{ $f }}</label>
                                    <input type="text" name="sections[filiacao][{{Str::slug($f)}}][nome]" value="{{ $anamnese->sections['filiacao'][Str::slug($f)]['nome'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                                </div>

                                <!-- Data de Nascimento / Idade -->
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Data de Nascimento / Idade</label>
                                    <input type="text" name="sections[filiacao][{{Str::slug($f)}}][nascimento]" value="{{ $anamnese->sections['filiacao'][Str::slug($f)]['nascimento'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                                </div>

                                <!-- Profissão -->
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Profissão</label>
                                    <input type="text" name="sections[filiacao][{{Str::slug($f)}}][profissao]" value="{{ $anamnese->sections['filiacao'][Str::slug($f)]['profissao'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                                </div>

                                <!-- Contato -->
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Tel. para contato</label>
                                    <input type="text" name="sections[filiacao][{{Str::slug($f)}}][contato]" value="{{ $anamnese->sections['filiacao'][Str::slug($f)]['contato'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                                </div>

                                <!-- Endereço (Novo campo adicionado) -->
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Endereço Residencial</label>
                                    <input type="text" name="sections[filiacao][{{Str::slug($f)}}][endereco]" value="{{ $anamnese->sections['filiacao'][Str::slug($f)]['endereco'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                                </div>
                            </div>
                        </div>
                        @endforeach

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Constelação Familiar</label>
                                <textarea name="sections[filiacao][constelacao]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['filiacao']['constelacao'] ?? '' }}</textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Nº de Irmãos</label>
                                    <input type="text" name="sections[filiacao][irmaos_qtd]" value="{{ $anamnese->sections['filiacao']['irmaos_qtd'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Posição na Ordem</label>
                                    <input type="text" name="sections[filiacao][ordem]" value="{{ $anamnese->sections['filiacao']['ordem'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- ABA 3: QUEIXA PRINCIPAL -->
                <div id="tab-queixa" class="tab-content hidden space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Queixa Principal</label>
                        <textarea name="sections[queixa][principal]" rows="4" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['principal'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Percepção dos Pais/Responsáveis em relação à Criança</label>
                        <textarea name="sections[queixa][percepcao_pais]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['percepcao_pais'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Como e quando começaram a perceber as “dificuldades”? (sentimentos, atitudes)</label>
                        <textarea name="sections[queixa][inicio_dificuldades]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['inicio_dificuldades'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Repercussões do “problema” na família e amigos</label>
                        <textarea name="sections[queixa][repercussoes]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['repercussoes'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- ABA 4: PRIMEIROS DIAS DE VIDA -->
                <div id="tab-primeiros_dias" class="tab-content hidden space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Abortos / Quando?</label>
                            <input type="text" name="sections[primeiros][abortos]" value="{{ $anamnese->sections['primeiros']['abortos'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Condições do Parto e da Criança ao nascer</label>
                            <input type="text" name="sections[primeiros][parto_condicoes]" value="{{ $anamnese->sections['primeiros']['parto_condicoes'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Amamentação (Regularidade, satisfação, dificuldades, período, mamadeira)</label>
                        <textarea name="sections[primeiros][amamentacao]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['primeiros']['amamentacao'] ?? '' }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Relações Primitivas Mãe-Filho (sentimentos, medos, inseguranças)</label>
                        <textarea name="sections[primeiros][relacao_mae]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['primeiros']['relacao_mae'] ?? '' }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Atitudes Paternas/Maternas frente às mudanças</label>
                            <textarea name="sections[primeiros][atitudes_pais]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['primeiros']['atitudes_pais'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Participação do Pai nos cuidados (primeiros meses)</label>
                            <textarea name="sections[primeiros][participacao_pai]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['primeiros']['participacao_pai'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ABA 5: DESENVOLVIMENTO (1º ANO) -->
                <div id="tab-desenvolvimento" class="tab-content hidden space-y-6">
                    @php
                        $camposDesenvolvimento = [
                            'ritmos' => 'Ritmos Biológicos (sono, alimentação, excreções)',
                            'organicos' => 'Dados Orgânicos Básicos (cólicas, infecções, convulsões)',
                            'psicotonicas' => 'Aquisição Psicotônicas (sorriso, pescoço, sentar, reconhecer mãe, engatinhar, andar)',
                            'marcha' => 'Marcha (características gerais)',
                            'linguagem' => 'Linguagem (aquisição, desenvolvimento)',
                            'ludica' => 'Atividade Lúdica Primitiva',
                            'objetos' => 'Interesse por Objetos (cor, movimento, sons)',
                            'esfincteres' => 'Controle dos Esfíncteres',
                            'rotina' => 'Rotina Diária'
                        ];
                    @endphp
                    @foreach($camposDesenvolvimento as $key => $label)
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">{{ $label }}</label>
                        <textarea name="sections[desenvolvimento][{{$key}}]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['desenvolvimento'][$key] ?? '' }}</textarea>
                    </div>
                    @endforeach
                </div>

                <!-- ABA 6: SOCIALIZAÇÃO -->
                <div id="tab-socializacao" class="tab-content hidden space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Relações Interpessoais na Família</label>
                            <textarea name="sections[social][familia]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['social']['familia'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Relações Interpessoais na Escola</label>
                            <textarea name="sections[social][escola]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['social']['escola'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Agressividade (Manifestação)</label>
                            <input type="text" name="sections[social][agressividade]" value="{{ $anamnese->sections['social']['agressividade'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Como escolhe os amigos / Nomes</label>
                            <input type="text" name="sections[social][amigos]" value="{{ $anamnese->sections['social']['amigos'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Relações nos Grupos de Brinquedos (adaptação, regras, divide objetos)</label>
                        <textarea name="sections[social][grupos]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['social']['grupos'] ?? '' }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Timidez/Extroversão</label>
                            <input type="text" name="sections[social][timidez]" value="{{ $anamnese->sections['social']['timidez'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Prática de Esportes</label>
                            <input type="text" name="sections[social][esportes]" value="{{ $anamnese->sections['social']['esportes'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Doenças da Infância</label>
                            <input type="text" name="sections[social][doencas]" value="{{ $anamnese->sections['social']['doencas'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Como vive Aniversário / Gosta de Festas?</label>
                        <textarea name="sections[social][festas]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['social']['festas'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- ABA 7: PSICOMOTRICIDADE -->
                <div id="tab-psicomotor" class="tab-content hidden space-y-6">
                    @php
                        $camposPsicomotor = [
                            'oculo_manuais' => 'Habilidades Óculo-Manuais (recorte, colagem, ferramentas)',
                            'dinamica_geral' => 'Coordenação Dinâmica Geral e Equilíbrio (pular, bicicleta, skate)',
                            'orientacao_temporal' => 'Orientação Temporal (noções de tempo/calendário)',
                            'lateral_pref' => 'Preferência Lateral (espontânea ou forçada)',
                            'atencao' => 'Capacidade de Atenção e Concentração',
                            'discriminacao' => 'Discriminação de Formas, Tamanho, Posição, Cores',
                            'noções' => 'Noção de quantidade, peso, medidas, dinheiro',
                            'realidade' => 'Contato com a Realidade / Interesses externos',
                            'decisoes' => 'Escolhas Pessoais / Opina nas decisões da Família?',
                            'rotina_dia' => 'Descrição de um dia na Rotina da Criança',
                            'limitacoes' => 'Medos?',
                            'sono' => 'Sono',
                            'ambiente' => 'Limitações Impostas pelo Ambiente',
                            'ambiente1' => 'Limitações Orgânicas (alergias, disritmias, deficiência senso-perceptivas, deficiência motoras)'
                        ];
                    @endphp
                    @foreach($camposPsicomotor as $key => $label)
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">{{ $label }}</label>
                        <textarea name="sections[psicomotor][{{$key}}]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['psicomotor'][$key] ?? '' }}</textarea>
                    </div>
                    @endforeach
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Dados Médicos</label>
                        <textarea name="sections[psicomotor][dados_medicos]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['psicomotor']['dados_medicos'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- ABA 8: COGNITIVO E FINAL -->
                <div id="tab-cognitivo" class="tab-content hidden space-y-6">
                    @php
                        $camposCognitivo = [
                            'pensamento' => 'Organização do Pensamento',
                            'linguagem' => 'Linguagem',
                            'curiosidades' => 'Curiosidades (Gerais, Sexuais)',
                            'criatividade' => 'Criatividade',
                            'memoria' => 'Memória',
                            'aproveitamento' => 'Adaptação e Aproveitamento Escolar (primeiras experiências / situação atual)',
                            'dificuldades' => 'Dificuldades Específicas'
                        ];
                    @endphp
                    @foreach($camposCognitivo as $key => $label)
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">{{ $label }}</label>
                        <textarea name="sections[cognitivo][{{$key}}]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['cognitivo'][$key] ?? '' }}</textarea>
                    </div>
                    @endforeach
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Observações Adicionais</label>
                        <textarea name="sections[cognitivo][obs_adicionais]" rows="6" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['cognitivo']['obs_adicionais'] ?? '' }}</textarea>
                    </div>
                </div>

                <!-- Botão Salvar -->
                <div class="mt-12 pt-6 border-t border-[#F9F6F3] flex justify-end no-print">
                    <button type="submit" class="bg-[#8C846C] text-white px-10 py-3 rounded-full font-bold shadow-lg hover:bg-[#766f5a] transition transform hover:scale-105 uppercase text-xs tracking-widest">
                        Salvar Anamnese Infantil
                    </button>
                </div>
            </form>

            <!-- Rodapé Institucional -->
            <div class="mt-16 text-center text-[10px] text-[#8C846C]/60 font-black uppercase tracking-widest border-t border-[#F9F6F3] pt-6">
                <p>Psicóloga Lydia Maria Sena Lima e Santos | CRP MG/41542</p>
                <p>Muzambinho – MG</p>
            </div>
        </div>
    </div>

    <!-- Botão PDF Flutuante -->
    <button onclick="gerarPDFInfantil()" class="fixed bottom-10 right-10 bg-[#8C846C] text-white p-4 rounded-full shadow-2xl no-print hover:scale-110 transition flex items-center justify-center z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-[#8C846C]', 'text-white');
            });
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            document.getElementById('btn-' + tabId).classList.add('bg-[#8C846C]', 'text-white');
        }

        function gerarPDFInfantil() {
            Swal.fire({
                title: 'Gerar PDF?',
                text: "Certifique-se de salvar os dados antes de gerar o documento.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#8C846C',
                confirmButtonText: 'Sim, gerar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("{{ route('anamnese.psico.infantil.pdf', $patient->id) }}", '_blank');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => switchTab('escolar'));
    </script>
</x-app-layout>