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
			const $catProductInput = $searchProduct.find('input[name="product_cat"]');

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
			// scroll window remove class active live search results
			$(window).scroll(function () {
				$liveSearchResults.removeClass('active');
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
							//	$liveSearchResults.addClass('loading');
							// Show loading skeleton for 3 product items
							let skeletonHtml = '';
							for (let i = 0; i < 3; i++) {
								skeletonHtml += `
							<div class="bt-product-skeleton product">
								<div class="bt-skeleton-thumb">
									<div class="bt-skeleton-image"></div>
									<div class="bt-skeleton-content">
										<div class="bt-skeleton-title"></div>
										<div class="bt-skeleton-price"></div>
									</div>
								</div>
								<div class="bt-skeleton-add-to-cart"></div>
							</div>
						`;
							}
							$dataSearch.html(skeletonHtml);
						},
						success: function (response) {
							if (response.success) {
								setTimeout(function () {
									$dataSearch.html(response.data['items']);
								}, 300);
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
		// Get Elementor breakpoints
		const $sliderSettings = $tiktokSlider.data('slider-settings');
		//	console.log($sliderSettings.breakpoints);
		if ($tiktokSlider.length > 0) {

			const $swiper = new Swiper($tiktokSlider[0], {
				slidesPerView: $sliderSettings.slidesPerView,
				loop: $sliderSettings.loop,
				spaceBetween: $sliderSettings.spaceBetween,
				speed: $sliderSettings.speed,
				pagination: {
					el: $tiktokSlider.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				autoplay: $sliderSettings.autoplay ? {
					delay: 3000,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $tiktokSlider.find('.bt-button-next')[0],
					prevEl: $tiktokSlider.find('.bt-button-prev')[0],
				},
				breakpoints: $sliderSettings.breakpoints,
			});

			if ($sliderSettings.autoplay) {
				$tiktokSlider[0].addEventListener('mouseenter', () => {
					$swiper.autoplay.stop();
				});
				$tiktokSlider[0].addEventListener('mouseleave', () => {
					$swiper.autoplay.start();
				});
			}
			// tiktok popup video
			$tiktokSlider.find('.js-open-popup').magnificPopup({
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
	function UtenzoshowToast(idproduct, tools = 'cart', status = 'add') {
		if ($(window).width() > 1024) { // Only run for screens wider than 1024px
			// ajax load product toast
			var param_ajax = {
				action: 'utenzo_load_product_toast',
				idproduct: idproduct,
				status: status,
				tools: tools
			};
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				beforeSend: function () {
				},
				success: function (response) {
					if (response.success) {
						// Append and show new toast
						$('.bt-toast').append(response.data['toast']);
						const $newToast = $('.bt-toast .bt-product-toast').last();
						setTimeout(() => {
							$newToast.addClass('show');
						}, 100);
						// Handle close button click
						$newToast.find('.bt-product-toast--close').on('click', function () {
							removeToast($newToast);
						});
						let toastTimeout;

						function startRemovalTimer($toast) {
							toastTimeout = setTimeout(() => {
								removeToast($toast);
							}, 3000);
						}

						// Handle hover events
						$newToast.hover(
							function () {
								// On mouse enter, clear the timeout
								clearTimeout(toastTimeout);
							},
							function () {
								// On mouse leave, start a new timeout
								startRemovalTimer($(this));
							}
						);

						// Start initial removal timer
						startRemovalTimer($newToast);

						function removeToast($toast) {
							$toast.addClass('remove-visibility');

							// Remove toast element after animation
							setTimeout(() => {
								$toast.addClass('remove-height');
								setTimeout(() => {
									$toast.remove();
								}, 300);
							}, 300);
						}
					}
				},
				error: function (xhr, status, error) {
					console.error('Ajax request failed:', {
						status: status,
						error: error,
						response: xhr.responseText
					});
				}
			});
		}
	}
	function UtenzoFreeShippingMessage() {
		$.ajax({
			url: AJ_Options.ajax_url,
			type: 'POST',
			data: {
				action: 'utenzo_get_free_shipping',
			},
			success: function (response) {
				if (response.success) {
					$(".bt-progress-bar").css("width", response.data['percentage'] + "%");
					$('#bt-free-shipping-message').html(response.data['message']);
				}
			},
		});
	}
	const HotspotProductHandler = function ($scope) {
		const $HotspotProduct = $scope.find('.bt-elwg-hotspot-product--default');
		if ($HotspotProduct.length > 0) {
			function getPositionPoint($point) {
				const pointLeft = $point.position().left;
				const pointTop = $point.position().top;
				const pointRight = $point.parent().width() - (pointLeft + $point.outerWidth());
				const pointBottom = $point.parent().height() - (pointTop + $point.outerHeight());
				const $info = $point.find('.bt-hotspot-product-info');
				const infoWidth = $info.outerWidth();
				const halfWidth = infoWidth / 2 - (window.innerWidth <= 600 ? 6 : 10);
				const infoHeight = $info.outerHeight();
				const halfHeight = infoHeight / 2 - (window.innerWidth <= 600 ? 6 : 10);
				const maxPoint = Math.max(pointLeft, pointTop, pointRight, pointBottom);
				if (maxPoint === pointRight) {
					if (infoWidth > pointRight) {
						const maxHigh = Math.max(pointTop, pointBottom);
						if (maxHigh === pointTop) {
							return 'topcenter';
						} else {
							return 'bottomcenter';
						}
					} else {
						if (halfHeight < pointTop && halfHeight < pointBottom) {
							return 'rightcenter';
						} else if (infoHeight < pointTop) {
							return 'righttop';
						} else {
							return 'rightbottom';
						}
					}
				} else if (maxPoint === pointLeft) {
					if (infoWidth > pointLeft) {
						const maxHigh = Math.max(pointTop, pointBottom);
						if (maxHigh === pointTop) {
							return 'topcenter';
						} else {
							return 'bottomcenter';
						}
					} else {
						if (halfHeight < pointTop && halfHeight < pointBottom) {
							return 'leftcenter';
						} else if (infoHeight < pointTop) {
							return 'lefttop';
						} else {
							return 'leftbottom';
						}
					}
				} else if (maxPoint === pointTop) {
					if (halfWidth < pointLeft && halfWidth < pointRight) {
						return 'topcenter';
					} else if (infoWidth < pointLeft) {
						return 'topleft';
					} else {
						return 'topright';
					}
				} else if (maxPoint === pointBottom) {
					if (halfWidth < pointLeft && halfWidth < pointRight) {
						return 'bottomcenter';
					} else if (infoWidth < pointLeft) {
						return 'bottomleft';
					} else {
						return 'bottomright';
					}
				}
			}
			function hotspotPoint() {
				$HotspotProduct.find('.bt-hotspot-point').each(function () {
					const $point = $(this);
					const $positionPoin = getPositionPoint($point);
					const $info = $point.find('.bt-hotspot-product-info');
					const containerWidth = $point.parent().width();
					let smallOffset = 5;
					let largeOffset = 15;
					if (containerWidth < 700) {
						smallOffset = 2;
						largeOffset = 8;
					}
					if ($positionPoin == 'rightcenter') {
						$info.css({
							'inset': 'auto auto auto 100%',
							'transform': `translateX(${largeOffset}px)`
						});
					} else if ($positionPoin == 'righttop') {
						$info.css({
							'inset': 'auto auto 100% 100%',
							'transform': `translate(0, -${smallOffset}px)`
						});
					} else if ($positionPoin == 'rightbottom') {
						$info.css({
							'inset': '100% auto auto 100%',
							'transform': `translate(0, ${smallOffset}px)`
						});
					} else if ($positionPoin == 'leftcenter') {
						$info.css({
							'inset': 'auto 100% auto auto',
							'transform': `translateX(-${largeOffset}px)`
						});
					} else if ($positionPoin == 'lefttop') {
						$info.css({
							'inset': 'auto 100% 100% auto',
							'transform': `translate(0, -${smallOffset}px)`
						});
					} else if ($positionPoin == 'leftbottom') {
						$info.css({
							'inset': '100% 100% auto auto',
							'transform': `translate(0, ${smallOffset}px)`
						});
					} else if ($positionPoin == 'topcenter') {
						$info.css({
							'inset': 'auto auto 100% auto',
							'transform': `translateY(-${largeOffset}px)`
						});
					} else if ($positionPoin == 'topleft') {
						$info.css({
							'inset': 'auto 100% 100% auto',
							'transform': `translate(0, -${smallOffset}px)`
						});
					} else if ($positionPoin == 'topright') {
						$info.css({
							'inset': 'auto auto 100% 100%',
							'transform': `translate(0, -${smallOffset}px)`
						});
					} else if ($positionPoin == 'bottomcenter') {
						$info.css({
							'inset': '100% auto auto auto',
							'transform': `translateY(${largeOffset}px)`
						});
					} else if ($positionPoin == 'bottomleft') {
						$info.css({
							'inset': '100% 100% auto auto',
							'transform': `translate(0, ${smallOffset}px)`
						});
					} else if ($positionPoin == 'bottomright') {
						$info.css({
							'inset': '100% auto auto 100%',
							'transform': `translate(0, ${smallOffset}px)`
						});
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
				loop: $sliderSettings.loop || false,
				spaceBetween: $sliderSettings.spaceBetween.mobile,
				speed: $sliderSettings.speed,
				pagination: {
					el: $Hotspotslider.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				autoplay: $sliderSettings.autoplay ? {
					delay: 3000,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $Hotspotslider.find('.bt-button-next')[0],
					prevEl: $Hotspotslider.find('.bt-button-prev')[0],
				},

				breakpoints: {
					1560: {
						slidesPerView: 3,
						spaceBetween: $sliderSettings.spaceBetween.desktop
					},
					1025: {
						slidesPerView: 2,
						spaceBetween: $sliderSettings.spaceBetween.desktop
					},
					767: {
						slidesPerView: 3,
						spaceBetween: $sliderSettings.spaceBetween.tablet
					},
					500: {
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
				if ($this.hasClass('bt-view-cart')) {
					window.location.href = AJ_Options.cart;
					return;
				}
				const productIds = $this.find('.bt-btn-price').data('ids');
				// Loop through each product ID and show toast notification
				if (Array.isArray(productIds)) {
					productIds.forEach(productId => {
						setTimeout(() => {
							UtenzoshowToast(productId, 'cart', 'add');
						}, productIds.indexOf(productId) * 300); // Add 300ms delay between each toast
					});
				}
				UtenzoshowToast
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
								$(document.body).trigger('updated_wc_div');
								UtenzoFreeShippingMessage();
								$this.html('View Cart');
								$this.addClass('bt-view-cart');
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
	const ProductTestimonialSliderHandler = function ($scope) {
		const $ProductTestimonialSlider = $scope.find('.js-data-testimonial-slider');
		if ($ProductTestimonialSlider.length > 0) {
			const $sliderSettings = $ProductTestimonialSlider.data('slider-settings') || {};
			console.log($sliderSettings);
			// Initialize the testimonial slider
			const testimonialSlider = new Swiper($ProductTestimonialSlider.find('.js-testimonial-slider')[0], {
				slidesPerView: $sliderSettings.slidesPerView,
				spaceBetween: $sliderSettings.spaceBetween,
				loop: $sliderSettings.loop,
				speed: $sliderSettings.speed,
				autoplay: $sliderSettings.autoplay ? {
					delay: $sliderSettings.autoplay_delay,
					disableOnInteraction: false
				} : false,
				navigation: {
					nextEl: $scope.find('.bt-button-next')[0],
					prevEl: $scope.find('.bt-button-prev')[0],
				},
				pagination: {
					el: $scope.find('.bt-swiper-pagination')[0],
					clickable: true,
					type: 'bullets',
					renderBullet: function (index, className) {
						return '<span class="' + className + '"></span>';
					},
				},
				breakpoints: $sliderSettings.breakpoints,
			});

			// Pause autoplay on hover if autoplay is enabled
			if (autoplay) {
				$ProductTestimonialSlider.find('.js-testimonial-slider')[0].addEventListener('mouseenter', () => {
					testimonialSlider.autoplay.stop();
				});

				$ProductTestimonialSlider.find('.js-testimonial-slider')[0].addEventListener('mouseleave', () => {
					testimonialSlider.autoplay.start();
				});
			}
		}
	};
	const countDownHandler = function ($scope) {
		const countDown = $scope.find('.bt-countdown-js');
		const countDownDate = new Date(countDown.data('time')).getTime();

		if (isNaN(countDownDate)) {
			console.error('Invalid countdown date');
			return;
		}
		const timer = setInterval(() => {
			const now = new Date().getTime();
			const distance = countDownDate - now;

			if (distance < 0) {
				clearInterval(timer);
				countDown.html('<div class="bt-countdown-expired">EXPIRED</div>');
				return;
			}

			const days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
			const hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
			const mins = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
			const secs = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');

			countDown.find('.bt-countdown-days').text(days);
			countDown.find('.bt-countdown-hours').text(hours);
			countDown.find('.bt-countdown-mins').text(mins);
			countDown.find('.bt-countdown-secs').text(secs);
		}, 1000);
	}
	const NotificationSliderHandler = function ($scope) {
		const $notificationWrapper = $scope.find('.bt-elwg-site-notification--default ');
		const $notificationContent = $notificationWrapper.find('.js-notification-content');

		if ($notificationContent.length > 0) {
			const $sliderSettings = $notificationWrapper.data('slider-settings');
			const sliderSpeed = $sliderSettings.speed || 1000;
			const autoplay = $sliderSettings.autoplay || false;
			const autoplayDelay = $sliderSettings.autoplay_delay || 3000;
			// Initialize the notification content slider
			const notificationContentSwiper = new Swiper($notificationContent[0], {
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
			});

			// Pause autoplay on hover if autoplay is enabled
			if (autoplay) {
				$notificationContent[0].addEventListener('mouseenter', () => {
					notificationContentSwiper.autoplay.stop();
				});

				$notificationContent[0].addEventListener('mouseleave', () => {
					notificationContentSwiper.autoplay.start();
				});
			}
		}
	};
	// mini cart
	const MiniCartHandler = function ($scope) {
		const $miniCart = $scope.find('.bt-elwg-mini-cart--default');
		const $sidebar = $miniCart.find('.bt-mini-cart-sidebar');

		// Toggle mini cart
		$miniCart.find('.js-cart-sidebar').on('click', function (e) {
			e.preventDefault();
			$sidebar.addClass('active');
			const scrollbarWidth = window.innerWidth - $(window).width();
			$('body').css({
				'overflow': 'hidden',
				'padding-right': scrollbarWidth + 'px' // Prevent layout shift
			});
		});

		// Close mini cart when clicking overlay or close button
		$miniCart.find('.bt-mini-cart-sidebar-overlay, .bt-mini-cart-close').on('click', function () {
			closeMiniCart();
		});

		// Close mini cart when pressing ESC key
		$(document).keyup(function (e) {
			if (e.key === "Escape") {
				closeMiniCart();
			}
		});

		// Helper function to close mini cart
		function closeMiniCart() {
			$sidebar.removeClass('active');
			$('body').css({
				'overflow': 'auto', // Restore body scroll
				'padding-right': '0' // Reset padding-right
			});
		}
	}
	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-location-list.default', LocationListHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-list-faq.default', FaqHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-search-product.default', SearchProductHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-heading-animation.default', headingAnimationHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-tiktok-shop-slider.default', TiktokShopSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-hotspot-product.default', HotspotProductHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-testimonial.default', ProductTestimonialHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-product-testimonial-slider.default', ProductTestimonialSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-countdown.default', countDownHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-site-notification.default', NotificationSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-mini-cart.default', MiniCartHandler);
	});

})(jQuery);
