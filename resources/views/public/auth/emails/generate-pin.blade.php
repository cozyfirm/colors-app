@component('mail::message')
## Restart password

Dear <span style="color: #6A2C2C"> {{ $_username }} </span>,

New PIN for password restart is given as:

<div style="width: 100%; text-align: center; background: #F6F6F6;">
    <h2 style="width: 100%; text-align: center; margin-top:10px; margin-bottom: 10px;"> {{ $_pin }} </h2>
</div>

In case you did not requested this action, please contact our admins for further actions. <br>

Thank you for using our system.
Have a nice rest of the day, <br><br>
<a href="{{ env('APP_DOMAIN') }}"> {{ env('APP_NAME') }} </a>
@endcomponent

