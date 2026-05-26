<x-app-layout>
    <div class="py-12 bg-[#F9F6F3] min-h-screen font-serif text-left">
        
        <div class="max-w-6xl mx-auto mb-6 no-print">
            <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center text-[#8C846C] font-bold text-sm hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Voltar ao Prontuário
            </a>
        </div>

        <div class="max-w-6xl mx-auto bg-white px-10 py-10 shadow-2xl rounded-[1rem] border border-[#E1D3C1] relative">
            
            <div class="text-center mb-10">
                <span class="text-4xl text-[#8C846C]">Ψ</span>
                <h1 class="text-xl text-[#8C846C] font-bold uppercase tracking-widest">Anamnese Neuropsicológica</h1>
                <p class="text-xs text-[#8C846C]/60 font-black mt-1 uppercase">MSc. LYDIA MARIA SENA LIMA E SANTOS - CRP 04/41542</p>
            </div>

            <div class="bg-[#F9F6F3]/50 p-6 rounded-2xl border border-[#E1D3C1]/50 mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="text-[10px] uppercase font-black text-[#8C846C]/50 block mb-1">Paciente</label>
                    <p class="font-bold text-[#8C846C] uppercase">{{ $patient->name }}</p>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-black text-[#8C846C]/50 block mb-1">Data de Nascimento</label>
                    <p class="font-bold text-[#8C846C]">{{ \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') }}</p>
                </div>
                <div>
                    <label class="text-[10px] uppercase font-black text-[#8C846C]/50 block mb-1">Idade</label>
                    <p class="font-bold text-[#8C846C]">{{ \Carbon\Carbon::parse($patient->birth_date)->age }} anos</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-8 no-print border-b border-[#E1D3C1] pb-4">
                @php
                    $abas = [
                        'identificacao' => '1. Identificação/Escolaridade',
                        'queixas' => '2. Queixas Físicas/Cognitivas',
                        'humor' => '3. Humor/Personalidade',
                        'historico' => '4. Histórico Médico',
                        'habitos' => '5. Hábitos/Substâncias',
                        'obs' => '6. Observações Finais'
                    ];
                @endphp
                @foreach($abas as $id => $titulo)
                    <button onclick="switchTab('{{$id}}')" id="btn-{{$id}}" class="tab-btn px-4 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all border border-[#E1D3C1] text-[#8C846C]">
                        {{ $titulo }}
                    </button>
                @endforeach
            </div>

            <form action="{{ route('anamnese.neuro.adulto.store', $patient->id) }}" method="POST">
                @csrf
                
                <div id="tab-identificacao" class="tab-content space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Datas (todos os encontros realizados):</label>
                        <textarea name="sections[identificacao][datas]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['identificacao']['datas'] ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Lateralidade</label>
                            <div class="flex gap-4 p-3 bg-white border border-[#E1D3C1] rounded-xl">
                                @foreach(['destro', 'sinistro', 'ambidestro'] as $lat)
                                <label class="flex items-center text-xs text-[#8C846C] uppercase font-bold cursor-pointer">
                                    <input type="radio" name="sections[identificacao][lateralidade]" value="{{$lat}}" class="mr-2 border-[#E1D3C1] text-[#8C846C]" {{ ($anamnese->sections['identificacao']['lateralidade'] ?? '') == $lat ? 'checked' : '' }}> {{$lat}}
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Naturalidade</label>
                            <input type="text" name="sections[identificacao][naturalidade]" value="{{ $anamnese->sections['identificacao']['naturalidade'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Procedência</label>
                            <input type="text" name="sections[identificacao][procedencia]" value="{{ $anamnese->sections['identificacao']['procedencia'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                    </div>

                    <div class="p-6 bg-[#F9F6F3]/50 rounded-2xl border border-[#E1D3C1]/50 space-y-4">
                        <label class="block text-[10px] font-black uppercase text-[#8C846C] mb-2">Escolaridade</label>
                        @foreach(['Fundamental', 'Médio', 'Superior', 'Pós-graduação'] as $esc)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end bg-white p-3 rounded-xl border border-[#E1D3C1]/30">
                            <span class="text-xs font-bold text-[#8C846C] uppercase">{{ $esc }}</span>
                            <div class="flex gap-3">
                                <label class="flex items-center text-[10px] text-[#8C846C]"><input type="radio" name="sections[identificacao][esc_{{$esc}}][status]" value="completo" class="mr-1" {{ ($anamnese->sections['identificacao']["esc_$esc"]['status'] ?? '') == 'completo' ? 'checked' : '' }}> Completo</label>
                                <label class="flex items-center text-[10px] text-[#8C846C]"><input type="radio" name="sections[identificacao][esc_{{$esc}}][status]" value="incompleto" class="mr-1" {{ ($anamnese->sections['identificacao']["esc_$esc"]['status'] ?? '') == 'incompleto' ? 'checked' : '' }}> Incompleto</label>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-[9px] font-black uppercase text-[#8C846C]/40 mb-1">Anos de educação / Curso(s)</label>
                                <input type="text" name="sections[identificacao][esc_{{$esc}}][detalhes]" value="{{ $anamnese->sections['identificacao']["esc_$esc"]['detalhes'] ?? '' }}" class="w-full h-8 rounded-lg border-[#E1D3C1] text-xs">
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Situação conjugal</label>
                            <div class="grid grid-cols-2 gap-2 p-3 bg-white border border-[#E1D3C1] rounded-xl text-[10px] font-bold text-[#8C846C]">
                                @foreach(['solteiro', 'casado/amasiado', 'separado/divorciado', 'viúvo'] as $sit)
                                <label class="flex items-center gap-2"><input type="radio" name="sections[identificacao][conjugal]" value="{{$sit}}" {{ ($anamnese->sections['identificacao']['conjugal'] ?? '') == $sit ? 'checked' : '' }}> {{ ucfirst($sit) }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Nome do companheiro / Idade / Escolaridade</label>
                                <textarea name="sections[identificacao][companheiro_dados]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['identificacao']['companheiro_dados'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="tab-queixas" class="tab-content hidden space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Solicitante</label>
                            <input type="text" name="sections[queixa][solicitante]" value="{{ $anamnese->sections['queixa']['solicitante'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm mb-4">
                            
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Hipótese diagnóstica do solicitante</label>
                            <textarea name="sections[queixa][hipotese_solicitante]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Breve descrição da queixa / Início da queixa</label>
                            <textarea name="sections[queixa][descricao_completa]" rows="5" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['descricao_completa'] ?? '' }}</textarea>
                        </div>
                    </div>

                    @php
                        $queixas = [
                            'Físicas' => ['Dor de cabeça', 'Náusea ou vômito', 'Fadiga excessiva', 'Incontinência urinária ou urgência', 'Problemas gastrointestinais', 'Tremores e amortecimento', 'Tiques e movimentos estranhos', 'Trombar em coisas e objetos', 'Escurecimento de vista / desmaios / diplopia'],

                            'Sensoriais' => ['Perda de sensibilidade', 'Breves períodos de cegueira', 'Perda auditiva', 'Uso de aparelho auditivo', 'Sensibilidade à luz e brilho', 'Ouve barulhos estranhos', 'Ver coisas que não estão presentes (alucinações)', 'Chiado / ruído no ouvido (zumbido)', 'Prejuízo visual', 'Problemas olfativos', 'Uso de lentes de contato / óculos', 'Problemas gustativos', 'Visão turva', 'Dor (quais partes do corpo)'],

                            'Cognitivas (Resolução de Problemas)' => ['Dificuldade em realizar coisas novas', 'Dificuldade para fazer coisas na ordem certa', 'Dificuldade para resolver coisas domésticas', 'Dificuldade para pensar tão rápido quanto necessário', 'Dificuldade de planejamento prévio', 'Dificuldade para completar atividade em tempo razoável', 'Dificuldade em mudar de planos', 'Desorganização maior que o usual'],

                            'Linguagem e Habilidades Matemáticas' => [
                                'Dificuldade para achar a palavra correta',
                                'Dificuldade para entender o que lê',
                                'Discurso incoerente',
                                'Dificuldade em entender o que os outros dizem',
                                'Dificuldade para expressar o pensamento',
                                'Dificuldade em escrever redações, cartas ou palavras (não por problemas motores)',
                                'Dificuldade em operação matemática (contas, troco etc.)'
                            ],

                            'Habilidades Não Verbais' => [
                                'Dificuldade em distinguir direita e esquerda',
                                'Dificuldade de se vestir (não por problemas motores)',
                                'Dificuldade de fazer coisas que deveria ser capaz de fazer automaticamente (p. ex.: escovar dentes)',
                                'Problemas para encontrar o caminho de casa ou lugares conhecidos',
                                'Dificuldade para reconhecer objetos e/ou pessoas',
                                'Perda da noção de tempo (dia, mês, ano)'
                            ],

                            'Consciência e Construção' => [
                                'Alta distração',
                                'Torna-se confuso facilmente e desorientado',
                                'Perde a linha de raciocínio facilmente',
                                'Não se sente alerta e atento às coisas',
                                'Dificuldade em fazer mais de uma coisa ao mesmo tempo',
                                'Execução de tarefas requer mais esforço e atenção que o usual'
                            ],

                            'Memória' => [
                                'Esquece onde deixa seus pertences (p. ex.: chave, celular, carteira, bolsa)',
                                'Esquece eventos recentes (o que fez ontem, o que tomou no café da manhã)',
                                'Esquece nomes de pessoas conhecidas',
                                'Esquece compromissos',
                                'Esquece o que estava fazendo',
                                'Esquece eventos passados / antigos',
                                'Esquece o que ia fazer e/ou para onde ia',
                                'Esquece a ordem dos acontecimentos',
                                'Depende que os outros o lembrem das coisas'
                            ]
                        ];
                    @endphp

                    @foreach($queixas as $titulo => $itens)
                    <div class="p-5 bg-white border border-[#E1D3C1] rounded-2xl">
                        <label class="block text-[10px] font-black uppercase text-[#8C846C] mb-4 border-l-4 border-[#8C846C] pl-2">Queixas {{ $titulo }}</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($itens as $item)
                            <label class="flex items-center gap-3 text-[10px] font-bold text-gray-600 hover:text-[#8C846C] transition-colors cursor-pointer">
                                <input type="checkbox" name="sections[queixa][items][]" value="{{$item}}" class="rounded border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ in_array($item, $anamnese->sections['queixa']['items'] ?? []) ? 'checked' : '' }}> {{$item}}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div id="tab-humor" class="tab-content hidden space-y-6">
                    <div class="p-6 bg-white border border-[#E1D3C1] rounded-2xl shadow-sm">
                        <label class="block text-[10px] font-black uppercase text-[#8C846C] mb-4 border-l-4 border-[#8C846C] pl-2">Humor, Comportamento e Personalidade</label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4">
                            {{-- Itens com Escala de Intensidade --}}
                            @php
                                $escalas = [
                                    'Tristeza e depressão' => 'tristeza',
                                    'Ansiedade e Nervosismo' => 'ansiedade',
                                    'Estresse' => 'estresse'
                                ];
                            @endphp

                            @foreach($escalas as $label => $key)
                            <div class="flex flex-col border-b border-[#F9F6F3] pb-2">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-2 text-[11px] font-bold text-[#8C846C]">
                                        <input type="checkbox" name="sections[humor][itens][]" value="{{$label}}" class="rounded border-[#E1D3C1]" {{ in_array($label, $anamnese->sections['humor']['itens'] ?? []) ? 'checked' : '' }}> {{$label}}
                                    </label>
                                    <div class="flex gap-3 text-[9px] font-black uppercase text-[#8C846C]/40">
                                        @foreach(['Leve', 'Moderada', 'Grave'] as $nivel)
                                        <label class="flex items-center gap-1 cursor-pointer">
                                            <input type="radio" name="sections[humor][nivel_{{$key}}]" value="{{$nivel}}" class="w-3 h-3" {{ ($anamnese->sections['humor']["nivel_$key"] ?? '') == $nivel ? 'checked' : '' }}> {{$nivel}}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            {{-- Itens de Checklist Simples --}}
                            @php
                                $outros_itens = [
                                    'Falta de interesse em atividades prazerosas',
                                    'Aumento da irritabilidade',
                                    'Problemas para dormir (adormecer, permanecer dormindo ou insônia)',
                                    'Aumento da agressividade',
                                    'Irrita-se facilmente',
                                    'Pesadelos diários ou semanais',
                                    'Euforia',
                                    'Mais emotivo, chora facilmente',
                                    'Frustra-se facilmente',
                                    'Não se importa mais com as coisas como antes',
                                    'Faz as coisas automaticamente (sem consciência)',
                                    'Menos inibição (faz coisas que antes não fazia)',
                                    'Dificuldade em ser espontâneo'
                                ];
                            @endphp

                            @foreach($outros_itens as $item)
                            <label class="flex items-center gap-2 text-[11px] font-bold text-gray-500 border-b border-[#F9F6F3] pb-2 cursor-pointer">
                                <input type="checkbox" name="sections[humor][itens][]" value="{{$item}}" class="rounded border-[#E1D3C1]" {{ in_array($item, $anamnese->sections['humor']['itens'] ?? []) ? 'checked' : '' }}> {{$item}}
                            </label>
                            @endforeach

                            {{-- Itens com Opção de Perda/Aumento --}}
                            @php
                                $mudancas = [
                                    'Mudança de energia / disposição' => 'energia',
                                    'Mudança de apetite' => 'apetite',
                                    'Mudança de interesse sexual' => 'libido'
                                ];
                            @endphp

                            @foreach($mudancas as $label => $key)
                            <div class="flex flex-col border-b border-[#F9F6F3] pb-2">
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-2 text-[11px] font-bold text-[#8C846C]">
                                        <input type="checkbox" name="sections[humor][itens][]" value="{{$label}}" class="rounded border-[#E1D3C1]" {{ in_array($label, $anamnese->sections['humor']['itens'] ?? []) ? 'checked' : '' }}> {{$label}}
                                    </label>
                                    <div class="flex gap-4 text-[9px] font-black uppercase text-[#8C846C]/60">
                                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="sections[humor][tipo_{{$key}}]" value="Perda" {{ ($anamnese->sections['humor']["tipo_$key"] ?? '') == 'Perda' ? 'checked' : '' }}> Perda</label>
                                        <label class="flex items-center gap-1 cursor-pointer"><input type="radio" name="sections[humor][tipo_{{$key}}]" value="Aumento" {{ ($anamnese->sections['humor']["tipo_$key"] ?? '') == 'Aumento' ? 'checked' : '' }}> Aumento</label>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Perguntas Discursivas e Resumo --}}
                    <div class="space-y-6 mt-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Os outros têm comentado sobre as mudanças de pensamento, comportamento, personalidade ou humor? Se SIM, como e o que dizem?</label>
                            <textarea name="sections[humor][comentarios_outros]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['humor']['comentarios_outros'] ?? '' }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center justify-between p-4 bg-white border border-[#E1D3C1] rounded-xl shadow-sm">
                                <span class="text-[10px] font-black uppercase text-[#8C846C]">Nos últimos seis meses, os sintomas:</span>
                                <div class="flex gap-4 text-[10px] font-bold text-[#8C846C]">
                                    @foreach(['Melhoraram', 'Estacionaram', 'Pioraram'] as $status)
                                    <label class="flex items-center gap-1 cursor-pointer">
                                        <input type="radio" name="sections[humor][evolucao_sintomas]" value="{{$status}}" class="text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['humor']['evolucao_sintomas'] ?? '') == $status ? 'checked' : '' }}> {{$status}}
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Tem algo que o sujeito ou alguém possa fazer para o problema deixá-lo menos intenso? O que parece fazer com que piore?</label>
                            <textarea name="sections[humor][fatores_melhora_piora]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['humor']['fatores_melhora_piora'] ?? '' }}</textarea>
                        </div>

                        <div class="p-4 bg-[#F9F6F3]/50 rounded-xl border border-[#E1D3C1]">
                            <label class="block text-[10px] font-black uppercase text-[#8C846C] mb-3">Em resumo:</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @foreach([
                                    'Definitivamente há algo de errado com o sujeito' => 'errado_definitivo',
                                    'Possivelmente algo está errado' => 'errado_possivel',
                                    'Não há nada de errado com o sujeito' => 'nada_errado'
                                ] as $label => $val)
                                <label class="flex items-center gap-2 text-[10px] font-bold text-[#8C846C] cursor-pointer">
                                    <input type="radio" name="sections[humor][resumo_final]" value="{{$val}}" class="text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['humor']['resumo_final'] ?? '') == $val ? 'checked' : '' }}> {{ $label }}
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Quais são suas metas e aspirações para o futuro?</label>
                            <textarea name="sections[humor][metas_futuro]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['humor']['metas_futuro'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div id="tab-historico" class="tab-content hidden space-y-8">
                    <div class="space-y-4">
                        <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2 uppercase text-xs tracking-widest">
                            Histórico Médico Prévio <span class="text-[9px] font-normal normal-case">(Anterior à condição recorrente. Se positivo, datar e descrever brevemente)</span>
                        </h3>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $medicos = [
                                    'Trauma craniano (TCE)', 'Alteração no colesterol', 'Crise convulsiva', 'Outros traumas',
                                    'Diabetes', 'Derrame (AVC)', 'Acidentes ou quedas', 'Problemas cardíacos',
                                    'Alteração na pressão', 'Problemas psiquiátricos', 'Problemas de tireoide', 'Cirurgias',
                                    'HIV, sífilis, meningite', 'Internações', 'Outros'
                                ];
                            @endphp
                            @foreach($medicos as $m)
                            <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-[#E1D3C1] text-[9px] font-bold text-[#8C846C] uppercase cursor-pointer hover:bg-[#F9F6F3]">
                                <input type="checkbox" name="sections[medico][historico][]" value="{{$m}}" class="rounded border-[#E1D3C1] text-[#8C846C]" {{ in_array($m, $anamnese->sections['medico']['historico'] ?? []) ? 'checked' : '' }}> {{$m}}
                            </label>
                            @endforeach
                        </div>

                        <div class="space-y-4 mt-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Medicação? (quais, dosagem e tempo de uso)</label>
                                <textarea name="sections[medico][medicacao_detalhes]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['medico']['medicacao_detalhes'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Já fez outros tratamentos médicos? Quais? Por quê?</label>
                                <textarea name="sections[medico][outros_tratamentos_medicos]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['medico']['outros_tratamentos_medicos'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Já fez outros tratamentos paramédicos (psicológico, fonoaudiológico, TO, fisioterapia)? Quais? Por quê? Por quanto tempo? Houve melhora?</label>
                                <textarea name="sections[medico][tratamentos_paramedicos]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['medico']['tratamentos_paramedicos'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2 uppercase text-xs tracking-widest">Exames, testes e Avaliações Recentes</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $exames = ['Angiografia', 'Ressonância magnética', 'Tomografia Computadorizada', 'SPECT', 'PET', 'Eletroencefalograma', 'Avaliação neuropsicológica', 'Avaliação fonoaudiológica'];
                            @endphp
                            @foreach($exames as $ex)
                            <label class="flex items-center gap-2 p-2 bg-white rounded-lg border border-[#E1D3C1] text-[9px] font-bold text-[#8C846C] uppercase cursor-pointer">
                                <input type="checkbox" name="sections[medico][exames_recentes][]" value="{{$ex}}" class="rounded border-[#E1D3C1] text-[#8C846C]" {{ in_array($ex, $anamnese->sections['medico']['exames_recentes'] ?? []) ? 'checked' : '' }}> {{$ex}}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-6 bg-[#F9F6F3]/50 rounded-2xl border border-[#E1D3C1]">
                        <h3 class="text-[#8C846C] font-bold italic border-b border-white pb-2 uppercase text-xs tracking-widest mb-4 text-center">Histórico de Uso de Substâncias</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach(['Etilismo', 'Tabagismo', 'Outras drogas'] as $substancia)
                            @php $slug = Str::slug($substancia); @endphp
                            <div class="bg-white p-4 rounded-xl border border-[#E1D3C1]/50 shadow-sm">
                                <span class="text-xs font-black text-[#8C846C] block mb-3 uppercase border-b border-[#F9F6F3] pb-1">{{ $substancia }}</span>
                                <div class="flex gap-4 mb-3">
                                    <label class="flex items-center text-[10px] font-bold text-[#8C846C] cursor-pointer">
                                        <input type="radio" name="sections[substancias][{{$slug}}][status]" value="atual" class="mr-1" {{ ($anamnese->sections['substancias'][$slug]['status'] ?? '') == 'atual' ? 'checked' : '' }}> Atual
                                    </label>
                                    <label class="flex items-center text-[10px] font-bold text-[#8C846C] cursor-pointer">
                                        <input type="radio" name="sections[substancias][{{$slug}}][status]" value="previo" class="mr-1" {{ ($anamnese->sections['substancias'][$slug]['status'] ?? '') == 'previo' ? 'checked' : '' }}> Prévio
                                    </label>
                                </div>
                                <div class="mt-2">
                                    <label class="text-[9px] font-black uppercase text-[#8C846C]/40 block mb-1">Doses/semana:</label>
                                    <input type="text" name="sections[substancias][{{$slug}}][doses]" value="{{ $anamnese->sections['substancias'][$slug]['doses'] ?? '' }}" class="w-full h-8 border-b border-[#E1D3C1] border-t-0 border-x-0 focus:ring-0 text-xs">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2 uppercase text-xs tracking-widest">Histórico Familiar</h3>
                        <div class="overflow-hidden rounded-2xl border border-[#E1D3C1]">
                            <table class="w-full text-left border-collapse bg-white">
                                <thead>
                                    <tr class="bg-[#8C846C] text-white text-[10px] uppercase tracking-widest font-black">
                                        <th class="p-3 border-r border-white/20">Parentesco</th>
                                        <th class="p-3 border-r border-white/20">Problema de Saúde</th>
                                        <th class="p-3 border-r border-white/20 text-center">Falecido?</th>
                                        <th class="p-3">Causa da Morte</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @for($i = 0; $i < 4; $i++)
                                    <tr class="border-b border-[#F9F6F3]">
                                        <td class="p-2 border-r border-[#E1D3C1]/30">
                                            <input type="text" name="sections[familiar][{{$i}}][parentesco]" value="{{ $anamnese->sections['familiar'][$i]['parentesco'] ?? '' }}" class="w-full border-none focus:ring-0 text-xs text-gray-600 p-1 bg-transparent">
                                        </td>
                                        <td class="p-2 border-r border-[#E1D3C1]/30">
                                            <input type="text" name="sections[familiar][{{$i}}][problema]" value="{{ $anamnese->sections['familiar'][$i]['problema'] ?? '' }}" class="w-full border-none focus:ring-0 text-xs text-gray-600 p-1 bg-transparent">
                                        </td>
                                        <td class="p-2 border-r border-[#E1D3C1]/30">
                                            <div class="flex justify-center gap-3">
                                                <label class="flex items-center text-[10px] font-bold text-[#8C846C] cursor-pointer"><input type="radio" name="sections[familiar][{{$i}}][falecido]" value="sim" {{ ($anamnese->sections['familiar'][$i]['falecido'] ?? '') == 'sim' ? 'checked' : '' }}> S</label>
                                                <label class="flex items-center text-[10px] font-bold text-[#8C846C] cursor-pointer"><input type="radio" name="sections[familiar][{{$i}}][falecido]" value="nao" {{ ($anamnese->sections['familiar'][$i]['falecido'] ?? '') == 'nao' ? 'checked' : '' }}> N</label>
                                            </div>
                                        </td>
                                        <td class="p-2">
                                            <input type="text" name="sections[familiar][{{$i}}][causa]" value="{{ $anamnese->sections['familiar'][$i]['causa'] ?? '' }}" class="w-full border-none focus:ring-0 text-xs text-gray-600 p-1 bg-transparent">
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="tab-habitos" class="tab-content hidden space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1 p-5 bg-white border border-[#E1D3C1] rounded-2xl shadow-sm">
                                <label class="block text-[10px] font-black uppercase text-[#8C846C] mb-3 border-l-4 border-[#8C846C] pl-2">Qualidade do sono</label>
                                <div class="space-y-2 text-[11px] font-bold text-[#8C846C]/70">
                                    @foreach(['Satisfatória', 'Dificuldade em iniciar o sono', 'Acorda mais cedo e não volta a dormir', 'Acorda várias vezes durante a noite', 'Sono agitado', 'Bruxismo'] as $s)
                                    <label class="flex items-center gap-2 cursor-pointer hover:text-[#8C846C] transition-colors">
                                        <input type="checkbox" name="sections[habitos][sono][]" value="{{$s}}" class="rounded border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ in_array($s, $anamnese->sections['habitos']['sono'] ?? []) ? 'checked' : '' }}> 
                                        {{$s}}
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="md:col-span-2 space-y-4">
                                @php
                                    $camposHabitos = [
                                        'Alteração do apetite' => 'alteracao_apetite',
                                        'Leitura (O que lê? Quando lê?)' => 'leitura',
                                        'Atividade física (Qual? Frequência?)' => 'atividade_fisica',
                                        'Atividades sociais (Qual? Frequência?)' => 'atividades_sociais',
                                        'Recebe ou faz visitas a amigos e familiares? Frequência?' => 'visitas'
                                    ];
                                @endphp

                                @foreach($camposHabitos as $label => $key)
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">{{ $label }}</label>
                                    <textarea name="sections[habitos][{{ $key }}]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['habitos'][$key] ?? '' }}</textarea>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                <div id="tab-obs" class="tab-content hidden space-y-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Observações:</label>
                        <textarea name="sections[final][observacoes_livre]" rows="8" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C]">{{ $anamnese->sections['final']['observacoes_livre'] ?? '' }}</textarea>
                    </div>
                </div>

                <div class="mt-12 pt-6 border-t border-[#F9F6F3] flex justify-end no-print">
                    <button type="submit" class="bg-[#8C846C] text-white px-10 py-3 rounded-full font-bold shadow-lg hover:bg-[#766f5a] transition transform hover:scale-105 uppercase text-xs tracking-widest">
                        Salvar Anamnese Neuro Adulto
                    </button>
                </div>
            </form>

            <div class="mt-16 text-center text-[10px] text-[#8C846C]/60 font-black uppercase tracking-widest border-t border-[#F9F6F3] pt-6">
                <p>MSc. Lydia Maria Sena Lima e Santos | CRP 04/41542</p>
                <p>Rua: Sete de setembro, 923, Centro, Muzambinho – MG</p>
            </div>
        </div>
    </div>

    <button onclick="gerarPDFNeuroAdulto()" class="fixed bottom-10 right-10 bg-[#8C846C] text-white p-4 rounded-full shadow-2xl no-print hover:scale-110 transition flex items-center justify-center z-50">
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
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            document.getElementById('btn-' + tabId).classList.add('bg-[#8C846C]', 'text-white');
        }

        function gerarPDFNeuroAdulto() {
            Swal.fire({
                title: 'Gerar PDF?',
                text: "Certifique-se de salvar antes para atualizar o documento.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Gerar PDF',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#8C846C'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("{{ route('anamnese.neuro.adulto.pdf', $patient->id) }}", '_blank');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => switchTab('identificacao'));
    </script>
</x-app-layout>