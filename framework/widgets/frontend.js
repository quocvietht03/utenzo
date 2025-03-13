(function ($) {
	/**
	   * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	**/

	/* location list toggle */
	var LocationListHandler = function ($scope, $) {
		var buttonMore = $scope.find('.bt-more-info');
		var contentList = $scope.find('.bt-location-list--content');
		if (buttonMore.length > 0) {
			buttonMore.on('click', function (e) {
				e.preventDefault();
				if ($(this).hasClass('active')) {
					$(this).parent().find('.bt-location-list--content').slideUp();
					$(this).removeClass('active');
					$(this).children('span').text('More Information');
				} else {
					contentList.slideUp();
					buttonMore.children('span').text('More Information');
					buttonMore.removeClass('active');
					$(this).parent().find('.bt-location-list--content').slideDown();
					$(this).addClass('active');
					$(this).children('span').text('Less Information');
				}
			});
		}
	};
	var BrandSliderHandler = function ($scope, $) {
		const $brandslider = $scope.find('.bt-elwg-brand-slider--default');
			if ($brandslider.length > 0) {
			const $item = $brandslider.data('item');
			const $itemTablet = $brandslider.data('item-tablet');
			const $itemMobile = $brandslider.data('item-mobile');
			const $speed = $brandslider.data('speed');
			const $spaceBetween = $brandslider.data('spacebetween');

			const $swiper = new Swiper($brandslider[0], {
				slidesPerView: $itemMobile,
				loop: true,
				spaceBetween: $spaceBetween,
				speed: $speed,
				freeMode:true,
				allowTouchMove: true,
				autoplay:
				{
					delay: 0,
					disableOnInteraction: false,
				},
				breakpoints: {
					1024: {
						slidesPerView: $item,
					},
					768: {
						slidesPerView: $itemTablet,
					},
				},
			});
			$brandslider[0].addEventListener('mouseenter', () => {
				$swiper.autoplay.stop();
			});
			$brandslider[0].addEventListener('mouseleave', () => {
				$swiper.autoplay.start();
			});
		}
	};
	var FaqHandler = function ($scope, $) {
		const $titleFaq = $scope.find('.bt-item-title');
		if ($titleFaq.length > 0) {
			$titleFaq.on('click', function (e) {
				e.preventDefault();
				if ($(this).hasClass('active')) {
					$(this).parent().find('.bt-item-content').slideUp();
					$(this).removeClass('active');
				} else {
					$(this).parent().find('.bt-item-content').slideDown();
					$(this).addClass('active');
				}
			});
		}
	};
	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-location-list.default', LocationListHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-brand-slider.default', BrandSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-list-faq.default', FaqHandler);
	});

})(jQuery);
