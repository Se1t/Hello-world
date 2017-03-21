/**
 * Created by Avreliy on 02.03.2017.
 */

$(document).ready(function() {

    $('.likes').on('click',function(){

        var url = ($(this).attr('href'));

        var that = $(this);

        var id = getURLParameter(url, 'id');

        var request = $.ajax({
            url: "addLike.php",
            method: "GET",
            //dataTypes: 'json',
            data: {'id': id, 'jsReq' : 'yes'}

    });

        function getURLParameter(url, name) {
            return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
        }

        
        request.done(function(data) {

            console.log(data);



            that.text("Лайков: "+data);


        });
        

        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });
        return false;
    });

    return false;

});
