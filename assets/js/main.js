!(function ($) {
	"use strict";

	/* Toggle submenu align */
	function UtenzoSubmenuAuto() {
		if ($('.bt-site-header .bt-container').length > 0) {
			var container = $('.bt-site-header .bt-container'),
				containerInfo = { left: container.offset().left, width: container.innerWidth() },
				contLeftPos = containerInfo.left,
				contRightPos = containerInfo.left + containerInfo.width;

			$('.children, .sub-menu').each(function () {
				var submenuInfo = { left: $(this).offset().left, width: $(this).innerWidth() },
					smLeftPos = submenuInfo.left,
					smRightPos = submenuInfo.left + submenuInfo.width;

				if (smLeftPos <= contLeftPos) {
					$(this).addClass('bt-align-left');
				}

				if (smRightPos >= contRightPos) {
					$(this).addClass('bt-align-right');
				}

			});
		}
	}

	/* Toggle menu mobile */
	function UtenzoToggleMenuMobile() {
		$('.bt-site-header .bt-menu-toggle').on('click', function (e) {
			e.preventDefault();

			if ($(this).hasClass('bt-menu-open')) {
				$(this).addClass('bt-is-hidden');
				$('.bt-site-header .bt-primary-menu').addClass('bt-is-active');
			} else {
				$('.bt-menu-open').removeClass('bt-is-hidden');
				$('.bt-site-header .bt-primary-menu').removeClass('bt-is-active');
			}
		});
	}

	/* Toggle sub menu mobile */
	function UtenzoToggleSubMenuMobile() {
		var hasChildren = $('.bt-site-header .page_item_has_children, .bt-site-header .menu-item-has-children');

		hasChildren.each(function () {
			var $btnToggle = $('<div class="bt-toggle-icon"></div>');

			$(this).append($btnToggle);

			$btnToggle.on('click', function (e) {
				e.preventDefault();
				$(this).toggleClass('bt-is-active');
				$(this).parent().children('ul').toggle();
			});
		});
	}
	/* Shop */
	function UtenzoShop() {
		if ($('.woocommerce-product-gallery__wrapper').length > 0) {
			$('.woocommerce-product-zoom__image').zoom();

			$('.woocommerce-product-gallery__slider').slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				fade: true,
				arrows: false,
				asNavFor: '.woocommerce-product-gallery__slider-nav',
				prevArrow: '<button type=\"button\" class=\"slick-prev\">Prev</button>',
				nextArrow: '<button type=\"button\" class=\"slick-next\">Next</button>'
			});
			$('.woocommerce-product-gallery__slider-nav').slick({
				slidesToShow: 5,
				slidesToScroll: 1,
				arrows: false,
				focusOnSelect: true,
				asNavFor: '.woocommerce-product-gallery__slider'
			});
		}
		if ($('.quantity input').length > 0) {
			/* Plus Qty */
			$(document).on('click', '.qty-plus', function () {
				var parent = $(this).parent();
				$('input.qty', parent).val(parseInt($('input.qty', parent).val()) + 1);
				$('input.qty', parent).trigger('change');
			});
			/* Minus Qty */
			$(document).on('click', '.qty-minus', function () {
				var parent = $(this).parent();
				if (parseInt($('input.qty', parent).val()) > 1) {
					$('input.qty', parent).val(parseInt($('input.qty', parent).val()) - 1);
					$('input.qty', parent).trigger('change');
				}
			});
		}
		if ($('.variations_form').length > 0) {
			$("form.variations_form").on("change", "input, select", function () {
				var variation_id = $("input.variation_id").val();
				if (variation_id) {
					var price = $(".single_variation_wrap .price").html();
					var priceAddCart = '<span class="price-add-cart"> - ' + $(".single_variation_wrap .price").html() + '</span>';
					if (price) {
						$(".single_add_to_cart_button").html("Add to cart" + priceAddCart);
					}
				}
			});
			$('.bt-attributes-wrap .bt-js-item').on('click', function () {
				var valueItem = $(this).data('value');
				var attributesItem = $(this).closest('.bt-attributes--item');
				var attributeName = attributesItem.data('attribute-name');
				attributesItem.find('.bt-js-item').removeClass('active'); // Remove active class only from items in the same attribute group
				$(this).addClass('active'); // Add active class to clicked item
				var nameItem = (attributeName == 'pa_color') ? $(this).find('label').text() : $(this).text();
				attributesItem.find('.bt-result').text(nameItem);
				$('select#' + attributeName).val(valueItem).trigger('change');
				/* button buy now */
				var variationId = $('input.variation_id').val();
				if (variationId) {
					$('.bt-button-buy-now a').removeClass('disabled').attr('data-variation', variationId);
				} else {
					$('.bt-button-buy-now a').addClass('disabled').removeAttr('data-variation');
				}
				$('.bt-attributes-wrap .bt-js-item').each(function () {
					var valueItem = $(this).data('value');
					var attributesItem = $(this).closest('.bt-attributes--item');
					var attributeName = attributesItem.data('attribute-name');
					var options = $('select#' + attributeName + ' option');
					var optionExists = false;
					options.each(function () {
						if ($(this).val() == valueItem) {
							optionExists = true;
							return false; // break the loop
						}
					});
					if (!optionExists) {
						$(this).addClass('disabled');
					} else {
						$(this).removeClass('disabled');
					}
				});
			});
		}
	}
	/* Validation form comment */
	function UtenzoCommentValidation() {
		if ($('#bt_comment_form').length) {
			jQuery('#bt_comment_form').validate({
				rules: {
					author: {
						required: true,
						minlength: 2
					},
					email: {
						required: true,
						email: true
					},
					comment: {
						required: true,
						minlength: 20
					}
				},
				errorElement: "div",
				errorPlacement: function (error, element) {
					element.after(error);
				}
			});
		}
	}
	/* Product wishlist */
	function UtenzoProductWishlist() {
		if ($('.bt-product-wishlist-btn').length > 0) {
			$(document).on('click', '.bt-product-wishlist-btn', function (e) {
				e.preventDefault();

				var post_id = $(this).data('id').toString(),
					wishlist_local = window.localStorage.getItem('productwishlistlocal');
				if (!wishlist_local) {
					window.localStorage.setItem('productwishlistlocal', post_id);
					wishlist_local = window.localStorage.getItem('productwishlistlocal');
					var wishlist_count = wishlist_local ? wishlist_local.split(',').length : 0;
					$('.bt-mini-wishlist .wishlist_total').html(wishlist_count);
					$(this).addClass('loading');
					$(this).removeClass('no-added');
					setTimeout(function () {
						$('.bt-product-wishlist-btn[data-id="' + post_id + '"]').addClass('added');
						$('.bt-product-wishlist-btn[data-id="' + post_id + '"]').removeClass('loading');
						$('.bt-product-wishlist-btn[data-id="' + post_id + '"] .tooltip').text("View Wishlist");
					}, 500);
				} else {
					var wishlist_arr = wishlist_local.split(',');

					if (wishlist_arr.includes(post_id)) {
						window.location.href = AJ_Options.page_wishlist;
					} else {
						window.localStorage.setItem('productwishlistlocal', wishlist_local + ',' + post_id);
						wishlist_local = window.localStorage.getItem('productwishlistlocal');
						var wishlist_count = wishlist_local ? wishlist_local.split(',').length : 0;
						$('.bt-mini-wishlist .wishlist_total').html(wishlist_count);
						$(this).addClass('loading');
						$(this).removeClass('no-added');
						setTimeout(function () {
							$('.bt-product-wishlist-btn[data-id="' + post_id + '"]').addClass('added');
							$('.bt-product-wishlist-btn[data-id="' + post_id + '"]').removeClass('loading');
							$('.bt-product-wishlist-btn[data-id="' + post_id + '"] .tooltip').text("View Wishlist");
						}, 500);
					}
				}
			});
		}

		if ($('.elementor-widget-bt-product-wishlist').length > 0) {
			$(document).on('click', '.bt-product-remove-wishlist', function (e) {
				e.preventDefault();
				$(this).addClass('deleting');
				var product_id = $(this).data('id').toString(),
					wishlist_str = $('.bt-productwishlistlocal').val(),
					wishlist_arr = wishlist_str.split(','),
					index = wishlist_arr.indexOf(product_id);

				if (index > -1) {
					wishlist_arr.splice(index, 1);
				}

				wishlist_str = wishlist_arr.toString();
				$('.bt-productwishlistlocal').val(wishlist_str);
				window.localStorage.setItem('productwishlistlocal', wishlist_str);
				UtenzoShareLocalStorage(wishlist_str);
				$('.bt-products-wishlist-form').submit();
			});

			// Ajax wishlist
			$('.bt-products-wishlist-form').submit(function () {

				var param_ajax = {
					action: 'utenzo_products_wishlist',
					productwishlist_data: $('.bt-productwishlistlocal').val()
				};

				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: AJ_Options.ajax_url,
					data: param_ajax,
					context: this,
					beforeSend: function () {
						$('.bt-table--body').addClass('loading');
					},
					success: function (response) {
						if (response.success) {
							$('.bt-product-list').html(response.data['items']).fadeIn('slow');
							$('.bt-mini-wishlist .wishlist_total').html(response.data['count']);
							$('.bt-table--body').removeClass('loading');
						} else {
							console.log('error');
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log('The following error occured: ' + textStatus, errorThrown);
					}
				});

				return false;
			});
		}
	}
	/* Product Wishlist Load */
	function UtenzoProductWishlistLoad() {
		var wishlist_local = window.localStorage.getItem('productwishlistlocal');
		if (!wishlist_local) {
			return;
		}
		if ($('.elementor-widget-bt-product-wishlist').length > 0) {
			UtenzoShareLocalStorage(wishlist_local);
			$('.bt-productwishlistlocal').val(wishlist_local);
			var param_ajax = {
				action: 'utenzo_products_wishlist',
				productwishlist_data: wishlist_local
			};

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				context: this,
				beforeSend: function () {
					$('.bt-table--body').addClass('loading');
				},
				success: function (response) {
					if (response.success) {
						$('.bt-product-list').html(response.data['items']).fadeIn('slow');
						$('.bt-mini-wishlist .wishlist_total').html(response.data['count']);
						$('.bt-table--body').removeClass('loading');
					} else {
						console.log('error');
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('The following error occured: ' + textStatus, errorThrown);
				}
			});
		}
	}
	/* Product content compare scroll  */
	function UtenzoCompareContentScroll() {
		const $compareTable = $('.bt-table--body');
		if ($compareTable.length) {
			let isDown = false;
			let startX, scrollLeft;

			$($compareTable).on("mousedown", function (e) {
				isDown = true;
				startX = e.pageX - $(this).offset().left;
				scrollLeft = $(this).scrollLeft();
				$(this).css({
					"cursor": "grabbing",
				});
			});

			$(document).on("mouseup", function () {
				isDown = false;
				$($compareTable).css({
					"cursor": "grab",
				});
			});

			$($compareTable).on("mousemove", function (e) {
				if (!isDown) return;
				e.preventDefault();
				let x = e.pageX - $(this).offset().left;
				let walk = (x - startX) * 1.5;
				$(this).scrollLeft(scrollLeft - walk);
			});

			// Add momentum scrolling
			$($compareTable).on("mouseleave", function () {
				if (isDown && typeof walk !== 'undefined') {
					let velocity = walk;
					let deceleration = 0.95;
					let momentum = setInterval(function () {
						velocity *= deceleration;
						$($compareTable).scrollLeft($($compareTable).scrollLeft() + velocity);
						if (Math.abs(velocity) < 0.5) {
							clearInterval(momentum);
						}
					}, 10);
				}
			});

			// Prevent text selection on mouseup outside the element
			$(document).on("selectstart", function (e) {
				if (isDown) {
					e.preventDefault();
					return false;
				}
			});
		}
	}
	/* Product compare */
	function UtenzoProductCompare() {
		function showComparePopup() {
			$('.bt-popup-compare').addClass('active');
			$('.bt-compare-body').addClass('show');
		}
		function removeComparePopup() {
			$('.bt-compare-body').removeClass('show');
			setTimeout(function () {
				$('.bt-popup-compare').removeClass('active');
			}, 300);
		}
		if ($('.bt-product-compare-btn').length > 0) {
			$('body').append('<div class="bt-popup-compare"><div class="bt-compare-overlay"></div><div class="bt-compare-body"><div class="bt-loading-wave"></div><div class="bt-compare-close"></div><div class="bt-compare-load"></div></div></div>').fadeIn('slow');

			$(document).on('click', '.bt-product-compare-btn', function (e) {
				e.preventDefault();
				$(this).find('.tooltip').remove();
				var post_id = $(this).data('id').toString(),
					compare_local = window.localStorage.getItem('productcomparelocal');
				if (!compare_local) {
					window.localStorage.setItem('productcomparelocal', post_id);
					compare_local = window.localStorage.getItem('productcomparelocal');
					$(this).addClass('loading');
					$(this).removeClass('no-added');
				} else {
					var compare_arr = compare_local.split(',');
					if (!compare_arr.includes(post_id)) {
						window.localStorage.setItem('productcomparelocal', compare_local + ',' + post_id);
						compare_local = window.localStorage.getItem('productcomparelocal');
						$(this).addClass('loading');
						$(this).removeClass('no-added');
					}
				}
				var param_ajax = {
					action: 'utenzo_products_compare',
					compare_data: compare_local,
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
							$('.bt-product-compare-btn[data-id="' + post_id + '"]').removeClass('loading');
							$('.bt-product-compare-btn[data-id="' + post_id + '"]').addClass('added');
							showComparePopup();
							$('.bt-popup-compare .bt-compare-load').html(response.data['product']).fadeIn('slow');
							UtenzoCompareContentScroll();
							// close popup quick view
							if ($('.bt-popup-quick-view').hasClass('active')) {
								$('.bt-quick-view-body').removeClass('show');
								setTimeout(function () {
									$('.bt-popup-quick-view').removeClass('active');
								}, 300);
							}
						} else {
							console.log('error');
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log('The following error occured: ' + textStatus, errorThrown);
					}
				});
			});
			$(document).on('click', '.bt-popup-compare .bt-compare-overlay', function () {
				removeComparePopup();
			});
			$(document).on('click', '.bt-popup-compare .bt-compare-close', function () {
				if (!$('.bt-popup-compare').hasClass('bt-compare-elwwg')) {
					removeComparePopup();
				}
			});
			$(document).on('keydown', function (e) {
				if (e.key === 'Escape') {
					removeComparePopup();
				}
			});
		}
		$(document).on('click', '.bt-product-add-compare .bt-cover-image', function () {
			$('.bt-compare-body').removeClass('show');
			setTimeout(function () {
				if ($('body').hasClass('archive')) {
					$('.bt-popup-compare').removeClass('active');
				} else {
					window.location.href = AJ_Options.shop;
				}
			}, 300);
		});

		$(document).on('click', '.bt-remove-item', function (e) {
			e.preventDefault();
			var compare_local = window.localStorage.getItem('productcomparelocal');
			var product_id = $(this).data('id').toString(),
				compare_arr = compare_local.split(','),
				index = compare_arr.indexOf(product_id);

			if (index > -1) {
				compare_arr.splice(index, 1);
			}
			window.localStorage.setItem('productcomparelocal', compare_arr);
			compare_local = window.localStorage.getItem('productcomparelocal');
			if ($('.bt-popup-compare').hasClass('bt-compare-elwwg')) {
				UtenzoShareLocalStorage(compare_local);
			}
			$('.bt-product-compare-btn[data-id="' + product_id + '"]').addClass('no-added');
			$('.bt-product-compare-btn[data-id="' + product_id + '"]').removeClass('added');
			if (!compare_local || compare_local === '') {
				removeComparePopup();
				$('.bt-compare-body').removeClass('loading');
				return;
			}
			var param_ajax = {
				action: 'utenzo_products_compare',
				compare_data: compare_local,
			};
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				beforeSend: function () {
					$('.bt-compare-body').addClass('loading');
				},
				success: function (response) {
					if (response.success) {
						//console.log(response.data['count']);
						if (response.data['count'] && response.data['count'] > 0) {
							$('.bt-popup-compare .bt-compare-load').html(response.data['product']).fadeIn('slow');
							$('.bt-compare-body').removeClass('loading');
							UtenzoCompareContentScroll();
						} else {
							if (!$('.bt-popup-compare').hasClass('bt-compare-elwwg')) {
								removeComparePopup();
								$('.bt-compare-body').removeClass('loading');
							} else {
								$('.bt-popup-compare .bt-compare-load').html(response.data['product']).fadeIn('slow');
								$('.bt-compare-body').removeClass('loading');
							}
						}
					} else {
						console.log('error');
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('The following error occured: ' + textStatus, errorThrown);
				}
			});
		});
	}
	/* Product Compare Load */
	function UtenzoProductCompareLoad() {
		var compare_local = window.localStorage.getItem('productcomparelocal');
		if (!compare_local) {
			return;
		}
		if ($('.elementor-widget-bt-product-compare').length > 0) {
			UtenzoShareLocalStorage(compare_local);
			var param_ajax = {
				action: 'utenzo_products_compare',
				compare_data: compare_local
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
						setTimeout(function () {
							$('.bt-popup-compare .bt-compare-load').html(response.data['product']).fadeIn('slow');
							UtenzoCompareContentScroll();
						}, 100);
					} else {
						console.log('error');
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('The following error occured: ' + textStatus, errorThrown);
				}
			});
		}
	}
	/* Product Quick View */
	function UtenzoProductQuickView() {
		if ($('.bt-product-quick-view-btn').length > 0) {
			$('body').append('<div class="bt-popup-quick-view"><div class="bt-quick-view-overlay"></div><div class="bt-quick-view-body"><div class="bt-quick-view-load"></div></div></div>');
			function showQuickViewPopup() {
				$('.bt-popup-quick-view').addClass('active');
				$('.bt-quick-view-body').addClass('show');
			}
			function removeQuickViewPopup() {
				$('.bt-quick-view-body').removeClass('show');
				setTimeout(function () {
					$('.bt-popup-quick-view').removeClass('active');
				}, 300);
			}
			$(document).on('click', '.bt-product-quick-view-btn', function (e) {
				e.preventDefault();
				var productid = $(this).data('id');
				$(this).find('.tooltip').remove();
				$(this).addClass('loading');
				var param_ajax = {
					action: 'utenzo_products_quick_view',
					productid: productid,
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
							$('.bt-product-quick-view-btn').removeClass('loading');
							showQuickViewPopup();
							$('.bt-popup-quick-view .bt-quick-view-load').html(response.data['product']).fadeIn('slow');
							UtenzoShop();
							UtenzoProductButtonStatus();
							if (typeof $.fn.wc_variation_form !== 'undefined') {
								$('.variations_form').wc_variation_form();
							}
						} else {
							console.log('error');
						}
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log('The following error occured: ' + textStatus, errorThrown);
					}
				});

			});
			$(document).on('click', '.bt-popup-quick-view .bt-quick-view-overlay', function () {
				removeQuickViewPopup();
			});
			$(document).on('click', '.bt-popup-quick-view .bt-quick-view-close', function () {
				removeQuickViewPopup();
			});
			$(document).on('keydown', function (e) {
				if (e.key === 'Escape') {
					removeQuickViewPopup();
				}
			});
		}

	}
	/* Product Filter */
	function UtenzoProductsFilter() {
		if (!$('body').hasClass('post-type-archive-product')) {
			return;
		}

		// Search by keywords
		$('.bt-product-filter-form .bt-field-type-search input').on('keyup', function (e) {
			if (e.key === 'Enter' || e.keyCode === 13) {
				$('.bt-product-filter-form .bt-product-current-page').val('');
				$('.bt-product-filter-form').submit();
			}
		});

		$('.bt-product-filter-form  .bt-field-type-search a').on('click', function (e) {
			e.preventDefault();
			$('.bt-product-filter-form .bt-product-current-page').val('');
			$('.bt-product-filter-form').submit();
		});

		//Sort order
		$('.bt-product-sort-block select').select2({
			dropdownParent: $('.bt-product-sort-block'),
			minimumResultsForSearch: Infinity
		});
		$('.bt-product-sort-block select').on('change', function () {
			var sort_val = $(this).val();

			$('.bt-product-filter-form .bt-product-sort-order').val(sort_val);
			$('.bt-product-filter-form').submit();
		});


		// Pagination
		$('.bt-product-pagination-wrap').on('click', '.bt-product-pagination a', function (e) {
			e.preventDefault();

			var current_page = $(this).data('page');

			if (1 < current_page) {
				$('.bt-product-filter-form .bt-product-current-page').val(current_page);
			} else {
				$('.bt-product-filter-form .bt-product-current-page').val('');
			}

			$('.bt-product-filter-form').submit();
		});

		// Filter Slider
		if ($('#bt-price-slider').length > 0) {
			const priceSlider = document.getElementById('bt-price-slider');

			var rangeMin = $('#bt-price-slider').data('range-min'),
				rangeMax = $('#bt-price-slider').data('range-max'),
				startMin = $('#bt-price-slider').data('start-min'),
				startMax = $('#bt-price-slider').data('start-max');
			noUiSlider.create(priceSlider, {
				start: [startMin, startMax],
				connect: true,
				range: {
					'min': rangeMin,
					'max': rangeMax
				},
				step: 1
			});

			const minPriceValue = $('#bt-min-price');
			const maxPriceValue = $('#bt-max-price');
			let timeout;
			priceSlider.noUiSlider.on('change', function (values, handle) {
				minPriceValue.val(parseInt(values[0]));
				maxPriceValue.val(parseInt(values[1]));
				$('.bt-product-filter-form .bt-product-current-page').val('');
				$('.bt-product-filter-form').submit();
			});
			minPriceValue.on('input', function () {
				clearTimeout(timeout);
				timeout = setTimeout(function () {
					let minValue = parseInt(minPriceValue.val());
					const maxValue = parseInt(maxPriceValue.val());

					if (!isNaN(minValue)) {
						if (minValue > maxValue) {
							minValue = maxValue;
							minPriceValue.val(minValue);
						} else if (minValue < rangeMin) {
							minValue = rangeMin;
							minPriceValue.val(minValue);
						}
						priceSlider.noUiSlider.set([minValue, null]);
						if (!isNaN(maxValue)) {
							$('.bt-product-filter-form .bt-product-current-page').val('');
							$('.bt-product-filter-form').submit();
						} else {
							maxPriceValue.val(rangeMax);
							$('.bt-product-filter-form .bt-product-current-page').val('');
							$('.bt-product-filter-form').submit();
						}
					}
				}, 500);
			});

			maxPriceValue.on('input', function () {
				clearTimeout(timeout);
				timeout = setTimeout(function () {
					const minValue = parseInt(minPriceValue.val());
					let maxValue = parseInt(maxPriceValue.val());

					if (!isNaN(maxValue)) {
						if (maxValue < minValue) {
							maxValue = minValue;
							maxPriceValue.val(maxValue);
						} else if (maxValue > rangeMax) {
							maxValue = rangeMax;
							maxPriceValue.val(maxValue);
						}
						priceSlider.noUiSlider.set([null, maxValue]);
						if (!isNaN(minValue)) {
							$('.bt-product-filter-form .bt-product-current-page').val('');
							$('.bt-product-filter-form').submit();
						} else {
							minPriceValue.val(rangeMin);
							$('.bt-product-filter-form .bt-product-current-page').val('');
							$('.bt-product-filter-form').submit();
						}
					}
				}, 500);
			});
		}

		//Filter single tax
		if ($('.bt-field-type-radio').length > 0) {
			$('.bt-field-type-radio input').on('change', function () {
				$('.bt-product-filter-form .bt-product-current-page').val('');
				$('.bt-product-filter-form').submit();
			});
		}

		//Filter multiple tax
		if ($('.bt-field-type-multi').length > 0) {
			$('.bt-field-type-multi a').on('click', function (e) {
				e.preventDefault();

				if ($(this).parent().hasClass('checked')) {
					$(this).parent().removeClass('checked');
				} else {
					$(this).parent().addClass('checked');
				}

				var value_arr = [];

				$(this).parents('.bt-form-field').find('.bt-field-item').each(function () {
					if ($(this).hasClass('checked')) {
						value_arr.push($(this).children().data('slug'));
					}
				});

				$(this).parents('.bt-form-field').find('input').val(value_arr.toString());
				$('.bt-product-filter-form .bt-product-current-page').val('');
				$('.bt-product-filter-form').submit();
			});
		}
		//Filter rating
		if ($('.bt-field-type-rating ').length > 0) {
			$('.bt-field-type-rating input').on('change', function () {
				$('.bt-product-filter-form .bt-product-current-page').val('');
				$('.bt-product-filter-form').submit();
			});
		}
		// Filter reset
		if (window.location.href.includes('?')) {
			$('.bt-product-filter-form .bt-reset-btn').removeClass('disable');
		}

		$('.bt-product-filter-form .bt-reset-btn').on('click', function (e) {
			e.preventDefault();

			if ($(this).hasClass('disable')) {
				return;
			}

			window.history.replaceState(null, null, window.location.pathname);
			$('.bt-product-filter-form input').not('[type="radio"]').val('');
			$('.bt-product-filter-form input[type="radio"]').prop('checked', false);
			$('.bt-product-filter-form .bt-field-item').removeClass('checked');
			$('.bt-product-filter-form select').select2().val('').trigger('change');
			$(this).addClass('disable')

			$('.bt-product-filter-form').submit();
		});

		// Ajax filter
		$('.bt-product-filter-form').submit(function () {
			var param_str = '',
				param_out = [],
				param_in = $(this).serialize().split('&');

			var param_ajax = {
				action: 'utenzo_products_filter',
			};

			param_in.forEach(function (param) {
				var param_key = param.split('=')[0],
					param_val = param.split('=')[1];

				if ('' !== param_val) {
					param_out.push(param);
					param_ajax[param_key] = param_val.replace(/%2C/g, ',');
				}
			});

			if (0 < param_out.length) {
				param_str = param_out.join('&');
			}

			if ('' !== param_str) {
				window.history.replaceState(null, null, `?${param_str}`);
				$(this).find('.bt-reset-btn').removeClass('disable');
			} else {
				window.history.replaceState(null, null, window.location.pathname);
				$(this).find('bt-reset-btn').addClass('disable');
			}

			// console.log(param_ajax);

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				context: this,
				beforeSend: function () {
					document.querySelector('.bt-filter-scroll-pos').scrollIntoView({
						behavior: 'smooth'
					});

					$('.bt-product-layout').addClass('loading');
					$('.bt-product-layout .woocommerce-loop-products').fadeOut('fast');
					$('.bt-product-pagination-wrap').fadeOut('fast');
				},
				success: function (response) {
					console.log(response);
					if (response.success) {

						setTimeout(function () {
							$('.bt-results-count').html(response.data['results']).fadeIn('slow');
							$('.bt-product-layout .woocommerce-loop-products').html(response.data['items']).fadeIn('slow');
							$('.bt-product-pagination-wrap').html(response.data['pagination']).fadeIn('slow');
							$('.bt-product-layout').removeClass('loading');
							UtenzoProductButtonStatus();
						}, 500);
					} else {
						console.log('error');
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('The following error occured: ' + textStatus, errorThrown);
				}
			});

			return false;
		});
	}
	/* Product Button toggle Filter*/
	function UtenzoProductFilterToggle() {
		if ($('.bt-product-filter-toggle').length > 0) {
			$('.bt-product-filter-toggle').on('click', function () {
				$(this).parents('.bt-main-content').find('.bt-products-sidebar').addClass('active');
			});
			$('.bt-popup-overlay').on('click', function () {
				$(this).parents('.bt-main-content').find('.bt-products-sidebar').removeClass('active');
			});
			$('.bt-form-button .bt-close-btn').on('click', function (e) {
				e.preventDefault();
				$(this).parents('.bt-main-content').find('.bt-products-sidebar').removeClass('active');
			});
		}
	}
	function UtenzoAttachTooltip(targetSelector, tooltipText) {
		var timeout;

		function hideTooltip(element) {
			timeout = setTimeout(function () {
				element.find('.tooltip').fadeOut(200, function () {
					$(this).remove();
				});
			}, 200);
		}

		$(document).on('mouseenter', targetSelector, function () {
			clearTimeout(timeout);
			if (!$(this).find('.tooltip').length) {
				var tooltip = $('<span class="tooltip"></span>').text(tooltipText);
				$(this).append(tooltip);
				tooltip.fadeIn(200);
			}
		});

		$(document).on('mouseleave', targetSelector, function () {
			hideTooltip($(this));
		});
	}
	function UtenzoAttachTooltips() {
		UtenzoAttachTooltip('.bt-product-wishlist-btn.no-added', 'Add to Wishlist');
		UtenzoAttachTooltip('.bt-product-wishlist-btn.added', 'View Wishlist');
		UtenzoAttachTooltip('.bt-product-compare-btn.no-added', 'Add to Compare');
		UtenzoAttachTooltip('.bt-product-compare-btn.added', 'View Compare');
		UtenzoAttachTooltip('.bt-product-quick-view-btn', 'Quick View');
	}
	function UtenzoUpdateMiniCart() {
		var timeout;
		$(document.body).on('change input', 'input.qty', function () {
			if (timeout !== undefined) clearTimeout(timeout);
			timeout = setTimeout(function () {
				$('[name=update_cart]').trigger('click');
			}, 500);
		});
		if (typeof wc_cart_fragments_params !== 'undefined') {
			$('form.woocommerce-cart-form').on('submit', function (event) {
				event.preventDefault();
				var $form = $(this);
				$.ajax({
					url: $form.attr('action'),
					type: 'POST',
					data: $form.serialize(),
					success: function () {
						$.ajax({
							url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
							type: 'POST',
							success: function (response) {
								if (response && response.fragments) {
									$.each(response.fragments, function (key, value) {
										$(key).replaceWith(value);
									});
								}
							},
							error: function () {
								console.error('Failed to update mini cart.');
							}
						});
					},
					error: function () {
						console.error('Failed to submit the cart form.');
					}
				});
			});
		}
	}
	function UtenzoProgressCart() {
		if ($('.bt-progress-container-cart').length > 0) {
			let targetWidth = $(".bt-progress-bar").data("width");
			let currentWidth = 0;
			var interval = setInterval(function () {
				if (currentWidth >= targetWidth) {
					clearInterval(interval);
				} else {
					currentWidth++;
					$(".bt-progress-bar").css("width", currentWidth + "%");
				}
			}, 30);
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
					if (response.data['is_appointment']) {
						$(".bt-progress-content").addClass('is_appointment');
						$(".cart-collaterals").addClass('is_appointment');
					} else {
						$(".bt-progress-content").removeClass('is_appointment');
						$(".cart-collaterals").removeClass('is_appointment');
					}
					$(".bt-progress-bar").css("width", response.data['percentage'] + "%");
					$('#bt-free-shipping-message').html(response.data['message']);

				}
			},
		});
	}
	function UtenzoCountdownCart() {
		if ($('.bt-time-promotion').length > 0) {
			var countdownElement = $('#countdown');
			var originalTime = countdownElement.data('time');
			var time = originalTime.split(':');
			var minutes = parseInt(time[0], 10);
			var seconds = parseInt(time[1], 10);
			var interval = setInterval(function () {
				if (seconds === 0 && minutes === 0) {
					countdownElement.text("00:00");
					countdownElement.data('time', originalTime);
					time = originalTime.split(':');
					minutes = parseInt(time[0], 10);
					seconds = parseInt(time[1], 10);
				} else {
					if (seconds === 0) {
						minutes--;
						seconds = 59;
					} else {
						seconds--;
					}
					countdownElement.text(formatTime(minutes, seconds));
					countdownElement.data('time', formatTime(minutes, seconds));
				}
			}, 1000);

			function formatTime(minutes, seconds) {
				return (minutes < 10 ? '0' + minutes : minutes) + ':' + (seconds < 10 ? '0' + seconds : seconds);
			}
		}
	}
	function UtenzoSelect2Appointment() {
		if ($('.rnb-cart').length > 0) {
			$('.rnb-cart .rnb-select-box').select2({
				dropdownParent: $('.rnb-cart'),
				minimumResultsForSearch: Infinity
			});
		}
	}

	function UtenzoMegaMenu() {
		/* mega menu add class custom */
		jQuery('.bt-mega-menu-sub').each(function () {
			jQuery(this).parent().parent().addClass('bt-submenu-content');
		});
		/* mega menu fix hover category*/
		$(document).on({
			mouseenter: function() {
				// Remove no-hover class when hovering in
				$('.bt-megamenu-shop-category').removeClass('bt-no-hover');
			},
			mouseleave: function() {
				// Add no-hover class when hovering out
				$('.bt-megamenu-shop-category').addClass('bt-no-hover');
			}
		}, '.bt-megamenu-shop-category.e-active .e-n-menu-title, .bt-megamenu-shop-category.e-active .e-n-menu-content,.bt-mega-menu.bt-main > .elementor-widget-container > .e-n-menu > .e-n-menu-wrapper > .e-n-menu-heading > li.e-n-menu-item:first-child > .e-n-menu-title');
	}
	function UtenzoBuyNow() {
		$(document).on('click', '.bt-button-buy-now a', function (e) {
			e.preventDefault();
			var product_id = $(this).data('id').toString();
			var variation_id = $(this).data('variation');
			var param_ajax = {
				action: 'utenzo_products_buy_now',
				product_id: product_id
			};

			// Add variation_id to AJAX data if it exists
			if (variation_id) {
				param_ajax.variation_id = variation_id;
			}

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: AJ_Options.ajax_url,
				data: param_ajax,
				beforeSend: function () {

				},
				success: function (response) {
					if (response.success) {
						window.location.href = response.data['redirect_url'];
					} else {
						console.log('error');
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log('The following error occured: ' + textStatus, errorThrown);
				}
			});
		});
	}
	function UtenzoScrollReview() {
		$(document).on('click', '.bt-action-review', function (e) {
			e.preventDefault();
			var reviewForm = $('#review_form_wrapper');
			if (reviewForm.length) {
				$('html, body').animate({
					scrollTop: reviewForm.offset().top - 100
				}, 500);
			}
		});
	}
	function UtenzoAddSelect2GravityForm() {
		$('.gform_wrapper').each(function () {
			const $self = $(this);
			$($self).find('select').each(function () {
				const placeholder = $(this).find('option.gf_placeholder').text();
				const select2Options = {
					dropdownParent: $($self),
					minimumResultsForSearch: Infinity,
				};
				if (placeholder) {
					select2Options.placeholder = placeholder;
				}
				$(this).select2(select2Options);
			});
		});
	}
	function UtenzoHookGravityFormEvents() {
		$(document).on('submit', '.gform_wrapper form', function (e) {
			let $form = $(this);
			let $submitButton = $form.find('input[type="submit"], button[type="submit"]');
			$form.addClass('loading');
			$submitButton.prop('disabled', true).addClass('loading');
		});
		$(document).on('gform_post_render', function (event, formId) {
			UtenzoAddSelect2GravityForm();
		});
	}
	function UtenzoValidationFormBooking() {
		if ($('.bt-site-appointment').length > 0) {
			var pickuptime = $('.pick-up-time-picker'),
				dropofftime = $('.drop-off-time-picker');
			pickuptime.addClass('bt-disabled');
			dropofftime.addClass('bt-disabled');
			$('.pick-up-date-picker input').on('change', function () {
				pickuptime.removeClass('bt-disabled');
				dropofftime.removeClass('bt-disabled');
			});
			$('#cal-submit-btn').on('click', function () {
				pickuptime.removeClass('bt-disabled');
				dropofftime.removeClass('bt-disabled');
			});
			$('.redq_add_to_cart_button').on('click', function () {
				$('.redq_add_to_cart_button').css('pointer-events', 'none');
			});
		}
	}
	function UtenzoBookingCoupon() {
		if ($('.bt-cart-content').length > 0) {
			var coupon = $('.bt-cart-content').data('coupon');
			if (coupon) {
				$('.woocommerce-cart-form .coupon input[name="coupon_code"]').val(coupon);
				setTimeout(function () {
					$('.woocommerce-cart-form .coupon button').trigger('click');
					var param_ajax = {
						action: 'utenzo_remove_section',
					};
					$.ajax({
						type: 'POST',
						dataType: 'json',
						url: AJ_Options.ajax_url,
						data: param_ajax,
						beforeSend: function () {
						},
						success: function (response) {

						},
						error: function (jqXHR, textStatus, errorThrown) {
							console.log('The following error occured: ' + textStatus, errorThrown);
						}
					});
				}, 500);
			}
		}
	}
	function UtenzoProductButtonStatus() {
		var productCompare = localStorage.getItem('productcomparelocal');
		var productCompareArray = productCompare ? productCompare.split(',') : [];
		var productWishlist = localStorage.getItem('productwishlistlocal');
		var productWishlistArray = productWishlist ? productWishlist.split(',') : [];
		var wishlist_count = productWishlist ? productWishlist.split(',').length : 0;
		$('.bt-mini-wishlist .wishlist_total').html(wishlist_count);
		$('.bt-product-compare-btn').each(function () {
			var productId = $(this).data('id');
			if (productCompareArray.includes(productId.toString())) {
				$(this).addClass('added').removeClass('no-added');
			} else {
				$(this).addClass('no-added').removeClass('added');
			}
		});
		$('.bt-product-wishlist-btn').each(function () {
			var productId = $(this).data('id');
			if (productWishlistArray.includes(productId.toString())) {
				$(this).addClass('added').removeClass('no-added');
			} else {
				$(this).addClass('no-added').removeClass('added');
			}
		});
	}
	/* Copyright Current Year */
	function UtenzoCopyrightCurrentYear() {
		var searchTerm = '{Year}',
			replaceWith = new Date().getFullYear();

		$('.bt-elwg-site-copyright').each(function () {
			this.innerHTML = this.innerHTML.replace(searchTerm, replaceWith);
		});
	}
	/* share button wishlist page and compare page */
	function UtenzoShareLocalStorage(datashare = []) {
		if (!datashare) {
			const url = new URL(window.location.href);
			url.searchParams.delete('datashare');
			window.history.pushState(null, '', url.toString());
			return;
		}
		const url = new URL(window.location.href);
		url.searchParams.set('datashare', datashare);
		window.history.pushState(null, '', url.toString());
		$('.bt-post-share a').each(function () {
			var currentHref = $(this).attr('href');
			// Handle both Facebook and Pinterest share links
			if (currentHref.includes('facebook.com/sharer')) {
				var newHref = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href);
			} else if (currentHref.includes('pinterest.com/pin')) {
				var newHref = 'https://pinterest.com/pin/create/button/?url=' + encodeURIComponent(window.location.href);
			} else {
				var newHref = currentHref.replace(/url=[^&]+/, 'url=' + encodeURIComponent(window.location.href));
			}
			$(this).attr('href', newHref);
		});
	}
	/* backtotop */
	function UtenzoBackToTop() {
		const $backToTop = $('.bt-back-to-top');
		if ($backToTop.length > 0) {
			$(window).on('scroll', function () {
				if ($(this).scrollTop() > 300) {
					$backToTop.addClass('show');
				} else {
					$backToTop.removeClass('show');
				}
			});

			$backToTop.on('click', function (e) {
				e.preventDefault();
				$('html, body').animate({ scrollTop: 0 }, 500);
			});
		}
	}
	/* */
	jQuery(document).ready(function ($) {
		UtenzoSubmenuAuto();
		UtenzoToggleMenuMobile();
		UtenzoToggleSubMenuMobile();
		UtenzoShop();
		UtenzoCommentValidation();
		UtenzoProductCompare();
		UtenzoProductCompareLoad();
		UtenzoProductWishlist();
		UtenzoProductWishlistLoad();
		UtenzoProductQuickView();
		UtenzoProductsFilter();
		UtenzoProductFilterToggle();
		UtenzoAttachTooltips();
		UtenzoUpdateMiniCart();
		UtenzoProgressCart();
		UtenzoCountdownCart();
		UtenzoSelect2Appointment();
		UtenzoMegaMenu();
		UtenzoBuyNow();
		UtenzoScrollReview();
		UtenzoHookGravityFormEvents();
		UtenzoValidationFormBooking();
		UtenzoBookingCoupon();
		UtenzoProductButtonStatus();
		UtenzoCopyrightCurrentYear();
		UtenzoCompareContentScroll();
		UtenzoBackToTop();
	});

	jQuery(window).on('resize', function () {
		UtenzoSubmenuAuto();
	});
	$(document.body).on('updated_cart_totals', function () {
		UtenzoFreeShippingMessage();
	});
	jQuery(window).on('scroll', function () {
	});
})(jQuery);
