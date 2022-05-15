// JS Document

(function ($) {
"use strict"; // Start of use strict

   // Smooth scrolling using jQuery easing
   $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
      if(
         location.pathname.replace(/^\//, "") ==
            this.pathname.replace(/^\//, "") &&
         location.hostname == this.hostname
      ) {
         var target = $(this.hash);
         target = target.length
            ? target
            : $("[name=" + this.hash.slice(1) + "]");
         if(target.length) {
            $("html, body").animate(
               {
                  scrollTop: target.offset().top, // Or -60
               },
               800,
               "easeInOutExpo"
            );
            return false;
         }
      }
   });

   $(".js-scroll-trigger").click(function () {
      // Closes responsive menu when a scroll trigger link is clicked
      $(".navbar-collapse").collapse("hide");

      // On utilise la méthode history.replaceState() pour modifier l'entrée de index.html dans l'historique
      window.history.replaceState("index", "Ludovic Lemaître | Développeur Back-End PHP",
         "/blog_php/");
   });

   // Activate scrollspy to add active class to navbar items on scroll
   /*$("body").scrollspy({
      target: "#sideNav",
   });*/
})(jQuery); // End of use strict


// On enlève l'ancre html de l'url pour le navigateur Firefox
let url = window.location.toString();
let anchor = new RegExp(/#([A-Za-z]+)/);
let result = anchor.test(url);

if(result && navigator.buildID == "20181001000000") {
   // Fonctionne mais recharge la page
   // window.location = url.replace(/index#([A-Za-z]+)/, "");

   /* Fonctionne mais ajoute une entrée dans l'historique pour la page index.html
   window.history.pushState("index", "Ludovic Lemaître | Développeur Back-End PHP",
      "/blog_php/"); */

   window.history.replaceState("index", "Ludovic Lemaître | Développeur Back-End PHP",
      "/blog_php/");
}