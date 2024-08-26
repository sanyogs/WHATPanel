window.addEventListener("scroll", () => {
  myFunction();
});

//bootstrap responsive header class remove start

//bootstrap responsive header class remove end

const handleScroll = () => {
  const currentScrollPos = window.pageYOffset;
  const navbar = document.getElementById("headerSection");

  if (prevScrollPos > currentScrollPos) {
    navbar.style.top = "0";
  } else if (prevScrollPos > 500) {
    navbar.style.top = "-100px";
  }
  prevScrollPos = currentScrollPos;
};

window.addEventListener("scroll", handleScroll);

function myFunction() {
  const navbar = document.getElementById("headerSection");
  
  if (window.pageYOffset > 0) {
    navbar.classList.add("sticky");
  } else {
    navbar.classList.remove("sticky");
  }
}
function addHeightToHeader() {
  var headerSticky = document.querySelector('.header');
  var body = document.querySelector('body');
  headerSticky.classList.toggle("addHeight");
  body.classList.toggle("overFlowClass");
}


var prevScrollPos = window.pageYOffset;



// Initialize Slick Slider
$(document).ready(function () {
  $(".servicesSlider").slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: 2000,
    arrows: false,
    dots: true,
    responsive: [
      {
        breakpoint: 1025,
        settings: {
          slidesToShow: 3,
        },
      },
      {
        breakpoint: 835,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 1,
        },
      },
    ],
  });

  $(".planSlider").slick({
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: false,
    autoplaySpeed: 2000,
    arrows: false,
    dots: true,
    responsive: [
      {
        breakpoint: 835,
        settings: {
          slidesToShow: 2,
        },
      },
      {
        breakpoint: 572,
        settings: {
          slidesToShow: 1,
        },
      },
    ],
  });
 

  //accordian start
  $('.commonAccordian').click(function(){
    $(this).toggleClass('active');
    $(this).find('.accordianBtn .accordianArrow').toggleClass('active');
    var accordianContent = $(this).find('.accordianContent');
    if(accordianContent.height() > 0) {
      accordianContent.css('height', '0px');
      $(this).find('.accordianBtn').removeClass('open');
    } else {
      var autoHeight = accordianContent.prop('scrollHeight') + 'px';
      accordianContent.css('height', autoHeight);
      $(this).find('.accordianBtn').addClass('open');
    }
  });
  
  
  //accordian end

});
//slick slider ended


//login-pass-toggle-start
$("#togglePassword").on("click", function () {
  const type = $(".login-password").eq(0).attr("type") === "password" ? "text" : "password";
  $(".login-password").eq(0).attr("type", type);
  $("#togglePassword").toggleClass("bi-eye");
});
//login-pass-toggle-end

//register datepicker start
$(".domain-date-select").datepicker({
  dateFormat: "MM yy",
});
//register datepicker end
