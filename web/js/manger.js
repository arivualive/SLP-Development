$(document).ready(function () {
    var app = {
        init: function () {

            // $($('#myModal-Manger #container-manger1')).show();//affiche la premiere
            $($(' #container-manger1')).show();
            $('#myModal-Manger .subComment').hide();//cacher les sous commentaires

            $('#myModal-Manger label').click('change', function () {

                var questionNumber = $(this).data('number');
                // console.log($(this).children('input'))
                $('#myModal-Manger #valider' + questionNumber).prop('disabled', false);
            });

            $('#myModal-Manger .valider').on('click', function () {
                var formId = $(this).data("formulid");
                var idSelected = $('#' + formId).find('.blur').attr("id");
                $('#' + formId + ' label').not($('#' + idSelected + '.blur')).css('display', 'none');

                var choiceId = $('#' + formId).find('.blur').data("choiceid");
                var questionNumber = $(this).data("number");
                $('#myModal-Manger #subComment' + choiceId ).css('display', 'block');


                $('#myModal-Manger #valider'+ questionNumber).css('display', 'none');
                $('#myModal-Manger #suivant'+ questionNumber).css('display', 'block');
                // $('#myModal-Manger .suivant_manger, .valider').css({'position': 'relative', 'top': '34px'});
            });

            $('#myModal-Manger .suivant_manger').on('click', function () {
                var questionNumber = $(this).data('number');
                var formulId = $(this).data('formulid');
                // console.log('#container-manger' + (questionNumber + 1));

                $.ajax({
                    method: $('#myModal-Manger #' + formulId).attr('method'),
                    url: $('#myModal-Manger #' + formulId).attr('action'),
                    data: $('#myModal-Manger #' + formulId).serialize(),
                    dataType: 'json',


                }).done(function (data) {
                    // console.log(data);
                }).fail(function (textmsg, errorThrown) {
                    // console.log(textmsg);
                    // console.log(errorThrown);
                });


                $('#myModal-Manger #container-manger' + questionNumber).hide();

                if ($('#myModal-Manger #container-manger' + questionNumber).next().hasClass('useless')) {
                    if ($('#myModal-Manger #container-manger' + questionNumber).next().next().hasClass('useless')) {
                        if ($('#myModal-Manger #container-manger' + questionNumber).next().next().next().hasClass('useless')) {
                            if ($('#myModal-Manger #container-manger' + questionNumber).next().next().next().next().hasClass('useless')) {
                                $('#myModal-Manger #container-manger' + questionNumber).next().next().next().next().next().show();
                            } else {
                                $('#myModal-Manger #container-manger' + questionNumber).next().next().next().next().show();
                            }
                        } else {
                            $('#myModal-Manger #container-manger' + questionNumber).next().next().next().show();
                        }
                    } else {
                        $('#myModal-Manger #container-manger' + questionNumber).next().next().show();
                    }
                } else {
                    $('#myModal-Manger #container-manger' + questionNumber).next().show();
                }
            })

            $('#myModal-Manger .uselessChoice').on('click', function () {
                var questionNumber = $(this).data('number');
                // console.log($('#myModal-Manger  .firstConditional' + questionNumber));
                // console.log($('#myModal-Manger #container-manger8').find('label'));
                if (questionNumber == 8) {
                    $('#myModal-Manger .firstConditional' + questionNumber).addClass('useless');
                    $('#myModal-Manger .secondConditional' + questionNumber).addClass('useless');
                    $('#myModal-Manger .firstConditional' + questionNumber).removeClass('useful'+questionNumber);
                } else if (questionNumber == 9) {
                    if ($('#myModal-Manger .firstConditional' + questionNumber).hasClass('useful8')) {
                    } else {
                        $('#myModal-Manger .firstConditional' + questionNumber).addClass('useless');
                        $('#myModal-Manger .secondConditional' + questionNumber).addClass('useless');
                    }
                } else {
                    $('#myModal-Manger .firstConditional' + questionNumber).addClass('useless');
                    $('#myModal-Manger .secondConditional' + questionNumber).addClass('useless');
                }
                });

            $('#myModal-Manger .showChoice1').on('click', function () {
                var questionNumber = $(this).data('number');
                // console.log($('#myModal-Manger .firstConditional' + questionNumber));
                if (questionNumber == 8) {
                    $('#myModal-Manger .secondConditional' + questionNumber).addClass('useless');
                    $('#myModal-Manger .firstConditional' + questionNumber).removeClass('useless');
                    $('#myModal-Manger .firstConditional' + questionNumber).addClass('useful'+questionNumber);
                } else {
                    $('#myModal-Manger .secondConditional' + questionNumber).addClass('useless');
                    $('#myModal-Manger .firstConditional' + questionNumber).removeClass('useless');
                }
            });

            $('#myModal-Manger .showChoice2').on('click', function () {
                var questionNumber = $(this).data('number');
                // console.log($('#myModal-Manger .showChoice1 .conditional' + questionNumber));

                $('#myModal-Manger .firstConditional' + questionNumber).addClass('useless');
                $('#myModal-Manger .secondConditional' + questionNumber).removeClass('useless');
            });

            $('#myModal-Manger label [type="radio"]').click('change', function (evt) {
                $(this).parent().siblings().removeClass('blur');
                $(this).parent().addClass('blur');
            });

            $('#myModal-Manger label [type="checkbox"]').click('change', function (evt) {
                if ($(this).parent().hasClass('blur')) {
                    $(this).parent().removeClass('blur');
                } else {
                    $(this).parent().addClass('blur');
                }
            });

            $('#myModal-Manger .dropdown-item').on('click', function () {
                var questionNumber = $(this).parent('.dropdown-menu').data('number');
                var nextNumber = $(this).data('dropdownnumber');
                $(this).parents('#container-manger' + questionNumber).hide();
                $('#myModal-Manger #container-manger' + nextNumber).show();
                console.log($(this).parent());
                console.log(questionNumber);
                console.log(nextNumber);


            });

            }//fin init function

    }//fin app
    $(app.init);
})