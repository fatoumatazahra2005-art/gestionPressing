<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; background: #EEF3F6; padding: 24px; margin: 0;">
<div style="max-width: 480px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 28px;">

    <h2 style="color: #4F7D6C; margin-top: 0;">Votre commande est prête !</h2>

    <p style="color: #333; line-height: 1.5;">
        Bonjour {{ $ticket->client->name }},<br><br>
        Votre commande <strong>n° {{ $ticket->id }}</strong> est prête à être récupérée.
        Vous trouverez votre reçu en pièce jointe.
    </p>

    <p style="color: #5B6B7A; font-size: 13px;">
        Montant à régler au retrait : <strong>{{ number_format($ticket->montant_total, 0, ',', ' ') }} FCFA</strong>
        (paiement en espèces).
    </p>

</div>
</body>
</html>
