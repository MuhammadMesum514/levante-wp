!function(i){var t=function(t,e){var s;this.$sliderWrapper=t,this.$slider=i(".rtcl-slider",this.$sliderWrapper),this.$sliderThumbs=i(".rtcl-slider-nav",this.$sliderWrapper),this.$slider.length&&(this.slider=this.$slider.get(0),this.swiperSlider=this.slider.swiper||null,this.sliderThumbs=this.$sliderThumbs.get(0),this.swiperThumbsSlider=(null==this||null===(s=this.sliderThumbs)||void 0===s?void 0:s.swiper)||null,this.$slider_images=i(".rtcl-slider-item",this.$slider),this.settings=Object.assign({},rtcl_single_listing_localized_params||{},this.$sliderWrapper.data("options")||{}),this.args=e||{},this.options=Object.assign({},this.args,this.settings.slider_options),this.options.rtl&&"rtl"===i("html").attr("dir")&&(this.options.rtl=!0),this.slider_enabled="function"==typeof Swiper&&this.settings.slider_enabled,this.zoom_enabled=i.isFunction(i.fn.zoom)&&this.settings.zoom_enabled,this.photoswipe_enabled="undefined"!=typeof PhotoSwipe&&this.settings.photoswipe_enabled,e&&(this.slider_enabled=!1!==e.slider_enabled&&this.slider_enabled,this.zoom_enabled=!1!==e.zoom_enabled&&this.zoom_enabled,this.photoswipe_enabled=!1!==e.photoswipe_enabled&&this.photoswipe_enabled),1===this.$slider_images.length&&(this.slider_enabled=!1),this.initSlider=function(){if(this.slider_enabled){var t=this.$slider,e=this.$sliderThumbs;this.options.rtl&&(t.attr("dir","rtl"),e.attr("dir","rtl"));var s,r,l=this;this.swiperThumbsSlider?(s=this.swiperThumbsSlider,this.swiperThumbsSlider.update()):(s=new Swiper(this.sliderThumbs,{watchSlidesVisibility:!0,spaceBetween:5,navigation:{nextEl:e.find(".swiper-button-next").get(0),prevEl:e.find(".swiper-button-prev").get(0)},breakpoints:{0:{slidesPerView:3},576:{slidesPerView:4},768:{slidesPerView:5}}}),this.swiperThumbsSlider=s);var n={navigation:{nextEl:t.find(".swiper-button-next").get(0),prevEl:t.find(".swiper-button-prev").get(0)},on:{init:function(i){i.slides[i.activeIndex].querySelector("iframe")&&i.el.classList.add("active-video-slider")}}};this.$sliderThumbs.length&&(n.thumbs={swiper:s});var a=Object.assign({},n,this.options);this.swiperSlider?(r=this.swiperSlider,this.swiperSlider.parents=a,this.swiperSlider.update()):(r=new Swiper(this.slider,a),this.swiperSlider=r),r.on("slideChange",(function(t){l.initZoomForTarget(r.activeIndex),r.slides.forEach((function(t,e){if(e!==r.activeIndex){var s=i(t).find("iframe");s.length&&s.each((function(){var t=i(this).attr("src");i(this).attr("src",t)}))}})),t.slides[t.activeIndex].querySelector("iframe")?t.el.classList.add("active-video-slider"):t.el.classList.remove("active-video-slider")}))}},this.imagesLoaded=function(){var t=this;if(i.fn.imagesLoaded.done)return this.$sliderWrapper.trigger("rtcl_gallery_loading",this),void this.$sliderWrapper.trigger("rtcl_gallery_loaded",this);this.$sliderWrapper.imagesLoaded().progress((function(i,e){t.$sliderWrapper.trigger("rtcl_gallery_loading",[t])})).done((function(i){t.$sliderWrapper.trigger("rtcl_gallery_loaded",[t])}))},this.initZoom=function(){this.zoom_enabled&&this.initZoomForTarget(0)},this.initZoomForTarget=function(t){if(this.zoom_enabled){var e=this.$slider.width(),s=!1,r=this.$slider_images.eq(t);if(i(r).each((function(t,r){var l=i(r).find("img");if(parseInt(l.data("large_image_width"))>e)return s=!0,!1})),s){var l=i.extend({touch:!1},this.settings.zoom_options);"ontouchstart"in document.documentElement&&(l.on="click"),r.trigger("zoom.destroy"),r.zoom(l),this.$sliderWrapper.on("rtcl_gallery_init_zoom",this.initZoom)}}},this.initPhotoswipe=function(){this.photoswipe_enabled&&(this.$slider.prepend('<a href="#" class="rtcl-listing-gallery__trigger"><i class="rtcl-icon-search"></i></i> </a>'),this.$slider.on("click",".rtcl-listing-gallery__trigger",this.openPhotoswipe.bind(this)))},this.getGalleryItems=function(){var t=this.$slider_images,e=[];return t.length>0&&t.each((function(t,s){var r=i(s).find("img");if(r.length){var l={src:r.attr("data-large_image"),w:r.attr("data-large_image_width"),h:r.attr("data-large_image_height"),title:r.attr("data-caption")?r.attr("data-caption"):r.attr("title")};e.push(l)}})),e},this.openPhotoswipe=function(t){t.preventDefault();var e,s=i(".pswp")[0],r=this.getGalleryItems(),l=i(t.target);e=i(t.currentTarget).hasClass("rtcl-listing-gallery__trigger")||i(t.currentTarget).closest(".rtcl-listing-gallery__trigger")||l.is(".rtcl-listing-gallery__trigger")||l.is(".rtcl-listing-gallery__trigger img")?this.$slider.find(".swiper-slide.swiper-slide-active"):l.closest(".rtcl-slider-item");var n=i.extend({index:i(e).index()},this.settings.photoswipe_options);new PhotoSwipe(s,PhotoSwipeUI_Default,r,n).init()},this.start=function(){var i=this;this.$sliderWrapper.on("rtcl_gallery_loaded",this.init.bind(this)),setTimeout((function(){i.imagesLoaded()}),1)},this.init=function(){this.initSlider(),this.initZoom(),this.initPhotoswipe()},this.start())};i.fn.rtcl_listing_gallery=function(i){return new t(this,i),this},i(".rtcl-slider-wrapper").each((function(){i(this).rtcl_listing_gallery()}))}(jQuery);
;