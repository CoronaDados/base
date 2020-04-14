@component('mail::message')
# <h1>Olá!</h1>

<p>Verificamos que você realizou o cadastro no sistema {{ config('app.name') }}.</p>

<p>Por favor, para confirmar seu cadastro é necessário clicar no botão abaixo.</p>

@component('mail::button', ['url' => $actionUrl, 'color' => 'success'])
{{ $actionText }}
@endcomponent

Ao clicar você nos permite enviar informações por e-mail e assim você terá acesso as funcionalidades do sistema.
@endcomponent
