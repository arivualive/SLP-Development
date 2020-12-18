$(document).ready(function () {
    var app = {
        init: function () {

            $($('.container-fluid')).show();//affiche la premiere

            $('.subComment').hide();
            //affichage des sous commentaires
            $(".valider").click(function () {
                var formId = $(this).data("formulid");
                console.log($('#' + formId).find('.blur').attr("id"));
                var idSelected = $('#' + formId).find('.blur').attr("id"); 
                $('#' + formId + ' #subComment' + idSelected).css('display', 'block');
                console.log($('#' + formId + ' label').not($('#' + idSelected + '.blur')));
                $('#' + formId + ' label').not($('#' + idSelected + '.blur')).css('display', 'none');
                $('.valider').css('display', 'none');
                $('.suivant_manger').css('display', 'block');
                $('.suivant_manger, .valider').css({'position': 'relative', 'top': '34px'});
            });



            $('.suivant_manger').on('click', function (evt) {
                evt.preventDefault();
                var questionNumero = $(this).data('numero');
                var formulId = $(this).data('formulid');


                $('#mon-blok' + questionNumero).hide();
                $('#mon-blok' + (questionNumero + 1)).show();


                $.ajax({
                    method: $('#' + formulId).attr('method'),
                    url: $('#' + formulId).attr('action'),
                    data: $('#' + formulId).serialize(),
                    dataType: 'json',


                }).done(function (data) {
                    console.log(data);
                }).fail(function (textmsg, errorThrown) {
                    console.log(textmsg);
                    console.log(errorThrown);
                });
                $(document).ajaxStop(function () {
                });
                
                $(".valider").prop('disabled', true);//permet de désactiver le button valider apres l'appel ajax
                $('.valider').css('display', 'block');
                $('.suivant_manger').css('display', 'none');
            });
            //verifie si checker et active le bouton valider
            $('.valider').prop('disabled', true);
            $(document).on('click', 'label', function () {
                $('input:radio').click(function () {
                    if ($(this).is(':checked')) {
                        $('.valider').prop('disabled', false);
                    } else {
                        $('.valider').prop('disabled', true);
                    }
                });
            });


            /*function to Display background-color => white when click on label input */
            $(function () {
                $('label').click('change', function () {
                    $('label').removeClass('blur');
                    $(this).addClass('blur');
                });
            });


        }//fin init function

    }//fin app
    $(app.init);
})