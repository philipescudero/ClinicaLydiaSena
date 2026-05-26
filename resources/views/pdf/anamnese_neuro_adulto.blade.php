<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 1.2cm; }

        .header-logo {
            display: block;
            margin: 0 auto 8px auto;
            max-width: 140px;
            width: 100%;
            height: auto;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #8C846C;
            padding-bottom: 10px;
        }

        .signature-block {
            margin-top: 60px;
            text-align: center;
            font-size: 8px;
            color: #aaa;
        }

        .signature-line {
            width: 320px;
            margin: 0 auto 10px auto;
            border-top: 1px solid #bbb;
            height: 1px;
        }

        .logo {
            font-size: 24px;
            color: #8C846C;
            margin-bottom: 2px;
        }

        .clinica-nome {
            font-size: 16px;
            font-weight: bold;
            color: #8C846C;
            text-transform: uppercase;
        }

        .sub-header {
            font-size: 8px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #F9F6F3;
            color: #8C846C;
            padding: 4px 8px;
            font-weight: bold;
            text-transform: uppercase;
            border-left: 3px solid #8C846C;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .subsection-title {
            color: #8C846C;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            margin: 10px 0 6px;
            border-bottom: 1px solid #E8E2D8;
            padding-bottom: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            table-layout: fixed;
        }

        td, th {
            vertical-align: top;
            padding: 4px;
            border: 1px solid #eee;
            word-wrap: break-word;
        }

        th {
            background: #fcfcfc;
            color: #8C846C;
            font-size: 8px;
            text-transform: uppercase;
            text-align: left;
        }

        .label {
            font-weight: bold;
            color: #8C846C;
            font-size: 8px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 1px;
        }

        .value {
            color: #000;
            font-size: 10px;
            word-wrap: break-word;
            white-space: pre-line;
        }

        .checkbox-grid {
            margin-left: 2px;
        }

        .check-item {
            display: inline-block;
            width: 48%;
            margin-bottom: 4px;
            font-size: 9px;
            vertical-align: top;
        }

        .check-item-third {
            display: inline-block;
            width: 32%;
            margin-bottom: 4px;
            font-size: 9px;
            vertical-align: top;
        }

        .box {
            display: inline-block;
            width: 9px;
            height: 9px;
            border: 1px solid #8C846C;
            margin-right: 4px;
            vertical-align: middle;
            text-align: center;
            line-height: 9px;
            font-size: 7px;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }

        .line {
            border-top: 0.5px solid #8C846C;
            width: 220px;
            margin: 0 auto 5px auto;
        }

        .signature {
            margin-top: 28px;
            text-align: center;
        }

        .signature-line {
            width: 250px;
            border-top: 1px solid #8C846C;
            margin: 0 auto 6px auto;
        }


        .signature-block {
            margin-top: 60px;
            text-align: center;
            font-size: 8px;
            color: #aaa;
        }

    </style>
