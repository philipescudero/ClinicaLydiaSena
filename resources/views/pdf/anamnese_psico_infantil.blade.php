<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 1.4cm; }

        .header-logo {
            display: block;
            margin: 0 auto 8px auto;
            max-width: 140px;
            width: 100%;
            height: auto;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10.5px;
            color: #333;
            line-height: 1.45;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
            border-bottom: 1px solid #8C846C;
            padding-bottom: 10px;
        }

        .logo {
            font-size: 28px;
            color: #8C846C;
            margin-bottom: 4px;
        }

        .signature-block {
            margin-top: 60px;
            text-align: center;
            font-size: 8px;
            color: #aaa;
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
            font-size: 17px;
            font-weight: bold;
            color: #8C846C;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sub-header {
            font-size: 9px;
            color: #777;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 18px;
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
            font-size: 9px;
            margin: 10px 0 6px;
            border-bottom: 1px solid #E8E2D8;
            padding-bottom: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
        }

        td {
            vertical-align: top;
            padding: 5px;
        }

        .label {
            font-weight: bold;
            color: #8C846C;
            font-size: 8.5px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .value {
            color: #222;
            font-size: 10.5px;
            border-bottom: 1px solid #F0F0F0;
            display: block;
            padding-top: 2px;
            min-height: 16px;
            white-space: pre-line;
        }

        .three-col td { width: 33.33%; }
        .two-col td { width: 50%; }

        .signature-line {
            width: 240px;
            border-top: 1px solid #8C846C;
            margin: 0 auto 6px auto;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/lydia_sena.png') }}" alt="Lydia Sena" class="header-logo">
    </div>

    <div class="section">
        <div class="section-title">Identificação da Criança</div>
        <table>
            <tr>
                <td width="45%">
                    <span class="label">Criança</span>
                    <span class="value">{{ $patient->name ?? '---' }}</span>
                </td>
                <td width="25%">
                    <span class="label">Data de Nascimento</span>
                    <span class="value">
                        {{ !empty($patient->birth_date) ? \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') : '---' }}
                    </span>
                </td>
                <td width="15%">
                    <span class="label">Idade Atual</span>
                    <span class="value">
                        {{ !empty($patient->birth_date) ? \Carbon\Carbon::parse($patient->birth_date)->age . ' anos' : '---' }}
                    </span>
                </td>
                <td width="15%">
                    <span class="label">Data de Emissão</span>
                    <span class="value">{{ date('d/m/Y') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">1. Dados Escolares</div>
        <table>
            <tr>
                <td width="50%">
                    <span class="label">Escola</span>
                    <span class="value">{{ $anamnese->sections['escolar']['escola'] ?? '---' }}</span>
                </td>
                <td width="25%">
                    <span class="label">Ano</span>
                    <span class="value">{{ $anamnese->sections['escolar']['ano'] ?? '---' }}</span>
                </td>
                <td width="25%">
                    <span class="label">Série</span>
                    <span class="value">{{ $anamnese->sections['escolar']['serie'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <span class="label">Endereço da Escola / Fone</span>
                    <span class="value">{{ $anamnese->sections['escolar']['endereco_fone'] ?? '---' }}</span>
                </td>
                <td width="50%" colspan="2">
                    <span class="label">Nome da Professora</span>
                    <span class="value">{{ $anamnese->sections['escolar']['professora'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="label">Horário que frequenta a escola</span>
                    <span class="value">{{ $anamnese->sections['escolar']['horario'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">2. Filiação e Constelação Familiar</div>

        <div class="subsection-title">Pai</div>
        <table class="two-col">
            <tr>
                <td>
                    <span class="label">Nome do Pai</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['pai']['nome'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Data de Nascimento / Idade</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['pai']['nascimento'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Profissão</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['pai']['profissao'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Tel. para contato</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['pai']['contato'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Endereço Residencial</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['pai']['endereco'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Mãe</div>
        <table class="two-col">
            <tr>
                <td>
                    <span class="label">Nome da Mãe</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['mae']['nome'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Data de Nascimento / Idade</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['mae']['nascimento'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Profissão</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['mae']['profissao'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Tel. para contato</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['mae']['contato'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Endereço Residencial</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['mae']['endereco'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Responsável</div>
        <table class="two-col">
            <tr>
                <td>
                    <span class="label">Nome do Responsável</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['responsavel']['nome'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Data de Nascimento / Idade</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['responsavel']['nascimento'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Profissão</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['responsavel']['profissao'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Tel. para contato</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['responsavel']['contato'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Endereço Residencial</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['responsavel']['endereco'] ?? '---' }}</span>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="50%">
                    <span class="label">Constelação Familiar</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['constelacao'] ?? '---' }}</span>
                </td>
                <td width="25%">
                    <span class="label">Nº de Irmãos</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['irmaos_qtd'] ?? '---' }}</span>
                </td>
                <td width="25%">
                    <span class="label">Posição na Ordem</span>
                    <span class="value">{{ $anamnese->sections['filiacao']['ordem'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">3. Queixa Principal</div>
        <table>
            <tr>
                <td>
                    <span class="label">Queixa Principal</span>
                    <span class="value">{{ $anamnese->sections['queixa']['principal'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Percepção dos Pais/Responsáveis em relação à Criança</span>
                    <span class="value">{{ $anamnese->sections['queixa']['percepcao_pais'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Como e quando começaram a perceber as dificuldades? (sentimentos, atitudes)</span>
                    <span class="value">{{ $anamnese->sections['queixa']['inicio_dificuldades'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Repercussões do problema na família e amigos</span>
                    <span class="value">{{ $anamnese->sections['queixa']['repercussoes'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">4. Primeiros Dias de Vida</div>
        <table class="two-col">
            <tr>
                <td>
                    <span class="label">Abortos / Quando?</span>
                    <span class="value">{{ $anamnese->sections['primeiros']['abortos'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Condições do Parto e da Criança ao nascer</span>
                    <span class="value">{{ $anamnese->sections['primeiros']['parto_condicoes'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Amamentação (regularidade, satisfação, dificuldades, período, mamadeira)</span>
                    <span class="value">{{ $anamnese->sections['primeiros']['amamentacao'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Relações Primitivas Mãe-Filho (sentimentos, medos, inseguranças)</span>
                    <span class="value">{{ $anamnese->sections['primeiros']['relacao_mae'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Atitudes Paternas/Maternas frente às mudanças</span>
                    <span class="value">{{ $anamnese->sections['primeiros']['atitudes_pais'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Participação do Pai nos cuidados (primeiros meses)</span>
                    <span class="value">{{ $anamnese->sections['primeiros']['participacao_pai'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">5. Desenvolvimento</div>
        <table>
            <tr>
                <td>
                    <span class="label">Ritmos Biológicos (sono, alimentação, excreções)</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['ritmos'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Dados Orgânicos Básicos (cólicas, infecções, convulsões)</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['organicos'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Aquisição Psicotônicas (sorriso, pescoço, sentar, reconhecer mãe, engatinhar, andar)</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['psicotonicas'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Marcha (características gerais)</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['marcha'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Linguagem (aquisição, desenvolvimento)</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['linguagem'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Atividade Lúdica Primitiva</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['ludica'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Interesse por Objetos (cor, movimento, sons)</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['objetos'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Controle dos Esfíncteres</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['esfincteres'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Rotina Diária</span>
                    <span class="value">{{ $anamnese->sections['desenvolvimento']['rotina'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">6. Socialização</div>
        <table class="two-col">
            <tr>
                <td>
                    <span class="label">Relações Interpessoais na Família</span>
                    <span class="value">{{ $anamnese->sections['social']['familia'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Relações Interpessoais na Escola</span>
                    <span class="value">{{ $anamnese->sections['social']['escola'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Agressividade (manifestação)</span>
                    <span class="value">{{ $anamnese->sections['social']['agressividade'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Como escolhe os amigos / Nomes</span>
                    <span class="value">{{ $anamnese->sections['social']['amigos'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Relações nos Grupos de Brinquedos (adaptação, regras, divide objetos)</span>
                    <span class="value">{{ $anamnese->sections['social']['grupos'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Timidez/Extroversão</span>
                    <span class="value">{{ $anamnese->sections['social']['timidez'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Prática de Esportes</span>
                    <span class="value">{{ $anamnese->sections['social']['esportes'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Doenças da Infância</span>
                    <span class="value">{{ $anamnese->sections['social']['doencas'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Como vive aniversário / gosta de festas?</span>
                    <span class="value">{{ $anamnese->sections['social']['festas'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">7. Psicomotricidade</div>
        <table>
            <tr><td><span class="label">Habilidades Óculo-Manuais (recorte, colagem, ferramentas)</span><span class="value">{{ $anamnese->sections['psicomotor']['oculo_manuais'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Coordenação Dinâmica Geral e Equilíbrio (pular, bicicleta, skate)</span><span class="value">{{ $anamnese->sections['psicomotor']['dinamica_geral'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Orientação Temporal (noções de tempo/calendário)</span><span class="value">{{ $anamnese->sections['psicomotor']['orientacao_temporal'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Preferência Lateral (espontânea ou forçada)</span><span class="value">{{ $anamnese->sections['psicomotor']['lateral_pref'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Capacidade de Atenção e Concentração</span><span class="value">{{ $anamnese->sections['psicomotor']['atencao'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Discriminação de Formas, Tamanho, Posição, Cores</span><span class="value">{{ $anamnese->sections['psicomotor']['discriminacao'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Noção de quantidade, peso, medidas, dinheiro</span><span class="value">{{ $anamnese->sections['psicomotor']['noções'] ?? ($anamnese->sections['psicomotor']['nocoes'] ?? '---') }}</span></td></tr>
            <tr><td><span class="label">Contato com a Realidade / Interesses externos</span><span class="value">{{ $anamnese->sections['psicomotor']['realidade'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Escolhas Pessoais / Opina nas decisões da Família?</span><span class="value">{{ $anamnese->sections['psicomotor']['decisoes'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Descrição de um dia na Rotina da Criança</span><span class="value">{{ $anamnese->sections['psicomotor']['rotina_dia'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Medos?</span><span class="value">{{ $anamnese->sections['psicomotor']['limitacoes'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Sono</span><span class="value">{{ $anamnese->sections['psicomotor']['sono'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Limitações Impostas pelo Ambiente</span><span class="value">{{ $anamnese->sections['psicomotor']['ambiente'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Limitações Orgânicas (alergias, disritmias, deficiência senso-perceptivas, deficiência motoras)</span><span class="value">{{ $anamnese->sections['psicomotor']['ambiente1'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Dados Médicos</span><span class="value">{{ $anamnese->sections['psicomotor']['dados_medicos'] ?? '---' }}</span></td></tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">8. Cognitivo e Observações Finais</div>
        <table>
            <tr><td><span class="label">Organização do Pensamento</span><span class="value">{{ $anamnese->sections['cognitivo']['pensamento'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Linguagem</span><span class="value">{{ $anamnese->sections['cognitivo']['linguagem'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Curiosidades (gerais, sexuais)</span><span class="value">{{ $anamnese->sections['cognitivo']['curiosidades'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Criatividade</span><span class="value">{{ $anamnese->sections['cognitivo']['criatividade'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Memória</span><span class="value">{{ $anamnese->sections['cognitivo']['memoria'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Adaptação e Aproveitamento Escolar (primeiras experiências / situação atual)</span><span class="value">{{ $anamnese->sections['cognitivo']['aproveitamento'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Dificuldades Específicas</span><span class="value">{{ $anamnese->sections['cognitivo']['dificuldades'] ?? '---' }}</span></td></tr>
            <tr><td><span class="label">Observações Adicionais</span><span class="value">{{ $anamnese->sections['cognitivo']['obs_adicionais'] ?? '---' }}</span></td></tr>
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