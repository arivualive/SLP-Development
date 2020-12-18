$(document).ready(function(){

    var app = {
        init: function () {

            // ******************************************** PRÉ-REMPLISSAGE DES CASES À COCHER **************************************
            // ************************************* SELON PRODUIT SÉLECTIONNÉ DANS LISTE DÉROULANTE ********************************

            $('.product').change(function(){
        
                var data = {};
                // on recherche l'id du produit qui vient d'être sélectionné, qui se trouve dans l'attr value
                var productId = $('.display.product-list').find('.product').val();
                
                data.productId = productId;

                $.ajax({
                    url: '/ajax_find_product',
                    async: true,
                    type: 'POST',
                    dataType: 'json',
                    data: data,
                    success: function(data){
                        if(data){
                            var productData = JSON.parse(data);

                            // pour chaque clef de productData, rechercher le label correspondant dans la vue

                            $.each(productData, function(key, value){
                                var arr = $.isArray(value);
                                if (arr){  // si la valeur est un array (exemples : bulk et detail)
                                    $.each(value, function(index, prop){
                                        var id = prop.id;
                                        $('#' + key + id).attr('checked', true); // on passe en checked le bouton radio
                                    })      
                                }else{ // si la valeur est une collection d'objets
                                    var id = value.id;
                                    $('#' + key + id).attr('checked', true); // on passe en checked la case à cocher
                                }
                            })
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(xhr, textStatus, errorThrown);
                    },
                });
            });

            // ************************************************ MENU LATERAL OU HORIZONTAL ******************************************
            // **********************************************************************************************************************

            // À AMÉLIORER DÈS QUE POSSIBLE *****************************************************************************************
            // à mettre en dynamique avec des ('.group').first() et des VALEURS pour catégorie et sous-catégorie dans les src *******
            // 1re catégorie et 1ere sous-catégorie actives par défaut +++ titre vert correspondant *********************************

            $('#boisson').attr('src', '/images/dashboard/budget/menu-vertical-eating/boisson-selectionne.png'); // catégorie "boissons" en sélectionné
            $('#group-boisson').find('.invisible').removeClass('invisible').addClass('display'); // "boissons" et ses sous-catégories en visible
            $('#eau').attr('src', '/images/dashboard/budget/menu-vertical-eating/eau-selectionne.png'); // sous-catégorie "eau" en sélectionné
            // afficher le bon menu déroulant pour les produits :
            $('.product-list').each(function(){
              if ( $(this).attr('id') == 'Eau' ) {
                $(this).removeClass('invisible').addClass('display');
              }
            });
            
            let isOpen = false;

            // VERSION ECRAN
            // au clic sur une catégorie l'image doit passer en sélectionné
            $('.ecran .big-image').on({
                'click': function() {
                    var attr = $(this).attr('src'); // on récupère l'adresse de l'image dans une variable
                    var category = $(this).data("category"); // on récupère la variable twig category.image
                    var categoryName = $(this).data("catname"); // on récupère la variable twig category.name
                    isOpen = true;
                    // For some browsers, 'attr' is undefined; for others, 'attr' is false. Check for both.
                    if (/* typeof attr !== typeof undefined && attr !== false */ $(this).length && attr === `/images/dashboard/budget/menu-vertical-eating/${category}.png`) {
                        if(isOpen){
                            $('.ecran .group').each(function(){
                            let category = $(this).find('img').data('category'); // on cherche le total des images dans la même catégorie
                            // on passe la catégorie en non sélectionné
                            $(this).find('.big-image').attr('src', `/images/dashboard/budget/menu-vertical-eating/${category}.png`);
                            $(this).find('.small').removeClass('display').addClass('invisible'); // et on ferme les sous-catégories correspondantes
                            })
                        isOpen = false;
                        }
                        // ici l'image de la catégories passe en sélectionné :
                        $(this).attr('src', `/images/dashboard/budget/menu-vertical-eating/${category}-selectionne.png`);
                        // on prend les autres images du groupe et on les passe en visible
                        let group = $(`.ecran #group-${category}`).find('.invisible').removeClass('invisible').addClass('display');
                        // s'il ya des sous-catégorie, la première passe en sélectionné :
                        let firstSubcat = $(group).find('.small-image').first().data('subcategory');
                        let firstSubcatName = $(group).find('.small-image').first().data('subcatname');
                        $(group).find('.small-image').first().attr('src', `/images/dashboard/budget/menu-vertical-eating/${firstSubcat}-selectionne.png`);
                        $('.first-form-title').text(`${firstSubcatName}`); // le titre vert à gauche prend en texte le nom de catégorie
                        $('.product-list').each(function(){
                            if ( $(this).attr('id') == `${firstSubcatName}` ) {
                                $(this).removeClass('invisible').addClass('display'); // afficher le bon menu déroulant pour les produits
                            } else {
                                $(this).removeClass('display').addClass('invisible');
                            }
                        });
                        // si pas de sous-catégorie (exemple Tabac / Alcool) :
                        if (!firstSubcat) {
                            $('.first-form-title').text(`${categoryName}`); // le titre vert à gauche prend en texte le nom de catégorie
                            $('.product-list').each(function(){
                                if ( $(this).attr('id') == `${categoryName}` ) {
                                $(this).removeClass('invisible').addClass('display'); // afficher le bon menu déroulant pour les produits
                                } else {
                                $(this).removeClass('display').addClass('invisible');
                                }
                            });
                        }
                    }
                }
            });

        // au clic sur une sous-catégorie l'image doit passer en sélectionné :
        $('.ecran .small-image').on({
            'click': function() {
                var attr = $(this).attr('src'); // on récupère l'adresse de l'image dans une variable
                var category = $(this).data('category'); // on récupère la variable twig category.image
                var subcategory = $(this).data('subcategory'); // on récupère la variable twig subcategory.image
                var subcatName = $(this).data('subcatname'); // on récupère la variable twig subcategory.name
                isOpen = true;
                // For some browsers, 'attr' is undefined; for others, 'attr' is false. Check for both.
                if (/* typeof attr !== typeof undefined && attr !== false */ $(this).length && attr === `/images/dashboard/budget/menu-vertical-eating/${subcategory}.png`) {
                    if(isOpen){
                        $('.ecran .group').each(function(){
                            $('.first-form-title').text(`${subcatName}`); // le titre vert à gauche prend en texte le nom de la sous-catégorie
                            $('.product-list').each(function(){
                                if ( $(this).attr('id') == `${subcatName}` ) {
                                    $(this).removeClass('invisible').addClass('display'); // afficher le bon menu déroulant pour les produits
                                } else {
                                $(this).removeClass('display').addClass('invisible'); // cacher les autre menus
                                }
                            });
                            // on passe la sous-catégorie cliquée en sélectionné :
                            $(this).attr('src', `/images/dashboard/budget/menu-vertical-eating/${subcategory}-selectionne.png`);
                            var group = $(`.ecran #group-${category}`).find('.small-image'); // trouver toutes les sous-catégories du groupe
                            // les autres sous-catégories du même groupe passent en non sélectionné :
                            $(group).each(function(){
                                var subcategory = $(this).data('subcategory'); // on récupère la variable twig subcategory.image
                                $(this).attr('src', `/images/dashboard/budget/menu-vertical-eating/${subcategory}.png`);
                            })
                        })
                        isOpen = false;
                    }
                    // ici l'image de la sous-catégorie passe en sélectionné :
                    $(this).attr('src', `/images/dashboard/budget/menu-vertical-eating/${subcategory}-selectionne.png`);
                }
            }
        });

            // VERSION MOBILE
            // au clic sur une catégorie l'image doit passer en sélectionné
            $('.mobile-eating .big-image').on({
                'click': function() {
                    //On met la 'big-image' a la premiere place
                    if ($(this).parents('.group').hasClass('active2')) {
                        var active = $(this).parents('.group');
                        active.siblings('.active1').removeClass('active1').addClass('invisible');
                        active.removeClass('active2').addClass('active1');
                        active.siblings('.active3').removeClass('active3').addClass('active2');

                        if (active.siblings('.active2').next().hasClass('group')) {
                            active.siblings('.active2').next().addClass('active3').removeClass('invisible')
                        } else {
                            active.parent().children().first().addClass('active3').removeClass('invisible');
                        }
                    } else if ($(this).parents('.group').hasClass('active3')) {
                        var active = $(this).parents('.group');
                        active.siblings('.active1').removeClass('active1').addClass('invisible');
                        active.siblings('.active2').removeClass('active2').addClass('invisible');
                        active.removeClass('active3').addClass('active1');

                        if (active.next().hasClass('group')) {
                            active.next().addClass('active2').removeClass('invisible');
                            if (active.next().next().hasClass('group')) {
                                active.next().next().addClass('active3').removeClass('invisible');
                            } else {
                                active.parent().children().first().addClass('active3').removeClass('invisible');
                            }
                        } else {
                            active.parent().children().first().addClass('active2').removeClass('invisible');
                            active.parent().children().first().next().addClass('active3').removeClass('invisible');
                        }
                    }

                    var attr = $(this).attr('src'); // on récupère l'adresse de l'image dans une variable
                    var category = $(this).data("category"); // on récupère la variable twig category.image
                    var categoryName = $(this).data("catname"); // on récupère la variable twig category.name

                    if (!$(this).hasClass('list-open')) {
                        testOpen();
                        //On defini la list comme ouverte
                        $(this).addClass('list-open');

                        isOpen = true;
                        // For some browsers, 'attr' is undefined; for others, 'attr' is false. Check for both.
                        if (/* typeof attr !== typeof undefined && attr !== false */ $(this).length && attr === `/images/dashboard/budget/menu-vertical-eating/${category}.png`) {
                            if(isOpen){
                                $('.mobile-eating .group').each(function(){
                                    let category = $(this).find('img').data('category'); // on cherche le total des images dans la même catégorie
                                    // on passe la catégorie en non sélectionné
                                    $(this).find('.big-image').attr('src', `/images/dashboard/budget/menu-vertical-eating/${category}.png`);
                                    $(this).find('.small').removeClass('display').addClass('invisible'); // et on ferme les sous-catégories correspondantes
                                    $(this).find('.secondary-rounded-green-bar').removeClass('display').addClass('invisible');
                                    $(this).find('.background-white').removeClass('display').addClass('invisible');
                                })
                                isOpen = false;
                            }
                            // ici l'image de la catégories passe en sélectionné :
                            $(this).attr('src', `/images/dashboard/budget/menu-vertical-eating/${category}-selectionne.png`);
                            // on prend les autres images du groupe et on les passe en visible
                            let group = $(`.mobile-eating #group-${category}`).find('.invisible').removeClass('invisible').addClass('display');
                            var barHeight = 20 + (67 * $(`.mobile-eating #group-${category}`).find('.small').length);
                            //Je definie la hauteur de la bar vertical selon le nombre de sous categories
                            $(`.mobile-eating #group-${category}`).find('.secondary-rounded-green-bar').css('height', barHeight);
                            $(`.mobile-eating #group-${category}`).find('.background-white').css('height', barHeight);

                            if ($(`.mobile-eating #group-${category}`).find('.subSelected').hasClass('subSelected')) {
                                var firstSubcat = $(`.mobile-eating #group-${category}`).find('.subSelected').data('subcategory');
                                var firstSubcatName = $(`.mobile-eating #group-${category}`).find('.subSelected').data('subcatname');
                                $(`.mobile-eating #group-${category}`).find('.subSelected').attr('src', `/images/dashboard/budget/menu-vertical-eating/${firstSubcat}-selectionne.png`);
                            } else {
                                // s'il ya des sous-catégorie, la première passe en sélectionné :
                                var firstSubcat = $(group).find('.small-image').first().data('subcategory');
                                var firstSubcatName = $(group).find('.small-image').first().data('subcatname');
                                $(group).find('.small-image').first().attr('src', `/images/dashboard/budget/menu-vertical-eating/${firstSubcat}-selectionne.png`);
                            }


                            $('.first-form-title').text(`${firstSubcatName}`); // le titre vert à gauche prend en texte le nom de catégorie
                            $('.product-list').each(function(){
                                if ( $(this).attr('id') == `${firstSubcatName}` ) {
                                    $(this).removeClass('invisible').addClass('display'); // afficher le bon menu déroulant pour les produits
                                } else {
                                    $(this).removeClass('display').addClass('invisible');
                                }
                            });
                            // si pas de sous-catégorie (exemple Tabac / Alcool) :
                            if (!firstSubcat) {
                                $('.first-form-title').text(`${categoryName}`); // le titre vert à gauche prend en texte le nom de catégorie
                                $('.product-list').each(function(){
                                    if ( $(this).attr('id') == `${categoryName}` ) {
                                        $(this).removeClass('invisible').addClass('display'); // afficher le bon menu déroulant pour les produits
                                    } else {
                                        $(this).removeClass('display').addClass('invisible');
                                    }
                                });
                            }
                        }
                    } else {
                        testOpen();
                    }
                }
            });

            // au clic sur une sous-catégorie l'image doit passer en sélectionné :
            $('.mobile-eating .small-image').on({
                'click': function() {
                    var attr = $(this).attr('src'); // on récupère l'adresse de l'image dans une variable
                    var category = $(this).data('category'); // on récupère la variable twig category.image
                    var subcategory = $(this).data('subcategory'); // on récupère la variable twig subcategory.image
                    var subcatName = $(this).data('subcatname'); // on récupère la variable twig subcategory.name
                    isOpen = true;
                    // For some browsers, 'attr' is undefined; for others, 'attr' is false. Check for both.
                    if (/* typeof attr !== typeof undefined && attr !== false */ $(this).length && attr === `/images/dashboard/budget/menu-vertical-eating/${subcategory}.png`) {
                        if(isOpen){
                            $('.mobile-eating .group').each(function(){
                                $('.first-form-title').text(`${subcatName}`); // le titre vert à gauche prend en texte le nom de la sous-catégorie
                                $('.product-list').each(function(){
                                    if ( $(this).attr('id') == `${subcatName}` ) {
                                        $(this).removeClass('invisible').addClass('display'); // afficher le bon menu déroulant pour les produits
                                    } else {
                                        $(this).removeClass('display').addClass('invisible'); // cacher les autre menus
                                    }
                                });
                                // on passe la sous-catégorie cliquée en sélectionné :
                                $(this).attr('src', `/images/dashboard/budget/menu-vertical-eating/${subcategory}-selectionne.png`);
                                $(this).find('.subSelected').removeClass('subSelected');
                                var group = $(`.mobile-eating #group-${category}`).find('.small-image'); // trouver toutes les sous-catégories du groupe
                                // les autres sous-catégories du même groupe passent en non sélectionné :
                                $(group).each(function(){
                                    var subcategory = $(this).data('subcategory'); // on récupère la variable twig subcategory.image
                                    $(this).attr('src', `/images/dashboard/budget/menu-vertical-eating/${subcategory}.png`);
                                })
                            })
                            isOpen = false;
                        }
                        // ici l'image de la sous-catégorie passe en sélectionné :
                        $(this).attr('src', `/images/dashboard/budget/menu-vertical-eating/${subcategory}-selectionne.png`);
                        $(this).addClass('subSelected');
                    }
                }
            });
            
        // *************************************** AJAX CASES COCHÉES VERS CONTROLLER **************************************
        // *****************************************************************************************************************

        $('#form-validation-button').on('click', function(){
            let results = new Array();
            $('input:checked').each(function(){
                var data = {};
                // var subcategory = $('#form-title').text();
                var productId = $('.display.product-list').find('.product').val();
                var table = $(this).attr('name');
                var propertyId = $(this).attr('value');
                data.productId = productId;
                data.table = table;
                data.propertyId = propertyId;
                results.push(data);
            })

        $.ajax({
            url: '/ajax_product_update',
            async: true,
            type: 'POST',
            dataType: 'json',
            data: {data:results},
            success: function(data){
                if(data){
                  alert('Votre produit a bien été mis à jour')
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(xhr, textStatus, errorThrown)
            },
        })
    })

    //********************* CLIC SUR LES TITRE DE SECTIONS POUR LES OUVRIR - UNIQUEMENT SUR MOBILE ******************
    //***************************************************************************************************************
    
    $('.section-title').on('click', function(){

        titleId = $(this).attr('id');

        if ($('#' + titleId + '-section').hasClass('invisible')){
            $('#' + titleId + '-section').removeClass('invisible').addClass('display');
            $('.section-title#' + titleId).addClass('green-back');
        }else{
            if ($('#' + titleId + '-section').hasClass('display')){
                $('#' + titleId + '-section').removeClass('display').addClass('invisible');
                $('.section-title#' + titleId).removeClass('green-back');
            }else{
                $('#' + titleId + '-section').addClass('display');
                $('.section-title#' + titleId).addClass('green-back');
            }
        }
    })

            $('.green-bar-right').on('click', function(){
                testOpen();
                var active1 = $(this).parents('.eating-budget-vertical-menu').find('.active1');
                active1.addClass('invisible').removeClass('active1');
                active1.next().addClass('active1').removeClass('active2');
                active1.next().next().addClass('active2').removeClass('active3');
                
                if (active1.next().next().next().hasClass('group')) {
                    active1.next().next().next().addClass('active3').removeClass('invisible');
                } else {
                    var active3 = active1.parent().children().first();
                    if (active3.hasClass('active3')) {
                        active3.removeClass('active3').addClass('active2');
                        active3.next().addClass('active3').removeClass('invisible');
                    } else {
                        if (active3.hasClass('active2')) {
                            active3.removeClass('active2').addClass('active1');
                            active3.next().removeClass('active3').addClass('active2');
                            active3.next().next().removeClass('invisible').addClass('active3');
                        } else {
                            active3.addClass('active3').removeClass('invisible');
                        }
                    }
                }
            })

            $('.green-bar-left').on('click', function(){
                testOpen();
                var active3 = $(this).parents('.eating-budget-vertical-menu').find('.active3');
                active3.addClass('invisible').removeClass('active3');
                active3.prev().addClass('active3').removeClass('active2');
                active3.prev().prev().addClass('active2').removeClass('active1');

                if (active3.prev().prev().prev().hasClass('group')) {
                    active3.prev().prev().prev().removeClass('invisible').addClass('active1');
                } else {
                    var active1 = active3.parent().children().last();
                    if (active1.hasClass('active1')) {
                        active1.removeClass('active1').addClass('active2');
                        active1.prev().removeClass('invisible').addClass('active1');
                    } else {
                        if (active1.hasClass('active2')) {
                            active1.removeClass('active2').addClass('active3');
                            active1.prev().removeClass('active1').addClass('active2');
                            active1.prev().prev().removeClass('invisible').addClass('active1');

                        } else {
                            active1.addClass('active1').removeClass('invisible');
                        }
                    }
                }
            })

            //Verifie si la list version mobile et ouverte
            function testOpen () {
                var listOpen = $('.mobile-eating .eating-budget-vertical-menu').find('.list-open');
                if (listOpen.hasClass('big-image')) {
                    //On veut fermer la list
                    listOpen.removeClass('list-open');
                    let category = listOpen.data('category'); // on cherche le total des images dans la même catégorie
                    // on passe la catégorie en non sélectionné
                    listOpen.parents('.group').find('.big-image').attr('src', `/images/dashboard/budget/menu-vertical-eating/${category}.png`);
                    listOpen.parents('.group').find('.small').removeClass('display').addClass('invisible'); // et on ferme les sous-catégories correspondantes
                    listOpen.parents('.group').find('.background-white').removeClass('display').addClass('invisible'); // et on ferme les sous-catégories correspondantes
                    listOpen.parents('.group').find('.secondary-rounded-green-bar').removeClass('display').addClass('invisible');
                    listOpen.parents('.group').find('.small').each(function(){
                        var subCategory = $(this).find('img').data('subcategory');
                        $(this).find('img').attr('src', `/images/dashboard/budget/menu-vertical-eating/${subCategory}.png`)
                    })
                }
            }
        }
    }
    app.init();
})