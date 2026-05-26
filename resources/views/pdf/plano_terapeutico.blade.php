<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 1.3cm; }

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
            margin-bottom: 22px;
            border-bottom: 1px solid #8C846C;
            padding-bottom: 10px;
        }

        .logo {
            font-size: 28px;
            color: #8C846C;
            margin-bottom: 4px;
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

        .patient-box {
            border: 1px solid #E1D3C1;
            background: #F9F6F3;
            padding: 10px 12px;
            margin-bottom: 16px;
            border-radius: 10px;
        }

        .patient-row {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .patient-col {
            display: table-cell;
            vertical-align: top;
            padding-right: 10px;
        }

        .label {
            display: block;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #8C846C;
            margin-bottom: 2px;
        }

        .value {
            display: block;
            font-size: 10.5px;
            color: #222;
            min-height: 14px;
            white-space: pre-line;
        }

        .section {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #F9F6F3;
            color: #8C846C;
            padding: 5px 10px;
            font-weight: bold;
            text-transform: uppercase;
            border-left: 4px solid #8C846C;
            margin-bottom: 8px;
            font-size: 10.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #8C846C;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background: #F9F6F3;
            color: #8C846C;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.04em;
            text-align: center;
        }

        td {
            font-size: 10px;
        }

        .date-cell {
            width: 90px;
            text-align: center;
            font-weight: bold;
            color: #8C846C;
            white-space: nowrap;
        }

        .objectives {
            font-style: italic;
            color: #6b6254;
        }

        .empty {
            color: #aaa;
            font-style: italic;
            text-align: center;
            padding: 20px;
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

        .signature {
            margin-top: 28px;
            text-align: center;
        }

        .signature-line {
            width: 250px;
            border-top: 1px solid #8C846C;
            margin: 0 auto 6px auto;
        }

        .small-note {
            font-size: 8.5px;
            color: #777;
            text-align: right;
            margin-top: 4px;
        }

        .break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/lydia_sena.png') }}" alt="Lydia Sena" class="header-logo">
    </div>

    <div class="patient-box">
        <div class="patient-row">
            <div class="patient-col" style="width: 55%;">
                <span class="label">Nome</span>
                <span class="value">{{ $patient->name ?? '---' }}</span>
            </div>
            <div class="patient-col" style="width: 25%;">
                <span class="label">Matrícula de Acesso ao Sistema</span>
                <span class="value">{{ $patient->cpf ?? 'Não cadastrado' }}</span>
            </div>
            <div class="patient-col" style="width: 20%;">
                <span class="label">Idade</span>
                <span class="value">
                    {{ !empty($patient->birth_date) ? \Carbon\Carbon::parse($patient->birth_date)->age . ' anos' : '---' }}
                </span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Plano Terapêutico</div>

        <table>
            <thead>
                <tr>
                    <th style="width: 95px;">Data</th>
                    <th>Evolução Clínica</th>
                    <th style="width: 28%;">Objetivos Terapêuticos</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patient->treatmentPlans()->orderBy('date', 'asc')->get() as $plan)
                    <tr>
                        <td class="date-cell">
                            {{ \Carbon\Carbon::parse($plan->date)->format('d/m/Y') }}
                        </td>
                        <td>
                            {{ $plan->clinical_evolution ?: '---' }}
                        </td>
                        <td class="objectives">
                            {{ $plan->therapeutic_objectives ?: '---' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="empty">
                            Nenhum registro encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="small-note">
            Registros organizados em ordem cronológica crescente para facilitar a leitura da evolução.
        </div>
    </div>
<span></span>
<span></span>
<span></span>
<span></span>
<span></span>
<span></span>
<span></span>
<span></span>
<span></span>
    <div style="margin-top: 200px;" class="signature">
        <div class="signature-line"></div>
        <div style="font-weight: bold; color: #8C846C;">Lydia Maria Sena Lima e Santos</div>
        <div style="font-size: 9px;">Psicóloga | CRP 04/41542</div>
        <div style="font-size: 9px; margin-top: 6px;">Expedido em {{ date('d/m/Y') }}</div>
    </div>
    </div>
    <div class="footer">
        Documento gerado pelo Sistema Clínico Lydia Sena — Muzambinho/MG
    </div>

</body>
</html>