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

        .signature {
            margin-top: 28px;
            text-align: center;
        }

        .signature-line {
            width: 250px;
            border-top: 1px solid #8C846C;
            margin: 0 auto 6px auto;
        }

        .logo {
            font-size: 28px;
            color: #8C846C;
            margin-bottom: 5px;
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
            margin-bottom: 10px;
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

        .two-col td { width: 50%; }
        .three-col td { width: 33.33%; }

    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/lydia_sena.png') }}" alt="Lydia Sena" class="header-logo">
    </div>

    <div style="text-align: center; margin-bottom: 15px;">
        <h2 style="color: #8C846C; text-transform: uppercase; font-size: 13px; margin: 0;">Anamnese Psicológica</h2>
    </div>

    <div class="section">
        <div class="section-title">1. Identificação do Paciente</div>
        <table>
            <tr>
                <td width="45%">
                    <span class="label">Paciente</span>
                    <span class="value">{{ $patient->name ?? '---' }}</span>
                </td>
                <td width="25%">
                    <span class="label">Matrícula de Acesso ao Sistema</span>
                    <span class="value">{{ $patient->cpf ?? 'Não informado' }}</span>
                </td>
                <td width="15%">
                    <span class="label">Idade</span>
                    <span class="value">
                        {{ !empty($patient->birth_date) ? \Carbon\Carbon::parse($patient->birth_date)->age . ' anos' : '---' }}
                    </span>
                </td>
                <td width="15%">
                    <span class="label">Data Emissão</span>
                    <span class="value">{{ date('d/m/Y') }}</span>
                </td>
            </tr>
        </table>

        <div class="subsection-title">Dados Pessoais</div>
        <table class="three-col">
            <tr>
                <td>
                    <span class="label">Sexo</span>
                    <span class="value">{{ $anamnese->sections['identificacao']['sexo'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Estado Civil</span>
                    <span class="value">{{ $anamnese->sections['identificacao']['estado_civil'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Escolaridade</span>
                    <span class="value">{{ $anamnese->sections['identificacao']['escolaridade'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <span class="label">Atividade Laboral (Profissão)</span>
                    <span class="value">{{ $anamnese->sections['identificacao']['profissao'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">2. Constelação Familiar</div>

        <div class="subsection-title">Contexto Familiar</div>
        <table class="two-col">
            <tr>
                <td>
                    <span class="label">Dados do Pai (Nome/Idade)</span>
                    <span class="value">{{ $anamnese->sections['familiar']['pai'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Dados da Mãe (Nome/Idade)</span>
                    <span class="value">{{ $anamnese->sections['familiar']['mae'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Irmãos (Sim/Não - Quantos?)</span>
                    <span class="value">{{ $anamnese->sections['familiar']['irmaos'] ?? '---' }}</span>
                </td>
                <td>
                    <span class="label">Cônjuge / Filhos</span>
                    <span class="value">{{ $anamnese->sections['familiar']['conjuge_filhos'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="label">Histórico Familiar (Doenças/Comportamentos)</span>
                    <span class="value">{{ $anamnese->sections['familiar']['historico'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">3. Histórico e Queixa</div>

        <div class="subsection-title">Queixa e Saúde</div>
        <table>
            <tr>
                <td colspan="2">
                    <span class="label">Queixa Principal (Por que procurou ajuda?)</span>
                    <span class="value">{{ $anamnese->sections['clinico']['queixa'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <span class="label">Acompanhamento Psicológico Anterior?</span>
                    <span class="value">{{ $anamnese->sections['clinico']['psicologico_anterior'] ?? '---' }}</span>
                </td>
                <td width="50%">
                    <span class="label">Faz uso de medicação controlada?</span>
                    <span class="value">{{ $anamnese->sections['clinico']['medicacao'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">4. Parecer e Observações</div>

        <div class="subsection-title">Análise Clínica</div>
        <table>
            <tr>
                <td>
                    <span class="label">Parecer Psicológico</span>
                    <span class="value">{{ $anamnese->sections['parecer']['analise'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Observações Adicionais</span>
                    <span class="value">{{ $anamnese->sections['parecer']['observacoes'] ?? '---' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">4. Parecer e Observações</div>

        <div class="subsection-title">Análise Clínica</div>
        <table>
            <tr>
                <td>
                    <span class="label">Parecer Psicológico</span>
                    <span class="value">{{ $anamnese->sections['parecer']['analise'] ?? '---' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">Observações Adicionais</span>
                    <span class="value">{{ $anamnese->sections['parecer']['observacoes'] ?? '---' }}</span>
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