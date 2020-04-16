@component('mail::message')
# Nova empresa cadastrada

Mais informações:<br>

<strong>Razão Social</strong>: {{ $razao }}<br>
<strong>CNPJ</strong>: {{ $cnpj }}<br>
<strong>Nome do responsável</strong>: {{ $userName }}<br>
<strong>E-mail do responsável</strong>: {{ $userEmail }}<br>
<strong>Data/hora</strong>: {{ $date }}<br>

@endcomponent