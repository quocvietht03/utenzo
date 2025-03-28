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
			$liveSearch.on('click', function () {
				const searchTerm = $(this).val().trim();
				if (searchTerm.length >= 2) {
					$liveSearchResults.addClass('active');
					if (window.location.href.includes('search_keyword')) {
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
				} else {
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
				loop: false,
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
			// tiktok popup video
			$tiktokSlider.find('.bt-play-video').magnificPopup({
				type: 'inline',
				preloader: false,
				removalDelay: 300,
				mainClass: 'mfp-fade',
				callbacks: {
					beforeOpen: function () {
						this.st.mainClass = this.st.el.attr('data-effect');
					},
					open: function () {
						// Initialize video elements when popup opens
						const videoPopup = this.content.find('.bt-video-wrap');
						const videoElement = videoPopup.find('video');

						if (videoElement.length > 0) {
							// Handle uploaded video
							videoElement[0].play();
						}
					},
					close: function () {
						// Pause video when popup closes
						const videoElement = this.content.find('video');
						if (videoElement.length > 0) {
							videoElement[0].pause();
							videoElement[0].currentTime = 0;
						}
					}
				}
			});
		}
	};
	const HotspotProductHandler = function ($scope) {
		const $HotspotProduct = $scope.find('.bt-elwg-hotspot-product--default');
		if ($HotspotProduct.length > 0) {

			function hotspotPoint() {
				$HotspotProduct.find('.bt-hotspot-point').each(function () {
					const $point = $(this);
					const $info = $point.find('.bt-hotspot-product-info');
					const pointLeft = $point.position().left;
					const pointTop = $point.position().top;
					const infoWidth = $info.outerWidth() + 55;
					const infoHeight = $info.outerHeight() + 55;
					const containerWidth = $point.parent().width();
					const containerHeight = $point.parent().height();
					let smallOffset = 5;
					let largeOffset = 15;
					if (containerWidth < 700) {
						smallOffset = 2;
						largeOffset = 8;
					}

					// Handle horizontal positioning
					if (pointLeft < infoWidth) {
						if (pointTop < infoHeight) {
							if (containerWidth < 700) {
								if (pointLeft + infoWidth < containerWidth) {
									$info.css({
										'inset': '100% auto auto 100%',
										'transform': `translate(${smallOffset}px, ${smallOffset}px)`
									});
								} else {
									if (pointTop < infoHeight) {
										$info.css({
											'inset': '100% auto auto auto',
											'transform': `translateY(${largeOffset}px)`
										});
									} else {
										$info.css({
											'inset': 'auto auto 100% auto',
											'transform': `translateY(-${largeOffset}px)`
										});
									}
								}
							} else {
								$info.css({
									'inset': '100% auto auto 100%',
									'transform': `translate(${smallOffset}px, ${smallOffset}px)`
								});
							}
						} else if (pointTop + infoHeight > containerHeight) {
							if (containerWidth < 700) {
								if (pointLeft + infoWidth < containerWidth) {
									$info.css({
										'inset': 'auto auto 100% 100%',
										'transform': `translate(${smallOffset}px, -${smallOffset}px)`
									});
								} else {
									if (pointTop < infoHeight) {
										$info.css({
											'inset': '100% auto auto auto',
											'transform': `translateY(${largeOffset}px)`
										});
									} else {
										$info.css({
											'inset': 'auto auto 100% auto',
											'transform': `translateY(-${largeOffset}px)`
										});
									}
								}
							} else {
								$info.css({
									'inset': 'auto auto 100% 100%',
									'transform': `translate(${smallOffset}px, -${smallOffset}px)`
								});
							}
						} else {
							if (containerWidth < 700) {
								if (pointLeft + infoWidth < containerWidth) {
									$info.css({
										'inset': 'auto auto auto 100%',
										'transform': `translateX(${largeOffset}px)`
									});
								} else {
									if (pointTop < infoHeight) {
										$info.css({
											'inset': '100% auto auto auto',
											'transform': `translateY(${largeOffset}px)`
										});
									} else {
										$info.css({
											'inset': 'auto auto 100% auto',
											'transform': `translateY(-${largeOffset}px)`
										});
									}
								}
							} else {
								$info.css({
									'inset': 'auto auto auto 100%',
									'transform': `translateX(${largeOffset}px)`
								});
							}
						}
					} else if (pointLeft + infoWidth > containerWidth) {
						if (pointTop < infoHeight) {
							$info.css({
								'inset': '100% 0 auto auto',
								'transform': `translate(${smallOffset}px, ${smallOffset}px)`
							});
						} else if (pointTop + infoHeight > containerHeight) {
							$info.css({
								'inset': 'auto 0 100% auto',
								'transform': `translate(${smallOffset}px, -${smallOffset}px)`
							});
						} else {
							$info.css({
								'inset': 'auto 100% auto auto',
								'transform': `translateX(-${largeOffset}px)`
							});
						}
					} else {
						if (pointTop < infoHeight) {
							$info.css({
								'inset': '100% auto auto auto',
								'transform': `translateY(${largeOffset}px)`
							});
						} else if (pointTop + infoHeight > containerHeight) {
							$info.css({
								'inset': 'auto auto 100% auto',
								'transform': `translateY(-${largeOffset}px)`
							});
						} else {
							if (containerWidth < 700) {
								if (pointLeft + infoWidth < containerWidth) {
									$info.css({
										'inset': 'auto auto auto 100%',
										'transform': `translateX(${largeOffset}px)`
									});
								} else {
									if (pointTop < infoHeight) {
										$info.css({
											'inset': '100% auto auto auto',
											'transform': `translateY(${largeOffset}px)`
										});
									} else {
										$info.css({
											'inset': 'auto auto 100% auto',
											'transform': `translateY(-${largeOffset}px)`
										});
									}
								}
							} else {
								$info.css({
									'inset': 'auto 100% auto auto',
									'transform': `translateX(-${largeOffset}px)`
								});
							}

						}
					}
				});
			}
			hotspotPoint();
			$(window).on('resize', function () {
				hotspotPoint();
			});
		}
		// slider hotspot
		const $Hotspotslider = $HotspotProduct.find('.bt-hotspot-slider');

		if ($Hotspotslider.length > 0) {
			const $sliderSettings = $Hotspotslider.data('slider-settings');
			const $Hotspotwrap = $Hotspotslider.find('.bt-hotspot-slider--inner');
			const $swiper = new Swiper($Hotspotwrap[0], {
				slidesPerView: 1,
				loop: false,
				spaceBetween: $sliderSettings.spaceBetween.mobile,
				speed: $sliderSettings.speed,
				freeMode: true,
				allowTouchMove: true,
				autoplay: $sliderSettings.autoplay ? {
					delay: 3000,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $Hotspotslider.find('.bt-button-next')[0],
					prevEl: $Hotspotslider.find('.bt-button-prev')[0],
				},
				breakpoints: {
					1490: {
						slidesPerView: 3,
						spaceBetween: $sliderSettings.spaceBetween.desktop
					},
					1024: {
						slidesPerView: 2,
						spaceBetween: $sliderSettings.spaceBetween.desktop
					},
					767: {
						slidesPerView: 3,
						spaceBetween: $sliderSettings.spaceBetween.tablet
					},
					600: {
						slidesPerView: 2,
						spaceBetween: $sliderSettings.spaceBetween.tablet
					},
				},
			});

			if ($sliderSettings.autoplay) {
				$Hotspotwrap[0].addEventListener('mouseenter', () => {
					$swiper.autoplay.stop();
				});
				$Hotspotwrap[0].addEventListener('mouseleave', () => {
					$swiper.autoplay.start();
				});
			}
		}
		// Add set to cart ajax 
		const $buttonAddToCart = $HotspotProduct.find('.bt-add-to-cart-wrapper');
		if ($buttonAddToCart.length > 0) {
			$buttonAddToCart.on('click', '.bt-add-to-cart-btn', function (e) {
				e.preventDefault();
				const $this = $(this);
				if ($this.hasClass('bt-view-card')) {
					window.location.href = AJ_Options.cart;
					return;
				}
				const productIds = $this.find('.bt-btn-price').data('ids');
				if (productIds.length > 0) {
					$.ajax({
						type: 'POST',
						url: AJ_Options.ajax_url,
						data: {
							action: 'utenzo_add_multiple_to_cart',
							product_ids: productIds
						},
						beforeSend: function () {
							$this.addClass('loading');
						},
						success: function (response) {
							$this.removeClass('loading');
							if (response.success) {
								// Update cart count and trigger cart refresh
								$('.bt-cart-count').text(response.data.cart_count);
								$(document.body).trigger('added_to_cart');
								$this.html('View Cart');
								$this.addClass('bt-view-card');
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							$this.removeClass('loading');
							console.log('Error adding products to cart:', textStatus, errorThrown);
						}
					});
				}
			});
		}
	};
	const ProductTestimonialHandler = function ($scope) {
		const $ProductTestimonial = $scope.find('.bt-elwg-product-testimonial--default');
		const $testimonialContent = $ProductTestimonial.find('.js-testimonial-content');
		const $testimonialImages = $ProductTestimonial.find('.js-testimonial-images');

		if ($testimonialContent.length > 0 && $testimonialImages.length > 0) {
			const $sliderSettings = $ProductTestimonial.data('slider-settings');
			const sliderSpeed = $sliderSettings.speed || 1000;
			const autoplay = $sliderSettings.autoplay || false;
			const autoplayDelay = $sliderSettings.autoplay_delay || 3000;
			// Initialize the testimonial content slider
			const testimonialContentSwiper = new Swiper($testimonialContent[0], {
				slidesPerView: 1,
				loop: true,
				speed: sliderSpeed,
				effect: 'fade',
				fadeEffect: {
					crossFade: true
				},
				autoplay: autoplay ? {
					delay: autoplayDelay,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $ProductTestimonial.find('.bt-button-next')[0],
					prevEl: $ProductTestimonial.find('.bt-button-prev')[0],
				},
			});
			// Initialize the testimonial images slider
			const testimonialImagesSwiper = new Swiper($testimonialImages[0], {
				slidesPerView: 1,
				loop: true,
				speed: sliderSpeed,
				effect: 'fade',
				fadeEffect: {
					crossFade: true
				},
				allowTouchMove: false,
			});
			
			// Sync both sliders
			testimonialContentSwiper.controller.control = testimonialImagesSwiper;
			testimonialImagesSwiper.controller.control = testimonialContentSwiper;
			
			// Pause autoplay on hover if autoplay is enabled
			if (autoplay) {
				$testimonialContent[0].addEventListener('mouseenter', () => {
					testimonialContentSwiper.autoplay.stop();
				});
				
				$testimonialContent[0].addEventListener('mouseleave', () => {
					testimonialContentSwiper.autoplay.start();
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
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-hotspot-product.default', HotspotProductHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-testimonial.default', ProductTestimonialHandler);
	});

})(jQuery);
