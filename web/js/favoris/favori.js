$(document).ready(function () {

    var step5 = $('.step5').hide();

    var data = $('.step4 img');

    $(data).click(function () {

        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');

        } else {
            $(this).addClass('selected');
        }
    });

    $("#valider").click(function () {

        var dataSelected = $('.step4 img.selected');
        dataSelected.serialize();

        var idSelected = [];
        $('.step4 img.selected').each(function(id, el){
            console.log($(this));
            idSelected.push($(this).data('image-id'));
        });
        console.log(JSON.stringify(idSelected));


        $.ajax({
            url:'/add_favoris',
            type: "POST",
            dataType: "json",
            data: {
                "dataJson": JSON.stringify(idSelected)
            },
            async: true,
            success: function (data)
            {
                console.log(data);
            }
        });


        $('.step4').hide();
        $('.step5').show();

    });

});