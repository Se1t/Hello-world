/**
 * Created by Avreliy on 01.03.2017.
 */

$(document).ready(function() {

    var limit = 0;


    $('.showMoreButton').click(function(){

        limit = limit + 5;

        var request = $.ajax({
            url: "showMore.php",
            method: "POST",
            dataTypes: 'json',
            data: { "limit" : limit }
        });


        request.done(function(html) {

            $('.loading').remove();
            $('.noMoreRec').remove();

            //res = JSON.parse(html);
            //var json = $.parseJSON(response);

            //console.log(html);
            //console.log(json);

            $('.buttonMore').before(html);

            $('.buttonMore').before('<div class="loading"><i class="fa-li fa fa-spinner fa-spin fa-3x"></i></div>');



            $('.loading').fadeIn('200');
            $('.noMoreRec').fadeIn('200');
            $('.loading').fadeOut('600');
            $('.noMoreRec').fadeOut('600');

        });

        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });

        return false;
    });

    return false;

});
