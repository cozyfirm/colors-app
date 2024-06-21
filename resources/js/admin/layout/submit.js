import { Notify } from './notify.ts';
import { Validator } from "./validator.ts";

$(document).ready(function (){

    let mainUri = '/requests';

    /*
     *  Get csrf_token from header
     */

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    //     let checkData = function (key, value, className = ''){
    // $(this).attr('type'), $(this).val(), $(this).attr('class')
    let checkData = function ($this){
        let status = {
            'code' : '0000',
            'message' : ''
        };

        if($this.attr('type') === 'email'){
            if(!Validator.email($this.val())){
                status['code'] = '6001';
                status['message'] = 'Email nije validan. Molimo unesite ponovo!';
            }
        }else if(($this.attr('type') === 'text' || $this.attr('type') === 'number' || $this.attr('type') === 'password') || ($this.is("select"))){
            /*
             *  Check all class names and check for their validations
             */

            let className = ((typeof $this.attr('class') !== "undefined") ? $this.attr('class') : '').split(' '); // Default class name

            for(let i=0; i<className.length; i++){
                if(className[i] === 'phone'){

                }else if(className[i] === 'datepicker'){
                    if(!Validator.date($this.val())){
                        status['code'] = '6003';
                        status['message'] = 'Datum nije validan. Molimo pokušajte ponovo!';
                    }
                }

                /*
                 *  Check for required fields
                 */

                if(className[i] === 'required'){
                    if($this.val() === ''){
                        status['code'] = '6004';
                        status['message'] = 'Polje "' + $this.parent().find('label').text() + '" ne može biti prazno!';
                    }
                }
            }
        }else{
            // TODO - Last case
        }

        return status;
    };

    /*
     *  All form with this feature should have ID of #js-form
     */
    $("#js-form").submit(function (e){
        e.preventDefault(); // Prevent from default

        let data = {}, names = [], counter = 0, foundN = false, send = true, status = [];

        /*
        * Method and action
        */
        let method = $(this).attr('method');
        let action = $(this).attr('action');

        $(this).find('input, select, textarea').each(function (){

            for(let i=0; i<names.length; i++){ if(names[i] === $(this).attr('name')) foundN = true; }

            if(!foundN){
                let element = $("[name = '" + $(this).attr('name') + "']");

                if(element.length > 1){
                    for(let i=0; i<names.length; i++){
                        if(names[i] === $(this).attr('name')) foundN = true;
                    }
                    if(!foundN){
                        names.push($(this).attr('name'));
                    }

                    let newData = [];
                    let name = $(this).attr('name').replace('[]', '');
                    let checkBox = ($(this).attr('type') === 'checkbox');

                    element.each(function (){
                        if(checkBox){
                            if($(this).is(":checked")) newData.push($(this).val());
                        }else{
                            newData.push($(this).val());

                            status.push(checkData($(this)));
                        }
                    });

                    data[name] = newData;
                }else {
                    let checkBox = ($(this).attr('type') === 'checkbox');

                    if(checkBox){
                        if(element.is(":checked")) data[$(this).attr('name')] = $(this).val();
                    }else{
                        data[$(this).attr('name')] = $(this).val();

                        /*
                         *  Check if data is valid
                         */
                        status.push(checkData($(this)));
                    }
                }
            }

            foundN = false;
        });

        for(let i=0; i<status.length; i++){
            if(status[i]['code'] !== '0000'){
                Notify.Me([status[i]['message'], "warn"]);
                send = false;
            }
        }

        if(send){
            if($(this).hasClass('js-form-php-submit')){
                /* This is used for form submitting */
                let submitThisForm = true;

                $(".required-file").each(function () {
                    if(!$(this).val()){
                        Notify.Me([$(this).attr('title'), "warn"]);
                        submitThisForm = false;
                    }
                });

                if(submitThisForm) e.currentTarget.submit();
            }else{
                /*
                * Attach api-token to request as parameter
                */
                data['api_token'] = $('meta[name=api-token]').attr('content');
                // data['api_uri']    = action; // URL for making requests
                // data['api_method'] = method; // Request method : POST, PUT, DELETE

                /*
                 * Trigger loading cover
                 */
                $(".loading-gif").removeClass('d-none');

                $.ajax({
                    url: action,
                    method: method,
                    dataType: "json",
                    data: data,
                    success: function success(response) {
                        $(".loading").fadeOut();

                        let code = response['code'];

                        if(code === '0000'){
                            if(typeof response['message'] !== 'undefined') Notify.Me([response['message'], "success"]);

                            setTimeout(function (){
                                if(typeof response['url'] !== 'undefined') window.location = response['url'];
                            }, 2000);
                        }else{
                            Notify.Me([response['message'], "warn"]);
                        }
                        // console.log(response, typeof response['link']);
                        $(".loading-gif").addClass('d-none');
                    }
                });
            }
        }
    });

    // ------------------------------------------------------------------------------------------------------------- //
    /*
     *  Fit inputs to satisfy form; except it is defined to stay in full width
     *  If it has class property of "force", than skipp it !!
     */

    let checkStructure = function (){

        let objects = $(".form-data-row").find('.col-md-6, .col-md-12');
        let total   = objects.length;
        let totalForced = $(".form-data-row").find('.forced, .d-none').length;
        let cForced     = 0;

        objects.each(function (index){
            if($(this).hasClass('force') || $(this).hasClass('d-none')){
                cForced++;
            }else{
                let classes  = $(this).attr('class');
                let classArr = classes.split(' ');
                let newClass = '';

                for(let i=0; i<classArr.length; i++){
                    if(classArr[i] === 'col-md-6' || classArr[i] === 'col-md-12'){
                        classArr[i] = 'col-md-6';
                    }
                    if((index === (total - 1) && (total - totalForced) %2 !== 0) || (index === (total - totalForced) && (total - totalForced) %2 !== 0) ){
                        if(classArr[i] === 'col-md-6' || classArr[i] === 'col-md-12'){
                            classArr[i] = 'col-md-12';
                        }
                    }else{
                        // console.log(index, total - totalForced);
                    }

                    newClass += (classArr[i] + ' ');
                }

                $(this).attr('class', newClass);

                // console.log(newClass);
            }
        });

        // console.log("Total: " + total + ', totalForced: ' + totalForced);
    }

    checkStructure();

    // ------------------------------------------------------------------------------------------------------------- //
    /*
     *  When class 'trigger-none' is given to html entity, it would trigger action for displaying or hiding form at all
     *  Also, it takes couple of more attributes:
     *
     *      - tr-value : Defines value, when picked element with class 'r-name' would be hidden
     *      - r-name : class name of entity that would be set as display="block / none"
     *
     *  Class name of manipulating entity must match attribute r-name, and has to be unique !
     */

    $("body").on('change', '.trigger-none', function (){
        let value   = $(this).val();

        $("." + $(this).attr('tr-name')).each(function (){
            let trValue = $(this).attr('tr-value');
            let $this = $(this);

            if(trValue.includes(',')){
                let values = trValue.split(',');

                for(let i=0; i<values.length; i++){
                    if(value === values[i]){
                        $this.removeClass('d-none');

                        return true;
                    }else $this.addClass('d-none');
                }
            }else{
                if(value === trValue){
                    $(this).removeClass('d-none');
                }else $(this).addClass('d-none');
            }
        });

        // if(value === trValue){
        //     $("." + $(this).attr('tr-name')).removeClass('d-none');
        // }else{
        //     $("." + $(this).attr('tr-name')).addClass('d-none');
        // }

        checkStructure();
    });


    /*
     *  For file uploads. When user pick some document, it will display the name of file here !
     */
    $(".required-file, .non-required-file").change(function(){
        $(this).parent().find('.real-file-name').text($(this).val().split('\\').pop());
    });

    /*
     *  Submit form with data for upload form
     */
    $(".upload-files").change(function () {
        $("#file-form").submit();
    });
});
