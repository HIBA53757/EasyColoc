<h1>Bonjour !</h1>
<p>Vous avez été invité à rejoindre la colocation : <strong>{{ $invitation->colocation->name }}</strong></p>
<p>Cliquez sur le lien ci-dessous pour accepter l'invitation :</p>

<a href="{{ route('invitation.accept', $invitation->token) }}" 
   style="background: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
    Accepter l'invitation
</a>

<p>Si vous ne souhaitez pas rejoindre, vous pouvez cliquer ici : 
   <a href="{{ route('invitation.refuse', $invitation->token) }}">Refuser</a>
</p>