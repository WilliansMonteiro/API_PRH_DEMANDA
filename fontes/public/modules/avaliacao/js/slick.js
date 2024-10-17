$(document).ready(function(e){

    $(".lazy").slick({
          arrows:false,
          dots:true,
          draggable: true,
          autoplay: true,
          autoplaySpeed: 3000,
          infinite: true,
          slidesToShow: 1,
          slidesToScroll: 1,
          touchThreshold: 1000,
        
      });

      //prevArrow:'<button type="button" class="slick-prev"></button>',
      //nextArrow:'<button type="button" class="slick-next"></button>',

});

