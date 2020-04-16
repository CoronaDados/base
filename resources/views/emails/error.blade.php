@component('mail::message')
# Erro no sistema

Mais informações:<br>

<strong>Nome do usuário</strong>: {{ $name ?? 'Não autenticado' }}<br>
<strong>E-mail do usuário</strong>: {{ $email }}<br>
<strong>Data/hora</strong>: {{ $date }}<br>
<strong>Arquivo</strong>: {{ $file }}<br>
<strong>Linha</strong>: {{ $line }}<br>
<strong>Mensagem de erro</strong>: {!! $message !!}

@endcomponent            