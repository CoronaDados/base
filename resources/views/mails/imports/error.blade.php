@component('mail::message')
# Erro na importação

Ocorreu erro na importação realizada por:<br>

<strong>Nome do usuário</strong>: {{ $name }}<br>
<strong>E-mail do usuário</strong>: {{ $email }}<br>
<strong>Mensagem de erro</strong>: {!! $message !!}

@endcomponent            