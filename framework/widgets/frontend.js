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
				freeMode: true,
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
	var SearchProductHandler = function ($scope, $) {
		const $searchProduct = $scope.find('.bt-elwg-search-product');
		if ($searchProduct.length) {
			const $selectedCategory = $searchProduct.find('.bt-selected-category');
			const $categoryList = $searchProduct.find('.bt-category-list');
			const $categoryItems = $searchProduct.find('.bt-category-item');
			const $catProductInput = $searchProduct.find('input[name="cat-product"]');

			$selectedCategory.on('click', function (e) {
				e.stopPropagation();
				$categoryList.toggle();
			});

			// Handle category selection
			$categoryItems.on('click', function (e) {
				e.preventDefault();
				const $this = $(this);
				const selectedText = $this.text();
				const catSlug = $this.data('cat-slug');

				// Update selected category
				$selectedCategory.find('span').text(selectedText);
				$catProductInput.val(catSlug);

				// Update active class
				$categoryItems.removeClass('active');
				$this.addClass('active');

				// Hide dropdown
				$categoryList.hide();
			});

			// Close dropdown when clicking outside
			$(document).on('click', function () {
				$categoryList.hide();
			});
			const $liveSearch = $searchProduct.find('.bt-live-search');
			const $liveSearchResults = $searchProduct.find('.bt-live-search-results');
			const $dataSearch = $searchProduct.find('.bt-live-search-results .bt-load-data');
			let typingTimer;
			const doneTypingInterval = 500; // 0.5 second delay after typing stops

			const performSearch = function () {
				const searchTerm = $liveSearch.val().trim();
				if (searchTerm.length >= 2) {
					var param_ajax = {
						action: 'utenzo_search_live',
						search_term: searchTerm,
						category_slug: $catProductInput.val()
					};
					$.ajax({
						type: 'POST',
						dataType: 'json',
						url: AJ_Options.ajax_url,
						data: param_ajax,
						context: this,
						beforeSend: function () {
							$liveSearchResults.addClass('active');
							$liveSearchResults.addClass('loading');
						},
						success: function (response) {
							if (response.success) {
								$dataSearch.html(response.data['items']);
								setTimeout(function () {
									$liveSearchResults.removeClass('loading');
								}, 100);
							} else {
								console.log('error');
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							console.log('The following error occured: ' + textStatus, errorThrown);
						}
					});
					return false;
				} else {
					$dataSearch.empty();
				}
			};

			$liveSearch.on('input', function () {
				clearTimeout(typingTimer);
				const searchTerm = $(this).val().trim();
				if (searchTerm.length >= 2) {
					typingTimer = setTimeout(performSearch, doneTypingInterval);
				} else {
					$dataSearch.empty();
					$liveSearchResults.removeClass('active');
				}
			});
			$liveSearch.on('click', function() {
				const searchTerm = $(this).val().trim();
				if (searchTerm.length >= 2) {
					$liveSearchResults.addClass('active');
					if(window.location.href.includes('search_keyword')){
						performSearch();
					}
				}
			});
			$categoryItems.on('click', function (e) {
				e.preventDefault();
				const searchTerm = $liveSearch.val().trim();
				// If search term exists, perform search
				if (searchTerm.length >= 2) {
					performSearch();
				}else{
					$liveSearchResults.removeClass('active');
				}
			});

			// Hide results when clicking outside
			$(document).on('click', function (e) {
				if (!$(e.target).closest('.bt-live-search-results').length &&
					!$(e.target).is('.bt-live-search') &&
					!$(e.target).closest('.bt-category-item').length) {
					$liveSearchResults.removeClass('active');
				}
			});
		}
	};
	function UtenzoAnimateText(selector, delayFactor = 50) {
		const $text = $(selector);
		const textContent = $text.text();
		$text.empty();

		let letterIndex = 0;

		textContent.split(" ").forEach((word) => {
			const $wordSpan = $("<span>").addClass("bt-word");

			word.split("").forEach((char) => {
				const $charSpan = $("<span>").addClass("bt-letter").text(char);
				$charSpan.css("animation-delay", `${letterIndex * delayFactor}ms`);
				$wordSpan.append($charSpan);
				letterIndex++;
			});

			$text.append($wordSpan).append(" ");
		});
	}
	function headingAnimationHandler($scope) {
		var headingAnimationContainer = $scope.find('.bt-elwg-heading-animation');
		var animationElement = headingAnimationContainer.find('.bt-heading-animation-js');
		var animationClass = headingAnimationContainer.data('animation');
		var animationDelay = headingAnimationContainer.data('delay');

		if (animationClass === 'none') {
			return;
		}
		function checkIfElementInView() {
			const windowHeight = $(window).height();
			const elementOffsetTop = animationElement.offset().top;
			const elementOffsetBottom = elementOffsetTop + animationElement.outerHeight();

			const isElementInView =
				elementOffsetTop < $(window).scrollTop() + windowHeight &&
				elementOffsetBottom > $(window).scrollTop();

			if (isElementInView) {
				if (!animationElement.hasClass('bt-animated')) {
					animationElement
						.addClass('bt-animated')
						.addClass(animationClass);
					UtenzoAnimateText(animationElement, animationDelay);
				}
			}
		}
		jQuery(window).on('scroll', function () {
			checkIfElementInView();
		});
		jQuery(document).ready(function () {
			checkIfElementInView();
		});
	}
	var TiktokShopSliderHandler = function ($scope, $) {
		const $tiktokSlider = $scope.find('.bt-elwg-tiktok-shop-slider--default');
		if ($tiktokSlider.length > 0) {
			const $item = parseInt($tiktokSlider.data('item')) || 1;
			const $itemTablet = parseInt($tiktokSlider.data('item-tablet')) || 1;
			const $itemMobile = parseInt($tiktokSlider.data('item-mobile')) || 1;
			const $speed = parseInt($tiktokSlider.data('speed')) || 1000;
			const $spaceBetween = parseInt($tiktokSlider.data('spacebetween')) || 0;
			const $spaceBetweenTablet = parseInt($tiktokSlider.data('spacebetween-tablet')) || 0;
			const $spaceBetweenMobile = parseInt($tiktokSlider.data('spacebetween-mobile')) || 0;
			const $autoplay = $tiktokSlider.data('autoplay') === 'true';

			const $swiper = new Swiper($tiktokSlider[0], {
				slidesPerView: $itemMobile,
				loop: true,
				spaceBetween: $spaceBetweenMobile,
				speed: $speed,
				freeMode: true,
				allowTouchMove: true,
				autoplay: $autoplay ? {
					delay: 3000,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $tiktokSlider.find('.bt-button-next')[0],
					prevEl: $tiktokSlider.find('.bt-button-prev')[0],
				},
				breakpoints: {
					1024: {
						slidesPerView: $item,
						spaceBetween: $spaceBetween
					},
					768: {
						slidesPerView: $itemTablet,
						spaceBetween: $spaceBetweenTablet
					},
				},
			});

			if ($autoplay) {
				$tiktokSlider[0].addEventListener('mouseenter', () => {
					$swiper.autoplay.stop();
				});
				$tiktokSlider[0].addEventListener('mouseleave', () => {
					$swiper.autoplay.start();
				});
			}
		}
	};
	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-location-list.default', LocationListHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-brand-slider.default', BrandSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-list-faq.default', FaqHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-search-product.default', SearchProductHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-heading-animation.default', headingAnimationHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-tiktok-shop-slider.default', TiktokShopSliderHandler);
	});

})(jQuery);
