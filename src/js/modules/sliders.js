document.addEventListener('DOMContentLoaded', () => {
  const sliders = document.querySelectorAll(".slider");

  const defaultSliderOptions = {
    speed: 1500,
    watchOverflow: true,
    observer: true,
    resizeObserver: true,
    loop: true,
  };

  const initSlider = (elem) => {
    if (!elem) return;

    const sliderOptions = {...defaultSliderOptions};

    if (elem.hasAttribute("data-single")) {
      sliderOptions.spaceBetween = 20;
      sliderOptions.grabCursor = true;
      sliderOptions.effect = 'fade';
      sliderOptions.fadeEffect = {
        crossFade: true,
      };
      sliderOptions.autoplay = {
        delay: 4000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      };
    }

    if (elem.hasAttribute("data-slides")) {
      sliderOptions.on = {
        init: function () {
          const lg = lightGallery(elem, {
            plugins: [lgZoom],
            selector: "li",
            speed: 500,
            download: false,
            actualSize: false,
            showZoomInOutIcons: true,
            infiniteZoom: true,
            mobileSettings: {
              showCloseIcon: true,
              controls: true,
            },
          });

          elem.addEventListener("lgBeforeOpen", () => {
            swiper.autoplay.stop();
          });

          elem.addEventListener("lgBeforeClose", () => {
            swiper.slideTo(lg.index, 1500)
            swiper.autoplay.start();
          });
        },
      };
      sliderOptions.spaceBetween = 20;
      sliderOptions.centeredSlides = true;
      sliderOptions.breakpoints = {
        480: {
          slidesPerView: 1,
        },
        550: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 3,
        },
        1280: {
          slidesPerView: 4,
        },
        1440: {
          slidesPerView: 5,
        },
      };
      sliderOptions.autoplay = {
        delay: 3000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      };
    }

    if (elem.hasAttribute("data-pagination")) {
      sliderOptions.pagination = {
        el: ".slider__pagination",
        type: "bullets",
        clickable: true,
      };
    }

    const swiper = new Swiper(elem, sliderOptions);
  };

  if (sliders.length > 0) {
    sliders.forEach((elem) => {
      initSlider(elem.querySelector('.slider__wrapper'));
    });
  }
});
