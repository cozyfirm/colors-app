$(document).ready(function () {

    // /*
    //  *  Datepicker function -- just add class name as .datepicker
    //  */
    //
    // $(function () {
    //     $(".datepicker").datepicker({
    //         dateFormat: 'dd.mm.yy',
    //         changeMonth: true,
    //         changeYear: true,
    //         yearRange: "-100:+00"
    //     });
    // });
    //
    // /*
    //  * Select - 2 search dropdown
    //  */
    //
    // $('.select-2').select2();

    /**
     *
     * @param state
     * @returns {*|jQuery|HTMLElement}
     *
     * Search clubs by name
     */

    let searchClubsURI = '/api/open-api/teams/by-name';

    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        let baseUrl = "/files/core/clubs/";
        return $(
            '<span><img src="' + baseUrl + '/' + state.flag + '"  class="s2-img-flag" /> ' + state.text + '</span>'
        );
    }


    $(function () {
        $(".s2-search-clubs").select2({
            minimumInputLength: 2,
            templateResult: formatState, //this is for append country flag.
            ajax: {
                url: searchClubsURI,
                dataType: 'json',
                type: "POST",
                data: function (term) {
                    return {
                        term: term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name + ', ' + item.venue_rel.city + ' (' + item.gender + ')',
                                id: item.id,
                                flag: item.flag
                            }
                        })
                    };
                }

            }
        });
    });

});
