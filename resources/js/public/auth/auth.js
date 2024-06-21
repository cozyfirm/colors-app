import {Notify} from "../../admin/layout/notify.ts";

$( document ).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let url = '/auth/authenticate', data = {};

    let signMeIn = function(){
        $.ajax({
            url: url,
            method: 'POST',
            dataType: "json",
            data: data,
            success: function success(response) {
                // response = JSON.parse(response);
                if(response['code'] === '0000'){
                    window.location = response['data']['uri'];
                }else{
                    Notify.Me([response['message'], "warn"]);
                }
                console.log(response);
            }
        });
    };

    /*
     *  Read parameters from input forms
     */
    let login = function(){
        data.username = $("#username").val();
        data.password = $("#password").val();
        data.cookie = $('#set-cookies').is(':checked');

        signMeIn();
    };
    let resetPasswordEmail = function(){
        data.email = $("#email").val();
        url = '/reset-password/send-email';
        signMeIn();
    };
    let newPassword = function(){
        url = '/reset-password/update-password';
        data.password = $("#password").val();
        data.password_again = $("#password_again").val();
        data.email = $("#email").val();

        signMeIn();
    };

    $("#sign-me-in").click(function () { login(); });
    $("#reset-password-btn").click(function () { resetPasswordEmail(); });
    $("#log-in-change-user-password").click(function () { newPassword(); });

    $(document).on('keypress',function(e) {
        if(e.which === 13) {
            if($(".log-in-user-email").length) { login(); }
            if($(".log-in-reset-psw-email").length) { resetPasswordEmail(); }
            if($(".log-in-change-password").length) { newPassword(); }
        }
    });


});


// $(function () {
//     $("#change_pass").click(function () {
//         let password = $("#password_new").val();
//         let confirmPassword = $("#password_new_again").val();
//         if (password.length === 0){
//             $.notify("Unesite novu šifru", "warn");
//             return false;
//         }
//         console.log(password,confirmPassword);
//         if (password !== confirmPassword) {
//             $.notify("Šifre se ne podudaraju", "warn");
//             return false;
//         }
//         return true;
//     });
// });
