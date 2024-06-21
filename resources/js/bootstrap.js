import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import jQuery from 'jquery';
import $ from 'jquery';
window.$ = jQuery;

import select2 from 'select2';
select2();

$(".select-2").select2();


import datepickerFactory from 'jquery-datepicker';
// import datepickerJAFactory from 'jquery-datepicker/i18n/jquery.ui.datepicker-ja';

datepickerFactory($);
// datepickerJAFactory($);

$(function() {
    $('.datepicker').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2050",
        dateFormat: 'dd.mm.yy'
    });
});
