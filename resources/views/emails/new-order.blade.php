<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background: #EEF3F6; padding: 24px; margin: 0;">
<div style="max-width: 480px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 28px;">

    <h2 style="color: #1B2430; margin-top: 0;">Nouvelle commande déposée</h2>

    <p style="color: #333; line-height: 1.5;">
        Le ticket <strong>n° {{ $ticket->id }}</strong> vient d'être déposé par
        <strong>{{ $ticket->client->name }}</strong> ({{ $ticket->client->email }}).
    </p>

    <table style="width: 100%; border-collapse: collapse; margin: 16px 0;">
        @foreach ($ticket->ticketServices as $ligne)
            <tr>
                <td style="padding: 6px 0; color: #333;">{{ $ligne->service->libelle }} × {{ $ligne->quantite }}</td>
                <td style="padding: 6px 0; text-align: right; color: #5B6B7A;">{{ number_format($ligne->sousTotal(), 0, ',', ' ') }} FCFA</td>
            </tr>
        @endforeach
    </table>

    <p style="border-top: 1px dashed #D6DEE3; padding-top: 12px; font-weight: bold; color: #1B2430;">
        Total : {{ number_format($ticket->montant_total, 0, ',', ' ') }} FCFA
    </p>

</div>
</body>
</html>
