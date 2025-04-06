$(document).ready(function () {

    let activeClubsUri = '/admin/core/clubs/active-status';

    $(".active-club-checkbox").change(function (){
        let value = $(this).is(":checked");
        let id = $(this).attr('id');
        let $this = $(this);

        $.ajax({
            url: activeClubsUri,
            method: "post",
            dataType: "json",
            data: { id:id,  value: value},
            success: function success(response) {
                let code = response['code'];

                if(code === '0000'){
                    if(value === true){
                        $this.parent().find("p").text('Da');
                    }else{
                        $this.parent().find("p").text('Ne');
                    }
                }
            }
        });
    })
});
