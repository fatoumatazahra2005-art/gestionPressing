<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1B2430; padding: 20px; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        .sub { color: #5B6B7A; font-size: 12px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        td { padding: 6px 0; font-size: 13px; }
        .total-row td { border-top: 1px dashed #D6DEE3; font-weight: bold; padding-top: 10px; }
        .right { text-align: right; }
    </style>
</head>
<body>

<h1>LIC Pressing — Reçu</h1>
<p class="sub">Ticket n° {{ $ticket->id }} — Déposé le {{ $ticket->date_depot->format('d/m/Y à H:i') }}</p>

<p><strong>Client :</strong> {{ $ticket->client->name }}</p>

<table>
    @foreach ($ticket->ticketServices as $ligne)
        <tr>
            <td>{{ $ligne->service->libelle }} × {{ $ligne->quantite }}</td>
            <td class="right">{{ number_format($ligne->sousTotal(), 0, ',', ' ') }} FCFA</td>
        </tr>
    @endforeach
    <tr class="total-row">
        <td>Total</td>
        <td class="right">{{ number_format($ticket->montant_total, 0, ',', ' ') }} FCFA</td>
    </tr>
</table>

<p class="sub" style="margin-top: 24px;">Merci de votre confiance.</p>

</body>
</html>
