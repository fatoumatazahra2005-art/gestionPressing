<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Commande annulée</title>
</head>

<body>

<h2>Bonjour {{ $ticket->client->name }},</h2>

<p>
    Nous vous informons que votre commande
    <strong>#{{ $ticket->id }}</strong>
    a été annulée.
</p>

<p>
    Nous serons pas disponible en ce moment.
</p>

<p>
    Merci d'avoir choisi notre pressing.
</p>

</body>
</html>
