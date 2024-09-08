@component('mail::message')
## The most important second app

Dear <span style="color: #6A2C2C"> {{ $_username }} </span>,

Your profile has been successfully created. <br>

To verify your email, please use <a href="{{ route('api.auth.verify-an-email', ['username' => $_username, 'api_token' => $_api_token]) }}">this link</a>.

Thank you for using our system.
Have a nice rest of the day, <br><br>
<a href="{{ env('APP_DOMAIN') }}"> Colors BA </a>
@endcomponent

