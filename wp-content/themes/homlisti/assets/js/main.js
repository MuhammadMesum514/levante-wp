(function ($) {

	function rdtheme_wc_scripts() {
		/* Shop change view */
		$('#shop-view-mode li a').on('click', function () {
			$('body').removeClass('product-grid-view').removeClass('product-list-view');

			if ($(this).closest('li').hasClass('list-view-nav')) {
				$('body').addClass('product-list-view');
				Cookies.set('shopview', 'list');
			} else {
				$('body').addClass('product-grid-view');
				Cookies.remove('shopview');
			}
			return false;
		});
	}


	/*-------------------------------------
	On Scroll
	-------------------------------------*/
	$(window).on('scroll', function () {
		// Sticky Header
		if ($('body').hasClass('sticky-header')) {
			var stickyPlaceHolder = $("#rt-sticky-placeholder");
			var mainMenu = $("#header-menu");
			var menuHeight = mainMenu.outerHeight() || 0;
			var headerTopbar = $('#header-topbar').outerHeight() || 0;
			var targrtScroll = headerTopbar + menuHeight;

			// Main Menu
			if ($(window).scrollTop() > targrtScroll) {
				mainMenu.addClass('rt-sticky');
				stickyPlaceHolder.height(menuHeight);
			} else {
				mainMenu.removeClass('rt-sticky');
				stickyPlaceHolder.height(0);
			}

			//Mobile Menu
			var mobileMenu = $("#meanmenu");
			var mobileTopHeight = $('#mobile-menu-sticky-placeholder');

			if ($(window).scrollTop() > mobileMenu.outerHeight() + headerTopbar) {
				mobileMenu.addClass('rt-sticky');
				mobileTopHeight.height(mobileMenu.outerHeight());
			} else {
				mobileMenu.removeClass('rt-sticky');
				mobileTopHeight.height(0);
			}
		}
	});

	/*-------------------------------------
	On load and resize
	-------------------------------------*/
	$(window).on("load resize", function () {
		if (HomListiObj.rtStickySidebar === 'enable') {
			$('#sticky_sidebar').rtStickySidebar({
				additionalMarginTop: Number(HomListiObj.lsSideOffset) + 10,
				additionalMarginBottom: 20,
			});
		}
	});

	/*-------------------------------------
	Tooltip
	-------------------------------------*/
	$('[data-toggle="tooltip"]').tooltip();

	/*-------------------------------------
	Video Popup
	-------------------------------------*/
	var yPopup = $(".popup-youtube");
	if (yPopup.length) {
		yPopup.magnificPopup({
			disableOn: 700,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false
		});
	}

	// $('iframe').filter(function () {
	//     return this.src.match(/(youtube\.com|youtu\.be)/i);
	// }).wrap("<div class='embed-responsive embed-responsive-16by9'></div>");

	if ('enable' === HomListiObj.rtMagnificPopup) {
		$('.blocks-gallery-item a').filter(function () {
			$(this).css({'pointerEvents': 'all'});
			return this.href.match(/((.jpg|.gif|.png|.jpeg|.svg))/i);
		}).magnificPopup({
			type: 'image',
			mainClass: 'mfp-with-zoom',
			zoom: {
				enabled: true,
				duration: 300,
				easing: 'ease-in-out',
				opener: function (openerElement) {
					return openerElement.is('img') ? openerElement : openerElement.find('img');
				}
			},
			gallery: {
				enabled: true
			}
		});
	}

	/*-------------------------------------
	One page Navigation
	-------------------------------------*/
	$('#one-page-nav').onePageNav({
		currentClass: 'current',
		changeHash: false,
		scrollSpeed: 750,
		scrollThreshold: 0.5,
		filter: '',
		easing: 'swing',
	});

	/*-------------------------------------
	Listing Floor Repeater
	-------------------------------------*/
	function updateFloorIndexing() {
		$('.rn-recipe-wrap').find('.rn-recipe-item').each(function (i, item) {
			$('input', item).each(function () {
				// Rename first array value from name to group index
				var _recipe_input = $(this);
				_recipe_input.attr('name', _recipe_input.attr('name').replace(/homlisti_floor_plan\[[^\]]*\]/, 'homlisti_floor_plan[' + i + ']'));
			});
			$('.homlisti-floor-image', item).each(function () {
				// Rename first array value from name to group index
				var _ingredient = $(this);
				_ingredient.attr('name', _ingredient.attr('name').replace(/homlisti_floor_img\[[^\]]*\]/, 'homlisti_floor_img[' + i + ']'));
			});
		});
	}

	function updateRecipeIndexing() {
		$('.rn-recipe-wrap').find('.rn-recipe-item').each(function (i, item) {
			$('input, textarea', item).each(function () {
				// Rename first array value from name to group index
				var _recipe_input = $(this);
				_recipe_input.attr('name', _recipe_input.attr('name').replace(/rn_recipes\[[^\]]*\]/, 'rn_recipes[' + i + ']'));
			});
			$('.rn-ingredient-item', item).each(function (ii, ingredient) {
				$('input', ingredient).each(function () {
					// Rename first array value from name to group index
					var _ingredient = $(this);
					_ingredient.attr('name', _ingredient.attr('name').replace(/\[ingredient\]\[[^\]]*\]/, '[ingredient][' + ii + ']'));
				});
			});
		});
	}

	function getFloortHtml() {
		return '<div class="rn-ingredient-item">' +
			'<span class="item-sort"><i class="fa fa-arrows-alt"></i></span>' +
			'<div class="rn-ingredient-fields">' +
			'<input type="text" placeholder="Bed" class="form-control" name="homlisti_floor_plan[][bed]">' +
			'<input type="text" placeholder="Bath" class="form-control" name="homlisti_floor_plan[][bath]">' +
			'<input type="text" placeholder="Size" class="form-control" name="homlisti_floor_plan[][size]">' +
			'<input type="text" placeholder="Parking" class="form-control" name="homlisti_floor_plan[][parking]">' +
			'</div>' +
			'</div>' +
			'<div class="floor-image-wrap"><div class="floor-input-wrapper"><input name="homlisti_floor_img[]" class="homlisti-floor-image" type="file"/></div></div>';
	}

	$(document).on('click', '.rn-recipe-wrapper .add-recipe', function (e) {
		e.preventDefault();

		var _self = $(this),
			recipe = '<div class="rn-recipe-item">' +
				'<span class="rn-remove"><i class="fa fa-times" aria-hidden="true"></i></span>' +
				'<div class="rn-recipe-title">' +
				'<input type="text" name="homlisti_floor_plan[][title]" class="form-control" placeholder="Title">' +
				'<textarea name="homlisti_floor_plan[][description]" class="form-control" placeholder="Description"></textarea>' +
				'</div>' +
				'<div class="rn-ingredient-wrap">' + getFloortHtml() + '</div>' +
				'</div>';
		_self.closest('.rn-recipe-wrapper').find('.rn-recipe-wrap').append(recipe);
		updateFloorIndexing();

	});

	$(document).on('click', '.rn-recipe-item > .rn-remove', function (e) {
		e.preventDefault();
		var _self = $(this);
		if (_self.closest('.rn-recipe-wrapper').find('.rn-recipe-item').length >= 2) {
			_self.closest('.rn-recipe-item').slideUp('slow', function () {
				$(this).remove();
				updateFloorIndexing();
			});
		} else {
			alert('You are not permited to remove all floor. If you do not want this remove from settings');
		}
	});

	function rtPreloader() {
		var $preloader = $('#preloader');
		if (!$preloader.length) {
			return;
		}
		$preloader.delay(1000).fadeOut('slow');
	}

	//Advanced custom field rendering by category filter
	function rtclCategoryAjax() {

		var searchForm = $('.advance-search-form');

		if(searchForm.length < 1){
			return
		}

		//Custom category button on click
		$('body').on('click', '.rtcl-category-ajax li', function (e) {
			var targetEl = $(this);
			var catId = targetEl.data('category');
			rtcl_cf_by_category(catId, targetEl)
		});

		//Default category on load
		var defaultTargetEl = $('.rtcl-category-ajax li');
		var defaultCategory = $('.rtcl-category-ajax li:first-child').data('category');
		rtcl_cf_by_category(defaultCategory, defaultTargetEl)


		//Category select2 on change
		$('.rtcl-category-search-ajax').each(function () {
			var currentElement = $(this);
			currentElement.on('select2:select', function (e) {
				var categoryId = $(e.params.data.element).data('term_id');
				rtcl_cf_by_category(categoryId, currentElement)
			});
		});

	}

	function rtcl_cf_by_category(catId, targetEl) {

		var parentElementor = targetEl.parents('.advance-search-form');
		var container = parentElementor.find('.rtcl_cf_by_category_html');
		var price_search = container.data('price-search');

		var minPrice = parentElementor.data('min-price');
		var maxPrice = parentElementor.data('max-price');

		var price = {
			'minPrice': minPrice,
			'maxPrice': maxPrice
		}

		$.ajax({
			type: "post",
			url: HomListiObj.ajaxUrl,
			data: {
				action: "rtcl_cf_by_category",
				cat_id: catId > 0 ? parseInt(catId) : 'all',
				price: price,
				price_search: price_search
			},
			beforeSend: function () {
				container.addClass('is-loading')
			},
			success: function (data) {
				container.html(data);
				isSelect2();
				homlistiIonRangeSlider();
				container.removeClass('is-loading')
			}
		})
	}

	// Window Ready
	jQuery(document).ready(function ($) {

		rdtheme_content_ready_scripts();
		rdtheme_wc_scripts();
		rtPreloader();
		rtclCategoryAjax();

		//Favourite Icon Update
		//=========================
		$(document).on('rtcl.favorite', function (e, data) {
			var $favCount = $(".rt-header-favourite-count").first();
			var $favCountAll = $(".rt-header-favourite-count");
			var favCountVal = parseInt($favCount.text(), 10);
			favCountVal = isNaN(favCountVal) ? 0 : favCountVal;
			if ("added" === data.action) {
				favCountVal++;
				$favCountAll.text(favCountVal);
			} else if ("removed" === data.action) {
				favCountVal--;
				$favCountAll.text(favCountVal);
			}
		});
		//End Favourite Icon Update

		//Compare icon update
		//====================
		$(document).on('rtcl.compare.added', function (e, data) {
			$('.rt-compare-count').text(data.current_listings);
		});

		$(document).on('rtcl.compare.removed', function (e, data) {
			$('.rt-compare-count').text(data.current_listings);
		});

		$(document).on('click', '.rtcl-compare-btn-clear', function () {
			$('.rt-compare-count').text('0');
		});

		$(document).on('click', '#virtual-tour-iframe .expand-iframe', function (e) {
			e.preventDefault();
			$(this).parent().addClass('expand-iframe');
		});

		$(document).on('click', '#virtual-tour-iframe .compress-iframe', function (e) {
			e.preventDefault();
			$(this).parent().removeClass('expand-iframe');
		});

		//End Compare icon update

		$('.rtcl-item-visible-btn').on('click', function (e) {
			e.preventDefault();
			$(this).parents('.advance-search-form').find('.expanded-wrap').toggleClass('is-open');
			$(this).parents('.advance-search-form').find('.expanded-wrap').slideToggle();
		});

		$('.input-group .form-control').on('focus', function () {
			$(this).parent('.input-group').addClass('active');
		}).on('focusout', function () {
			$(this).parent('.input-group').removeClass('active');
		});

		$('.single-listing-style-2 .single-product .product-heading').fadeIn();


		/* Scroll to top */
		$('.scrollToTop').on('click', function () {
			$('html, body').animate({scrollTop: 0}, 800);
			return false;
		});
		$(window).scroll(function () {
			if ($(this).scrollTop() > 100) {
				$('.scrollToTop').fadeIn();
				$('.rtcl-single-side-menu').addClass('show');
			} else {
				$('.scrollToTop').fadeOut();
				$('.rtcl-single-side-menu').removeClass('show');
			}
		});

		// Add class to listing search filter radios
		$('.search-radio-check ul li:first-child label').addClass('active');
		var $rtSearchRadioButtons = $('.search-radio-check input[type="radio"]');
		$rtSearchRadioButtons.click(function () {
			$rtSearchRadioButtons.each(function () {
				$(this).parent().toggleClass('active', this.checked);
			});
		});

		// Panorama View
		if ($('#panorama').length > 0) {
			pannellum.viewer('panorama', {
				"type": "equirectangular",
				"panorama": HomListiObj.pannellumIMG,
				"showControls": HomListiObj.showControls,
				"autoLoad": HomListiObj.autoLoad ? true : false,
			});
		}

		// Mobile Menu

		var mobileMenu = $('.offscreen-navigation nav ul');

		if (mobileMenu.length) {
			mobileMenu.children("li").addClass("menu-item-parent");

			mobileMenu.find(".menu-item-has-children > a, .page_item_has_children > a").append('<span class="pointer"></span>')
			mobileMenu.find(".menu-item-has-children > a > .pointer, .page_item_has_children > a > .pointer").on("click", function (e) {
				e.preventDefault();
				$(this).parent().toggleClass("opened");
				var n = $(this).parent().next(".sub-menu, .children"),
					s = $(this).parent().closest(".menu-item-parent").find(".sub-menu, .children");
				//mobileMenu.find(".sub-menu, .children").not(s).slideUp(250).prev('a').removeClass('opened');
				n.slideToggle(250);
			});

			mobileMenu.find('.menu-item:not(.menu-item-has-children, .page_item_has_children) > a').on('click', function (e) {
				$('.rt-slide-nav').slideUp();
				$('body').removeClass('slidemenuon');
			});
		}


		$('.sidebarBtn.circle-btn').on('click', function (e) {
			e.preventDefault();
			$('.overly-sidebar-wrapper').addClass('show');
			$('.offcanvas-menu-btn').addClass('menu-status-open');
		});

		$('.mean-bar .sidebarBtn').on('click', function (e) {
			e.preventDefault();

			if ($('.rt-slide-nav').is(":visible")) {
				$('.rt-slide-nav').slideUp();
				$('body').removeClass('slidemenuon');
			} else {
				$('.rt-slide-nav').slideDown();
				$('body').addClass('slidemenuon');
			}

		});

	});


	function homlistiIonRangeSlider() {
		if ($.fn.ionRangeSlider) {
			$(".ion-rangeslider").each(function () {
				var $this = $(this);
				var rangeType = $this.data('type');
				$this.ionRangeSlider({
					type: rangeType || "double",
					drag_interval: true,
					min_interval: null,
					max_interval: null,
					prettify_enabled: true,
					prettify_separator: ",",
					onChange: function (data) {
						var $inp = data.input;
						$inp.parent().find('.min-volumn').val(data.from);
						$inp.parent().find('.max-volumn').val(data.to);
					},
				});
			});
		}
	}

	// Window Load
	$(window).on('load', function () {
		// Scripts needs loading inside content area
		rdtheme_content_load_scripts();
		isSelect2();

		// Number Field range slider
		homlistiIonRangeSlider();


		// Advanced Search Revel
		$(".advanced-btn").on("click", function () {
			$(this).toggleClass("collapsed");
			$("#advanced-search").toggleClass("show");
		});

		// Share Icon reveled
		$("#share-btn").on("click", function (e) {
			e.preventDefault();
			$(this).siblings('.share-icon').toggleClass('open');
		});

		// Delete Panorama Image
		$(".remove-panorama-image a").on("click", function (e) {
			e.preventDefault();
			let attachmentID = $(this).data('attachment_id');
			let postID = $(this).data('post_id');
			let container = $(this).parents('.panorama-image');
			let inputWrapper = $('.panorama-input-wrapper');

			let r = confirm('Are you want to delete this attachment?');

			if (r) {
				$.ajax({
					type: "post",
					url: HomListiObj.ajaxUrl,
					data: {
						action: "delete_panorama_attachment",
						attachment_id: attachmentID,
						post_id: postID,
					},
					success: function (response) {
						if (response === 'success') {
							container.fadeOut(function () {
								container.remove();
								inputWrapper.toggleClass('d-none');
							});
						}
					}
				})
			}
		});

		// Delete Floor Image
		$(".remove-floor-image a").on("click", function (e) {
			e.preventDefault();
			let attachmentID = $(this).data('attachment_id');
			let indexNo = $(this).data('index');
			let postID = $(this).data('post_id');
			let container = $(this).parents('.floor-image');
			let inputWrapper = $('.floor-input-wrapper');

			let r = confirm('Are you want to delete this attachment?');

			if (r) {
				$.ajax({
					type: "post",
					url: HomListiObj.ajaxUrl,
					data: {
						action: "delete_floor_attachment",
						index: indexNo,
						attachment_id: attachmentID,
						post_id: postID,
					},
					success: function (response) {
						if (response === 'success') {
							container.fadeOut(function () {
								container.remove();
								inputWrapper.toggleClass('d-none');
							});
						}
					}
				})
			}
		});

	});

	// Elementor Frontend Load
	$(window).on('elementor/frontend/init', function () {
		if (elementorFrontend.isEditMode()) {
			elementorFrontend.hooks.addAction('frontend/element_ready/widget', function () {
				rdtheme_content_ready_scripts();
				rdtheme_content_load_scripts();
				isSelect2();
			});
		}
	});

	function rdtheme_content_ready_scripts() {

		/**
		 * Common JS
		 */

		$('.theme-homlisti .agent-block .social-icon').each(function () {
			var $elm = $(this).get(0);
			$(this).find('.social-hover-icon').on('click', function (e) {
				e.preventDefault();
				$(this).parent().toggleClass('active');
			});
			$(this).attr('style', '--hl-self-height:' + ($elm.scrollHeight + 4) + 'px');
		});

		$(".advance-search-form.is-preloader").each(function () {
			var $this = $(this);
			setTimeout(function () {
				$this.removeClass('is-preloader');
			}, 1000);
		});

		/*---------------------------------------
		  Background Parallax
		  --------------------------------------- */
		if ($(".rt-parallax-bg-yes").length) {
			$(".rt-parallax-bg-yes").each(function () {
				var speed = $(this).data('speed');
				$(this).parallaxie({
					speed: speed ? speed : 0.5,
					offset: 0,
				});
			});
		}


		$('.rtcl-single-side-menu .side-menu').navpoints({
			updateHash: true,
			offset: HomListiObj.lsSideOffset
		});


		/*======================================
		//TweenMax Mouse Effect
		====================================*/

		$('.follow-with-mouse').parents('.elementor-section').addClass('motion-effects-wrap');
		$(".motion-effects-wrap").each(function () {
			var $mainWrap = $(this);
			var $motionsItem = $(this).find('.follow-with-mouse');
			var radnomPosition = Math.floor(Math.random() * (150 - 50 + 1) + 50)
			if ($motionsItem.length) {
				$mainWrap.mousemove(function (e) {
					$.each($motionsItem, function (index, item) {
						var $movement = $(item).data('position') || radnomPosition;
						parallaxIt(e, $(item), $mainWrap, $movement);
					});
				});
			}
		})

		function parallaxIt(e, targetClass, mainWrap, movement) {
			let $wrap = mainWrap;
			let relX = e.pageX - $wrap.offset().left;
			let relY = e.pageY - $wrap.offset().top;
			TweenMax.to(targetClass, 1, {
				x: ((relX - $wrap.width() / 2) / $wrap.width()) * movement,
				y: ((relY - $wrap.height() / 2) / $wrap.height()) * movement,
			});
		}

		// Swiper Slider
		//=====================================

		if ($(".rt-main-slider-wrapper").length) {

			$(".rt-main-slider-wrapper").each(function () {
				$(this).fadeIn();
				var container = $(this);

				var swiperSlider = container.find('.rt-swiper-slider');
				var sliderOptions = swiperSlider.data('options');
				var isGallery = swiperSlider.data('gallery');

				if ('enable' === isGallery) {
					//Gallery Thumb
					var swiperGallery = container.find('.rt-gallery-thumbs');
					var space_between = swiperGallery.data('space_between');
					var slides_per_view = swiperGallery.data('slides_per_view');
					var slider_loop = swiperGallery.data('slider_loop');

					var galleryThumbs = new Swiper(swiperGallery[0], {
						spaceBetween: parseInt(space_between),
						slidesPerView: parseInt(slides_per_view),
						loop: (slider_loop === 'yes') ? true : false,
						freeMode: false,
						watchSlidesVisibility: true,
						watchSlidesProgress: true,
						autoHeight: true,
					});

					sliderOptions.thumbs = {
						swiper: galleryThumbs
					}
				}

				//Main Slider
				var rtSlider = new Swiper(swiperSlider[0], sliderOptions);

			});
		}

		$(".list-slick-carousel").each(function () {
			var slider_el = $(this),
				prevButton = slider_el.parent().find(".elementor-swiper-button-prev").get(0),
				nextButton = slider_el.parent().find(".elementor-swiper-button-next").get(0);
			slider_el.fadeIn();
			var listSliderData = slider_el.data('slider-settings');
			Object.assign(listSliderData, {
				navigation: {
					nextEl: nextButton,
					prevEl: prevButton
				},
			});
			var swiper = new Swiper(slider_el[0], listSliderData);
		});

		$('.listing-archive-carousel').each(function () {
			var $this = $(this);
			new Swiper($this[0], {
				loop: true,
				slidesPerView: 1,
				navigation: {nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev'},
			});
		});

		$(".slick-carousel").each(function () {
			var $this = $(this);
			var sliderData = $this.data('slick');
			new Swiper($this[0], sliderData);
		});

	}

	// end swiper slider


	//Select2 js
	function isSelect2() {
		// Select2 Activation
		var $select2 = $('select.select2');
		if ($select2.length) {
			$select2.select2({
				theme: 'classic',
				dropdownAutoWidth: true,
				width: '100%',
			});
		}
	}

	function rdtheme_content_load_scripts() {

		$('.rtcl-sold-out, .section-title-wrapper .bg-title-wrap').fadeIn();
		$('.rtrs-review-wrap .rtrs-review-form .rtrs-form-group .rtrs-submit-btn').parent('.rtrs-form-group').addClass('rtrs-submit-button');
		// $('.single-product .product-amenities .amenities-list ')

		$('.button-times').on('click', function (e) {
			e.preventDefault();
			$(this).parents('.advanced-search-box').removeClass('show');
		});


		//Add Class on search hover
		$(".header-btn .search-icon-wrapper .input-group .form-control").click(function () {
			$(this).parents('.search-icon').addClass('active');
		});

		//Remove Class on click out site of search
		$(document).on("click", function (e) {
			if ($(e.target).is(".header-btn .search-icon-wrapper .input-group .form-control") === false) {
				$(".header-btn .icon-hover-item").removeClass("active");
			}
		});


		/*-------------------------------------
		Animated Text
		-------------------------------------*/
		if (typeof Typed == 'function' && $(window).width() > 767.98) {
			$('.title-typejs').each(function (index, el) {
				var options = $(this).data('options');
				new Typed(this, options);
			});
		} else {
			$('.title-typejs').each(function (index, el) {
				var options = $(this).data('options');
				$(this).text(options.strings);
			});
		}


		// Isotope
		if (typeof $.fn.isotope == 'function') {
			// Run 1st time
			var $isotopeContainer = $('#inner-isotope');

			setTimeout(function () {
				$isotopeContainer.each(function () {
					var $container = $(this).find('.featuredContainer'),
						filter = $(this).find('.isotope-classes-tab a.current').data('filter');
					runIsotope($container, filter);
				});

				// Run on click event

				$('.isotope-classes-tab a').on('click', function () {
					$(this).closest('.isotope-classes-tab').find('.current').removeClass('current');
					$(this).addClass('current');
					var $container = $(this).closest('.isotope-wrap').find('.featuredContainer'),
						filter = $(this).attr('data-filter');
					runIsotope($container, filter);
					return false;
				});

			}, 1000);
		}

	}

	$('.homlisti-isotope-wrapper').each(function () {
		var $container = $(this).find('.featuredContainer');
		$container.isotope({
			transitionDuration: "1s",
			hiddenStyle: {
				opacity: 0,
				transform: "scale(0.001)"
			},
			visibleStyle: {
				transform: "scale(1)",
				opacity: 1
			}
		});
	})

	function runIsotope($container, filter) {
		$container.isotope({
			filter: filter,
			transitionDuration: "1s",
			hiddenStyle: {
				opacity: 0,
				transform: "scale(0.001)"
			},
			visibleStyle: {
				transform: "scale(1)",
				opacity: 1
			}
		});
	}

})(jQuery);