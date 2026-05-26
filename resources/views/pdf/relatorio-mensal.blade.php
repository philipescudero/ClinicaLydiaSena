<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Relatório Mensal - {{ $month }}/{{ $year }}</title>
    <style>
        @page {
            margin: 1cm 1cm 2.8cm 1cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            color: #444;
            line-height: 1.45;
            font-size: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #8C846C;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .header h1 {
            color: #8C846C;
            margin: 0;
            font-size: 24pt;
            font-style: italic;
            font-weight: bold;
        }

        .header p {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 9pt;
            color: #8C846C;
            margin: 5px 0 0 0;
        }

        .title-box {
            text-align: center;
            background: #F9F6F3;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .title-box h2 {
            font-size: 12pt;
            color: #766f5a;
            margin: 0;
            text-transform: uppercase;
        }

        .meta {
            margin-bottom: 18px;
            font-size: 9pt;
            color: #766f5a;
        }

        .meta strong {
            color: #8C846C;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            display: table-header-group;
        }

        tr {
            page-break-inside: avoid;
        }

        th {
            background-color: #8C846C;
            color: white;
            padding: 10px 8px;
            font-size: 8.5pt;
            text-align: center;
            text-transform: uppercase;
        }

        td {
            border-bottom: 1px solid #E1D3C1;
            padding: 9px 8px;
            font-size: 9pt;
            text-align: center;
            vertical-align: middle;
        }

        .text-left {
            text-align: left;
        }

        .status-pago {
            color: #166534;
            font-weight: bold;
        }

        .status-pendente {
            color: #8C846C;
            font-weight: bold;
        }

        .money {
            font-weight: bold;
            color: #766f5a;
        }

        .empty-message {
            text-align: center;
            padding: 24px;
            color: #999;
            font-style: italic;
            border: 1px solid #E1D3C1;
            border-top: 0;
        }

        .footer {
            position: fixed;
            bottom: -1.8cm;
            left: 0;
            right: 0;
            width: 100%;
            border-top: 1px solid #E1D3C1;
            padding-top: 10px;
            font-size: 9pt;
        }

        .summary {
            float: right;
            width: 260px;
            background: #F9F6F3;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #E1D3C1;
        }

        .summary p {
            margin: 5px 0;
            font-size: 10pt;
        }

        .total-val {
            font-size: 14pt;
            color: #8C846C;
            font-weight: bold;
        }

        .clear {
            clear: both;
        }

        .page-note {
            text-align: left;
            font-size: 8pt;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lydia Sena</h1>
        <p>Psicologia Clínica e Neuropsicologia</p>
    </div>

    <div class="title-box">
        <h2>
            Relatório de Atendimentos -
            {{ \Carbon\Carbon::create($year, $month, 1)->locale('pt_BR')->translatedFormat('F / Y') }}
        </h2>
    </div>

    <div class="meta">
        <strong>Mês/Ano:</strong> {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}<br>
        <strong>Emitido em:</strong> {{ now()->format('d/m/Y H:i') }}
    </div>

    @php
        $patientsComAtendimento = $patients->filter(function ($patient) {
            return $patient->total_mes > 0;
        });

        $faturamentoPrevisto = $patientsComAtendimento->sum('total_mes');
        $totalSessoes = $patientsComAtendimento->sum('total_sessoes_mes');
        $totalPendentes = $patientsComAtendimento->where('pendentes_no_mes', '>', 0)->count();
    @endphp

    @if($patientsComAtendimento->count() > 0)
        <table>
            <thead>
                <tr>
                    <th class="text-left">Paciente</th>
                    <th>Sessões</th>
                    <th>S1</th>
                    <th>S2</th>
                    <th>S3</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patientsComAtendimento as $patient)
                    <tr>
                        <td class="text-left">{{ $patient->name }}</td>
                        <td>{{ $patient->total_sessoes_mes }}</td>
                        <td>{{ $patient->s1_count }}x</td>
                        <td>{{ $patient->s2_count }}x</td>
                        <td>{{ $patient->s3_count }}x</td>
                        <td class="money">R$ {{ number_format($patient->total_mes, 2, ',', '.') }}</td>
                        <td class="{{ $patient->pendentes_no_mes == 0 ? 'status-pago' : 'status-pendente' }}">
                            {{ $patient->pendentes_no_mes == 0 ? 'PAGO' : 'PENDENTE' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-message">
            Nenhum atendimento encontrado para o período informado.
        </div>
    @endif

    <div class="footer">
        <div class="summary">
            <p><strong>Pacientes no mês:</strong> {{ $patientsComAtendimento->count() }}</p>
            <p><strong>Total de sessões:</strong> {{ $totalSessoes }}</p>
            <p><strong>Pacientes pendentes:</strong> {{ $totalPendentes }}</p>
            <p style="margin-top: 10px;">Faturamento Previsto:</p>
            <p class="total-val">R$ {{ number_format($faturamentoPrevisto, 2, ',', '.') }}</p>
            <p style="font-size: 8pt; margin-top: 10px;">
                Gerado em: {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>

        <div class="clear"></div>

        <div class="page-note">
            Documento gerado pelo Sistema Clínico Lydia Sena — Muzambinho/MG.
        </div>
    </div>
</body>
</html>