<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title> {{__('Login page')}} </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/icon.ico')}}"/>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <script src="https://kit.fontawesome.com/cdf2a0a58b.js"></script>

    <!-- Javascript scripts -->
    @vite(['resources/css/public/public.scss', 'resources/js/public/public.js'])
</head>
<body>
<div class="left_images">
    <img src="{{asset('files/images/colors.png')}}" alt="">
</div>

<div class="right_part">
    <div class="inside_div">

        <div class="div_with_message login-data">
            <h2> {{__('Welcome back')}} </h2>
            <p> {{__('Please enter your credentials and enjoy using Colors App! ')}} </p>
        </div>

        <!-- --------------------------------------------------------------------------------------------------- -->

        <div class="input_forms">
            <div class="single_input_col">
                <div class="single_input_label">
                    <p> {{__('Your username')}} </p>
                </div>
                <div class="single_input_input">
                    <input type="text" autocomplete="off" id="username" class="log-in-user-email">
                </div>
            </div>
            <div class="single_input_col single_input_col2">
                <div class="single_input_label">
                    <p> {{__('Password')}} </p>
                </div>
                <div class="single_input_input">
                    <input type="password" autocomplete="off" id="password">
                </div>
            </div>
        </div>

        <div class="checkbox">
            <div class="stay-signed">
                <input type="checkbox" name="set-cookies" id="set-cookies">
                <p class="pt-3"> {{__('Stay logged in')}} </p>
            </div>
            <div class="register-form-w register-form-n">
                <a href="">
                    <p class="pt-3">{{__('|')}}</p>
                </a>
            </div>
            <div class="register-form-w register-form-r" title="Kreirajte korisnički nalog">
                <a href="#">
                    <b><p class="pt-3">{{__('Create new account')}}</p></b>
                </a>
            </div>
        </div>

        <div class="buttons">
            <div class="button" id="sign-me-in">
                <p> {{__("Let's start")}} </p>
            </div>
            <div class="forgot_passw">
                <a href="#">
                    <p> {{__('Forgot password?')}} </p>
                </a>
            </div>
        </div>

        <div class="sign_link">
            <!-- <a href="#">
                Nemate još nalog? <span>Registrujte se</span>
            </a> -->
        </div>
    </div>
</div>
</body>
</html>
