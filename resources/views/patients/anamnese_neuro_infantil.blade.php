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
                <h1 class="text-xl text-[#8C846C] font-bold uppercase">Anamnese Neuropsicológica Infantil</h1>
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
                    $abas = [
                        'identificacao' => '1. Identificação',
                        'queixa' => '2. Queixa/Motivo',
                        'escolar' => '3. Histórico Escolar',
                        'habitos' => '4. Hábitos/Sono',
                        'gestacional' => '5. Gestação/Parto',
                        'desenvolvimento' => '6. Desenvolvimento',
                        'saude' => '7. Saúde Clínica'
                    ];
                @endphp
                @foreach($abas as $id => $titulo)
                    <button onclick="switchTab('tab-{{$id}}')" id="btn-tab-{{$id}}" class="tab-btn px-4 py-2 rounded-full text-[11px] font-bold uppercase tracking-tighter transition-all border border-[#E1D3C1] text-[#8C846C] hover:bg-[#E1D3C1]/30">
                        {{ $titulo }}
                    </button>
                @endforeach
            </div>

            <form action="{{ route('anamnese.neuro.infantil.store', $patient->id) }}" method="POST">
                @csrf
                
                <div id="tab-identificacao" class="tab-content space-y-6">
                    
                    {{-- NOVO BLOCO: Metadados da Entrevista e Escolaridade Inicial --}}
                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Informações da Consulta</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Data da Entrevista:</label>
                            <input type="date" name="sections[identificacao][data_entrevista]" value="{{ $anamnese->sections['identificacao']['data_entrevista'] ?? date('Y-m-d') }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Data das Sessões (Cronograma):</label>
                            <input type="text" name="sections[identificacao][data_sessoes]" value="{{ $anamnese->sections['identificacao']['data_sessoes'] ?? '' }}" placeholder="Ex: Segundas às 14h, ou listar datas específicas" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Naturalidade (Cidade/Estado):</label>
                            <input type="text" name="sections[identificacao][naturalidade]" value="{{ $anamnese->sections['identificacao']['naturalidade'] ?? '' }}" placeholder="Onde a criança nasceu" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Procedência (Cidade/Estado atual):</label>
                            <input type="text" name="sections[identificacao][procedencia]" value="{{ $anamnese->sections['identificacao']['procedencia'] ?? '' }}" placeholder="De onde ela está vindo" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-[#F9F6F3]/30 p-5 rounded-2xl border border-[#E1D3C1]/40">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-[#8C846C]">Escolaridade / Ano Atual:</label>
                            <input type="text" name="sections[identificacao][escolaridade_topo]" value="{{ $anamnese->sections['identificacao']['escolaridade_topo'] ?? '' }}" placeholder="Ex: 2º Ano do Ensino Fundamental" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-[#8C846C]">Instituição de Ensino (Escola):</label>
                            <input type="text" name="sections[identificacao][escola_topo]" value="{{ $anamnese->sections['identificacao']['escola_topo'] ?? '' }}" placeholder="Nome do colégio" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                        </div>
                        <div class="md:col-span-1 flex flex-col justify-end">
                            <span class="block text-[10px] uppercase font-black text-[#8C846C]/60 mb-2 tracking-widest">Dependência Administrativa</span>
                            <div class="flex gap-6 h-10 items-center pl-1">
                                <label class="flex items-center text-xs text-[#8C846C] font-bold cursor-pointer select-none">
                                    <input type="radio" name="sections[identificacao][rede_topo]" value="publica" class="mr-2 border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['identificacao']['rede_topo'] ?? '') == 'publica' ? 'checked' : '' }}> Pública
                                </label>
                                <label class="flex items-center text-xs text-[#8C846C] font-bold cursor-pointer select-none">
                                    <input type="radio" name="sections[identificacao][rede_topo]" value="particular" class="mr-2 border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['identificacao']['rede_topo'] ?? '') == 'particular' ? 'checked' : '' }}> Particular
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Mantém o início estrutural do bloco que você já tinha abaixo --}}
                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2 pt-4">Contexto Familiar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <label class="block text-xs font-bold text-[#8C846C]">Situação dos Pais:</label>
                            <select name="sections[familiar][situacao_pais]" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                                <option value="">Selecione...</option>
                                @foreach(['casados' => 'Casados', 'divorciados' => 'Divorciados', 'guarda_compartilhada' => 'Guarda Compartilhada', 'guarda_exclusiva' => 'Guarda Exclusiva', 'solo' => 'Pai/Mãe Solo'] as $val => $label)
                                    <option value="{{ $val }}" {{ ($anamnese->sections['familiar']['situacao_pais'] ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Como é a Relação Parental?</label>
                            <textarea name="sections[familiar][relacao]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['familiar']['relacao'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Conta com ajuda de familiares? Quem?</label>
                            <textarea name="sections[familiar][ajuda_familiares]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['familiar']['ajuda_familiares'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Quem cuida da criança no contraturno escolar? (Parentesco/Escolaridade)</label>
                            <textarea name="sections[familiar][cuidado_contraturno]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['familiar']['cuidado_contraturno'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#8C846C]">Quem reside com a criança? (Nome, Idade, Parentesco, Instrução)</label>
                        <textarea name="sections[familiar][residentes]" rows="4" class="w-full rounded-xl border-[#E1D3C1] text-sm" placeholder="Liste aqui as pessoas que moram na casa...">{{ $anamnese->sections['familiar']['residentes'] ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#8C846C]">Antecedentes / Queixas que possam justificar herança genética ou influência ambiental:</label>
                        <textarea name="sections[familiar][antecedentes_geneticos]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['familiar']['antecedentes_geneticos'] ?? '' }}</textarea>
                    </div>
                </div>

                <div id="tab-queixa" class="tab-content hidden space-y-6">
                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Motivo do Encaminhamento</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Solicitante:</label>
                            <input type="text" name="sections[queixa][solicitante]" value="{{ $anamnese->sections['queixa']['solicitante'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Especialidade:</label>
                            <input type="text" name="sections[queixa][especialidade]" value="{{ $anamnese->sections['queixa']['especialidade'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-[#8C846C]">Qual o objetivo / finalidade da avaliação?</label>
                        <textarea name="sections[queixa][objetivo_avaliacao]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['objetivo_avaliacao'] ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Hipótese Diagnóstica:</label>
                            <input type="text" name="sections[queixa][hipotese]" value="{{ $anamnese->sections['queixa']['hipotese'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">CID:</label>
                            <input type="text" name="sections[queixa][cid]" value="{{ $anamnese->sections['queixa']['cid'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                    </div>

                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2 mt-8">Histórico da Queixa</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <label class="block text-xs font-bold text-[#8C846C]">Por que procurou ajuda?</label>
                            <textarea name="sections[queixa][motivo_ajuda]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['motivo_ajuda'] ?? '' }}</textarea>
                            
                            <label class="block text-xs font-bold text-[#8C846C]">Principais dificuldades (Sintomas/Comportamento):</label>
                            <textarea name="sections[queixa][dificuldades]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['dificuldades'] ?? '' }}</textarea>
                            
                            <label class="block text-xs font-bold text-[#8C846C]">Data de início da queixa:</label>
                            <input type="text" name="sections[queixa][data_inicio_queixa]" value="{{ $anamnese->sections['queixa']['data_inicio_queixa'] ?? '' }}" placeholder="DD/MM/AAAA" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>

                        <div class="space-y-4">
                            <label class="block text-xs font-bold text-[#8C846C]">Queixas secundárias?</label>
                            <textarea name="sections[queixa][queixas_secundarias]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['queixas_secundarias'] ?? '' }}</textarea>
                            
                            <label class="block text-xs font-bold text-[#8C846C]">Problemas de Aprendizagem / Dificuldade de Atenção:</label>
                            <textarea name="sections[queixa][problemas_aprendizagem]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['problemas_aprendizagem'] ?? '' }}</textarea>
                            
                            <div class="flex items-center gap-4 bg-[#F9F6F3] p-3 rounded-xl border border-[#E1D3C1]">
                                <span class="text-xs font-bold text-[#8C846C]">Termina suas atividades?</span>
                                <label class="flex items-center text-sm text-[#8C846C]"><input type="radio" name="sections[queixa][termina_atividades]" value="sim" class="mr-1" {{ ($anamnese->sections['queixa']['termina_atividades'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                <label class="flex items-center text-sm text-[#8C846C]"><input type="radio" name="sections[queixa][termina_atividades]" value="nao" class="mr-1" {{ ($anamnese->sections['queixa']['termina_atividades'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Como a criança lida com as dificuldades?</label>
                            <textarea name="sections[queixa][reacao_crianca]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['reacao_crianca'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Como o PAI lida?</label>
                            <textarea name="sections[queixa][reacao_pai]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['reacao_pai'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Como a MÃE lida?</label>
                            <textarea name="sections[queixa][reacao_mae]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['queixa']['reacao_mae'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2 mt-8">Nível de Independência</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @php
                            $itens_independencia = [
                                'evita_perigos' => 'Evita perigos?',
                                'pequenas_compras' => 'Faz pequenas compras?',
                                'transporte' => 'Usa transporte independente?',
                                'autonomia_higiene' => 'Tem autonomia (banho/veste)?',
                                'anota_recados' => 'Dá ou anota recados?',
                                'conhece_rotina' => 'Conhece a própria rotina?'
                            ];
                        @endphp

                        @foreach($itens_independencia as $key => $label)
                            <div class="flex flex-col p-3 bg-white border border-[#E1D3C1] rounded-xl">
                                <span class="text-[10px] font-bold text-[#8C846C] uppercase mb-2">{{ $label }}</span>
                                <div class="flex gap-4">
                                    <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[queixa][independencia][{{$key}}]" value="sim" class="mr-1" {{ ($anamnese->sections['queixa']['independencia'][$key] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                    <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[queixa][independencia][{{$key}}]" value="nao" class="mr-1" {{ ($anamnese->sections['queixa']['independencia'][$key] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div id="tab-escolar" class="tab-content hidden space-y-6">
                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Antecedentes Escolares</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Com que idade começou a frequentar a escola?</label>
                            <input type="text" name="sections[escolar][idade_inicio]" value="{{ $anamnese->sections['escolar']['idade_inicio'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Quando foi alfabetizado?</label>
                            <input type="text" name="sections[escolar][alfabetizacao]" value="{{ $anamnese->sections['escolar']['alfabetizacao'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                    </div>

                    <div class="p-4 bg-[#F9F6F3] rounded-2xl border border-[#E1D3C1]/50 space-y-3">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-bold text-[#8C846C]">Reprovações?</span>
                            <label class="flex items-center text-sm text-[#8C846C]"><input type="radio" name="sections[escolar][reprovacao]" value="nao" class="mr-1" {{ ($anamnese->sections['escolar']['reprovacao'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                            <label class="flex items-center text-sm text-[#8C846C]"><input type="radio" name="sections[escolar][reprovacao]" value="sim" class="mr-1" {{ ($anamnese->sections['escolar']['reprovacao'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                        </div>
                        <textarea name="sections[escolar][reprovacao_detalhes]" placeholder="Quantas vezes? Em que ano? Por qual motivo?" class="w-full rounded-xl border-[#E1D3C1] text-sm" rows="2">{{ $anamnese->sections['escolar']['reprovacao_detalhes'] ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-[#8C846C]">Matriculado em que ano?</label>
                            <input type="text" name="sections[escolar][ano_atual]" value="{{ $anamnese->sections['escolar']['ano_atual'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div class="flex items-center gap-4 bg-white p-2 rounded-xl border border-[#E1D3C1]">
                            <label class="flex items-center text-[10px] font-bold text-[#8C846C]"><input type="radio" name="sections[escolar][rede]" value="particular" class="mr-1" {{ ($anamnese->sections['escolar']['rede'] ?? '') == 'particular' ? 'checked' : '' }}> PARTICULAR</label>
                            <label class="flex items-center text-[10px] font-bold text-[#8C846C]"><input type="radio" name="sections[escolar][rede]" value="publica" class="mr-1" {{ ($anamnese->sections['escolar']['rede'] ?? '') == 'publica' ? 'checked' : '' }}> PÚBLICA</label>
                            <label class="flex items-center text-[10px] font-bold text-[#8C846C]"><input type="radio" name="sections[escolar][rede]" value="nao_estuda" class="mr-1" {{ ($anamnese->sections['escolar']['rede'] ?? '') == 'nao_estuda' ? 'checked' : '' }}> NÃO ESTUDA</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach(['inclusao' => 'INCLUSÃO', 'adaptacao' => 'ADAPTAÇÃO CURRICULAR', 'auxiliar' => 'PROFESSOR AUXILIAR'] as $key => $label)
                            <label class="flex items-center text-[10px] font-bold text-[#8C846C] bg-white p-3 rounded-xl border border-[#E1D3C1]">
                                <input type="checkbox" name="sections[escolar][suporte][{{$key}}]" value="1" class="mr-2 rounded" {{ isset($anamnese->sections['escolar']['suporte'][$key]) ? 'checked' : '' }}> {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" name="sections[escolar][nome_escola]" value="{{ $anamnese->sections['escolar']['nome_escola'] ?? '' }}" placeholder="Nome da Escola" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        <input type="text" name="sections[escolar][tel_escola]" value="{{ $anamnese->sections['escolar']['tel_escola'] ?? '' }}" placeholder="Telefone da Escola" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        <input type="text" name="sections[escolar][responsavel_pedagogico]" value="{{ $anamnese->sections['escolar']['responsavel_pedagogico'] ?? '' }}" placeholder="Professora / Resp. Pedagógico" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                    </div>

                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-[#8C846C]">Quais as dificuldades na escola e desde quando? (Consegue relacionar algum fato?)</label>
                        <textarea name="sections[escolar][dificuldades_detalhes]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['escolar']['dificuldades_detalhes'] ?? '' }}</textarea>
                        
                        <label class="block text-xs font-bold text-[#8C846C]">Mudanças de escola? (Quantas vezes, motivos e adaptação):</label>
                        <textarea name="sections[escolar][mudancas_escola]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['escolar']['mudancas_escola'] ?? '' }}</textarea>

                        <label class="block text-xs font-bold text-[#8C846C]">Preferência ou dificuldade em alguma disciplina/atividade?</label>
                        <textarea name="sections[escolar][preferencia_disciplina]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['escolar']['preferencia_disciplina'] ?? '' }}</textarea>

                        <label class="block text-xs font-bold text-[#8C846C]">Rotina de Estudo (Horários de escola e como estuda em casa):</label>
                        <textarea name="sections[escolar][rotina_estudo]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['escolar']['rotina_estudo'] ?? '' }}</textarea>

                        <label class="block text-xs font-bold text-[#8C846C]">Notas obtidas atualmente (Resumo):</label>
                        <textarea name="sections[escolar][notas_atuais]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['escolar']['notas_atuais'] ?? '' }}</textarea>
                    </div>
                </div>

                <div id="tab-habitos" class="tab-content hidden space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Sono</h3>
                            <div class="grid grid-cols-1 gap-2">
                                @php
                                    $itens_sono = [
                                        'satisfatorio' => 'Satisfatório', 'tranquilo' => 'Tranquilo', 
                                        'agitado' => 'Agitado', 'sonambulo' => 'Sonâmbulo', 
                                        'acorda_cansado' => 'Acorda Cansado', 'acorda_noite' => 'Acorda várias vezes',
                                        'volta_dormir' => 'Volta a dormir facilmente'
                                    ];
                                @endphp
                                @foreach($itens_sono as $key => $label)
                                <div class="flex justify-between items-center bg-white p-2 rounded-lg border border-[#E1D3C1]">
                                    <span class="text-[11px] font-bold text-[#8C846C] uppercase">{{ $label }}</span>
                                    <div class="flex gap-3">
                                        <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[habitos][sono][{{$key}}]" value="sim" class="mr-1" {{ ($anamnese->sections['habitos']['sono'][$key] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                        <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[habitos][sono][{{$key}}]" value="nao" class="mr-1" {{ ($anamnese->sections['habitos']['sono'][$key] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="sections[habitos][onde_dorme]" value="{{ $anamnese->sections['habitos']['onde_dorme'] ?? '' }}" placeholder="Onde dorme?" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                                <input type="text" name="sections[habitos][com_quem_dorme]" value="{{ $anamnese->sections['habitos']['com_quem_dorme'] ?? '' }}" placeholder="Com quem dorme?" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                            </div>
                            <input type="text" name="sections[habitos][sono_inicio_problema]" value="{{ $anamnese->sections['habitos']['sono_inicio_problema'] ?? '' }}" placeholder="Se houver problemas, quando iniciaram?" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Alimentação</h3>
                            <div class="grid grid-cols-1 gap-2">
                                @php
                                    $itens_ali = [
                                        'restricao' => 'Restrição Alimentar?', 'seletividade' => 'Seletividade Alimentar?',
                                        'balanceada' => 'Alimentação Balanceada?', 'deficit' => 'Baixa/Excesso Nutricional?',
                                        'horario' => 'Há horário para refeições?', 'respeita_horario' => 'Respeita os horários?',
                                        'impulsividade' => 'Impulsividade para comer?', 'peso_alterado' => 'Acima/Abaixo do peso?'
                                    ];
                                @endphp
                                @foreach($itens_ali as $key => $label)
                                <div class="flex flex-col bg-white p-2 rounded-lg border border-[#E1D3C1]">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[11px] font-bold text-[#8C846C] uppercase">{{ $label }}</span>
                                        <div class="flex gap-3">
                                            <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[habitos][alimentacao][{{$key}}]" value="sim" class="mr-1" {{ ($anamnese->sections['habitos']['alimentacao'][$key] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                            <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[habitos][alimentacao][{{$key}}]" value="nao" class="mr-1" {{ ($anamnese->sections['habitos']['alimentacao'][$key] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                                        </div>
                                    </div>
                                    @if(in_array($key, ['restricao', 'seletividade']))
                                    <input type="text" name="sections[habitos][alimentacao][{{$key}}_obs]" value="{{ $anamnese->sections['habitos']['alimentacao'][$key.'_obs'] ?? '' }}" placeholder="Se sim, a quê?" class="w-full rounded-lg border-[#E1D3C1] text-[10px] h-7">
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 pt-4">
                        <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Lazer e Rotina Extra</h3>
                        <div class="space-y-4">
                            <label class="block text-xs font-bold text-[#8C846C]">O que faz no contraturno escolar quando está em casa?</label>
                            <textarea name="sections[habitos][lazer_casa]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['habitos']['lazer_casa'] ?? '' }}</textarea>

                            <label class="block text-xs font-bold text-[#8C846C]">Faz atividade esportiva/artística? Qual carga horária?</label>
                            <textarea name="sections[habitos][atividade_fisica]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['habitos']['atividade_fisica'] ?? '' }}</textarea>

                            <label class="block text-xs font-bold text-[#8C846C]">Faz atividade extracurricular (línguas/reforço)? Qual carga horária?</label>
                            <textarea name="sections[habitos][atividade_extra]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['habitos']['atividade_extra'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div id="tab-gestacional" class="tab-content hidden space-y-6">
                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Histórico Gestacional</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach(['desejada' => 'Criança desejada?', 'planejada' => 'Foi planejada?', 'pre_natal' => 'Pré-natal a termo?'] as $key => $label)
                        <div class="flex flex-col p-3 bg-white border border-[#E1D3C1] rounded-xl">
                            <span class="text-[10px] font-bold text-[#8C846C] uppercase mb-2">{{ $label }}</span>
                            <div class="flex gap-4">
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[gestacao][{{$key}}]" value="sim" class="mr-1" {{ ($anamnese->sections['gestacao'][$key] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[gestacao][{{$key}}]" value="nao" class="mr-1" {{ ($anamnese->sections['gestacao'][$key] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                            </div>
                            @if($key == 'pre_natal')
                            <input type="text" name="sections[gestacao][pre_natal_obs]" value="{{ $anamnese->sections['gestacao']['pre_natal_obs'] ?? '' }}" placeholder="Se não, por quê?" class="mt-2 w-full rounded-lg border-[#E1D3C1] text-[10px] h-7">
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-4">
                        @foreach(['intercorrencias' => 'Intercorrências na gestação (clínica/emocional)?', 'medicacoes' => 'Fez uso de medicações?', 'substancias' => 'Fez uso de álcool/drogas ou fumou?'] as $key => $label)
                        <div class="p-4 bg-[#F9F6F3] rounded-2xl border border-[#E1D3C1]/50">
                            <div class="flex items-center gap-4 mb-2">
                                <span class="text-xs font-bold text-[#8C846C]">{{ $label }}</span>
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[gestacao][{{$key}}_flag]" value="sim" class="mr-1" {{ ($anamnese->sections['gestacao'][$key.'_flag'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[gestacao][{{$key}}_flag]" value="nao" class="mr-1" {{ ($anamnese->sections['gestacao'][$key.'_flag'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                            </div>
                            <textarea name="sections[gestacao][{{$key}}_detalhes]" rows="2" placeholder="Quais?" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['gestacao'][$key.'_detalhes'] ?? '' }}</textarea>
                        </div>
                        @endforeach
                    </div>

                    <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2 mt-8">O Parto</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2 p-3 bg-white border border-[#E1D3C1] rounded-xl">
                            <label class="flex items-center text-xs font-bold text-[#8C846C]"><input type="radio" name="sections[gestacao][tipo_parto]" value="cesareo" class="mr-2" {{ ($anamnese->sections['gestacao']['tipo_parto'] ?? '') == 'cesareo' ? 'checked' : '' }}> Cesáreo</label>
                            <div class="pl-6 space-y-1">
                                <label class="flex items-center text-[10px] text-[#8C846C]"><input type="radio" name="sections[gestacao][cesareo_tipo]" value="agendado" class="mr-1" {{ ($anamnese->sections['gestacao']['cesareo_tipo'] ?? '') == 'agendado' ? 'checked' : '' }}> Agendado</label>
                                <label class="flex items-center text-[10px] text-[#8C846C]"><input type="radio" name="sections[gestacao][cesareo_tipo]" value="indicacao" class="mr-1" {{ ($anamnese->sections['gestacao']['cesareo_tipo'] ?? '') == 'indicacao' ? 'checked' : '' }}> Indicação médica</label>
                                <input type="text" name="sections[gestacao][cesareo_obs]" value="{{ $anamnese->sections['gestacao']['cesareo_obs'] ?? '' }}" placeholder="Qual indicação?" class="w-full rounded-lg border-[#E1D3C1] text-[10px] h-7">
                            </div>
                        </div>
                        <div class="space-y-2 p-3 bg-white border border-[#E1D3C1] rounded-xl">
                            <label class="flex items-center text-xs font-bold text-[#8C846C]"><input type="radio" name="sections[gestacao][tipo_parto]" value="normal" class="mr-2" {{ ($anamnese->sections['gestacao']['tipo_parto'] ?? '') == 'normal' ? 'checked' : '' }}> Normal / Natural</label>
                            <div class="pl-6">
                                <span class="text-[10px] text-[#8C846C] block">Usou fórceps?</span>
                                <label class="inline-flex items-center text-[10px] text-[#8C846C] mr-2"><input type="radio" name="sections[gestacao][forceps]" value="sim" {{ ($anamnese->sections['gestacao']['forceps'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                <label class="inline-flex items-center text-[10px] text-[#8C846C]"><input type="radio" name="sections[gestacao][forceps]" value="nao" {{ ($anamnese->sections['gestacao']['forceps'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                            </div>
                        </div>
                        <div class="p-3 bg-white border border-[#E1D3C1] rounded-xl">
                            <label class="flex items-center text-xs font-bold text-[#8C846C]"><input type="radio" name="sections[gestacao][tipo_parto]" value="domiciliar" class="mr-2" {{ ($anamnese->sections['gestacao']['tipo_parto'] ?? '') == 'domiciliar' ? 'checked' : '' }}> Domiciliar</label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                        <input type="text" name="sections[gestacao][semanas]" value="{{ $anamnese->sections['gestacao']['semanas'] ?? '' }}" placeholder="Nº semanas" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        <input type="text" name="sections[gestacao][peso]" value="{{ $anamnese->sections['gestacao']['peso'] ?? '' }}" placeholder="Peso (g)" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        <input type="text" name="sections[gestacao][comprimento]" value="{{ $anamnese->sections['gestacao']['comprimento'] ?? '' }}" placeholder="Comprimento (cm)" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        <input type="text" name="sections[gestacao][apgar]" value="{{ $anamnese->sections['gestacao']['apgar'] ?? '' }}" placeholder="Apgar" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center justify-between p-3 bg-white border border-[#E1D3C1] rounded-xl">
                            <span class="text-xs font-bold text-[#8C846C]">Icterícia?</span>
                            <div class="flex gap-4">
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[gestacao][ictericia]" value="sim" {{ ($anamnese->sections['gestacao']['ictericia'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[gestacao][ictericia]" value="nao" {{ ($anamnese->sections['gestacao']['ictericia'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-white border border-[#E1D3C1] rounded-xl">
                            <span class="text-xs font-bold text-[#8C846C]">Alta junto com a mãe?</span>
                            <div class="flex gap-4">
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[gestacao][alta_mae]" value="sim" {{ ($anamnese->sections['gestacao']['alta_mae'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[gestacao][alta_mae]" value="nao" {{ ($anamnese->sections['gestacao']['alta_mae'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <textarea name="sections[gestacao][amamentacao]" placeholder="Como foi a amamentação?" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['gestacao']['amamentacao'] ?? '' }}</textarea>
                        <textarea name="sections[gestacao][desmame]" placeholder="Quando foi o desmame e como foi?" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['gestacao']['desmame'] ?? '' }}</textarea>
                    </div>
                </div>

                <div id="tab-desenvolvimento" class="tab-content hidden space-y-8">
                    <div class="space-y-4">
                        <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Fala e Linguagem</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Com quantos meses balbuciou?</label>
                                <input type="text" name="sections[desenvolvimento][balbucio]" value="{{ $anamnese->sections['desenvolvimento']['balbucio'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Primeiras palavras?</label>
                                <input type="text" name="sections[desenvolvimento][palavras]" value="{{ $anamnese->sections['desenvolvimento']['palavras'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Frases completas?</label>
                                <input type="text" name="sections[desenvolvimento][frases]" value="{{ $anamnese->sections['desenvolvimento']['frases'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">
                            </div>
                        </div>
                        <div class="p-4 bg-[#F9F6F3] rounded-2xl border border-[#E1D3C1]/50">
                            <div class="flex items-center gap-4 mb-2">
                                <span class="text-xs font-bold text-[#8C846C]">Houve persistência nas trocas de fala?</span>
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[desenvolvimento][troca_fala]" value="sim" {{ ($anamnese->sections['desenvolvimento']['troca_fala'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[desenvolvimento][troca_fala]" value="nao" {{ ($anamnese->sections['desenvolvimento']['troca_fala'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                            </div>
                            <textarea name="sections[desenvolvimento][troca_fala_obs]" placeholder="Se sim, quais?" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['desenvolvimento']['troca_fala_obs'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Mobilidade e Controle Esfincteriano</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-left">
                            <div><label class="text-[10px] font-bold text-[#8C846C]/60 uppercase">Com quantos meses sentou sozinho?</label><input type="text" name="sections[desenvolvimento][sentou]" value="{{ $anamnese->sections['desenvolvimento']['sentou'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm"></div>
                            <div><label class="text-[10px] font-bold text-[#8C846C]/60 uppercase">Com quantos meses engatinhou?</label><input type="text" name="sections[desenvolvimento][engatinhou]" value="{{ $anamnese->sections['desenvolvimento']['engatinhou'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm"></div>
                            <div><label class="text-[10px] font-bold text-[#8C846C]/60 uppercase">Com quantos meses levantou-se?</label><input type="text" name="sections[desenvolvimento][levantou]" value="{{ $anamnese->sections['desenvolvimento']['levantou'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm"></div>
                            <div><label class="text-[10px] font-bold text-[#8C846C]/60 uppercase">Com quantos meses andou?</label><input type="text" name="sections[desenvolvimento][andou]" value="{{ $anamnese->sections['desenvolvimento']['andou'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-sm"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <input type="text" name="sections[desenvolvimento][desfralde_diurno]" value="{{ $anamnese->sections['desenvolvimento']['desfralde_diurno'] ?? '' }}" placeholder="Desfralde Diurno (idade)" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                            <input type="text" name="sections[desenvolvimento][desfralde_noturno]" value="{{ $anamnese->sections['desenvolvimento']['desfralde_noturno'] ?? '' }}" placeholder="Desfralde Noturno (idade)" class="w-full rounded-xl border-[#E1D3C1] text-sm">
                        </div>
                        <div class="p-4 bg-[#F9F6F3] rounded-2xl border border-[#E1D3C1]/50 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <span class="text-xs font-bold text-[#8C846C] block mb-2">Enurese (xixi involuntário)?</span>
                                <div class="flex gap-4 mb-2">
                                    <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[desenvolvimento][enurese]" value="sim" {{ ($anamnese->sections['desenvolvimento']['enurese'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                    <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[desenvolvimento][enurese]" value="nao" {{ ($anamnese->sections['desenvolvimento']['enurese'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                                </div>
                                <input type="text" name="sections[desenvolvimento][enurese_obs]" value="{{ $anamnese->sections['desenvolvimento']['enurese_obs'] ?? '' }}" placeholder="Frequência e desde quando?" class="w-full rounded-lg border-[#E1D3C1] text-xs h-8">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-[#8C846C] block mb-2">Encoprese (segura fezes)?</label>
                                <textarea name="sections[desenvolvimento][encoprese]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['desenvolvimento']['encoprese'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">Comportamento e Humor</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Como se relaciona com adultos?</label>
                                <textarea name="sections[comportamento][relacionamento_adultos]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['comportamento']['relacionamento_adultos'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Como se relaciona com outras crianças?</label>
                                <textarea name="sections[comportamento][relacionamento_criancas]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['comportamento']['relacionamento_criancas'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Brinca em grupo ou prefere isolar-se?</label>
                                <textarea name="sections[comportamento][brincadeiras]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['comportamento']['brincadeiras'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Respeita regras dos jogos coletivos?</label>
                                <textarea name="sections[comportamento][regras]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['comportamento']['regras'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Respeita a vontade do grupo (passivo/impositivo)?</label>
                                <textarea name="sections[comportamento][vontade_grupo]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['comportamento']['vontade_grupo'] ?? '' }}</textarea>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                            @foreach(['Agressivo', 'Muito Quieto', 'Tímido', 'Impulsivo', 'Agitado'] as $humor)
                            <label class="flex items-center text-[10px] font-bold text-[#8C846C] bg-white p-2 rounded-lg border border-[#E1D3C1] cursor-pointer hover:bg-[#F9F6F3] transition-colors">
                                <input type="checkbox" name="sections[comportamento][perfil][]" value="{{ $humor }}" class="mr-2 rounded border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ in_array($humor, $anamnese->sections['comportamento']['perfil'] ?? []) ? 'checked' : '' }}> {{ $humor }}
                            </label>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center justify-between p-3 bg-white border border-[#E1D3C1] rounded-xl">
                                <span class="text-xs font-bold text-[#8C846C]">Segue ordens?</span>
                                <div class="flex gap-4">
                                    <label class="flex items-center text-xs text-[#8C846C] cursor-pointer"><input type="radio" name="sections[comportamento][segue_ordens]" value="sim" class="mr-1 border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['comportamento']['segue_ordens'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                    <label class="flex items-center text-xs text-[#8C846C] cursor-pointer"><input type="radio" name="sections[comportamento][segue_ordens]" value="nao" class="mr-1 border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['comportamento']['segue_ordens'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Como reage à frustração?</label>
                                <textarea name="sections[comportamento][reacao_frustracao]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['comportamento']['reacao_frustracao'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Quais características positivas chamam atenção?</label>
                                <textarea name="sections[comportamento][caracteristicas_positivas]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['comportamento']['caracteristicas_positivas'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Do que mais gosta de brincar?</label>
                                <textarea name="sections[comportamento][gosta_brincar]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['comportamento']['gosta_brincar'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Tem algum interesse restrito?</label>
                                <textarea name="sections[comportamento][interesse_restrito]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['comportamento']['interesse_restrito'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center justify-between p-3 bg-white border border-[#E1D3C1] rounded-xl h-fit">
                                <span class="text-xs font-bold text-[#8C846C]">Olhar no olho?</span>
                                <div class="flex gap-4">
                                    <label class="flex items-center text-xs text-[#8C846C] cursor-pointer"><input type="radio" name="sections[comportamento][olhar_no_olho]" value="sim" class="mr-1 border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['comportamento']['olhar_no_olho'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                    <label class="flex items-center text-xs text-[#8C846C] cursor-pointer"><input type="radio" name="sections[comportamento][olhar_no_olho]" value="nao" class="mr-1 border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['comportamento']['olhar_no_olho'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white border border-[#E1D3C1] rounded-xl h-fit">
                                <span class="text-xs font-bold text-[#8C846C]">Ponta dos pés?</span>
                                <div class="flex gap-4">
                                    <label class="flex items-center text-xs text-[#8C846C] cursor-pointer"><input type="radio" name="sections[comportamento][ponta_dos_pes]" value="sim" class="mr-1 border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['comportamento']['ponta_dos_pes'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                    <label class="flex items-center text-xs text-[#8C846C] cursor-pointer"><input type="radio" name="sections[comportamento][ponta_dos_pes]" value="nao" class="mr-1 border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['comportamento']['ponta_dos_pes'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                                </div>
                            </div>
                            <div class="p-3 bg-white border border-[#E1D3C1] rounded-xl">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-bold text-[#8C846C]">Mov. repetitivo?</span>
                                    <div class="flex gap-4">
                                        <label class="flex items-center text-xs text-[#8C846C] cursor-pointer"><input type="radio" name="sections[comportamento][mov_repetitivo]" value="sim" class="mr-1 border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['comportamento']['mov_repetitivo'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                        <label class="flex items-center text-xs text-[#8C846C] cursor-pointer"><input type="radio" name="sections[comportamento][mov_repetitivo]" value="nao" class="mr-1 border-[#E1D3C1] text-[#8C846C] focus:ring-[#8C846C]" {{ ($anamnese->sections['comportamento']['mov_repetitivo'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                                    </div>
                                </div>
                                <input type="text" name="sections[comportamento][mov_repetitivo_obs]" value="{{ $anamnese->sections['comportamento']['mov_repetitivo_obs'] ?? '' }}" placeholder="Quais?" class="w-full rounded-lg border-[#E1D3C1] text-[10px] h-7 focus:ring-[#8C846C] focus:border-[#8C846C]">
                            </div>
                        </div>
                    </div>
                </div>

            <div id="tab-saude" class="tab-content hidden space-y-8">
                <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2">História Clínica</h3>
                
                <div class="space-y-4">
                    <label class="block text-xs font-bold text-[#8C846C]">Desde que momento apresenta esses sintomas/comportamentos?</label>
                    <textarea name="sections[saude][inicio_sintomas]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['saude']['inicio_sintomas'] ?? '' }}</textarea>

                    <label class="block text-xs font-bold text-[#8C846C]">Eles vêm piorando ou houve momentos melhores? Explique:</label>
                    <textarea name="sections[saude][evolucao_sintomas]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['saude']['evolucao_sintomas'] ?? '' }}</textarea>

                    <div class="p-4 bg-[#F9F6F3] rounded-2xl border border-[#E1D3C1]/50 space-y-3">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-bold text-[#8C846C]">Consegue relacionar algum fato pessoal a esses sintomas?</span>
                            <label class="flex items-center text-sm text-[#8C846C]"><input type="radio" name="sections[saude][fato_relacionado_flag]" value="sim" {{ ($anamnese->sections['saude']['fato_relacionado_flag'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                            <label class="flex items-center text-sm text-[#8C846C]"><input type="radio" name="sections[saude][fato_relacionado_flag]" value="nao" {{ ($anamnese->sections['saude']['fato_relacionado_flag'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                        </div>
                        <textarea name="sections[saude][fato_relacionado_obs]" placeholder="Se sim, quais?" class="w-full rounded-xl border-[#E1D3C1] text-sm" rows="2">{{ $anamnese->sections['saude']['fato_relacionado_obs'] ?? '' }}</textarea>
                    </div>

                    <div class="p-4 bg-[#F9F6F3] rounded-2xl border border-[#E1D3C1]/50 space-y-3">
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-bold text-[#8C846C]">Já procurou auxílio para investigar esse quadro?</span>
                            <label class="flex items-center text-sm text-[#8C846C]"><input type="radio" name="sections[saude][auxilio_investigacao_flag]" value="sim" {{ ($anamnese->sections['saude']['auxilio_investigacao_flag'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                            <label class="flex items-center text-sm text-[#8C846C]"><input type="radio" name="sections[saude][auxilio_investigacao_flag]" value="nao" {{ ($anamnese->sections['saude']['auxilio_investigacao_flag'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                        </div>
                        <textarea name="sections[saude][auxilio_investigacao_obs]" placeholder="Se sim, quais profissionais ou exames?" class="w-full rounded-xl border-[#E1D3C1] text-sm" rows="2">{{ $anamnese->sections['saude']['auxilio_investigacao_obs'] ?? '' }}</textarea>
                    </div>

                    <label class="block text-xs font-bold text-[#8C846C]">Já houve outros diagnósticos/avaliações anteriores? (Quem fez e quando?)</label>
                    <textarea name="sections[saude][diagnosticos_anteriores]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm">{{ $anamnese->sections['saude']['diagnosticos_anteriores'] ?? '' }}</textarea>
                </div>

                <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2 mt-8">Antecedentes de Saúde</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach(['vacinas' => 'Toma vacinas regularmente?', 'internacao' => 'Já houve alguma internação?', 'acidente_cirurgia' => 'Acidente ou necessidade cirúrgica?'] as $key => $label)
                    <div class="p-4 bg-white border border-[#E1D3C1] rounded-2xl space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-[#8C846C]">{{ $label }}</span>
                            <div class="flex gap-3">
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[saude][{{$key}}_flag]" value="sim" {{ ($anamnese->sections['saude'][$key.'_flag'] ?? '') == 'sim' ? 'checked' : '' }}> Sim</label>
                                <label class="flex items-center text-xs text-[#8C846C]"><input type="radio" name="sections[saude][{{$key}}_flag]" value="nao" {{ ($anamnese->sections['saude'][$key.'_flag'] ?? '') == 'nao' ? 'checked' : '' }}> Não</label>
                            </div>
                        </div>
                        <textarea name="sections[saude][{{$key}}_obs]" placeholder="Detalhes (quando, por que, quanto tempo)..." class="w-full rounded-lg border-[#E1D3C1] text-xs" rows="2">{{ $anamnese->sections['saude'][$key.'_obs'] ?? '' }}</textarea>
                    </div>
                    @endforeach

                    <div class="p-4 bg-white border border-[#E1D3C1] rounded-2xl">
                        <label class="block text-xs font-bold text-[#8C846C] mb-2">Doenças e Intercorrências:</label>
                        <div class="grid grid-cols-2 gap-2 mb-2">
                            @foreach(['Sarampo', 'Rubéola', 'Caxumba', 'Pneumonia'] as $d)
                            <label class="flex items-center text-[10px] text-[#8C846C] uppercase font-bold">
                                <input type="checkbox" name="sections[saude][doencas][]" value="{{$d}}" class="mr-1 rounded" {{ in_array($d, $anamnese->sections['saude']['doencas'] ?? []) ? 'checked' : '' }}> {{$d}}
                            </label>
                            @endforeach
                        </div>
                        <input type="text" name="sections[saude][infeccoes_obs]" value="{{ $anamnese->sections['saude']['infeccoes_obs'] ?? '' }}" placeholder="Infecções otorrinolaringológicas? Idade?" class="w-full rounded-lg border-[#E1D3C1] text-[10px] mb-2">
                        <input type="text" name="sections[saude][convulsao_obs]" value="{{ $anamnese->sections['saude']['convulsao_obs'] ?? '' }}" placeholder="Crises convulsivas (única)? Quando?" class="w-full rounded-lg border-[#E1D3C1] text-[10px]">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-[#F9F6F3] border border-[#E1D3C1] rounded-2xl space-y-3">
                        <h4 class="text-[10px] font-black text-[#8C846C] uppercase">Epilepsia / Crises Atuais</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="sections[saude][epilepsia_inicio]" value="{{ $anamnese->sections['saude']['epilepsia_inicio'] ?? '' }}" placeholder="Idade de início" class="w-full rounded-lg border-[#E1D3C1] text-xs">
                            <input type="text" name="sections[saude][epilepsia_freq]" value="{{ $anamnese->sections['saude']['epilepsia_freq'] ?? '' }}" placeholder="Frequência atual" class="w-full rounded-lg border-[#E1D3C1] text-xs">
                        </div>
                        <textarea name="sections[saude][epilepsia_desc]" placeholder="Como são as crises e medicações?" rows="2" class="w-full rounded-lg border-[#E1D3C1] text-xs">{{ $anamnese->sections['saude']['epilepsia_desc'] ?? '' }}</textarea>
                    </div>
                    <div class="p-4 bg-[#F9F6F3] border border-[#E1D3C1] rounded-2xl space-y-3">
                        <h4 class="text-[10px] font-black text-[#8C846C] uppercase">Traumatismo / Meningite</h4>
                        <input type="text" name="sections[saude][neuro_intercorrencia]" value="{{ $anamnese->sections['saude']['neuro_intercorrencia'] ?? '' }}" placeholder="TCE ou Meningite? Quando?" class="w-full rounded-lg border-[#E1D3C1] text-xs">
                        <input type="text" name="sections[saude][neuro_lesao]" value="{{ $anamnese->sections['saude']['neuro_lesao'] ?? '' }}" placeholder="Localização da lesão / Área afetada" class="w-full rounded-lg border-[#E1D3C1] text-xs">
                        <input type="text" name="sections[saude][neuro_coma]" value="{{ $anamnese->sections['saude']['neuro_coma'] ?? '' }}" placeholder="Houve coma? Quanto tempo?" class="w-full rounded-lg border-[#E1D3C1] text-xs">
                    </div>
                </div>

                <h3 class="text-[#8C846C] font-bold italic border-b border-[#F9F6F3] pb-2 mt-8">Tratamentos e Rotina</h3>

                    <div class="space-y-4">
                        @php
                            $tratamentos = [
                                'psiquiatrico' => 'Psiquiátrico', 
                                'psicologico' => 'Psicológico', 
                                'fono' => 'Fonoaudiológico', 
                                'psicopedagogo' => 'Psicopedagógico', 
                                'to' => 'Terapia Ocupacional'
                            ];
                        @endphp

                        @foreach($tratamentos as $key => $label)
                        <div class="p-4 bg-white border border-[#E1D3C1] rounded-2xl grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div class="md:col-span-1">
                                <span class="text-xs font-bold text-[#8C846C] uppercase block mb-2">{{ $label }}</span>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Idade e Tempo</label>
                                <input type="text" name="sections[saude][tratamento][{{$key}}][idade]" value="{{ $anamnese->sections['saude']['tratamento'][$key]['idade'] ?? '' }}" class="w-full rounded-xl border-[#E1D3C1] text-xs focus:ring-[#8C846C] focus:border-[#8C846C]">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Houve melhora dos sintomas/comportamento?</label>
                                <textarea name="sections[saude][tratamento][{{$key}}][melhora]" rows="1" class="w-full rounded-xl border-[#E1D3C1] text-xs focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['saude']['tratamento'][$key]['melhora'] ?? '' }}</textarea>
                            </div>
                        </div>
                        @endforeach

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Outros tratamentos / acompanhamentos:</label>
                                <textarea name="sections[saude][outros_tratamentos]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['saude']['outros_tratamentos'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Medicações de uso crônico (Dose/Hora):</label>
                                <textarea name="sections[saude][medicacoes_atuais]" rows="2" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['saude']['medicacoes_atuais'] ?? '' }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Exames feitos ou solicitados (Datas e Resumo):</label>
                            <textarea name="sections[saude][exames_resumo]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['saude']['exames_resumo'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Descreva um dia da rotina da criança:</label>
                            <textarea name="sections[saude][rotina_diaria]" rows="4" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['saude']['rotina_diaria'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-[#8C846C]/60 mb-1 ml-1">Observações Finais:</label>
                            <textarea name="sections[saude][observacoes_finais]" rows="3" class="w-full rounded-xl border-[#E1D3C1] text-sm focus:ring-[#8C846C] focus:border-[#8C846C]">{{ $anamnese->sections['saude']['observacoes_finais'] ?? '' }}</textarea>
                        </div>
                    </div>
            </div>

                <div class="mt-12 pt-6 border-t border-[#F9F6F3] flex justify-end no-print">
                    <button type="submit" class="bg-[#8C846C] text-white px-10 py-3 rounded-full font-bold shadow-lg hover:bg-[#766f5a] transition transform hover:scale-105">
                        Salvar Anamnese Completa
                    </button>
                </div>
            </form>

            <div class="mt-16 text-center text-[10px] text-[#8C846C]/60 font-black uppercase tracking-widest">
                <p>Rua: Sete de setembro, 923, Centro. Muzambinho – MG | (35) 3571-3142 / 98704-2011</p>
            </div>
        </div>
    </div>

    <a href="{{ route('anamnese.pdf', $patient->id) }}" 
    target="_blank" 
    class="fixed bottom-10 right-10 bg-[#8C846C] text-white p-4 rounded-full shadow-2xl no-print hover:scale-110 transition flex items-center justify-center"
    title="Gerar PDF Profissional">
        
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
    </a>

    <script>
        function switchTab(tabId) {
            // Esconde todos os conteúdos
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Remove destaque de todos os botões
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-[#8C846C]', 'text-white');
                el.classList.add('text-[#8C846C]');
            });
            
            // Mostra o atual
            document.getElementById(tabId).classList.remove('hidden');
            // Destaque no botão clicado
            document.getElementById('btn-' + tabId).classList.add('bg-[#8C846C]', 'text-white');
        }

        // Inicia na primeira aba
        document.addEventListener('DOMContentLoaded', () => switchTab('tab-identificacao'));

        function gerarPDF() {
            Swal.fire({
                title: 'Gerar PDF?',
                text: "Certifique-se de ter clicado em 'Salvar Anamnese' para que as alterações recentes apareçam no documento.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Sim, gerar PDF',
                cancelButtonText: 'Vou salvar primeiro',
                confirmButtonColor: '#8C846C',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open("{{ route('anamnese.pdf', $patient->id) }}", '_blank');
                }
            });
        }
    </script>

    <style>
        @media print {
            body * { visibility: hidden !important; }
            #conteudo-imprimivel, #conteudo-imprimivel * { visibility: visible !important; }
            #conteudo-imprimivel { position: absolute !important; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
            .tab-content { display: block !important; } /* Na impressão, mostra todas as seções uma abaixo da outra */
            .no-print { display: none !important; }
            input, textarea, select { border: none !important; background: transparent !important; padding: 0 !important; }
        }
    </style>
</x-app-layout>