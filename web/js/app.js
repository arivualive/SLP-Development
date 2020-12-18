var app = {
    init: function () {

        // $('.suivant').prop('disabled', true);
        // $('.active').hide();
        $('.notif-end').hide();
        // $($('.socio.question-box')[0]).show();//sert a prendre la 1er question et a lafficher
        //$('.container').show(); //temporaire
        //$('.notif-end').show(); //temporaire
        $('.carousel-item.active').show();
        var img = $(".suivant").find('img');
        // $('#form7').find('.sub-answer-box').hide();

        if (lastquestionnumber > 0 && lastquestionnumber != 50) {
            for (n = 0;n < 50 ;n++) {
                if ($('#container'+n).data('questionnumber') == lastquestionnumber) {
                    $('#container'+n).next().show();//sert a reprendre le questionnaire la ou la personne c'est arreter
                    break;
                }
            }
        } else if (lastquestionnumber == 50) {
            $('.notif-end').show();

        } else {
            $($('.socio.question-box')[0]).show();//sert a prendre la 1er question et a lafficher
        }

        
        var isChoiceSelected = function (questionNumber) {
            //console.log(questionNumber);
            //console.log($('#container'+questionNumber).find('label'));
            if($('#container'+questionNumber).find('.label-div label').length > 0){

                //cas si les réponses sont des cases à cocher ou des boutons radio
                if($('#container'+questionNumber).find('label-div label').hasClass('selected')) {
                    return false;
                } else {
                    return true;
                }
            }
        };

        var isFieldFilled = function (questionNumber) {
            if($('#container'+questionNumber).find('input')/*.length > 0*/) {
                rempli=true;
                for(var i=0;i<$('#container'+questionNumber).find('input').length;i++) {
                    //console.log('input'+i+':');
                    if($('#container'+questionNumber).find('input')[i].value) {
                        value = $('#container'+questionNumber).find('input')[i].value;
                        //console.log('value is '+value);
                        rempli = false;
                    }
                }
                return rempli;
            }
        };
        
        //pour vérifier si il y a un champ vide, ne concerne que les input[type=text]
        //il peut y avoir au moins un champs texte
        var isFieldEmpty = function(questionNumber) {
            questionBox = $('.profil-page').find('#container'+questionNumber);
            inputs = questionBox.find('input[type=text]');
            var result = "noEmpty";
            // console.log(inputs);

            //s'il y a au moins un élément vide, on désactive le bouton
            $.each(inputs, function (index, input) {
                value = input.value.toString();
                if(value == "") {
                    result = "oneEmpty"
                }
            });
            if (result == "oneEmpty") {
                return true;
            } else {
                return false;
            }
        }

        var emptyField = function (masterid) {
            //result = parseInt(masterid)+1;
            //nbSubFree = parseInt(result);
            questionBox = $('.profil-page').find('#container'+masterid);
            input = questionBox.find('input[type=text]');
            input.val('');
            //$("input[name="+nbSubFree+"]").val('');
        };

        var deselectChoice = function(masterid) {
            questionBox = $('.profil-page').find('#container'+masterid);
            selectedChoices = questionBox.find('.selected');
            //console.log(selectedChoices);
            selectedChoices.removeClass("selected");
        }

        $(".themebtn, .validerTheme").on('click', function(evt){
            //affichage prochain container
            $(this).parents('.containerTheme').hide();
            if($(this).parents('.containerTheme').next().hasClass('useless')){
                $(this).parents('.containerTheme').next().next().show();    
            }else{
                $(this).parents('.containerTheme').next().show();
            }
        });

        $('.menuQuestion').click(function(e){
            questionNum = $(this).data('questionnumber');
            $(this).parents('.containerQuestion').hide();
            $('#container' + questionNum).show();
        });
        
        $(".themebtn").click(function(e){
            //affichage numéro question
            questionNumber = 0;
            if($(this).parents('.containerTheme').next().hasClass('useless')){
                questionNumber = $(this).parents('.containerTheme').next().next().data('questionnumber');
            }else{
                questionNumber = $(this).parents('.containerTheme').next().data('questionnumber');
            }
            themeid = $(this).data('themeid');
        });

        

        //les fonction sur suivant et précédent permettent de cacher la question en cour et d afficher la précédente/suivante.
        $('.suivant').on('click', function (evt) {
            var questionNumber = $(this).data('number');
            // console.log(lastquestionnumber);

            var questionthemenum = $(this).data('questionthemenumid');
            var themeid =  $(this).data('themeid');
            // console.log('question'+themeid+''+questionthemenum);
            $('.question'+themeid+''+questionthemenum).addClass('menuQuestion');
            $('.question'+themeid+''+questionthemenum).attr('src','/images/survey/menuNumero/' + questionthemenum + '-petit.jpg');
            $('.question'+themeid+''+questionthemenum).click(function(){
                $(this).parents('.containerQuestion').hide();
                $('.container'+themeid+''+questionthemenum).show();
            })
            //j'ai cliqué sur suivant donc je regarde pour la question suivante qui est le container+1
            $(this).parents('.containerQuestion').hide();
            if($(this).parents('.containerQuestion').next().hasClass('useless')){
                if ($(this).parents('.containerQuestion').next().next().hasClass('useless')) {
                    if ($(this).parents('.containerQuestion').next().next().next().hasClass('useless')) {
                        if ($(this).parents('.containerQuestion').next().next().next().next().hasClass('useless')) {
                            if ($(this).parents('.containerQuestion').next().next().next().next().next().hasClass('useless')) {
                                $(this).parents('.containerQuestion').next().next().next().next().next().next().show();
                            } else {
                                $(this).parents('.containerQuestion').next().next().next().next().next().show();
                            }
                        } else {
                            $(this).parents('.containerQuestion').next().next().next().next().show();
                        }
                    } else {
                        $(this).parents('.containerQuestion').next().next().next().show();
                    }
                }else {
                    $(this).parents('.containerQuestion').next().next().show();
                }
            }else{
                $(this).parents('.containerQuestion').next().show();
            }
            //console.log(questionNumber);
            //console.log($('#container' + numQuestionSuivante).find('input[type=radio]'));
        

            evt.preventDefault();
            //form1, form2

            $('#container' + questionNumber).hide();
            
            

            var formId = $(this).data('formid'); 

            //appel ajax
            $.ajax({
                method: $('#' + formId).attr('method'), //form1, form2
                url: $('#' + formId).attr('action'), //answer/save se trouve dans Questionbundle DefaultController
                data: $('#' + formId).serialize(),
                dataType: 'json',
            }).done(function (data) {
                //console.log(data);
            }).fail(function (textmsg, errorThrown) {
                //console.log(textmsg);
                //console.log(errorThrown);
            });

            // $('.suivant').prop('disabled',true);


        });

        $('.precedent').on('click', function (evt) {
            evt.preventDefault();
            var questionNumber = $(this).data('number');

            $('#container' + questionNumber).hide();

            $(this).parents('.containerQuestion').hide();
            if($(this).parents('.containerQuestion').prev().hasClass('useless')){
                if ($(this).parents('.containerQuestion').prev().prev().hasClass('useless')) {
                    if ($(this).parents('.containerQuestion').prev().prev().prev().hasClass('useless')) {
                        if ($(this).parents('.containerQuestion').prev().prev().prev().prev().hasClass('useless')) {
                            if ($(this).parents('.containerQuestion').prev().prev().prev().prev().prev().hasClass('useless')) {
                                $(this).parents('.containerQuestion').prev().prev().prev().prev().prev().prev().show();
                            } else {
                                $(this).parents('.containerQuestion').prev().prev().prev().prev().prev().show();
                            }
                        } else {
                            $(this).parents('.containerQuestion').prev().prev().prev().prev().show();
                        }
                    } else {
                        $(this).parents('.containerQuestion').prev().prev().prev().show();
                    }
                }else {
                    $(this).parents('.containerQuestion').prev().prev().show();
                }
            }else{
                $(this).parents('.containerQuestion').prev().show();
            }
            // $('.suivant').prop('disabled',false);
        });

        //permet d afficher ou non les questions conditionnelles
        $('.decisivechoice').change(function () {
            var triger = $(this).attr('name');
            var trigerId = triger.split('_')[0];
            // console.log(trigerId);
            if (!$(this).siblings('.normalchoice').is(':checked') && !$(this).siblings('.specialchoice').is(':checked')) {
                $($('.conditional' + trigerId)[0]).addClass('useless');
            }
            if (trigerId == 149) {
                var container = ['#container35', '#container36', '#container37']
                specialAddClassUseless(container);
            }
        });

        //permet d'afficher la question conditionnel suivante
        $('.normalchoice').change(function () {
            var triger = $(this).attr('name'); // n° de la question
            var trigerId = triger.split('_')[0];
            //console.log(trigerId);
            // console.log($(this).siblings('.normalchoice').is(':checked'))

            if (!$(this).is(':checked') && !$(this).siblings('.normalchoice').is(':checked') || $(this).siblings('.decisivechoice').is(':checked')) {
                $($('.conditional' + trigerId)[0]).addClass('useless');
            } else {
                $($('.conditional' + trigerId)[0]).removeClass('useless');
            }
            if (trigerId == 6) {
                var container = ['#container17', '#container18', '#container19', '#container20']
                if (!$(this).siblings('.normalchoice').is(':checked') && !$(this).is(':checked')) {
                    specialAddClassUseless(container);
                } else {
                    specialRemoveClassUseless(container);
                }
            } else if (trigerId == 149) {
                var container = ['#container35', '#container36', '#container37']
                specialRemoveClassUseless(container);
            }
        });

        //permet d'afficher la question conditionnel suivante
        $('.specialchoice').change(function () {
            var triger = $(this).attr('name'); // n° de la question
            var trigerId = triger.split('_')[0];
            // console.log(trigerId);

            if (trigerId == 6) {
                var container = ['#container17', '#container18', '#container19', '#container20']
                if ($(this).is(':checked') && !$(this).siblings('.normalchoice').is(':checked') || $(this).siblings('.specialchoice').is(':checked') && !$(this).siblings('.normalchoice').is(':checked')) {
                    specialAddClassUseless(container);
                } else {
                    specialRemoveClassUseless(container);
                }
            }
            if (!$(this).siblings('.normalchoice').is(':checked')) {
                $($('.conditional' + trigerId)[0]).addClass('useless');
            } else {
                $($('.conditional' + trigerId)[0]).removeClass('useless');
            }
        });



        // fonction pour terminer le formulaire 
        $('#termine').on('click', function (e) {
            e.preventDefault();
            var formId = $(this).data('formid');
            var userId = $(this).data('user');

            //appel ajax     
            $.ajax({
                method: $('#' + formId).attr('method'),
                url: $('#' + formId).attr('action'),
                data: $('#' + formId).serialize(),
                dataType: 'json',
            }).done(function (data) {
                //console.log(data);
            }).fail(function (textmsg, errorThrown) {
                //console.log(textmsg);
                //console.log(errorThrown);
            });
            $('.notif-end').show();
            if (this) {
                $('.question-box').hide();
            }
        })
        

        //permet de mettre un carré de séléction autour des images séléctionnés type checkbox--rodrigue//
        $('.checkbox-pict').change(function (evt) {
            var labelId = $(this).attr('id');
            var nameId = $(this).attr('name'); //id question
            var idquesion = $(this).data('masterid');
            var key = $(this).data('keychoice');
            console.log(idquesion)
            // console.log($(this).prev().siblings().hasClass('selected'));
            if ($('#label' + labelId).hasClass('selected')) {
                $('#label' + labelId).removeClass('selected')
                // console.log($(this).prev().find('img'));
                $(this).prev().find('img').attr('src',"/images/survey/"+$(this).prev().find('img').data('img'));
                $('#form' + idquesion +  ' .suivant').prop('disabled', isChoiceSelected(idquesion));
                // console.log($('#form' + idquesion +  ' .suivant'));
                if(!$(this).prev().siblings().hasClass('selected')){
                    $('#form' + idquesion +  ' .suivant').find('.desactive').show();
                    $('#form' + idquesion +  ' .suivant').find('.active').hide();
                }else{
                    $('#form' + idquesion +  ' .suivant').find('.desactive').hide();
                    $('#form' + idquesion +  ' .suivant').find('.active').show();
                }
                if(idquesion == 6){
                    $(this).parents('.containerQuestion').next().find('.subQuestion'+ key).hide();
                }
            } else {
                $('#form' + idquesion +  ' .suivant').prop('disabled', false);
                $('#label' + labelId).addClass('selected')
                $(this).prev().find('img').attr('src',"/images/survey/icones-selectionnes/"+$(this).prev().find('img').data('imgselected'));
                $('#form' + idquesion +  ' .suivant').find('.desactive').hide();
                $('#form' + idquesion +  ' .suivant').find('.active').show();

                //questionNumber = this.name;
                //console.log(questionNumber);
                //emptyField(questionNumber);
                if(idquesion == 6){
                    // console.log('subQuestion'+ key);
                    // console.log($(this).parents('.containerQuestion').next());
                    // console.log($(this).parents('.containerQuestion').next().find('.subQuestion'+ key));
                    $(this).parents('.containerQuestion').next().find('.subQuestion'+ key).show();
                }
            }
        })

        //permet de mettre un carré de séléction autour des images séléctionnés type radio--abdel//
        $('.label-div [type="radio"]').click('change', function (evt) {
            if ($(this).prev().data("imageselected") != undefined) {
                imageSelected = $(this).prev().data("imageselected");
            } else {
                imageSelected = $(this).prev().children().data("imgselected");
            }
            //new image selected
            // console.log(imageSelected);
            $(this).prev().find("img").attr("src","/images/survey/icones-selectionnes/"+imageSelected);
           
            $(this).prev().siblings("label").each(function(index){
                if ($(this).data("image") != undefined) {
                    thisImage = $(this).data("image");
                } else {
                    thisImage = $(this).children().data("img");
                }
                // console.log(thisImage);

                $(this).find("img").attr("src","/images/survey/"+thisImage)
            })
            var masterid = $(this).data('masterid');

            $('#form' + masterid +  ' .suivant').prop('disabled', false);
            // $('.suivant').prop('disabled', false);
            $('#form' + masterid +  ' .suivant').find('.desactive').hide();
            $('#form' + masterid +  ' .suivant').find('.active').show();
            

            //questionNumber = this.name;
            //console.log(questionNumber);
            emptyField(masterid);
        });

        $('select').on('change',function(evt){
            var masterid = $(this).data('masterid');
            // console.log(masterid);
            if ($(this).children('.normalchoice').is(':selected') && $(this).parents('.containerQuestion').next().hasClass('useless')){
                $(this).parents('.containerQuestion').next().removeClass('useless')
            } else if ($(this).children('.decisivechoice').is(':selected')) {
                $(this).parents('.containerQuestion').next().addClass('useless')
            }
            if (masterid == 143) {
                if ($(this).children('.normalchoice').is(':selected') && $(this).parents('.containerQuestion').next().hasClass('useless')){
                    $(this).parents('.containerQuestion').next().removeClass('useless')
                    $(this).parents('.containerQuestion').next().next().addClass('useless')
                    $(this).parents('.containerQuestion').next().next().next().addClass('useless')
                } else if ($(this).children('.specialchoice').is(':selected') && $(this).parents('.containerQuestion').next().next().hasClass('useless')){
                    $(this).parents('.containerQuestion').next().addClass('useless')
                    $(this).parents('.containerQuestion').next().next().removeClass('useless')
                    $(this).parents('.containerQuestion').next().next().next().addClass('useless')
                } else if ($(this).children('.secondchoice').is(':selected') && $(this).parents('.containerQuestion').next().next().next().hasClass('useless')){
                    $(this).parents('.containerQuestion').next().addClass('useless')
                    $(this).parents('.containerQuestion').next().next().addClass('useless')
                    $(this).parents('.containerQuestion').next().next().next().removeClass('useless')
                } else if ($(this).children('.decisivechoice').is(':selected')) {
                    $(this).parents('.containerQuestion').next().addClass('useless')
                    $(this).parents('.containerQuestion').next().next().addClass('useless')
                    $(this).parents('.containerQuestion').next().next().next().addClass('useless')
                }
            }
            $('#form' + masterid +  ' .suivant').find('.desactive').hide();
            $('#form' + masterid +  ' .suivant').find('.active').show();
            $('#form' + masterid +  ' .suivant').prop('disabled',false);
        })

        $('.free-answer-box [type="text"]').change(function(evt) {
            
            var masterid = $(this).data('masterid');
            var questionNumber = $(this).data('number');

            // console.log(masterid);
            //console.log(isChoiceSelected(questionNumber));
            $('#form' + masterid +  ' .suivant').prop('disabled',isFieldEmpty(questionNumber));
            
            if(isFieldEmpty(questionNumber)) { //vérifier si il y a un champ non rempli dans la question courante (pour le cas ou il y a plusieurs champs)
                $('#form' + masterid +  ' .suivant').find('.desactive').show();
                $('#form' + masterid +  ' .suivant').find('.active').hide();
                // console.log('test nul');
                // console.log(masterid);


            } else {
                $('#form' + masterid +  ' .suivant').find('.desactive').hide();
                $('#form' + masterid +  ' .suivant').find('.active').show();
                // console.log('test');
            }
            // console.log(isFieldEmpty(masterid));
        });

        $(".select-box").change(function(){
            id = $(this).data("masterid");
            selected = $(this).children("option:selected").val();
            masterid = 0;
            console.log(id);
            if($(this).hasClass("subfreeselect")){
                $(this).next().find('input').attr('value',selected);
                masterid = $("input#free"+id).data("masterid");
            } else {
                $("input#free"+id).val(selected);
                masterid = $("input#free"+id).data("masterid");
            }
            
            $('#form' + masterid +  ' .suivant').find('.desactive').hide();
            $('#form' + masterid +  ' .suivant').find('.active').show();
        })

        //affiche un message de transition POUR LA QUESTION CONDITIONNAL 28----abdel//
        var Q28 = $('.label-div input[id="choice86"]');
        var termine = $('#suivant28');
        $(termine).click(function (e) {
            e.preventDefault();
            var formId = $(this).data('formid');
            var userId = $(this).data('user');

            if ($(this) && Q28.is(':checked')) {
                $('.notif-end').show();
            }
            //appel ajax     
            $.ajax({
                method: $('#' + formId).attr('method'),
                url: $('#' + formId).attr('action'),
                data: $('#' + formId).serialize(),
                dataType: 'json',
            }).done(function (data) {
                //console.log(data);
            }).fail(function (textmsg, errorThrown) {
                //console.log(textmsg);
                //console.log(errorThrown);
            });
        });
        
        $(".menuTheme").click(function(e){
            var themeId = $(this).data("themeid");
            $(this).parents('.containerQuestion').hide();
            $('.containerTheme' + themeId).show();
        });


        function specialAddClassUseless(container) {
            for (var i = 0; i < container.length; i++) {
                $(container[i]).addClass('useless');
            }
        }
        function specialRemoveClassUseless(container) {
            for (var i = 0; i < container.length; i++) {
                $(container[i]).removeClass('useless');
            }
        }
    }

}
$(app.init);