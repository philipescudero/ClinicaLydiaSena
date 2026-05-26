<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <style>
        .header-logo {
            display: block;
            margin: 0 auto 8px auto;
            max-width: 140px;
            width: 100%;
            height: auto;
        }

        @page { margin: 1.5cm; }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #444;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #8C846C;
            padding-bottom: 10px;
        }
        .logo {
            font-size: 28px;
            color: #8C846C;
            margin-bottom: 5px;
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

        .clinica-nome {
            font-size: 18px;
            font-weight: bold;
            color: #8C846C;
            text-transform: uppercase;
        }
        .sub-header {
            font-size: 9px;
            color: #777;
            letter-spacing: 1px;
        }

        .section {
            margin-bottom: 18px;
            clear: both;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #F9F6F3;
            color: #8C846C;
            padding: 6px 10px;
            font-weight: bold;
            text-transform: uppercase;
            border-left: 4px solid #8C846C;
            margin-bottom: 10px;
            font-size: 11px;
        }
        .subsection-title {
            color: #8C846C;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            margin: 10px 0 6px;
            border-bottom: 1px solid #E8E2D8;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        td {
            vertical-align: top;
            padding: 5px;
        }

        .label {
            font-weight: bold;
            color: #8C846C;
            font-size: 9px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }
        .value {
            color: #333;
            font-size: 11px;
            border-bottom: 1px solid #F0F0F0;
            display: block;
            padding-top: 2px;
            min-height: 15px;
            white-space: pre-line;
        }

        .check-container {
            margin-bottom: 4px;
        }
        .box {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #8C846C;
            text-align: center;
            line-height: 10px;
            font-size: 8px;
            font-weight: bold;
            margin-right: 4px;
        }
        .checked {
            background-color: #8C846C;
            color: white;
        }

        .two-col td { width: 50%; }
        .three-col td { width: 33.33%; }
        .four-col td { width: 25%; }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #aaa;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    @php
        function simNao($valor) {
            if ($valor === 'sim') return 'Sim';
            if ($valor === 'nao') return 'Não';
            return '---';
        }
    @endphp

    <div class="header">
        <img src="{{ public_path('images/lydia_sena.png') }}" alt="Lydia Sena" class="header-logo">
    </div>

    <div class="section">
        <div class="section-title">1. Identificação do Paciente</div>
            <table>
                <tr>
                    <td width="45%">
                        <span class="label">Paciente</span>
                        <span class="value">{{ $patient->name ?? '---' }}</span>
                    </td>
                    <td width="20%">
                        <span class="label">Matrícula de Acesso ao Sistema</span>
                        <span class="value">{{ $patient->cpf ?? 'Não informado' }}</span>
                    </td>
                    <td width="15%">
                        <span class="label">Idade</span>
                        <span class="value">
                            {{ !empty($patient->birth_date) ? \Carbon\Carbon::parse($patient->birth_date)->age . ' anos' : '---' }}
                        </span>
                    </td>
                    <td width="20%">
                        <span class="label">Data Emissão</span>
                        <span class="value">{{ date('d/m/Y') }}</span>
                    </td>
                </tr>
            </table>

            <div class="subsection-title">Informações da Consulta</div>
            <table class="two-col">
                <tr>
                    <td>
                        <span class="label">Data da Entrevista</span>
                        <span class="value">
                            {{ !empty($anamnese->sections['identificacao']['data_entrevista']) ? \Carbon\Carbon::parse($anamnese->sections['identificacao']['data_entrevista'])->format('d/m/Y') : '---' }}
                        </span>
                    </td>
                    <td>
                        <span class="label">Data das Sessões (Cronograma)</span>
                        <span class="value">{{ $anamnese->sections['identificacao']['data_sessoes'] ?? '---' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">Naturalidade (Cidade/Estado)</span>
                        <span class="value">{{ $anamnese->sections['identificacao']['naturalidade'] ?? '---' }}</span>
                    </td>
                    <td>
                        <span class="label">Procedência (Cidade/Estado atual)</span>
                        <span class="value">{{ $anamnese->sections['identificacao']['procedencia'] ?? '---' }}</span>
                    </td>
                </tr>
            </table>

            <div class="subsection-title">Escolaridade Inicial</div>
            <table class="three-col">
                <tr>
                    <td>
                        <span class="label">Escolaridade / Ano Atual</span>
                        <span class="value">{{ $anamnese->sections['identificacao']['escolaridade_topo'] ?? '---' }}</span>
                    </td>
                    <td>
                        <span class="label">Instituição de Ensino (Escola)</span>
                        <span class="value">{{ $anamnese->sections['identificacao']['escola_topo'] ?? '---' }}</span>
                    </td>
                    <td>
                        <span class="label">Dependência Administrativa</span>
                        <span class="value">
                            @if(($anamnese->sections['identificacao']['rede_topo'] ?? '') === 'publica')
                                Pública
                            @elseif(($anamnese->sections['identificacao']['rede_topo'] ?? '') === 'particular')
                                Particular
                            @else
                                ---
                            @endif
                        </span>
                    </td>
                </tr>
            </table>

            <div class="subsection-title">Contexto Familiar</div>
            <table>
                <tr>
                    <td width="35%">
                        <span class="label">Situação dos Pais</span>
                        <span class="value">{{ ucfirst($anamnese->sections['familiar']['situacao_pais'] ?? 'Não informado') }}</span>
                    </td>
                    <td width="65%">
                        <span class="label">Como a Relação Parental?</span>
                        <span class="value">{{ $anamnese->sections['familiar']['relacao'] ?? '---' }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="label">Ajuda de Familiares / Quem?</span>
                        <span class="value">{{ $anamnese->sections['familiar']['ajuda_familiares'] ?? '---' }}</span>
                    </td>
                    <td>
                        <span class="label">Quem cuida da criança no contraturno? Parentesco/Escolaridade</span>
                        <span class="value">{{ $anamnese->sections['familiar']['cuidado_contraturno'] ?? '---' }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="label">Quem reside com a criança? Nome, idade, parentesco, instrução</span>
                        <span class="value">{{ $anamnese->sections['familiar']['residentes'] ?? '---' }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="label">Antecedentes / Queixas com herança genética ou influência ambiental</span>
                        <span class="value">{{ $anamnese->sections['familiar']['antecedentes_geneticos'] ?? '---' }}</span>
                    </td>
                </tr>
            </table>
        </div>

    <div class="section">
        <div class="section-title">2. Queixa e Motivo do Encaminhamento</div>

        <table class="two-col">
            <tr>
                <td>
                    <span class="label">Solicitante</span>
                    <span class="value">{{ $anamnese->sections['queixa']['solicitante'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Especialidade</span>
                    <span class="value">{{ $anamnese->sections['queixa']['especialidade'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Hipótese Diagnóstica</span>
                    <span class="value">{{ $anamnese->sections['queixa']['hipotese'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">CID</span>
                    <span class="value">{{ $anamnese->sections['queixa']['cid'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Objetivo / Finalidade da Avaliação</span>
                    <span class="value">{{ $anamnese->sections['queixa']['objetivo_avaliacao'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Histórico da Queixa</div>
        <table>
            <tr>
                <td width="50%">
                    <span class="label">Por que procurou ajuda?</span>
                    <span class="value">{{ $anamnese->sections['queixa']['motivo_ajuda'] ?? '---' }}</span>
                </td>
                <td width="50%">
                    <span class="label">Principais dificuldades / sintomas / comportamento</span>
                    <span class="value">{{ $anamnese->sections['queixa']['dificuldades'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Data de início da queixa</span>
                    <span class="value">{{ $anamnese->sections['queixa']['data_inicio_queixa'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Queixas secundárias</span>
                    <span class="value">{{ $anamnese->sections['queixa']['queixas_secundarias'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Problemas de aprendizagem / dificuldade de atenção</span>
                    <span class="value">{{ $anamnese->sections['queixa']['problemas_aprendizagem'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Termina suas atividades?</span>
                    <span class="value">{{ simNao($anamnese->sections['queixa']['termina_atividades'] ?? null) }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Como a criança lida com as dificuldades?</span>
                    <span class="value">{{ $anamnese->sections['queixa']['reacao_crianca'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Como o pai lida?</span>
                    <span class="value">{{ $anamnese->sections['queixa']['reacao_pai'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Como a mãe lida?</span>
                    <span class="value">{{ $anamnese->sections['queixa']['reacao_mae'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Nível de Independência</div>
        @php
            $itensIndependencia = [
                'evita_perigos' => 'Evita perigos?',
                'pequenas_compras' => 'Faz pequenas compras?',
                'transporte' => 'Usa transporte independente?',
                'autonomia_higiene' => 'Tem autonomia banho/veste?',
                'anota_recados' => 'Dá ou anota recados?',
                'conhece_rotina' => 'Conhece a própria rotina?'
            ];
        @endphp
        <table class="three-col">
            @foreach(array_chunk($itensIndependencia, 3, true) as $linha)
                <tr>
                    @foreach($linha as $key => $label)
                        <td>
                            <span class="label">{{ $label }}</span>
                            <span class="value">{{ simNao($anamnese->sections['queixa']['independencia'][$key] ?? null) }}</span>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>

    <div class="section">
        <div class="section-title">3. Histórico Escolar</div>

        <table class="two-col">
            <tr>
                <td>
                    <span class="label">Idade em que começou a frequentar a escola</span>
                    <span class="value">{{ $anamnese->sections['escolar']['idade_inicio'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Quando foi alfabetizado?</span>
                    <span class="value">{{ $anamnese->sections['escolar']['alfabetizacao'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Reprovações?</span>
                    <span class="value">{{ simNao($anamnese->sections['escolar']['reprovacao'] ?? null) }}</span>
                </td>
                <td>
                    <span class="label">Detalhes da reprovação</span>
                    <span class="value">{{ $anamnese->sections['escolar']['reprovacao_detalhes'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Matriculado em que ano?</span>
                    <span class="value">{{ $anamnese->sections['escolar']['ano_atual'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Rede Escolar</span>
                    <span class="value">{{ strtoupper($anamnese->sections['escolar']['rede'] ?? '---') }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Suportes Escolares</div>
        @php
            $suportes = [
                'inclusao' => 'Inclusão',
                'adaptacao' => 'Adaptação Curricular',
                'auxiliar' => 'Professor Auxiliar'
            ];
        @endphp
        <table class="three-col">
            <tr>
                @foreach($suportes as $key => $label)
                    <td>
                        <div class="check-container">
                            <span class="box {{ isset($anamnese->sections['escolar']['suporte'][$key]) ? 'checked' : '' }}">
                                {{ isset($anamnese->sections['escolar']['suporte'][$key]) ? 'X' : '' }}
                            </span>
                            <span>{{ $label }}</span>
                        </div>
                    </td>
                @endforeach
            </tr>
        </table>

        <table>
            <tr>
                <td width="40%">
                    <span class="label">Nome da Escola</span>
                    <span class="value">{{ $anamnese->sections['escolar']['nome_escola'] ?? '---' }}</span>
                </td>
                <td width="25%">
                    <span class="label">Telefone da Escola</span>
                    <span class="value">{{ $anamnese->sections['escolar']['tel_escola'] ?? '---' }}</span>
                </td>
                <td width="35%">
                    <span class="label">Professora / Responsável Pedagógico</span>
                    <span class="value">{{ $anamnese->sections['escolar']['responsavel_pedagogico'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="label">Dificuldades na escola e desde quando / relação com algum fato</span>
                    <span class="value">{{ $anamnese->sections['escolar']['dificuldades_detalhes'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="label">Mudanças de escola, quantas vezes, motivos e adaptação</span>
                    <span class="value">{{ $anamnese->sections['escolar']['mudancas_escola'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Preferência ou dificuldade em disciplina/atividade</span>
                    <span class="value">{{ $anamnese->sections['escolar']['preferencia_disciplina'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Rotina de estudo</span>
                    <span class="value">{{ $anamnese->sections['escolar']['rotina_estudo'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Notas obtidas atualmente</span>
                    <span class="value">{{ $anamnese->sections['escolar']['notas_atuais'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">4. Hábitos, Sono e Alimentação</div>

        <div class="subsection-title">Sono</div>
        @php
            $itensSono = [
                'satisfatorio' => 'Satisfatório',
                'tranquilo' => 'Tranquilo',
                'agitado' => 'Agitado',
                'sonambulo' => 'Sonâmbulo',
                'acorda_cansado' => 'Acorda cansado',
                'acorda_noite' => 'Acorda várias vezes',
                'volta_dormir' => 'Volta a dormir facilmente'
            ];
        @endphp
        <table class="two-col">
            <tr>
                <td width="50%">
                    @foreach($itensSono as $key => $label)
                        <div class="check-container">
                            <span class="box {{ ($anamnese->sections['habitos']['sono'][$key] ?? '') == 'sim' ? 'checked' : '' }}">
                                {{ ($anamnese->sections['habitos']['sono'][$key] ?? '') == 'sim' ? 'X' : '' }}
                            </span>
                            <span>{{ $label }}</span>
                        </div>
                    @endforeach
                </td>
                <td width="50%">
                    <span class="label">Onde dorme?</span>
                    <span class="value">{{ $anamnese->sections['habitos']['onde_dorme'] ?? '---' }}</span>

                    <span class="label">Com quem dorme?</span>
                    <span class="value">{{ $anamnese->sections['habitos']['com_quem_dorme'] ?? '---' }}</span>

                    <span class="label">Se houver problemas, quando iniciaram?</span>
                    <span class="value">{{ $anamnese->sections['habitos']['sono_inicio_problema'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Alimentação</div>
        @php
            $itensAli = [
                'restricao' => 'Restrição Alimentar',
                'seletividade' => 'Seletividade Alimentar',
                'balanceada' => 'Alimentação Balanceada',
                'deficit' => 'Baixa/Excesso Nutricional',
                'horario' => 'Há horário para refeições',
                'respeita_horario' => 'Respeita os horários',
                'impulsividade' => 'Impulsividade para comer',
                'peso_alterado' => 'Acima/Abaixo do peso'
            ];
        @endphp
        <table>
            <tr>
                <td width="50%">
                    @foreach($itensAli as $key => $label)
                        <div class="check-container">
                            <span class="box {{ ($anamnese->sections['habitos']['alimentacao'][$key] ?? '') == 'sim' ? 'checked' : '' }}">
                                {{ ($anamnese->sections['habitos']['alimentacao'][$key] ?? '') == 'sim' ? 'X' : '' }}
                            </span>
                            <span>{{ $label }}</span>
                        </div>

                        @if(in_array($key, ['restricao', 'seletividade']))
                            <span class="label">Observação - {{ $label }}</span>
                            <span class="value">{{ $anamnese->sections['habitos']['alimentacao'][$key . '_obs'] ?? '---' }}</span>
                        @endif
                    @endforeach
                </td>
                <td width="50%">
                    <span class="label">O que faz no contraturno escolar quando está em casa?</span>
                    <span class="value">{{ $anamnese->sections['habitos']['lazer_casa'] ?? '---' }}</span>

                    <span class="label">Atividade esportiva/artística e carga horária</span>
                    <span class="value">{{ $anamnese->sections['habitos']['atividade_fisica'] ?? '---' }}</span>

                    <span class="label">Atividade extracurricular / línguas / reforço e carga horária</span>
                    <span class="value">{{ $anamnese->sections['habitos']['atividade_extra'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">5. Gestação e Parto</div>

        <div class="subsection-title">Histórico Gestacional</div>
        <table class="three-col">
            <tr>
                <td>
                    <span class="label">Criança desejada?</span>
                    <span class="value">{{ simNao($anamnese->sections['gestacao']['desejada'] ?? null) }}</span>
                </td>
                <td>
                    <span class="label">Foi planejada?</span>
                    <span class="value">{{ simNao($anamnese->sections['gestacao']['planejada'] ?? null) }}</span>
                </td>
                <td>
                    <span class="label">Pré-natal a termo?</span>
                    <span class="value">{{ simNao($anamnese->sections['gestacao']['prenatal'] ?? null) }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="label">Observação do pré-natal</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['prenatal_obs'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <table class="three-col">
            <tr>
                <td>
                    <span class="label">Intercorrências na gestação clínica/emocional</span>
                    <span class="value">{{ simNao($anamnese->sections['gestacao']['intercorrencias']['flag'] ?? null) }}</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['intercorrencias']['detalhes'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Uso de medicações</span>
                    <span class="value">{{ simNao($anamnese->sections['gestacao']['medicacoes']['flag'] ?? null) }}</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['medicacoes']['detalhes'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Uso de álcool/drogas ou fumou</span>
                    <span class="value">{{ simNao($anamnese->sections['gestacao']['substancias']['flag'] ?? null) }}</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['substancias']['detalhes'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">O Parto</div>
        <table class="four-col">
            <tr>
                <td>
                    <span class="label">Tipo de Parto</span>
                    <span class="value">{{ ucfirst($anamnese->sections['gestacao']['tipo_parto'] ?? '---') }}</span>
                </td>
                <td>
                    <span class="label">Cesárea - Tipo</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['cesarea_tipo'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Cesárea - Observação</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['cesareo_obs'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Usou fórceps?</span>
                    <span class="value">{{ simNao($anamnese->sections['gestacao']['forceps'] ?? null) }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Nº de semanas</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['semanas'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Peso</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['peso'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Comprimento</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['comprimento'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Apgar</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['apgar'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Icterícia?</span>
                    <span class="value">{{ simNao($anamnese->sections['gestacao']['ictericia'] ?? null) }}</span>
                </td>
                <td>
                    <span class="label">Alta junto com a mãe?</span>
                    <span class="value">{{ simNao($anamnese->sections['gestacao']['alta_mae'] ?? null) }}</span>
                </td>
                <td colspan="2">
                    <span class="label">Amamentação</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['amamentacao'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <span class="label">Desmame - quando foi e como foi</span>
                    <span class="value">{{ $anamnese->sections['gestacao']['desmame'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
    <div class="section-title">6. Desenvolvimento e Comportamento</div>

    <div class="subsection-title">Fala e Linguagem</div>
        <table class="three-col">
            <tr>
                <td>
                    <span class="label">Com quantos meses balbuciou?</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['balbucio'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Primeiras palavras?</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['palavras'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Frases completas?</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['frases'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Houve persistência nas trocas de fala?</span>
                    <span class="value">{{ simNao($anamnese->sections['desenvolvimento']['troca_fala'] ?? null) }}</span>
                </td>
                <td colspan="2">
                    <span class="label">Se sim, quais?</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['troca_fala_obs'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Mobilidade e Controle Esfincteriano</div>
        <table class="four-col">
            <tr>
                <td>
                    <span class="label">Com quantos meses sentou sozinho?</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['sentou'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Com quantos meses engatinhou?</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['engatinhou'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Com quantos meses levantou-se?</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['levantou'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Com quantos meses andou?</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['andou'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Desfralde diurno</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['desfralde_diurno'] ?? '---' }}</span>
                </td>
                <td colspan="2">
                    <span class="label">Desfralde noturno</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['desfralde_noturno'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Enurese (xixi involuntário)?</span>
                    <span class="value">{{ simNao($anamnese->sections['desenvolvimento']['enurese'] ?? null) }}</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['enurese_obs'] ?? '---' }}</span>
                </td>
                <td colspan="2">
                    <span class="label">Encoprese (segura fezes)?</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['encoprese'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Comportamento e Humor</div>
        <table>
            <tr>
                <td width="50%">
                    <span class="label">Como se relaciona com adultos?</span>
                    <span class="value">{{ $anamnese->sections['comportamento']['relacionamento_adultos'] ?? '---' }}</span>
                </td>
                <td width="50%">
                    <span class="label">Como se relaciona com outras crianças?</span>
                    <span class="value">{{ $anamnese->sections['comportamento']['relacionamento_criancas'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Brinca em grupo ou prefere isolar-se?</span>
                    <span class="value">{{ $anamnese->sections['comportamento']['brincadeiras'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Respeita regras dos jogos coletivos?</span>
                    <span class="value">{{ $anamnese->sections['comportamento']['regras'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Respeita a vontade do grupo? Passivo/impositivo?</span>
                    <span class="value">{{ $anamnese->sections['comportamento']['vontade_grupo'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Como reage à frustração?</span>
                    <span class="value">{{ $anamnese->sections['comportamento']['reacao_frustracao'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Segue ordens?</span>
                    <span class="value">{{ simNao($anamnese->sections['comportamento']['segue_ordens'] ?? null) }}</span>
                </td>
                <td>
                    <span class="label">Olhar no olho?</span>
                    <span class="value">{{ simNao($anamnese->sections['comportamento']['olhar_no_olho'] ?? null) }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Ponta dos pés?</span>
                    <span class="value">{{ simNao($anamnese->sections['comportamento']['ponta_dos_pes'] ?? null) }}</span>
                </td>
                <td>
                    <span class="label">Movimentos repetitivos?</span>
                    <span class="value">{{ simNao($anamnese->sections['comportamento']['mov_repetitivo'] ?? null) }}</span>
                    <span class="value">{{ $anamnese->sections['comportamento']['mov_repetitivo_obs'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Características positivas</span>
                    <span class="value">{{ $anamnese->sections['comportamento']['caracteristicas_positivas'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Do que mais gosta de brincar?</span>
                    <span class="value">{{ $anamnese->sections['comportamento']['gosta_brincar'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Tem algum interesse restrito?</span>
                    <span class="value">{{ $anamnese->sections['comportamento']['interesse_restrito'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">7. Saúde Clínica, Tratamentos e Rotina</div>

        <div class="subsection-title">História Clínica</div>
        <table>
            <tr>
                <td width="50%">
                    <span class="label">Desde quando apresenta os sintomas/comportamentos?</span>
                    <span class="value">{{ $anamnese->sections['saude']['inicio_sintomas'] ?? '---' }}</span>
                </td>
                <td width="50%">
                    <span class="label">Evolução dos sintomas</span>
                    <span class="value">{{ $anamnese->sections['saude']['evolucao_sintomas'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Relaciona algum fato pessoal aos sintomas?</span>
                    <span class="value">{{ simNao($anamnese->sections['saude']['fato_relacionado']['flag'] ?? null) }}</span>
                    <span class="value">{{ $anamnese->sections['saude']['fato_relacionado']['obs'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Já procurou auxílio para investigar?</span>
                    <span class="value">{{ simNao($anamnese->sections['saude']['auxilio_investigacao']['flag'] ?? null) }}</span>
                    <span class="value">{{ $anamnese->sections['saude']['auxilio_investigacao']['obs'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Outros diagnósticos / avaliações anteriores</span>
                    <span class="value">{{ $anamnese->sections['saude']['diagnosticos_anteriores'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Antecedentes de Saúde</div>
        <table class="three-col">
            <tr>
                <td>
                    <span class="label">Vacinas regularmente?</span>
                    <span class="value">{{ simNao($anamnese->sections['saude']['vacinas']['flag'] ?? null) }}</span>
                    <span class="value">{{ $anamnese->sections['saude']['vacinas']['obs'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Já houve internação?</span>
                    <span class="value">{{ simNao($anamnese->sections['saude']['internacao']['flag'] ?? null) }}</span>
                    <span class="value">{{ $anamnese->sections['saude']['internacao']['obs'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Acidente ou cirurgia?</span>
                    <span class="value">{{ simNao($anamnese->sections['saude']['acidente_cirurgia']['flag'] ?? null) }}</span>
                    <span class="value">{{ $anamnese->sections['saude']['acidente_cirurgia']['obs'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="50%">
                    <span class="label">Doenças e intercorrências</span>
                    <span class="value">{{ !empty($anamnese->sections['saude']['doencas']) ? implode(', ', $anamnese->sections['saude']['doencas']) : '---' }}</span>
                </td>
                <td width="50%">
                    <span class="label">Infecções otorrinolaringológicas / idade</span>
                    <span class="value">{{ $anamnese->sections['saude']['infeccoes_obs'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Crises convulsivas</span>
                    <span class="value">{{ $anamnese->sections['saude']['convulsao_obs'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Neurointercorrências / TCE / meningite</span>
                    <span class="value">{{ $anamnese->sections['saude']['neurointercorrencia'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Localização da lesão / área afetada</span>
                    <span class="value">{{ $anamnese->sections['saude']['neurolesao'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Houve coma? Quanto tempo?</span>
                    <span class="value">{{ $anamnese->sections['saude']['neurocoma'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Epilepsia / Crises Atuais</div>
        <table class="three-col">
            <tr>
                <td>
                    <span class="label">Idade de início</span>
                    <span class="value">{{ $anamnese->sections['saude']['epilepsia_inicio'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Frequência atual</span>
                    <span class="value">{{ $anamnese->sections['saude']['epilepsia_freq'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Como são as crises e medicações</span>
                    <span class="value">{{ $anamnese->sections['saude']['epilepsia_desc'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Tratamentos e Rotina</div>

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
            <table style="width: 100%; border: 1px solid #E1D3C1; border-radius: 8px; margin-bottom: 10px; table-layout: fixed;">
                <tr>
                    <td width="22%" style="background: #FCFBF9;">
                        <span class="label">{{ $label }}</span>
                        <span class="value">Tratamento informado</span>
                    </td>
                    <td width="23%">
                        <span class="label">Idade e Tempo</span>
                        <span class="value">{{ $anamnese->sections['saude']['tratamento'][$key]['idade'] ?? '---' }}</span>
                    </td>
                    <td width="55%">
                        <span class="label">Houve melhora dos sintomas/comportamento?</span>
                        <span class="value">{{ $anamnese->sections['saude']['tratamento'][$key]['melhora'] ?? '---' }}</span>
                    </td>
                </tr>
            </table>
        @endforeach

        <table style="table-layout: fixed;">
            <tr>
                <td width="50%">
                    <span class="label">Outros tratamentos / acompanhamentos</span>
                    <span class="value">{{ $anamnese->sections['saude']['outros_tratamentos'] ?? '---' }}</span>
                </td>
                <td width="50%">
                    <span class="label">Medicações de uso crônico (Dose/Hora)</span>
                    <span class="value">{{ $anamnese->sections['saude']['medicacoes_atuais'] ?? 'Nenhuma informada' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Exames feitos ou solicitados (Datas e Resumo)</span>
                    <span class="value">{{ $anamnese->sections['saude']['exames_resumo'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Descreva um dia da rotina da criança</span>
                    <span class="value">{{ $anamnese->sections['saude']['rotina_diaria'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Observações Finais</span>
                    <span class="value">{{ $anamnese->sections['saude']['observacoes_finais'] ?? '---' }}</span>
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