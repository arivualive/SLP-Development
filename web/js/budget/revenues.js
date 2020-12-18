$(document).ready(function(){
  
  var app = {
  init: function () {

// le bouton "historique des revenus" devient blanc au clic et ouvre le tableau des revenus
$('.margin-top-history-revenues').on({
  'click': function(){
      if ($(this).hasClass("blue-background")){
          $(this).removeClass("blue-background").addClass( "white-background" );
          $('#history-tables').removeClass("invisible").addClass("display");
          $(function() {
              /* Smooth scrolling vers le tableau des revenus */
              function scrollTo( target ) {
                  if( target.length ) {
                      $("html, body").stop().animate( { scrollTop: target.offset().top }, 1500);
                  }
              }
              scrollTo( $("#history-tables") );
          });
      } else {
          /* si on clique à nouveau, le bouton change et le tableau se ferme */
          $(this).removeClass("white-background").addClass( "blue-background" );
          $('#history-tables').removeClass("display").addClass("invisible");
      }
  }
});

// *** le bouton "annuel" est actif par défaut ***
// le bouton "mensuel" bleu devient blanc au clic :
$('#mensuel').on({
  'click': function() {
var attr = $("#mensuel").attr('src');
  // For some browsers, 'attr' is undefined; for others, 'attr' is false. Check for both.
  if (typeof attr !== typeof undefined && attr !== false && attr === "{{ asset('images/revenus/mensuel1.jpg') }}") {
      $("#mensuel").attr('src', "{{ asset('images/revenus/mensuel2.jpg') }}");
      $("#annuel").attr('src', "{{ asset('images/revenus/annuel1.jpg') }}");
      // le tableau annuel disparaît, le tableau mensuel réapparaît
      $('#annualRevenues').removeClass("display").addClass("invisible");
      $('#monthlyRevenues').removeClass("invisible").addClass("display");
      }
  }
  });
// ensuite le bouton "annuel" devient blanc au clic seulement s'il est bleu :
$('#annuel').on({
  'click': function() {
var attr = $("#annuel").attr('src');
  // For some browsers, 'attr' is undefined; for others, 'attr' is false. Check for both.
  if (typeof attr !== typeof undefined && attr !== false && attr === "{{ asset('images/revenus/annuel1.jpg') }}") {
      $("#annuel").attr('src', "{{ asset('images/revenus/annuel2.jpg') }}");
      $("#mensuel").attr('src', "{{ asset('images/revenus/mensuel1.jpg') }}");
      // le tableau mensuel disparaît, le tableau annuel réapparaît
      $('#monthlyRevenues').removeClass("display").addClass("invisible");
      $('#annualRevenues').removeClass("invisible").addClass("display");
      }
  }
  });
}}

  app.init();

})