</head>
<body>

    @php
        $escolaridades = ['Fundamental', 'Médio', 'Superior', 'Pós-graduação'];

        $lateralidades = ['destro', 'sinistro', 'ambidestro'];

        $situacoesConjugais = ['solteiro', 'casado/amasiado', 'separado/divorciado', 'viúvo'];

        $queixas = [
            'Físicas' => [
                'Dor de cabeça',
                'Náusea ou vômito',
                'Fadiga excessiva',
                'Incontinência urinária ou urgência',
                'Problemas gastrointestinais',
                'Tremores e amortecimento',
                'Tiques e movimentos estranhos',
                'Trombar em coisas e objetos',
                'Escurecimento de vista / desmaios / diplopia',
            ],
            'Sensoriais' => [
                'Perda de sensibilidade',
                'Breves períodos de cegueira',
                'Perda auditiva',
                'Uso de aparelho auditivo',
                'Sensibilidade à luz e brilho',
                'Ouve barulhos estranhos',
                'Ver coisas que não estão presentes (alucinações)',
                'Chiado / ruído no ouvido (zumbido)',
                'Prejuízo visual',
                'Problemas olfativos',
                'Uso de lentes de contato / óculos',
                'Problemas gustativos',
                'Visão turva',
                'Dor (quais partes do corpo)',
            ],
            'Cognitivas (Resolução de Problemas)' => [
                'Dificuldade em realizar coisas novas',
                'Dificuldade para fazer coisas na ordem certa',
                'Dificuldade para resolver coisas domésticas',
                'Dificuldade para pensar tão rápido quanto necessário',
                'Dificuldade de planejamento prévio',
                'Dificuldade para completar atividade em tempo razoável',
                'Dificuldade em mudar de planos',
                'Desorganização maior que o usual',
            ],
            'Linguagem e Habilidades Matemáticas' => [
                'Dificuldade para achar a palavra correta',
                'Dificuldade para entender o que lê',
                'Discurso incoerente',
                'Dificuldade em entender o que os outros dizem',
                'Dificuldade para expressar o pensamento',
                'Dificuldade em escrever redações, cartas ou palavras (não por problemas motores)',
                'Dificuldade em operação matemática (contas, troco etc.)',
            ],
            'Habilidades Não Verbais' => [
                'Dificuldade em distinguir direita e esquerda',
                'Dificuldade de se vestir (não por problemas motores)',
                'Dificuldade de fazer coisas que deveria ser capaz de fazer automaticamente (p. ex.: escovar dentes)',
                'Problemas para encontrar o caminho de casa ou lugares conhecidos',
                'Dificuldade para reconhecer objetos e/ou pessoas',
                'Perda da noção de tempo (dia, mês, ano)',
            ],
            'Consciência e Construção' => [
                'Alta distração',
                'Torna-se confuso facilmente e desorientado',
                'Perde a linha de raciocínio facilmente',
                'Não se sente alerta e atento às coisas',
                'Dificuldade em fazer mais de uma coisa ao mesmo tempo',
                'Execução de tarefas requer mais esforço e atenção que o usual',
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
                'Depende que os outros o lembrem das coisas',
            ],
        ];

        $escalasHumor = [
            'Tristeza e depressão' => 'tristeza',
            'Ansiedade e Nervosismo' => 'ansiedade',
            'Estresse' => 'estresse',
        ];

        $outrosItensHumor = [
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
            'Dificuldade em ser espontâneo',
        ];

        $mudancasHumor = [
            'Mudança de energia / disposição' => 'energia',
            'Mudança de apetite' => 'apetite',
            'Mudança de interesse sexual' => 'libido',
        ];

        $historicoMedico = [
            'Trauma craniano (TCE)',
            'Alteração no colesterol',
            'Crise convulsiva',
            'Outros traumas',
            'Diabetes',
            'Derrame (AVC)',
            'Acidentes ou quedas',
            'Problemas cardíacos',
            'Alteração na pressão',
            'Problemas psiquiátricos',
            'Problemas de tireoide',
            'Cirurgias',
            'HIV, sífilis, meningite',
            'Internações',
            'Outros',
        ];

        $examesRecentes = [
            'Angiografia',
            'Ressonância magnética',
            'Tomografia Computadorizada',
            'SPECT',
            'PET',
            'Eletroencefalograma',
            'Avaliação neuropsicológica',
            'Avaliação fonoaudiológica',
        ];

        $substancias = [
            'Etilismo' => 'etilismo',
            'Tabagismo' => 'tabagismo',
            'Outras drogas' => 'outras-drogas',
        ];

        $sonoItens = [
            'Satisfatória',
            'Dificuldade em iniciar o sono',
            'Acorda mais cedo e não volta a dormir',
            'Acorda várias vezes durante a noite',
            'Sono agitado',
            'Bruxismo',
        ];
    @endphp

    <div class="header">
        <img src="{{ public_path('images/lydia_sena.png') }}" alt="Lydia Sena" class="header-logo">
    </div>

    <div style="text-align: center; margin-bottom: 15px;">
        <h2 style="color: #8C846C; text-transform: uppercase; font-size: 13px; margin: 0;">Anamnese Neuropsicológica</h2>
    </div>

    <div class="section">
        <div class="section-title">I. Identificação e Escolaridade</div>

        <table>
            <tr>
                <td colspan="2">
                    <span class="label">Paciente</span>
                    <span class="value">{{ $patient->name ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Data de Nascimento</span>
                    <span class="value">{{ !empty($patient->birth_date) ? \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') : '---' }}</span>
                </td>
                <td>
                    <span class="label">Idade</span>
                    <span class="value">{{ !empty($patient->birth_date) ? \Carbon\Carbon::parse($patient->birth_date)->age . ' anos' : '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="label">Datas (todos os encontros realizados)</span>
                    <span class="value">{{ $anamnese->sections['identificacao']['datas'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Naturalidade</span>
                    <span class="value">{{ $anamnese->sections['identificacao']['naturalidade'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Procedência</span>
                    <span class="value">{{ $anamnese->sections['identificacao']['procedencia'] ?? '---' }}</span>
                </td>
                <td colspan="2">
                    <span class="label">Nome do companheiro / Idade / Escolaridade</span>
                    <span class="value">{{ $anamnese->sections['identificacao']['companheiro_dados'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Lateralidade</div>
        <table>
            <tr>
                <td>
                    @foreach($lateralidades as $lat)
                        <div class="check-item-third">
                            <span class="box">{{ ($anamnese->sections['identificacao']['lateralidade'] ?? '') == $lat ? 'x' : '' }}</span>
                            {{ ucfirst($lat) }}
                        </div>
                    @endforeach
                </td>
            </tr>
        </table>

        <div class="subsection-title">Escolaridade</div>
        <table>
            <tr>
                <th width="20%">Nível</th>
                <th width="22%">Status</th>
                <th>Detalhes</th>
            </tr>
            @foreach($escolaridades as $esc)
                <tr>
                    <td><span class="value"><strong>{{ $esc }}</strong></span></td>
                    <td>
                        <span class="value">
                            [{{ ($anamnese->sections['identificacao']["esc_$esc"]['status'] ?? '') == 'completo' ? 'x' : ' ' }}] Completo
                            &nbsp;&nbsp;
                            [{{ ($anamnese->sections['identificacao']["esc_$esc"]['status'] ?? '') == 'incompleto' ? 'x' : ' ' }}] Incompleto
                        </span>
                    </td>
                    <td><span class="value">{{ $anamnese->sections['identificacao']["esc_$esc"]['detalhes'] ?? '---' }}</span></td>
                </tr>
            @endforeach
        </table>

        <div class="subsection-title">Situação Conjugal</div>
        <table>
            <tr>
                <td>
                    @foreach($situacoesConjugais as $sit)
                        <div class="check-item-third">
                            <span class="box">{{ ($anamnese->sections['identificacao']['conjugal'] ?? '') == $sit ? 'x' : '' }}</span>
                            {{ ucfirst($sit) }}
                        </div>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">II. Queixas Físicas, Sensoriais e Cognitivas</div>

        <table>
            <tr>
                <td width="40%">
                    <span class="label">Solicitante</span>
                    <span class="value">{{ $anamnese->sections['queixa']['solicitante'] ?? '---' }}</span>
                </td>
                <td width="60%">
                    <span class="label">Hipótese diagnóstica do solicitante</span>
                    <span class="value">{{ $anamnese->sections['queixa']['hipotese_solicitante'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Breve descrição da queixa / início da queixa</span>
                    <span class="value">{{ $anamnese->sections['queixa']['descricao_completa'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        @foreach($queixas as $titulo => $itens)
            <div class="subsection-title">{{ $titulo }}</div>
            <table>
                <tr>
                    <td>
                        <div class="checkbox-grid">
                            @foreach($itens as $item)
                                <div class="check-item">
                                    <span class="box">{{ in_array($item, $anamnese->sections['queixa']['items'] ?? []) ? 'x' : '' }}</span>
                                    {{ $item }}
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
            </table>
        @endforeach
    </div>

    <div class="section">
        <div class="section-title">III. Humor, Comportamento e Personalidade</div>

        <div class="subsection-title">Itens com escala de intensidade</div>
        <table>
            <tr>
                <th width="35%">Item</th>
                <th width="20%">Marcado</th>
                <th>Intensidade</th>
            </tr>
            @foreach($escalasHumor as $label => $key)
                <tr>
                    <td><span class="value">{{ $label }}</span></td>
                    <td><span class="value">{{ in_array($label, $anamnese->sections['humor']['itens'] ?? []) ? 'Sim' : 'Não' }}</span></td>
                    <td>
                        <span class="value">
                            [{{ ($anamnese->sections['humor']["nivel_$key"] ?? '') == 'Leve' ? 'x' : ' ' }}] Leve
                            &nbsp;
                            [{{ ($anamnese->sections['humor']["nivel_$key"] ?? '') == 'Moderada' ? 'x' : ' ' }}] Moderada
                            &nbsp;
                            [{{ ($anamnese->sections['humor']["nivel_$key"] ?? '') == 'Grave' ? 'x' : ' ' }}] Grave
                        </span>
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="subsection-title">Checklist simples</div>
        <table>
            <tr>
                <td>
                    <div class="checkbox-grid">
                        @foreach($outrosItensHumor as $item)
                            <div class="check-item">
                                <span class="box">{{ in_array($item, $anamnese->sections['humor']['itens'] ?? []) ? 'x' : '' }}</span>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Itens com opção de perda / aumento</div>
        <table>
            <tr>
                <th width="35%">Item</th>
                <th width="20%">Marcado</th>
                <th>Tipo</th>
            </tr>
            @foreach($mudancasHumor as $label => $key)
                <tr>
                    <td><span class="value">{{ $label }}</span></td>
                    <td><span class="value">{{ in_array($label, $anamnese->sections['humor']['itens'] ?? []) ? 'Sim' : 'Não' }}</span></td>
                    <td>
                        <span class="value">
                            [{{ ($anamnese->sections['humor']["tipo_$key"] ?? '') == 'Perda' ? 'x' : ' ' }}] Perda
                            &nbsp;&nbsp;
                            [{{ ($anamnese->sections['humor']["tipo_$key"] ?? '') == 'Aumento' ? 'x' : ' ' }}] Aumento
                        </span>
                    </td>
                </tr>
            @endforeach
        </table>

        <table>
            <tr>
                <td>
                    <span class="label">Os outros têm comentado sobre as mudanças de pensamento, comportamento, personalidade ou humor? Se sim, como e o que dizem?</span>
                    <span class="value">{{ $anamnese->sections['humor']['comentarios_outros'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Nos últimos seis meses, os sintomas</span>
                    <span class="value">
                        [{{ ($anamnese->sections['humor']['evolucao_sintomas'] ?? '') == 'Melhoraram' ? 'x' : ' ' }}] Melhoraram
                        &nbsp;&nbsp;
                        [{{ ($anamnese->sections['humor']['evolucao_sintomas'] ?? '') == 'Estacionaram' ? 'x' : ' ' }}] Estacionaram
                        &nbsp;&nbsp;
                        [{{ ($anamnese->sections['humor']['evolucao_sintomas'] ?? '') == 'Pioraram' ? 'x' : ' ' }}] Pioraram
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Tem algo que o sujeito ou alguém possa fazer para o problema deixá-lo menos intenso? O que parece fazer com que piore?</span>
                    <span class="value">{{ $anamnese->sections['humor']['fatores_melhora_piora'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Em resumo</span>
                    <span class="value">
                        [{{ ($anamnese->sections['humor']['resumo_final'] ?? '') == 'errado_definitivo' ? 'x' : ' ' }}] Definitivamente há algo de errado com o sujeito
                        <br>
                        [{{ ($anamnese->sections['humor']['resumo_final'] ?? '') == 'errado_possivel' ? 'x' : ' ' }}] Possivelmente algo está errado
                        <br>
                        [{{ ($anamnese->sections['humor']['resumo_final'] ?? '') == 'nada_errado' ? 'x' : ' ' }}] Não há nada de errado com o sujeito
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Quais são suas metas e aspirações para o futuro?</span>
                    <span class="value">{{ $anamnese->sections['humor']['metas_futuro'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">IV. Histórico Médico, Exames, Substâncias e Histórico Familiar</div>

        <div class="subsection-title">Histórico médico prévio</div>
        <table>
            <tr>
                <td>
                    <div class="checkbox-grid">
                        @foreach($historicoMedico as $item)
                            <div class="check-item">
                                <span class="box">{{ in_array($item, $anamnese->sections['medico']['historico'] ?? []) ? 'x' : '' }}</span>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Medicação? (quais, dosagem e tempo de uso)</span>
                    <span class="value">{{ $anamnese->sections['medico']['medicacao_detalhes'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Já fez outros tratamentos médicos? Quais? Por quê?</span>
                    <span class="value">{{ $anamnese->sections['medico']['outros_tratamentos_medicos'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Já fez outros tratamentos paramédicos (psicológico, fonoaudiológico, TO, fisioterapia)? Quais? Por quê? Por quanto tempo? Houve melhora?</span>
                    <span class="value">{{ $anamnese->sections['medico']['tratamentos_paramedicos'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Exames, testes e avaliações recentes</div>
        <table>
            <tr>
                <td>
                    <div class="checkbox-grid">
                        @foreach($examesRecentes as $item)
                            <div class="check-item">
                                <span class="box">{{ in_array($item, $anamnese->sections['medico']['exames_recentes'] ?? []) ? 'x' : '' }}</span>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Histórico de uso de substâncias</div>
        <table>
            <tr>
                <th width="25%">Substância</th>
                <th width="35%">Status</th>
                <th width="40%">Doses/semana</th>
            </tr>
            @foreach($substancias as $titulo => $slug)
                <tr>
                    <td><span class="value">{{ $titulo }}</span></td>
                    <td>
                        <span class="value">
                            [{{ ($anamnese->sections['substancias'][$slug]['status'] ?? '') == 'atual' ? 'x' : ' ' }}] Atual
                            &nbsp;&nbsp;
                            [{{ ($anamnese->sections['substancias'][$slug]['status'] ?? '') == 'previo' ? 'x' : ' ' }}] Prévio
                        </span>
                    </td>
                    <td><span class="value">{{ $anamnese->sections['substancias'][$slug]['doses'] ?? '---' }}</span></td>
                </tr>
            @endforeach
        </table>

        <div class="subsection-title">Histórico Familiar</div>
        <table>
            <tr>
                <th width="25%">Parentesco</th>
                <th width="35%">Problema de Saúde</th>
                <th width="15%">Falecido?</th>
                <th width="25%">Causa da Morte</th>
            </tr>
            @for($i = 0; $i < 4; $i++)
                <tr>
                    <td><span class="value">{{ $anamnese->sections['familiar'][$i]['parentesco'] ?? '---' }}</span></td>
                    <td><span class="value">{{ $anamnese->sections['familiar'][$i]['problema'] ?? '---' }}</span></td>
                    <td>
                        <span class="value">
                            [{{ ($anamnese->sections['familiar'][$i]['falecido'] ?? '') == 'sim' ? 'x' : ' ' }}] Sim
                            &nbsp;
                            [{{ ($anamnese->sections['familiar'][$i]['falecido'] ?? '') == 'nao' ? 'x' : ' ' }}] Não
                        </span>
                    </td>
                    <td><span class="value">{{ $anamnese->sections['familiar'][$i]['causa'] ?? '---' }}</span></td>
                </tr>
            @endfor
        </table>
    </div>

    <div class="section">
        <div class="section-title">V. Hábitos e Rotina</div>

        <div class="subsection-title">Qualidade do sono</div>
        <table>
            <tr>
                <td>
                    <div class="checkbox-grid">
                        @foreach($sonoItens as $item)
                            <div class="check-item">
                                <span class="box">{{ in_array($item, $anamnese->sections['habitos']['sono'] ?? []) ? 'x' : '' }}</span>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="50%">
                    <span class="label">Alteração do apetite</span>
                    <span class="value">{{ $anamnese->sections['habitos']['alteracao_apetite'] ?? '---' }}</span>
                </td>
                <td width="50%">
                    <span class="label">Leitura (O que lê? Quando lê?)</span>
                    <span class="value">{{ $anamnese->sections['habitos']['leitura'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Atividade física (Qual? Frequência?)</span>
                    <span class="value">{{ $anamnese->sections['habitos']['atividade_fisica'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Atividades sociais (Qual? Frequência?)</span>
                    <span class="value">{{ $anamnese->sections['habitos']['atividades_sociais'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Recebe ou faz visitas a amigos e familiares? Frequência?</span>
                    <span class="value">{{ $anamnese->sections['habitos']['visitas'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section" style="page-break-inside: avoid;">
        <div class="section-title">VI. Observações Finais</div>
        <table>
            <tr>
                <td>
                    <span class="label">Observações</span>
                    <span class="value">{{ $anamnese->sections['final']['observacoes_livre'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 200px;" class="signature">
        <div class="signature-line"></div>
        <div style="font-weight: bold; color: #8C846C;">Lydia Maria Sena Lima e Santos</div>
        <div style="font-size: 9px;">Psicóloga | CRP 04/41542</div>
        <div style="font-size: 9px; margin-top: 6px;">Expedido em {{ date('d/m/Y') }}</div>
    </div>
    <div class="footer">
        Documento gerado pelo Sistema Clínico Lydia Sena — Muzambinho/MG
    </div>

</body>
</html>