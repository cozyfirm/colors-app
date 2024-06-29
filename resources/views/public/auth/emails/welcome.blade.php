@component('mail::message')
    ## The most important second app

    Dear <span style="color: #6A2C2C"> {{ $_username }} </span>,

    Your profile has been successfully created. Use this email ( {{ $_mail }} ) for further verifications. <br>

    Thank you for using our system.
    Have a nice rest of the day, <br><br>
    <a href="https://colors.ba"> Colors BA </a>
@endcomponent

