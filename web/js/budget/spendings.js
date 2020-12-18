$(document).ready(function(){


  var app = {
    init: function () {
        
      $(".add-bread-cereals").click(function() {
        console.log("test");
      });
        
      // set category's form name
      formName = categoryName+"-itms-form";
      //console.log(formName);

      // find category's form
      form = $('body').find('#'+formName);
      //console.log(form);

      // show category's form
      form.css("display","block");

      // smoothly go to end of page
      $("html, body").animate({ scrollTop: $(document).height() }, "slow");
    }};

    var converter = ""; //valeur de 1CHF = {value}EUR

      $.ajax({
        url: "http://api.devises.zone/v1/quotes/CHF/EUR/json?qty=1&key=2672|4Fc1DMTXFnjjttLLagR0PypZ5xsRox0U",
        method: "GET"
      })
      .done(function(data) {
        //console.log(data);
        converter = data.result.value;
        //console.log(converter);
      })
      .fail(function( jqXHR, textStatus ) {

      });
        // alert( "Request failed: " + textStatus );
      //console.log('test');
        /*$(".image-subject-container").click(function() {
          console.log("test");
          attr = $(this).find('.image-subject').attr();
          console.log(attr);
        });*/
  
        /*$( ".selector" ).selectmenu({
          width: 100
        });*/
        
      //console.log("test");
  
      $(".brands-input").autocomplete({
        source: ["AI","CG","DS","OS","C","C#","JAVA"]
      });
  
      $(".names-input").autocomplete({
        source: ["AI","CG","DS","OS","C","C#","JAVA"]
      });
        
      // show category's form
      $(".theme").click(function() {
        // hide all forms
        allForms = $('body').find(".form-item");
        allForms.css("display","none");
  
        // enable blue film on all categories
  
        $('.blue-film').css('display','block');
  
        // disable category's blue film
  
        $(this).find('.blue-film').css('display','none');
  
        // find clicked category name
        categoryName = $(this).attr('id');
          
        // set category's form name
        formName = categoryName+"-form";
        //console.log(formName);
  
        // find category's form
        form = $('body').find('#'+formName);
        //console.log(form);
  
        // show category's form
        form.css("display","block");
  
        // smoothly go to end of page
        //$("html, body").animate({ scrollTop: $(document).height() }, "slow");
      });
        
        /*productSpan.val('');
            productSpan.children().remove();
            productSpan.append(bdProductNameValue);*/
        /*
        $(document).ready(function(){
          select = $(document).find("#select name-dropdown-list")
          productSpan = $(document).find("#myList-non-alcoholic-drinks").find('.value');
          bdProductNameValue = $(document).find("#db-product-name-value");
          $(document).mousemove(function(event){
            
            select.append('<span class=\'value\'\>\
            {{ spending.getProduct().getName() \}\}\</span>');
          });
        });
        */
  
      // show category's form (mobile)
      $(".category").click(function() {
        // hide all forms
        allForms = $('body').find(".form-item");
        allForms.css("display","none");
  
        // find clicked category name
        categoryName = $(this).attr('id');
          
        // set category's form name
        formName = categoryName+"-itms-form";
        //console.log(formName);
  
        // find category's form
        form = $('body').find('#'+formName);
        //console.log(form);
  
        // show category's form
        form.css("display","block");
  
        // smoothly go to end of page
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
      });
  
      var converter = ""; //valeur de 1CHF = {value}EUR
  
        $.ajax({
          url: "http://api.devises.zone/v1/quotes/CHF/EUR/json?qty=1&key=2672|4Fc1DMTXFnjjttLLagR0PypZ5xsRox0U",
          method: "GET"
        })
        .done(function(data) {
          //console.log(data);
          converter = data.result.value;
          //console.log(converter);
        })
        .fail(function( jqXHR, textStatus ) {
          // alert( "Request failed: " + textStatus );
        });
        
        // get equivalent value in CHF
        var convertMoney = function(price) {
          equivalentInChf = price*converter;
          //console.log("in chf : "+equivalentInChf);
          return equivalentInChf;
        }
        
        // reverse operation
        var reverseConvertMoney = function(price) {
          equivalentInChf = price/converter;
          //console.log("in chf : "+equivalentInChf);
          return equivalentInChf;
        }
  
        previouslyClickedCurrency = "CHF";
  
        $(".currency-dropdown-list").click(function(converter) {
          //calculateTotalAmount(converter);
          event = "click-currency-dropdown-list";
          calculateTotalAmount(event);
        });
  
        /*
        $(".total-amount-currency").find('li').click(function() {
          event = "click-total-amount-currency-dropdown-list";
          calculateTotalAmount(event);
        });
        */
  
      var calculateTotalAmount = function (event) {
        console.log(converter);
        // non alcoholic drinks
         var nonAlcoholicDrinkSum = 0;
        var iteration = 0;
        $('#myList-non-alcoholic-drinks').find('.amount-input').each(function() {
          // if currency CHF no conversion else find equivalent in CHF
          iteration +=  Number(1);
          amountInput = $(this);
          //console.log('amount input below :');
          //console.log(amountInput);
          price = amountInput.val();
          //console.log('price value below : ');
          //console.log(price);
  
          commonAncestor = amountInput.parents('.amount-and-currency-selection');
  
          currencyDropdownList = commonAncestor.find('.currency-dropdown-list');
  
          selectedCurrency = currencyDropdownList.find('.value').text();
          //console.log('selected currency '+iteration+' : '+selectedCurrency);
  
          if(selectedCurrency != 'CHF') {
              
            equivalentInChf = convertMoney(price);
            //console.log("in CHF : "+price);
            nonAlcoholicDrinkSum += Number(equivalentInChf);
  
          } else {
  
            equivalentInChf = price;
            nonAlcoholicDrinkSum += Number(price);
              
          }
        });
        
        //console.log(nonAlcoholicDrinkSum);
  
        $('.total-amount-non-alcoholic-drinks').val(nonAlcoholicDrinkSum);
          
        if (event == "entering-spendings-in-fields") {
  
        } else if (event == "click-currency-dropdown-list") {
            
  
        } else if  (event == "click-total-amount-currency-dropdown-list") {
            ///////////////////////////////////////////////
            // update total amount value if currency change
            ///////////////////////////////////////////////
  
            //totalAmountCurrencyDropdownList = $(this);
  
          totalAmountCurrencyDropdownList = $('#total-amount-currency-dropdown-list');
  
          console.log('total amount currency dropdown list below :');
          console.log(totalAmountCurrencyDropdownList);
  
          currency = totalAmountCurrencyDropdownList.find('.value').text();
  
          catTotalAmountAndCurrency = totalAmountCurrencyDropdownList.parents('.category-total-amount-and-currency');
  
          console.log('catTotalAmountAndCurrency value below');
          console.log(catTotalAmountAndCurrency);
  
          totalAmountField = catTotalAmountAndCurrency.find('.total-amount');
  
          console.log('totalAmountField value below');
          console.log(totalAmountField);
            
          totalAmountValue = totalAmountField.val();
  
          console.log('totalAmountValue value below');
          console.log(totalAmountValue);
  
          var convertedTotalAmountvalue = null;
          var action = null;
  
          console.log('currencyBelow');
          console.log(currency);
  
          console.log('previouslyClickedCurrency value below');
          console.log(previouslyClickedCurrency);
  
          if (currency == 'CHF') {
            console.log('CHF');
            if (previouslyClickedCurrency == 'EUR') {
              console.log('previously clicked on  EUR');
              console.log(totalAmountValue);
              //convertedTotalAmountvalue = reverseConvertMoney(totalAmountValue);
              convertedTotalAmountvalue = totalAmountValue;
              action = 'change-input-value';
            }
            previouslyClickedCurrency = "CHF";
            } else if (currency == 'EUR') {
              console.log('EUR');
              if(previouslyClickedCurrency == 'CHF') {
                convertedTotalAmountvalue = convertMoney(totalAmountValue);
                action = 'change-input-value';
              }
              previouslyClickedCurrency = "EUR";
            }
            
            if (action == 'change-input-value') {
              totalAmountField.val(convertedTotalAmountvalue);
            }
  
          console.log('convertedTotalAmountvalue value below');
          console.log(convertedTotalAmountvalue);
          }
        }
  
        $('.amount-input').keyup(function(evt) {
          //calculateTotalAmount(converter);
          //console.log(converter);
          event = "entering-spendings-in-fields";
          calculateTotalAmount(event);
        });
        
        // non alcoholic drinks
        var hiddenFormRowNonAlcoholicDrinks = $('#template .hidden-row-non-alcoholic-drinks').clone(true, true);
        $('#myList-non-alcoholic-drinks').append(hiddenFormRowNonAlcoholicDrinks);
  
        // bread cereals
        var hiddenFormRowBreadCereals = $('#template .hidden-row-non-alcoholic-drinks').clone(true, true);
        $('#myList-bread-cereals').append(hiddenFormRowBreadCereals);
        
        // milk cheese eggs
        var hiddenFormRowMilkCheeseEggs = $('#template .hidden-row-non-alcoholic-drinks').clone(true, true);
        $('#myList-milk-cheese-eggs').append(hiddenFormRowMilkCheeseEggs);
  
        // fish sea foods
        var hiddenFormRowFishSeaFoods = $('#template .hidden-row-non-alcoholic-drinks').clone(true, true);
        $('#myList-fish-sea-foods').append(hiddenFormRowFishSeaFoods);
  
        // meat
        var hiddenFormRowMeat = $('#template .hidden-row-non-alcoholic-drinks').clone(true, true);
        $('#myList-meat').append(hiddenFormRowMeat);
  
        // fruits vegetables
        var hiddenFormRowFruitsVegetables = $('#template .hidden-row-non-alcoholic-drinks').clone(true, true);
        $('#myList-fruits-vegetables').append(hiddenFormRowFruitsVegetables);
  
        $(".add-product-line").click(function() {
          productLine = $('.product-line');
          console.log(productLine);
        });
  
        // new product form line
        $(".addproduct").click(function() {
  
          //calculateTotalAmount();
  
          addProductBtn = $(this);
  
          subcategory = addProductBtn.attr('id');
  
          //console.log(subcategory);
  
          //console.log($(addProductBtn));
          addProductBtnId = addProductBtn.attr('id');
          //console.log($('#template .hidden-row-'+addProductBtnId));
          var hiddenFormRow = $('#template .hidden-row-'+addProductBtnId).clone(true);
          //console.log(hiddenFormRow);
          //$(hiddenFormRow).insertAfter("#non-alcoholic-drink");
          $('#myList-'+addProductBtnId).append($('#template .hidden-row-'+addProductBtnId).clone(true));
          //console.log('#myList-'+addProductBtnId+" .hidden-form:last-child");
          //console.log($('#myList-'+addProductBtnId+" .hidden-form:last-child .select"));
          var selectList = $('#myList-'+addProductBtnId+" .hidden-form:last-child .select");
  
          $('#myList-'+addProductBtnId);
  
          nonAlcoholicDrinksFormLines = $("#myList-non-alcoholic-drinks").find(".container");
  
          // iteration of new product line to add autocomplete fields and select lists with different id's
          var newNonAlcoholicDrinksFormLineIteration = 0;
  
          nonAlcoholicDrinksFormLines.each(function() {
            newNonAlcoholicDrinksFormLineIteration = newNonAlcoholicDrinksFormLineIteration+1;
            console.log(newNonAlcoholicDrinksFormLineIteration);
  
            nameInput = $(this).find(".names-input");
            console.log(nameInput);
  
            nameLabel = $(this).find(".names-label");
            
            brandInput = $(this).find(".brands-input");
            console.log(brandInput);
            
            brandLabel = $(this).find(".brands-label");
  
            nameInput.attr("id", "names-"+newNonAlcoholicDrinksFormLineIteration);
            //nameLabel.attr("for", "names-"+newNonAlcoholicDrinksFormLineIteration);
  
            brandInput.attr("id", "brands-"+newNonAlcoholicDrinksFormLineIteration);
            //brandLabel.attr("for", "brands-"+newNonAlcoholicDrinksFormLineIteration);
  
            setSuggestions();
          });
          
        });
          
          // when the user clicks on validation button
          $("#form-validation-button").click(function() {
              
            /*getQuantity();
            getName();
            getBrand();*/
            myList = $('#myList-non-alcoholic-drinks').find('.row');
            var array = new Array();
            var iteration = 0;
  
            myList.each(function() {
  
              var miniArray = {
                "id" : 1,
                "name" : 'name4',
                "quantity" : 4,
                "brand" : 'brand4',
                "amount" : 4,
                "currency" : 'cur',
                "subcategory" : "non-alcoholic-drinks",
                "period" : "semaine"
              };
  
              console.log(miniArray);
              array.push(miniArray);
              iteration += Number(1);
            });
  
            console.log(array);
  
            var myJSON = JSON.stringify(array);
  
            console.log(myJSON);
  
            $.ajax({
              url:'/ajax_spendings_update',
              type: "POST",
              dataType: "json",
              data: {
                "myJSON": myJSON
              },
              async: true,
              success: function (data)
              {
                myJSON;
              }
            })
          });
          
          function setSuggestions() {
            // ajout des suggestions du nom de produit
            $(".brands-input").autocomplete({
              source: ["AI","CG","DS","OS","C","C#","JAVA"]
            });
            console.log('setSuggestions');
            $(".names-input").autocomplete({
              source: ["AI","CG","DS","OS","C","C#","JAVA"]
            });
          };
            //console.log();
            /*var productNames = new Array();
            $( function() {
              $(".product-name").each(function( index ) {
                productName = $( this ).text();
                
                productNames.push(productName);
                console.log( index + ": " + productName );
              });
              console.log(productNames);
              var i;
              for (i = 0; i < 50; i++) {
                $( "#names-"+i ).autocomplete({
                  source: productNames
                });
              }
            });
            
            // ajout des suggestions du nom de la marque
            var brandNames = new Array();
            $( function() {
              $(".brand-name").each(function( index ) {
                brandName = $( this ).text();
                
                brandNames.push(brandName);
                console.log( index + ": " + brandName );
              });
              console.log(brandNames);
              
              var j;
              for (j = 0; j < 50; j++) {
                $( "#brands-"+j ).autocomplete({
                  source: brandNames
                });
              }
            });
        });

        let jQuery = $('.names').on('click', function(){
          let id=$(this).attr('id');
          data={
              id:id,
              prenom:prenom,
            */
          
          
          /*$( function() {
            $( "#currency" ).selectmenu();
            $( "#quantity" ).selectmenu();
          } );*/
  
          $(document).ready(function() {
              $(".amount-input").keypress(function(e) {
                  var keyCode = e.which;
                  /*
                  8 - (backspace)
                  32 - (space)
                  48-57 - (0-9)Numbers
                  */
             
                  if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) {
                      //showNoCharactersNotification();
                      return false;
                  } else {
                      //hideNoCharactersNotification();
                  }
              })
          });

    //function image_change(elmnt) {
      //var idImages = ['boisson', 'eau', 'the-cafe', 'boissons-vegetales' , 'fruits-et-legumes', 'feculents', 'produits-laitiers', 'matieres-grasses', 'vpo', 'produits-sucre'];

      //idImages.forEach((elements) => {
          //document.getElementById(elements).setAttribute('src', "/images/dashboard/budget/menu-vertical-eating/" + elements + ".png");
      //});

  app.init();

})
