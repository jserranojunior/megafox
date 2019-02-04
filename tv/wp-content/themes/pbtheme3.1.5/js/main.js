(function ($) {
    "use strict";
    var infiniteClickFlag = true;
    window.pbthemeHorisontalTabsFlag = [];
    var $root = $('html, body');
    var stickyFlag = false;
    var headHeig;
    var winScr = 0;
    var marginTop = 0;
    var menuWrap = 0;
    var adminbar = 0;
    var fullHeadHeig = 0;
    var navActive = false;
    var divTouchOptimizedStart;
    var GridSliderStorage = [];
    var GridResponsiveStorage = [];
    var gridSliderResizeTimer;
    var divSliderPosiitonMark, divSliderPosiitonMarkNew;
    var numberOfSlides = [];
    var numberOfSlidesFormated = [];
    var resizeTimer;
    var swipeboxInstance;
    var entranceId = [], $divPortItem;
    var woocart_timer;

    var pointerEventToXY = function (e) {
        var out = {x: 0, y: 0};
        if (e.type == 'touchstart' || e.type == 'touchmove' || e.type == 'touchend' || e.type == 'touchcancel') {
            var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
            out.x = touch.pageX;
            out.y = touch.pageY;
        } else if (e.type == 'mousedown' || e.type == 'mouseup' || e.type == 'mousemove' || e.type == 'mouseover' || e.type == 'mouseout' || e.type == 'mouseenter' || e.type == 'mouseleave') {
            out.x = e.pageX;
            out.y = e.pageY;
        } else if (e.type == 'MSPointerDown' || e.type == 'MSPointerMove' || e.type == 'MSPointerUp') {
            var touch = e.originalEvent;
            out.x = touch.pageX;
            out.y = touch.pageY;
        }
        return out;
    };


    function directionCalc($el, coordinates, mod) {
        var offset = $el.offset();
        if (mod) {
            var refOffset = $('.div_portfolio_slider .pbtheme_hover.current-in').offset(),
                    left = refOffset.left - offset.left,
                    top = refOffset.top - offset.top,
                    vert = (Math.abs(top) >= Math.abs(left)) ? true : false;
            output = vert ? ((top >= 0) ? 'bottom' : 'top') : ((left >= 0) ? 'right' : 'left');
        } else {
            var w = $el.outerWidth(),
                    h = $el.outerHeight(),
                    x = (coordinates.x - offset.left - (w / 2)) * (w > h ? (h / w) : 1),
                    y = (coordinates.y - offset.top - (h / 2)) * (h > w ? (w / h) : 1),
                    direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4,
                    output;
            switch (direction) {
                case 0:
                    output = 'top';
                    break;
                case 1:
                    output = 'right';
                    break;
                case 2:
                    output = 'bottom';
                    break;
                case 3:
                    output = 'left';
                    break;
            }
            ;
        }
        return output;
    }

    function divPortFlipDefaulting($el, direction) {
        var origin, rot;
        if (direction == 'left') {
            rot = 'rotate3d(1,0,0,110deg)';
            origin = '0% 0% 0';
        }
        if (direction == 'top') {
            rot = 'rotate3d(-1,0,0,110deg)';
            origin = ' 0% 0% 0';
        }
        if (direction == 'right') {
            rot = 'rotate3d(0,-1,0,110deg)';
            origin = ' 100% 0% 0';
        }
        if (direction == 'bottom') {
            rot = 'rotate3d(0,1,0,110deg)';
            origin = ' 0% 100% 0';
        }
        $el.find('.pbtheme_hover_over').css({'transform': rot, '-ms-transform': rot, '-webkit-transform': rot});
    }
    pbtheme_navigation();


    $(document).ready(function () {

        $(document).on('mouseleave', '.div_portfolio_slider .div_portfolio_slides', function (e) {
            var divPortModExit = directionCalc($(this), pointerEventToXY(e), false);
            $(this).find('.pbtheme_hover.current-in').removeClass('in-left in-right in-bottom in-top').addClass('out-' + divPortModExit).removeClass('current-in');
        });

        if (window.navigator.userAgent.indexOf('MSIE') >= 0 || window.navigator.userAgent.indexOf('Opera') >= 0) {
            $('.div_portfolio_slider').addClass('fallback');
        }
        $(document).on('mouseenter', '.div_portfolio_slider .pbtheme_hover', function (e) {
            var $this = $(this);
            if (window.navigator.userAgent.indexOf('MSIE') >= 0 || window.navigator.userAgent.indexOf('Opera') >= 0) {
                $this.find('.pbtheme_hover_over').stop(true).animate({opacity: 1}, 300);
            } else {
                entranceId[$this.index()] = directionCalc($this, pointerEventToXY(e), $this.hasClass('current-in'));
                divPortFlipDefaulting($this, entranceId[$this.index()]);
                var inverse = entranceId[$this.index()] != 'top' ? (entranceId[$this.index()] != 'left' ? (entranceId[$this.index()] != 'right' ? 'top' : 'left') : 'right') : 'bottom';
                $('.div_portfolio_slider .pbtheme_hover.current-in').removeClass('in-left in-right in-bottom in-top').addClass('out-' + inverse).removeClass('current-in');
                $this.removeClass('out-left out-right out-bottom out-top');
                $this.addClass('current-in in-' + entranceId[$this.index()]);
            }
        });


        $(document).on('mouseleave', '.div_portfolio_slider .pbtheme_hover', function () {
            var $this = $(this);
            if (window.navigator.userAgent.indexOf('MSIE') >= 0 || window.navigator.userAgent.indexOf('Opera') >= 0) {
                $this.find('.pbtheme_hover_over').stop(true).animate({opacity: 0}, 300);
            }
        });

        if (pbtheme.page_bg == "bgimage" || pbtheme.is_mobile_tablet == "yes") {
            var theWindow = $(window),
                    $bg = $("#bg"),
                    aspectRatio = $bg.width() / $bg.height();

            theWindow.resize(function () {
                if ((theWindow.width() / theWindow.height()) < aspectRatio)
                    $bg.removeClass().addClass('bgheight');
                else
                    $bg.removeClass().addClass('bgwidth');
            }).trigger("resize");
        } else if (pbtheme.page_bg == "videoembed") {
            var atts = {
                mute: (pbtheme.video_mute == 1 ? true : false),
                loop: (pbtheme.video_loop == 1 ? true : false),
                hd: (pbtheme.video_hd == 1 ? true : false)
            };
            pbuilderYoutube("pbtheme_page_bg_inner", pbtheme.video_bg, atts);
        }


        $("a[rel^='prettyPhoto']").attr('rel', 'lightbox');

        $('.blog_content iframe').each(function () {
            var url = $(this).attr("src");
            $(this).attr("src", url + "?wmode=transparent");
        });

        /*					Shopping cart					*/

        $(document).on('mouseenter', '.element-woo-cart', function () {
            if (!$(this).hasClass('hovered')) {
                $(this).addClass('hovered');
                $('#div_woocart').stop(true, true).slideDown(100);
            }
            else {
                window.clearTimeout(woocart_timer);
            }
            return false;
        });

        $(document).on('mouseleave', '.element-woo-cart', function () {
            if ($(this).hasClass('hovered')) {
                woocart_timer = setTimeout(function () {
                    $('.element-woo-cart').removeClass('hovered');
                    $('#div_woocart').stop(true, true).fadeOut(100);
                }, 300);
            }
            return false;
        });

        winScr = window.scrollY;
        if ($('#wpadminbar').length > 0) {
            adminbar = $('#wpadminbar').outerHeight();
        }
        headHeig = $('.header_wrapper').outerHeight();
        menuWrap = $('nav.menu_wrapper').outerHeight();
        fullHeadHeig = $('.header_wrapper').height() + adminbar;
        pbthemeHeader();
//		swipebox
        swipeboxInstance = $('a[rel="lightbox"]').addClass('swipebox').swipebox();

    });


    $(window).scroll(function () {
		winScr = (window.scrollY || window.pageYOffset);
        
		/*if ($(window).width() < pbtheme.go_responsive) {
            if ($('.header_wrapper').hasClass('sticky-header')) {
                $('.header_wrapper').css('padding-top', 0).removeClass('sticky-header');
            }
            return;
        }*/

		if (winScr > 2) {
            stickyFlag = true;
            if ($('#widgets-trigger a').hasClass('widgets-open') == true) {
                $('.header_wrapper .pbtheme_top:not(.element-to-be-hidden)').css('display', 'block');
            }
            else {
                $('.header_wrapper .pbtheme_top').removeAttr('style');
            }
        }
		else if (winScr > fullHeadHeig) {
            stickyFlag = true;
            if ($('#widgets-trigger a').hasClass('widgets-open') == true) {
                $('.header_wrapper .pbtheme_top:not(.element-to-be-hidden)').css('display', 'block');
            }
            else {
                $('.header_wrapper .pbtheme_top').removeAttr('style');
            }
        }
        else {
            stickyFlag = false;
        }
        pbthemeHeader();

    });

    function pbthemeHeader() {
		
        if (stickyFlag === true) {
            if (!$('.header_wrapper').hasClass('sticky-header')) {
                $('.header_wrapper').addClass('div_notransition');

                $('.header_wrapper').css({top: -menuWrap}).addClass('sticky-header');

                $('.header_wrapper .header_alt_nav, .header_wrapper .header_main_nav').children('div').each(function () {
                    if ($(this).is(':visible')) {
                        $(this).addClass('element-to-be-hidden');
                    }
                });

                $('#pbtheme_wrapper').css({paddingTop: headHeig});
                pbthemeAnimate();
                setTimeout(function () {
                    $('.header_wrapper').removeClass('div_notransition');
                }, 300);
            }
        }
        else {
            if ($('.header_wrapper').hasClass('sticky-header')) {
                $('.header_wrapper').addClass('div_notransition');

                $('#pbtheme_wrapper').css({paddingTop: 0});

                $('.header_wrapper').children('div').each(function () {
                    if ($(this).is(':hidden')) {
                        $(this).removeClass('element-to-be-hidden');
                    }
                });

                $('.header_wrapper').removeClass('sticky-header').css({top: 0});
                setTimeout(function () {
                    $('.header_wrapper').removeClass('div_notransition');
                }, 300);
            }
        }
    }

    function pbthemeAnimate() {
        if ($('.header_wrapper').hasClass('sticky-header')) {
            $('.header_wrapper').stop(true).animate({top: adminbar}, 300);
        }
    }

    $(document).on('click', '#search-trigger a', function () {
        var $this = $(this);
        if ($(this).hasClass('search-open')) {
            $('.pbtheme_search').stop(true, true).fadeOut(200);
            $this.removeClass('search-open');
        }
        else {
            var searchPos = $this.position().left + $this.outerWidth() / 2 - 204;
            $this.addClass('search-open');
            $('.pbtheme_search').css({left: searchPos}).stop(true, true).fadeIn(200);
            $('#div_header_menu .pbtheme_search input[name="s"]').focus();
        }
        return false;
    });


    $(document).on('click', '#widgets-trigger a, #topmenu-trigger, #div_rwidgetized', function (e) {
        e.preventDefault();
        var $this = $(this);
        if ($(this).hasClass('widgets-open')) {
            $('.pbtheme_header_widgets').stop(true, true).slideUp(300);
            if (stickyFlag == true || $('body').hasClass('div_responsive')) {
                $('.header_wrapper .pbtheme_top').slideUp(300, function () {
                    $('.header_wrapper .pbtheme_top').removeAttr('style');
                });
            }
            $this.removeClass('widgets-open');
            $this.children('i').attr('class', 'divicon-plus')
        }
        else {
            $this.addClass('widgets-open');
            $('.pbtheme_header_widgets').stop(true, true).slideDown(300);
            if (stickyFlag == true || $('body').hasClass('div_responsive')) {
                $('.header_wrapper .pbtheme_top ').slideDown(300);
            }
            $this.children('i').attr('class', 'divicon-minus')
        }
    });

    $(document).on('click', 'a#div_rtrigger', function (e) {
        e.preventDefault();
        var $this = $(this);
        if ($(this).hasClass('responsive-open')) {
            $('#div_header_menu').stop(true, true).slideUp(300);
            $this.removeClass('responsive-open');
        }
        else {
            $this.addClass('responsive-open');
            $('#div_header_menu').stop(true, true).slideDown(300);
        }
    });

//			Grid Position Measurement


    $(document).on('touchstart', '.div_touch_optimized', function (e) {
        divTouchOptimizedStart = pointerEventToXY(e).x;
    });


    $(document).on('touchend', '.div_touch_optimized', function (e) {
        var curr = pointerEventToXY(e).x;
        var res = divTouchOptimizedStart - curr;
        if (Math.abs(res) > 50 && res > 0) {
            $(this).find('.blog_top_pagination .next').trigger('click');
        } else if (Math.abs(res) > 30 && res < 0) {
            $(this).find('.blog_top_pagination .previous').trigger('click');
        }
    });

    var gridSlider = new Array();
    var pbthemeSlider = new Array();
    var pbthemeSliderWidthReference = new Array();

    $(document).ready(function () {


        $(document).on('click', '.widget-pbtheme-catthree h3 a', function () {
            $(this).parents().eq(3).children('ul').slideUp();
            $(this).parents().eq(2).next().slideDown();
            return false;
        });

        $('.pbtheme_grid.grid_content').each(function (ind) {
            var $this = $(this);
            var slideClassStr;
            var h = ($(window).width() > 800 * window.devicePixelRatio) ? ($this.width() / 2 - $this.width() / 200) : ($this.width() * 3 / 4);
            $this.height(h).find('.grid_slides, .grid_slide').height(h);
            slideClassStr = 'grid_slide';
//		GridSliderStorage[ind] = $this.not('.div_grid_responsive')clone();
//		GridResponsiveStorage[ind] = $this.not('.div_grid_notresponsive')clone();


//		GridSliderStorage[ind] = $this.html();
//		console.log(GridSliderStorage[0]);
//		GridSliderRespFlag[ind] = false;
//		if($(window).width() > 800*window.devicePixelRatio) {
//			var h = $this.width()/2 - $this.width()/200;
//			$this.height(h).find('.grid_slides, .grid_slide').height(h);
//			slideClassStr = 'grid_slide';
//			$this.find('.grid_single_post').css('float','none');
//		}else {
//			var sldString = [];
//			$this.find('.grid_single_post').each(function(ind2){
//				sldString[ind2] = $(this).clone();
//				slideClassStr = 'grid_single_post';
//			});
//			
//			for(var i=0; i<sldString.length;i++) {
//				$this.find('.grid_slides').append(sldString[i]);
//			}
//			$this.find('.grid_single_post').css('float','left');
//			
//			$this.find('.grid_slide').each(function(){$(this).remove();});
//			var h = $this.width()/2 - $this.width()/200;
//			$this.height(h).find('.grid_slides, .grid_slide').height(h);
//			GridSliderRespFlag[ind] = true;
//		}




            gridSlider[ind] = $this.swiper({
                createPagination: false,
                wrapperClass: 'grid_slides',
                slideClass: slideClassStr,
                loop: true,
                grabCursor: true
            });



            if ($(window).width() > 800 * window.devicePixelRatio) {
                $this.not('.div_grid_responsive').removeClass('div_dis_none');
                $this.not('.div_grid_notresponsive').addClass('div_dis_none');
                gridSlider[ind].reInit();
            } else {
                $this.not('.div_grid_responsive').addClass('div_dis_none');
                $this.not('.div_grid_notresponsive').removeClass('div_dis_none');
                gridSlider[ind].reInit();
            }


            $this.find('.grid_navigation_previous').on('click', function () {
                gridSlider[ind].swipePrev();

            });
            $this.find('.grid_navigation_next').on('click', function () {
                gridSlider[ind].swipeNext();
            });


        });

        $(window).load(function () {
            if ($('.pbtheme_grid.grid_content').length > 0) {
                gridSlider[0].reInit();
            }

        });

        $(window).resize(function () {
            //fullHeadHeig = $('.header_wrapper').outerHeight() + adminbar + 20;
            clearTimeout(gridSliderResizeTimer);
            gridSliderResizeTimer = setTimeout(function () {

                $('.pbtheme_grid.grid_content').each(function (ind) {
                    var $this = $(this);
                    var h = ($(window).width() > 800 * window.devicePixelRatio) ? ($this.width() / 2 - $this.width() / 200) : ($this.width() * 3 / 4);
                    $this.height(h).find('.grid_slides, .grid_slide').height(h);

                    if ($(window).width() > 800 * window.devicePixelRatio) {
                        $this.not('.div_grid_responsive').removeClass('div_dis_none');
                        $this.not('.div_grid_notresponsive').addClass('div_dis_none');
                        gridSlider[ind].reInit();
                    } else {
                        $this.not('.div_grid_responsive').addClass('div_dis_none');
                        $this.not('.div_grid_notresponsive').removeClass('div_dis_none');
                        gridSlider[ind].reInit();
                    }
                });

                /*		$('.pbtheme_grid.grid_content').each(function(ind) {
                 var $this = $(this);
                 var slideClassStr;
                 if($(window).width() > 800*window.devicePixelRatio) {
                 if($this.find('.grid_slide').length == 0){$this.html(GridSliderStorage[ind]);GridSliderRespFlag[ind] = true;}
                 var h = $this.width()/2 - $this.width()/200;
                 $this.height(h).find('.grid_slides, .grid_slide').height(h);
                 slideClassStr = 'grid_slide';
                 $this.find('.grid_single_post').css('float','none');
                 } else {
                 var sldString = [];
                 $this.find('.grid_single_post').each(function(ind2){
                 sldString[ind2] = $(this).clone();
                 slideClassStr = 'grid_single_post';
                 });
                 
                 for(var i=0; i<sldString.length;i++) {
                 $this.find('.grid_slides').append(sldString[i]);
                 }
                 $this.find('.grid_single_post').css('float','left');
                 
                 $this.find('.grid_slide').each(function(){$(this).remove();});
                 var h = $this.width()/2 - $this.width()/200;
                 $this.height(h).find('.grid_slides, .grid_slide').height(h);
                 GridSliderRespFlag[ind] = false;
                 }
                 console.log(GridSliderRespFlag[ind]);
                 if(GridSliderRespFlag[ind]){
                 gridSlider[ind] = $this.swiper({
                 createPagination : false,
                 wrapperClass : 'grid_slides',
                 slideClass : slideClassStr,
                 loop:true,
                 grabCursor: true
                 });
                 
                 } 
                 gridSlider[ind].params.slideClass=slideClassStr;
                 gridSlider[ind].reInit();
                 
                 
                 
                 $this.find('.grid_navigation_previous').on('click', function(){
                 gridSlider[ind].swipePrev();
                 });
                 $this.find('.grid_navigation_next').on('click', function(){
                 gridSlider[ind].swipeNext();
                 });
                 
                 
                 });*/
            }, 200);

            headHeig = $('.header_wrapper').outerHeight();
            menuWrap = $('nav.menu_wrapper').outerHeight();
            pbtheme_navigation();
        });



//			pbtheme slider
        $('.pbtheme_slider_wrapper').each(function (ind) {
            var $this = $(this);
            $this.addClass('pbtheme_slider_' + ind).children('.pbtheme_slider_content').addClass('pbtheme_slider_content_' + ind).children('.separate-slider-column').addClass('separate-slider-column_' + ind);
            numberOfSlides[ind] = parseInt($this.attr('data-slides'));

            pbthemeSliderWidthReference[ind] = 280;


            $(document).on('mousedown', '.pbtheme_slider_wrapper a.div_slider_img', function (e) {
                divSliderPosiitonMark = pointerEventToXY(e);
            });
            $(document).on('mousemove', '.pbtheme_slider_wrapper a.div_slider_img', function (e) {
                divSliderPosiitonMarkNew = pointerEventToXY(e);
            });

            $(document).on('click', '.pbtheme_slider_wrapper a.div_slider_img', function (e) {
                if (Math.abs(divSliderPosiitonMark.x - divSliderPosiitonMarkNew.x) >= 50 || Math.abs(divSliderPosiitonMark.y - divSliderPosiitonMarkNew.y) >= 50) {
                    console.log('bleh');
                    e.preventDefault();
                }
            });

            numberOfSlidesFormated[ind] = (numberOfSlides[ind] < Math.floor($this.width() / (pbthemeSliderWidthReference[ind]))) ? numberOfSlides[ind] : Math.floor($this.width() / (pbthemeSliderWidthReference[ind]));
            var wrapSel = 'pbtheme_slider_content_' + ind;
            var slideSel = 'separate-slider-column_' + ind;
            var itemHeight = ($this.width() / numberOfSlidesFormated[ind]) / 1.6;
            $this.height(itemHeight).find('.pbtheme_slider_content, .separate-slider-column').height(itemHeight);

            pbthemeSlider[ind] = $('.pbtheme_slider_' + ind).swiper({
                createPagination: false,
                wrapperClass: wrapSel,
                slideClass: slideSel,
                loop: true,
                grabCursor: true,
                loopedSlides: 6,
                slidesPerView: numberOfSlidesFormated[ind]
            });
        });



        $(window).resize(function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                $('.pbtheme_slider_wrapper').each(function (ind) {
                    var $this = $(this);
                    numberOfSlidesFormated[ind] = (numberOfSlides[ind] < Math.floor($this.width() / (pbthemeSliderWidthReference[ind]))) ? numberOfSlides[ind] : Math.floor($this.width() / (pbthemeSliderWidthReference[ind]));
                    var itemHeight = ($this.width() / numberOfSlidesFormated[ind]) / 1.6;
                    var wrapSel = 'pbtheme_slider_content_' + ind;
                    var slideSel = 'separate-slider-column_' + ind;
                    $this.height(itemHeight).find('.pbtheme_slider_content, .separate-slider-column').height(itemHeight);
                    pbthemeSlider[ind].params.slidesPerView = numberOfSlidesFormated[ind];
                    pbthemeSlider[ind].reInit();
                    pbthemeSlider[ind].swipeTo(0, 500);
                });
            }, 200);
        });


        $(window).load(function () {
//			pbtheme slider
            $('.pbtheme_slider_wrapper').each(function (ind) {
                var $this = $(this);
                $this.addClass('pbtheme_slider_' + ind).children('.pbtheme_slider_content').addClass('pbtheme_slider_content_' + ind).children('.separate-slider-column').addClass('separate-slider-column_' + ind);
                numberOfSlides[ind] = parseInt($this.attr('data-slides'));

                pbthemeSliderWidthReference[ind] = 280;


                numberOfSlidesFormated[ind] = (numberOfSlides[ind] < Math.floor($this.width() / (pbthemeSliderWidthReference[ind]))) ? numberOfSlides[ind] : Math.floor($this.width() / (pbthemeSliderWidthReference[ind]));
                var wrapSel = 'pbtheme_slider_content_' + ind;
                var slideSel = 'separate-slider-column_' + ind;
                var itemHeight = ($this.width() / numberOfSlidesFormated[ind]) / 1.6;
                $this.height(itemHeight).find('.pbtheme_slider_content, .separate-slider-column').height(itemHeight);

                pbthemeSlider[ind] = $('.pbtheme_slider_' + ind).swiper({
                    createPagination: false,
                    wrapperClass: wrapSel,
                    slideClass: slideSel,
                    loop: true,
                    grabCursor: true,
                    loopedSlides: 6,
                    slidesPerView: numberOfSlidesFormated[ind]
                });

            });
        });




        /*					Add 'has_children' class to menus					*/

        $.each($('#div_header_menu > ul > li, li.menu-item').has('ul.sub-menu'), function () {
            $(this).addClass('has_children');
        });


        /*					Magazine tags					*/

        $(document).on('mouseenter', '.category_tag', function () {
            if (!$(this).hasClass('active')) {
                $(this).addClass('active');
                $(this).find('a').each(function (i) {
                    $(this).delay(100 * i).animate({opacity: 1}, 200);
                });
            }
        });

        $(document).on('mouseleave', '.category_tag', function () {
            $($(this).find('.mag_tag:not(:first-child) a').get().reverse()).each(function (i) {
                $(this).stop(true).delay(100 * i).animate({opacity: 0}, 200);
            });
            $(this).removeClass('active');

        });


    });

    
	$(document).on('click', '.pbtheme_menu a[href^="#"]:not(:last-child), .element-to-the-top a', function () {
        var target = $(this).attr('href');

        if (target == '#') {
            return false;
        }

        var offset = (target == '#pbtheme_wrapper' ? 0 : $(target).offset().top);
        var speed = $(document).height() / 5000;
        TweenLite.to(window, speed, {scrollTo: {y: offset, x: 0}, ease: Quad.easeInOut});
        return false;
    });
	

    $(document).ready(function () {

        $('#div_header_menu > ul.pbtheme_menu').append('<li id="search-trigger"><a href="#"><i class="divicon-search"></i></a></li>');
        if (typeof hidetopbar != "undefined" && hidetopbar != "1") {
            if ($('.pbtheme_header_widgets').length) {
                $('#div_header_menu > ul.pbtheme_menu').append('<li id="widgets-trigger"><a href="#"><i class="divicon-plus cl1"></i></a></li>');
            } else {
                $('#div_header_menu > ul.pbtheme_menu').append('<li id="topmenu-trigger"><a href="#"><i class="divicon-plus cl2"></i></a></li>');
            }
        }

//		news feed tabs 

        $(document).on('click', '.news_feed_tabs .tabsnav a', function (e) {
            e.preventDefault();
            if (!$(this).hasClass('active')) {
                $(this).closest('ul').find('a').removeClass('active background-color-main-rgba');
                $(this).addClass('active div_main_bg');

                $(this).closest('.news_feed_tabs').find('.single_slide').removeClass('shown').stop(true).animate({opacity: 0}, 300, function () {
                    $(this).hide();
                });

                $(this).closest('.news_feed_tabs').find($(this).attr('href')).addClass('shown').show().stop(true).animate({opacity: 1}, 300);
            }

        });


        $('.news_feed_tabs .tabsnav').each(function () {
            $(this).find('a').eq(0).trigger('click');
        });


//			Header Menu Solid Hover

        $(document).on('click', '.div_responsive #div_header_menu li.has_children a', function (e) {
            if (e.pageX - $(this).offset().left > $(this).parent().width() - 36) {
                $(this).next().slideToggle(200);
                return false;
            }
        });

        if ($(window).width() > pbtheme.go_responsive) {
            navActive = true;
        } else {
            $('body').addClass('div_responsive');

        }

        var nav = $('.menu_wrapper ul.pbtheme_menu');




        function is_touch_device() {
            return !!('ontouchstart' in window);
        }

        if (navActive == true) {
            if (is_touch_device() === false) {
                nav.on('mouseover', function (e) {
                    if ($('body').hasClass('div_responsive')) {
                        return;
                    }
                    nav.doTimeout('menu-item', 100, over, e.target);
                    return false;

                }).on('mouseout', function () {
                    if ($('body').hasClass('div_responsive')) {
                        return;
                    }
                    nav.doTimeout('menu-item', 300, out);
                    return false;
                });
            }
            else {
                nav.on('touchstart', function (e) {
                    if ($('body').hasClass('div_responsive')) {
                        return;
                    }
                    var curr = $(e.target).closest('li');
                    if (!curr.hasClass('div-nav-activated')) {
                        curr.addClass('div-nav-activated');
                        nav.doTimeout('menu-item', 100, over, e.target);
                        return false;
                    }

                });
            }

        }


        function over(elem) {
            if ($(elem).parents().eq(1).is('.is_fullwidth, .navmenu_fullwidth')) {
                return false;
            }
            var parent = $(elem).closest('li');
            var liHeight = $(elem).outerHeight();
            out(parent);
            parent.addClass('hovered');
            parent.children('ul:hidden').css({top: '100%'}).delay(100).slideDown(100);
        }
        ;

        function out(elem) {
            if ($(elem).parents().eq(1).is('.is_fullwidth, .navmenu_fullwidth')) {
                return false;
            }
            var parents = elem ? $(elem).closest('li').siblings() : nav.children();
            if (nav.is('.menu_wrapper ul.pbtheme_menu')) {
                parents = parents.not('.active');
            }
            parents.removeClass('hovered');
            parents.find('li.menu-item.hovered').removeClass('hovered');
            parents.find('ul.sub-menu').delay(100).fadeOut(100);

        }
        ;


//	Input Field Clear


        $('input.input_field').focus(function () {
            if (!$(this).hasClass('collected')) {
                $(this).attr('data-val', $(this).val());
                $(this).addClass('collected');
                $(this).val('');
            } else {
                if ($(this).val() === $(this).attr('data-val')) {
                    $(this).val('');
                }
            }

        });
        $('input.input_field').focusout(function () {
            if ($(this).val() === '') {
                $(this).val($(this).attr('data-val'));
            }

        });


//	Textarea Field Clear

        $('textarea.textarea_field').focus(function () {
            if (!$(this).hasClass('collected')) {
                $(this).attr('data-val', $(this).html());
                $(this).addClass('collected');
                $(this).html('');
            } else {
                if ($(this).html() === $(this).attr('data-val')) {
                    $(this).html('');
                }
            }
        });
        $('textarea.textarea_field').focusout(function () {
            if ($(this).html() === '') {
                $(this).html($(this).attr('data-val'));
            }


        });

//			select replace
        $('#bbpress-forums .bbp_select_wrapper > select').each(function () {
            var $this = $(this);
            $this.hide();
            var selected = $this.find('option[value=' + $this.val() + ']').html();
            var html = '<div class="select_menu" data-name="' + $this.attr('name') + '">' +
                    '<span>' + selected + '</span>' +
                    '<a href="" class="drop_button"><i class="fa fa-angle-down"></i></a>' +
                    '<ul style="display:none">';
            $this.find('option').each(function () {
                html += '<li><a href="#" data-value="' + $(this).attr('value') + '">' + $(this).html() + '</a></li>';
            });
            html +=
                    '</ul>' +
                    '<div class="clear"></div><!-- clear -->' +
                    '</div><!-- select_menu -->';
            $(html).insertAfter($this);
        });

        $('.select_menu').hover(function () {
            $(this).data('hover', true);
        }, function () {
            $(this).data('hover', false);
        });


        $('.drop_button').click(function (e) {
            e.preventDefault();
            var $parent = $(this).parent();
            if (!$parent.hasClass('active')) {
                $parent.addClass('active').find('ul').show();
            }
            else {
                $parent.removeClass('active').find('ul').hide();
            }
        });

        $('.select_menu ul a').click(function (e) {
            e.preventDefault();
            var $parent = $(this).parent().parent().parent();
            var $select = $('select[name="' + $parent.attr('data-name') + '"]');
            $select.val($(this).attr('data-value')).change();
            $parent.find('span').html($(this).html());
            $parent.removeClass('active').find('ul').hide();
        });

        $('body').click(function () {
            $('.select_menu.active').each(function () {
                if (!$(this).data('hover')) {
                    $(this).removeClass('active').find('ul').hide();
                }
            });
        });



//			team member module

        $(document).on('mouseenter', '.team_member_module .img_wrapper', function () {
            $(this).find('.hover_element').show().stop(true).animate({opacity: 1}, 300);
            $(this).find('.hover_element').find('.socials li a').each(function (index) {
                $(this).stop(true).delay(index * 50).animate({opacity: 1}, 200);
            });
        });
        $(document).on('mouseleave', '.team_member_module .img_wrapper', function () {
            $(this).find('.hover_element').show().stop(true).animate({opacity: 0}, 300, function () {
                $(this).hide();
            }).find('.socials li a').stop(true).animate({opacity: 0}, 300);
        });



        $(document).on('click', '.infinite-load-button', function () {
            infiniteClickFlag = false;
            $(this).addClass('ilb-active');
            var pbthemeInfiniteIndex = 0;
            window.pbthemeInfiniteDelayIndex = 0;
            window.pbthemeInfiniteScreenMeasurement = $(window).scrollTop() - $('.infinite-load-target').find('div > .pbuilder_column').eq(0).find('.magazine_image_column:last').offset().top - $('.infinite-load-target').find('div > .pbuilder_column').eq(0).find('.magazine_image_column:last').height() + $(window).height();

            var currentItem = $(this);
            var oldItem = currentItem.parent().prev().prev();
            var string = oldItem.attr('data-string');
            var actionSend = 'pbtheme_ajaxinfinite_send';
            var ajaxPage = currentItem.attr('data-page');
            var color = oldItem.find('.hover_transparent:first-child').hasClass('not-transparent');
            var data = {
                action: actionSend,
                page: ajaxPage,
                data: string,
                colored: color
            };

            $.post(pbtheme.ajaxurl, data, function (response) {
                if (response) {

                    $('body').append('<div id="infinitePreLoad" style="overflow:hidden; height:0;">' + response + '<div>');
                    var imagesNum = $('#infinitePreLoad img').length;
                    if (imagesNum != 0) {
                        $('#infinitePreLoad img').load(function () {
                            imagesNum--;
                            if (imagesNum == 0) {
                                window.pbthemeInfiniteLoadItemArray = [];
                                window.pbthemeInfiniteLoadItemCounter = 0;
                                window.pbthemeInfiniteLoadItemArrayInit = [];
                                window.pbthemeInfiniteLoadItemCounterInit = 0;
                                var pbthemeInfiniteIndex = 0;
                                window.pbthemeInfiniteDelayIndex = 0;
                                currentItem.attr('data-page', parseInt(ajaxPage, 10) + 1);
                                window.pbthemeInfiniteLoadItemArray = (response).split("--||--");
                                while (window.pbthemeInfiniteLoadItemArray.length > window.pbthemeInfiniteLoadItemCounter + 1) {
                                    var pbthemeInfiniteHeightCal = 0;
                                    pbthemeInfiniteIndex = 0;

                                    pbthemeInfiniteHeightCal = oldItem.find('div > .pbuilder_column').eq(0).height();

                                    var arrone = oldItem.find('div > .pbuilder_column');
                                    for (var m = 0; m < arrone.length; m++) {
                                        var itemx = arrone.eq(m);
                                        if (itemx.height() < pbthemeInfiniteHeightCal) {
                                            pbthemeInfiniteHeightCal = itemx.height();
                                            pbthemeInfiniteIndex = itemx.index();
                                        }
                                    }
                                    window.pbthemeInfiniteScreenMeasurement = $(window).scrollTop() - oldItem.find('div > .pbuilder_column').eq(pbthemeInfiniteIndex).find('.magazine_image_column:last').offset().top - oldItem.find('div > .pbuilder_column').eq(pbthemeInfiniteIndex).find('.magazine_image_column:last').height() + $(window).height();

                                    if (jQuery.isFunction((jQuery.fn.kkstarratings))) {
                                        oldItem.children('div').children('.pbuilder_column').eq(pbthemeInfiniteIndex).append(window.pbthemeInfiniteLoadItemArray[window.pbthemeInfiniteLoadItemCounter]).find('.magazine_image_column:last').css({opacity: 0}).delay(window.pbthemeInfiniteDelayIndex * 50).animate({opacity: 1}, 300).find('.kk-star-ratings').kkstarratings();
                                    }
                                    else {
                                        oldItem.children('div').children('.pbuilder_column').eq(pbthemeInfiniteIndex).append(window.pbthemeInfiniteLoadItemArray[window.pbthemeInfiniteLoadItemCounter]).find('.magazine_image_column:last').css({opacity: 0}).delay(window.pbthemeInfiniteDelayIndex * 50).animate({opacity: 1}, 300);
                                    }

                                    window.pbthemeInfiniteDelayIndex++;
                                    window.pbthemeInfiniteLoadItemCounter++;
                                }

                                $('.pbtheme_ultimate_fix a[rel="lightbox"]').swipebox();
                                $('.pbtheme_ultimate_fix').removeClass('pbtheme_ultimate_fix');

                                $('#infinitePreLoad').remove();

                            }
                        });
                        infiniteClickFlag = true;
                        $('.infinite-load-button').removeClass('ilb-active');
                    } else {
                        window.pbthemeInfiniteLoadItemArray = [];
                        window.pbthemeInfiniteLoadItemCounter = 0;
                        window.pbthemeInfiniteLoadItemArrayInit = [];
                        window.pbthemeInfiniteLoadItemCounterInit = 0;
                        var pbthemeInfiniteIndex = 0;
                        window.pbthemeInfiniteDelayIndex = 0;
                        currentItem.attr('data-page', parseInt(ajaxPage, 10) + 1);
                        window.pbthemeInfiniteLoadItemArray = (response).split("--||--");
                        while (window.pbthemeInfiniteScreenMeasurement > 0 && window.pbthemeInfiniteLoadItemArray.length > window.pbthemeInfiniteLoadItemCounter + 1) {
                            var pbthemeInfiniteHeightCal = 0;
                            pbthemeInfiniteIndex = 0;

                            pbthemeInfiniteHeightCal = oldItem.find('div > .pbuilder_column').eq(0).height();

                            var arrone = oldItem.find('div > .pbuilder_column');
                            for (var m = 0; m < arrone.length; m++) {
                                var itemx = arrone.eq(m);
                                if (itemx.height() < pbthemeInfiniteHeightCal) {
                                    pbthemeInfiniteHeightCal = itemx.height();
                                    pbthemeInfiniteIndex = itemx.index();
                                }

                            }
                            window.pbthemeInfiniteScreenMeasurement = $(window).scrollTop() - oldItem.find('div > .pbuilder_column').eq(pbthemeInfiniteIndex).find('.magazine_image_column:last').offset().top - oldItem.find('div > .pbuilder_column').eq(pbthemeInfiniteIndex).find('.magazine_image_column:last').height() + $(window).height();

                            oldItem.children('div').children('.pbuilder_column').eq(pbthemeInfiniteIndex).append(window.pbthemeInfiniteLoadItemArray[window.pbthemeInfiniteLoadItemCounter]).find('.magazine_image_column:last').css({opacity: 0}).delay(window.pbthemeInfiniteDelayIndex * 50).animate({opacity: 1}, 300);
                            window.pbthemeInfiniteDelayIndex++;
                            window.pbthemeInfiniteLoadItemCounter++;
                        }
                        $('#infinitePreLoad').remove();
                        infiniteClickFlag = true;
                        $('.infinite-load-button').removeClass('ilb-active');

                    }
                    $(this).find('.pbuilder_module').trigger('refresh');
                } else {
                    currentItem.addClass('infinite-load-button-no-more').removeClass('infinite-load-button').text('No more posts');
                }



            });
        });


//		portfolio columns

        $(document).ready(function () {
            var newItem = $('.portfolio_content, .div_portfolio_slider');
            var prel = newItem.find('.div_ajax_col .pbtheme_hover').width();
            newItem.find('.div_ajax_col .pbtheme_hover').each(function () {
                /*
                 Code Added by Asim Ashraf - DevBatch
                 Date: 2/2/2015
                 css set min-height Comment
                 */
                //$(this).css('min-height', prel);
//		jQuery(this).css({opacity: 0});
//		jQuery('<div class="img_prel"><i class="fa fa-spinner fa-spin"></i></div>').insertAfter(jQuery(this));
//		var prel = jQuery(this).siblings('.img_prel');
//		prel.height(prel.width());
            });

            portfolioColumnsInit(newItem);

//	setTimeout(function(){
//		$('.portfolio_content, .div_portfolio_slider').find('.div_ajax_col .pbtheme_hover img:not(".appeared")').each(function(){
//			jQuery(this).css({'display' :'block'}).animate({opacity:1}, 200);
//			jQuery(this).siblings('.img_prel').hide();
//		});
//	},5000);

//		$('.portfolio_content, .div_portfolio_slider').find('.div_ajax_col .pbtheme_hover img').load(function(){
//			jQuery(this).addClass('appeared').css({'display' :'block'}).animate({opacity:1}, 200);
//			jQuery(this).siblings('.img_prel').hide();
//		});
        });

//$(window).load(function(){
//	var newItem = $('.portfolio_content, .div_portfolio_slider');
//	portfolioColumnsInit(newItem);
//});
//	

        $(document).on('click', '.div_top_nav_cat li a', function () {
            $(this).closest('.div_top_nav_cat').find('.div_ajax_port_selected').removeClass('div_ajax_port_selected');
            $(this).parent().addClass('div_ajax_port_selected');
        });

//				Fbuilder fixed

        $(document).on('refresh', '.pbuilder_module', function () {


//	portfolio columns

            portfolioColumnsInit($(this).find('.portfolio_content, .div_portfolio_slider'));

//		grid slider

            $(this).find('.pbtheme_grid.grid_content').each(function (ind) {
                var $this = $(this);
                var h = $(this).width() / 2 - $(this).width() / 200;
                $(this).height(h);
                $(this).find('.grid_slide, .grid_slides').height(h);
                $(this).find('.grid_navigation').children('div').css('line-height', h + 'px');

                gridSlider[ind] = $this.swiper({
                    createPagination: false,
                    wrapperClass: 'grid_slides',
                    slideClass: 'grid_slide',
                    loop: true,
                    grabCursor: true
                });


            });


//		pbtheme slider

            $(this).find('.pbtheme_slider_wrapper').each(function (ind) {
                var $this = $(this);
                $this.addClass('pbtheme_slider_' + ind).children('.pbtheme_slider_content').addClass('pbtheme_slider_content_' + ind).children('.separate-slider-column').addClass('separate-slider-column_' + ind);
                numberOfSlides[ind] = parseInt($this.attr('data-slides'));

                pbthemeSliderWidthReference[ind] = 280;


                numberOfSlidesFormated[ind] = (numberOfSlides[ind] < Math.floor($this.width() / (pbthemeSliderWidthReference[ind]))) ? numberOfSlides[ind] : Math.floor($this.width() / (pbthemeSliderWidthReference[ind]));
                var wrapSel = 'pbtheme_slider_content_' + ind;
                var slideSel = 'separate-slider-column_' + ind;
                var itemHeight = ($this.width() / numberOfSlidesFormated[ind]) / 1.6;
                console.log(itemHeight);
                $this.height(itemHeight).find('.pbtheme_slider_content, .separate-slider-column').height(itemHeight);

                pbthemeSlider[ind] = $('.pbtheme_slider_' + ind).swiper({
                    createPagination: false,
                    wrapperClass: wrapSel,
                    slideClass: slideSel,
                    loop: true,
                    grabCursor: true,
                    loopedSlides: 6,
                    slidesPerView: numberOfSlidesFormated[ind]
                });
            });



//	magazine infinite load

            $(this).find('.infinite-load-target').each(function () {
                window.pbthemeInfiniteLoadItemCounter = 0;
                window.pbthemeInfiniteLoadItemArrayInit = [];
                window.pbthemeInfiniteLoadItemCounterInit = 0;
                var pbthemeInfiniteIndex = 0;
                window.pbthemeInfiniteDelayIndex = 0;
                $(this).children('.infinite-load-init').children('li').each(function (index) {
                    window.pbthemeInfiniteLoadItemArrayInit[index] = $(this).html();
                });

                $(this).children('.infinite-load-init').remove();

                var targetwidth = $(this).width();

                if (targetwidth < 480) {
                    $(this).append('<div><div class="pbuilder_column pbuilder_column-1-1"></div><!-- pbtheme-1-1 -->');
                }
                else if (targetwidth < 900) {
                    $(this).append('<div><div class="pbuilder_column pbuilder_column-1-2"></div><!-- pbtheme-1-2 --><div class="pbuilder_column pbuilder_column-1-2"></div></div><!-- pbtheme-1-2 -->');
                }
                else {
                    $(this).append('<div><div class="pbuilder_column pbuilder_column-1-3"></div><!-- pbtheme-1-3 --><div class="pbuilder_column pbuilder_column-1-3"></div><!-- pbtheme-1-3 --><div class="pbuilder_column pbuilder_column-1-3"></div></div><!-- pbtheme-1-3 -->');
                }

                window.pbthemeInfiniteLoadColumnCounter = $(this).find('div > .pbuilder_column').length;

                for (var i = 0; i < window.pbthemeInfiniteLoadColumnCounter; i++) {
                    $(this).children('div').children('.pbuilder_column').eq(i).append(window.pbthemeInfiniteLoadItemArrayInit[window.pbthemeInfiniteLoadItemCounterInit]).find('.magazine_image_column:last').css({opacity: 0}).delay(window.pbthemeInfiniteDelayIndex * 50).animate({opacity: 1}, 300);
                    window.pbthemeInfiniteDelayIndex++;
                    window.pbthemeInfiniteLoadItemCounterInit++;

                }

                while (window.pbthemeInfiniteLoadItemArrayInit.length > window.pbthemeInfiniteLoadItemCounterInit) {
                    var pbthemeInfiniteHeightCal = 0;
                    pbthemeInfiniteIndex = 0;

                    pbthemeInfiniteHeightCal = $(this).find('div > .pbuilder_column').eq(0).height();

                    var arrone = $(this).find('div > .pbuilder_column');
                    for (var m = 0; m < arrone.length; m++) {
                        var itemx = arrone.eq(m);
                        if (itemx.height() < pbthemeInfiniteHeightCal) {
                            pbthemeInfiniteHeightCal = itemx.height();
                            pbthemeInfiniteIndex = itemx.index();
                        }
                    }

                    window.pbthemeInfiniteScreenMeasurement = $(window).scrollTop() - $(this).find('div > .pbuilder_column').eq(0).find('.magazine_image_column:last').offset().top - $(this).find('div > .pbuilder_column').eq(0).find('.magazine_image_column:last').height() + $(window).height();

                    $(this).children('div').children('.pbuilder_column').eq(pbthemeInfiniteIndex).append(window.pbthemeInfiniteLoadItemArrayInit[window.pbthemeInfiniteLoadItemCounterInit]).find('.magazine_image_column:last').css({opacity: 0}).delay(window.pbthemeInfiniteDelayIndex * 50).animate({opacity: 1}, 300);
                    window.pbthemeInfiniteDelayIndex++;
                    window.pbthemeInfiniteLoadItemCounterInit++;

                }

                infiniteClickFlag = true;

                if (typeof (swipeboxInstance) != 'undefined')
                    swipeboxInstance.refresh();
                $(this).find('.pbuilder_module').trigger('refresh');

            });

//		news feed tabs 	

            $(this).find('.news_feed_tabs .tabsnav').each(function () {
                $(this).find('a').eq(0).trigger('click');
            });


        });



    });   // document.ready END


    $(window).load(function () {
//		infinite load - Magazine category	
        window.pbthemeInfiniteLoadItemArray = [];
        window.pbthemeInfiniteLoadItemCounter = 0;
        window.pbthemeInfiniteLoadItemArrayInit = [];
        window.pbthemeInfiniteLoadItemCounterInit = 0;
        var pbthemeInfiniteIndex = 0;
        window.pbthemeInfiniteDelayIndex = 0;
        $('.infinite-load-target').each(function () {

            window.pbthemeInfiniteLoadItemCounter = 0;
            window.pbthemeInfiniteLoadItemArrayInit = [];
            window.pbthemeInfiniteLoadItemCounterInit = 0;
            var pbthemeInfiniteIndex = 0;
            window.pbthemeInfiniteDelayIndex = 0;
            $(this).children('.infinite-load-init').children('li').each(function (index) {
                window.pbthemeInfiniteLoadItemArrayInit[index] = $(this).html();
            });

            $(this).addClass('pbuilder_row').children('.infinite-load-init').remove();

            var targetwidth = $(this).width();
            if (targetwidth < 480) {
                $(this).append('<div><div class="pbuilder_column pbuilder_column-1-1"></div></div><!-- pbtheme-1-1 -->');
            }
            else if (targetwidth < 900) {
                $(this).append('<div><div class="pbuilder_column pbuilder_column-1-2"></div><!-- pbtheme-1-2 --><div class="pbuilder_column pbuilder_column-1-2"></div><!-- pbtheme-1-2 --></div>');
                2
            }
            else {
                $(this).append('<div><div class="pbuilder_column pbuilder_column-1-3"></div><!-- pbtheme-1-3 --><div class="pbuilder_column pbuilder_column-1-3"></div><!-- pbtheme-1-3 --><div class="pbuilder_column pbuilder_column-1-3"></div><!-- pbtheme-1-3 --></div>');
            }

            window.pbthemeInfiniteLoadColumnCounter = $(this).find('div > .pbuilder_column').length;

            for (var i = 0; i < window.pbthemeInfiniteLoadColumnCounter; i++) {
                $(this).children('div').children('.pbuilder_column').eq(i).append(window.pbthemeInfiniteLoadItemArrayInit[window.pbthemeInfiniteLoadItemCounterInit]).find('.magazine_image_column:last').css({opacity: 0}).delay(window.pbthemeInfiniteDelayIndex * 50).animate({opacity: 1}, 300);
                window.pbthemeInfiniteDelayIndex++;
                window.pbthemeInfiniteLoadItemCounterInit++;

            }
            while (window.pbthemeInfiniteLoadItemArrayInit.length > window.pbthemeInfiniteLoadItemCounterInit) {
                var pbthemeInfiniteHeightCal = 0;
                pbthemeInfiniteIndex = 0;

                pbthemeInfiniteHeightCal = $(this).find('div > .pbuilder_column').eq(0).height();

                var arrone = $(this).find('div > .pbuilder_column');
                for (var m = 0; m < arrone.length; m++) {
                    var itemx = arrone.eq(m);
                    if (itemx.height() < pbthemeInfiniteHeightCal) {
                        pbthemeInfiniteHeightCal = itemx.height();
                        pbthemeInfiniteIndex = itemx.index();
                    }
                }

                window.pbthemeInfiniteScreenMeasurement = $(window).scrollTop() - $(this).find('div > .pbuilder_column').eq(0).find('.magazine_image_column:last').offset().top - $(this).find('div > .pbuilder_column').eq(0).find('.magazine_image_column:last').height() + $(window).height();

                $(this).children('div').children('.pbuilder_column').eq(pbthemeInfiniteIndex).append(window.pbthemeInfiniteLoadItemArrayInit[window.pbthemeInfiniteLoadItemCounterInit]).find('.magazine_image_column:last').css({opacity: 0}).delay(window.pbthemeInfiniteDelayIndex * 50).animate({opacity: 1}, 300);
                window.pbthemeInfiniteDelayIndex++;
                window.pbthemeInfiniteLoadItemCounterInit++;

            }
            $('a[rel="lightbox"]').swipebox();
            $('.infinite-load-button').animate({'opacity': 1}, 300);
            $(this).find('.pbuilder_module').trigger('refresh');
        });

    });



    $(document).on('refresh', '.pbuilder_module', function () {

        $(this).find('.pbtheme_featured_video').each(function () {
            window.afv_margin_bottom = $(this).css('margin-bottom');
            window.afv_margin_right = $(this).css('margin-right');
            window.afv_margin_top = $(this).css('margin-top');
            window.afv_margin_left = $(this).css('margin-left');
            window.afv_floating = $(this).css('float');
            $(this).wrap('<div></div>').parent().addClass('pbtheme_featured_video_wrapper').css({'margin-bottom': window.afv_margin_bottom, 'margin-right': window.afv_margin_right, 'margin-top': window.afv_margin_top, 'margin-left': window.afv_margin_left, 'float': window.afv_floating}).prepend('<div><div><i class="fa fa-play-circle"></i></div></div>');
        });

        $(this).find('[class^="attachment-shop_"]').each(function () {
            if ($(this).parents('.widget').length > 0) {
                return;
            }
            var productImg = $(this);
            if (!productImg.hasClass('pbtheme_zoom_interface_installed')) {
                productImg.wrap('<div class="pbtheme_zoom_interface" />');
                productImg.parent().css({'position': 'relative', 'overflow': 'hidden', width: '100%', height: '100%', 'margin-bottom': '20px'});
                productImg.css({'max-width': 'none', 'max-height': 'none', 'display': 'block', 'margin-bottom': 0}).addClass('pbtheme_zoom_interface_installed');
                var liParent = productImg.parent().parent().parent('li');
                if (liParent.hasClass('product')) {
                    productImg.parent().append('<div class="product_hover"><span class="product_hover_text">' + pbtheme.ts_viewmore + '</span><span class="product_hover_icon">&gt;</span></div>')

                }
            }
        });


    });

    $(document).on('click', 'a.language_selected', function (e) {
        e.preventDefault();
        if (!$(this).closest('.element-language-bar').find('ul').hasClass('opened')) {
            var ulHeight = $(this).closest('.element-language-bar').find('ul li').height() * $(this).closest('.element-language-bar').find('ul li').length;
            $(this).closest('.element-language-bar').find('ul').stop(true).slideDown(150, function () {
                $(this).addClass('opened');
            });
        }
        else {
            $(this).closest('.element-language-bar').find('ul').stop(true).slideUp(150).removeClass('opened');
        }
    });

    $(document).on('click', '.element-language-bar ul li a', function () {
        var currLang = $('a.language_selected').html();
        var selectedLang = $(this).html();
        if (currLang != selectedLang) {
            $(this).closest('.element-language-bar').find('a.language_selected span').html(selectedLang);
        }
    });

    function pbtheme_navigation() {
        function over(elem) {
            if ($(elem).parents().eq(1).is('.is_fullwidth, .navmenu_fullwidth')) {
                return false;
            }
            var parent = $(elem).closest('li');
            var liHeight = $(elem).outerHeight();
            out(parent);
            parent.addClass('hovered');
            parent.children('ul:hidden').css({top: liHeight}).slideDown(100);
        }
        ;

        function out(elem) {
            var parents = elem ? $(elem).closest('li').siblings() : nav.children();
            if (nav.is('.menu_wrapper ul.pbtheme_menu')) {
                parents = parents.not('.active');
            }
            parents.removeClass('hovered');
            parents.find('li.menu-item.hovered').removeClass('hovered');
            parents.find('ul.sub-menu ul.sub-menu').fadeOut(100);
            parents.children('ul.sub-menu').delay(100).fadeOut(100);
        }
        ;

        var currentWidth = $(window).width();
        if (currentWidth < pbtheme.go_responsive) {
            $('#div_header_menu').removeClass('menu_wrapper').css('display', 'none');
            $('body').addClass('div_responsive');
            $('.header_wrapper').removeClass('sticky-header').css('top', 0);
        }
        else {
            if ($('#div_header_menu').hasClass('menu_wrapper') === false) {
                $('#pbtheme_wrapper').removeAttr('style');
                $('#div_header_menu').addClass('menu_wrapper').css('display', 'block');
                $('body').removeClass('div_responsive');

                if (navActive == false) {
                    var nav = $('.menu_wrapper ul.pbtheme_menu');
                    nav.mouseover(function (e) {
                        if ($('body').hasClass('div_responsive')) {
                            return;
                        }
                        nav.doTimeout('menu-item', 100, over, e.target);
                    }).mouseout(function () {
                        if ($('body').hasClass('div_responsive')) {
                            return;
                        }
                        nav.doTimeout('menu-item', 300, out);
                    });
                }
            }
        }
    }

})(jQuery);



//		portfolio columns entry anim


var portColInitDReady = true;
function portfolioColumnsInit(newItem) {
    var colNumb = parseInt(newItem.attr('data-columns'));
    newItem.find('.div_ajax_col').css({'display': 'none', 'opacity': 0});

    newItem.css({'display': 'none'});
    for (i = 1; i <= Math.floor(newItem.find('.div_ajax_col').length / colNumb); i++) {
        if (newItem.find('.div_ajax_col').eq(i * colNumb - 1).next().attr('style') !== 'clear:both') {
            //jQuery('<div style="clear:both"></div>').insertAfter(newItem.find('.div_ajax_col').eq(i*colNumb-1));
        }
    }
    if (newItem.find('.div_top_nav_cat').hasClass('pbtheme_anim_fade')) {
        newItem.show().find('.div_ajax_col').each(function (ind) {
            jQuery(this).show().delay(ind * 150).animate({opacity: 1}, 300);
        });
    } else if (newItem.find('.div_top_nav_cat').hasClass('pbtheme_anim_snake')) {
        var mainCount = 0, dirIden = 1;
        for (var i = 0; i < newItem.find('.div_ajax_col').length; i++) {
            for (var j = 0; j < colNumb; j++) {
                var delaySet = (dirIden > 0) ? mainCount : (mainCount + colNumb - 1 - 2 * j);
                newItem.show().find('.div_ajax_col').eq(mainCount).show().delay(delaySet * 200).animate({opacity: 1}, 300);
                if (j == colNumb - 1) {
                    dirIden = dirIden * (-1);
                }
                mainCount++;
            }
        }
    }
}


// Ajax Load
var ajaxLoading = false;
function pbtheme_ajaxload(currentItem) {
    if (ajaxLoading) {
        return;
    }
    ajaxLoading = true;
    "use strict";
    var nav = currentItem.parents().eq(4);
    var oldItem = nav.next();

    oldItem.parent().parent().css({'overflow': 'hidden'});
    var string = oldItem.attr('data-string');
    var shortData = oldItem.attr('data-shortcode').split('|');
    var stringClass = oldItem.attr('class');
    var direction = currentItem.attr('class');

    if (stringClass.indexOf('blog_content') >= 0) {
        var stringClassType = stringClass.replace('blog_content ', '');
        var actionSend = 'pbtheme_ajaxload_send';
    }

    var ajaxPage = currentItem.children('.pbtheme_page').text();

    var data = {
        action: actionSend,
        page: ajaxPage,
        type: stringClassType,
        data: string,
        ajax: 'yes',
        excerpt: shortData[0],
        bot_margin: shortData[1],
        title: shortData[2]
    };

    jQuery.post(pbtheme.ajaxurl, data, function (response) {
        if (response) {
            var content = response.split('@@@!SPLIT!@@@');
            var margin = oldItem.find('.pbuilder_column').css('border-left-width');
            margin = margin.replace('px', '');
            var width = oldItem.width() + 2 * parseInt(margin, 10);
            nav.remove();
            oldItem.before(content[0]);
            oldItem.after(content[1]);
            var newItem = oldItem.next();
            if (direction.indexOf('next') >= 0) {
                newItem.css({'position': 'absolute', 'width': 'inherit', 'left': width.toString() + 'px', 'top': '51px'});
                newItem.show();
                TweenLite.to(oldItem, 0.5, {left: '-=' + width.toString(), onComplete: function () {
                        oldItem.remove();
                    }});

                TweenLite.to(newItem, 0.5, {left: '-=' + width.toString(), onComplete: function () {
                        newItem.css({'position': 'relative', 'left': '0', 'top': '0'});
                        ajaxLoading = false;
                    }});
                jQuery('body').animate({
                    scrollTop: newItem.offset().top - 208
                });
            }
            else {
                newItem.css({'position': 'absolute', 'width': 'inherit', 'left': '-' + width.toString() + 'px', 'top': '51px'});
                newItem.show();
                TweenLite.to(oldItem, 0.5, {left: width.toString(), onComplete: function () {
                        oldItem.remove();
                    }});
                TweenLite.to(newItem, 0.5, {left: 0, onComplete: function () {
                        newItem.css({'position': 'relative', 'left': '0', 'top': '0'});
                        ajaxLoading = false;
                    }});

                jQuery('body').animate({
                    scrollTop: newItem.offset().top - 208
                });
            }

            //		swipebox
            swipeboxInstance = newItem.find('a[rel="lightbox"]').swipebox();

            newItemMod = newItem.find('.pbuilder_module');
            newItemMod.trigger('refresh');
            newItemMod.find('.frb-swiper-container').height(newItemMod.width() * 400 / 640);

        } else {
            alert('fail');
        }
    });
}

// Ajax Load
var ajaxLoading = false;
function pbtheme_ajaxload_send_woo(currentItem) {

    if (ajaxLoading) {
        return;
    }
    ajaxLoading = true;
    "use strict";
    var nav = currentItem.parents().eq(4);
    var oldItem = nav.next();

    oldItem.parent().parent().parent().css({'overflow': 'hidden'});
    var string = oldItem.parent().attr('data-string');

    var shortData = oldItem.parent().attr('data-shortcode').split('|');

    var direction = currentItem.attr('class');

    var ajaxPage = currentItem.children('.pbtheme_page').text();

    var data = {
        action: 'pbtheme_ajaxload_send_woo',
        page: ajaxPage,
        data: string,
        ajax: 'yes',
        bot_margin: shortData[0],
        title: shortData[1],
        columns: shortData[2]
    };

    jQuery.post(pbtheme.ajaxurl, data, function (response) {
        if (response) {
            var content = response.split('@@@!SPLIT!@@@');
            var margin = oldItem.find('.pbuilder_column').css('border-left-width');
            margin = margin.replace('px', '');
            var width = oldItem.width() + 2 * parseInt(margin, 10);
            nav.remove();
            oldItem.before(content[0]);
            oldItem.after(content[1]);
            var newItem = oldItem.next();
            if (direction.indexOf('next') >= 0) {
                newItem.css({'position': 'absolute', 'width': 'inherit', 'left': width.toString() + 'px', 'top': '51px'});
                newItem.show();
                TweenLite.to(oldItem, 0.5, {left: '-=' + width.toString(), onComplete: function () {
                        oldItem.remove();
                    }});
                TweenLite.to(newItem, 0.5, {left: '-=' + width.toString(), onComplete: function () {
                        newItem.css({'position': 'relative', 'left': '0', 'top': '0'});
                        ajaxLoading = false;
                    }});
                jQuery('body').animate({
                    scrollTop: newItem.offset().top - 208
                });
            }
            else {
                newItem.css({'position': 'absolute', 'width': 'inherit', 'left': '-' + width.toString() + 'px', 'top': '51px'});
                newItem.show();
                TweenLite.to(oldItem, 0.5, {left: width.toString(), onComplete: function () {
                        oldItem.remove();
                    }});

                TweenLite.to(newItem, 0.5, {left: 0, onComplete: function () {
                        newItem.css({'position': 'relative', 'left': '0', 'top': '0'});
                        ajaxLoading = false;
                    }});
                jQuery('body').animate({
                    scrollTop: newItem.offset().top - 208
                });
            }
        } else {
            alert('fail');
        }
    });
}

// Ajax Load
var ajaxLoading = false;
function pbtheme_ajaxload_send_woo_cat(currentItem) {

    if (ajaxLoading) {
        return;
    }
    ajaxLoading = true;
    "use strict";
    var nav = currentItem.parents().eq(4);
    var oldItem = nav.next();

    oldItem.parent().parent().parent().css({'overflow': 'hidden'});

    var shortData = oldItem.parent().attr('data-shortcode').split('|');

    var direction = currentItem.attr('class');

    var ajaxPage = currentItem.children('.pbtheme_page').text();

    var data = {
        action: 'pbtheme_ajaxload_send_woo_cat',
        page: ajaxPage,
        ajax: 'yes',
        bot_margin: shortData[0],
        title: shortData[1],
        columns: shortData[2],
        per_page: shortData[3],
        order: shortData[4],
        orderby: shortData[5],
        ids: shortData[6]
    };

    jQuery.post(pbtheme.ajaxurl, data, function (response) {
        if (response) {
            var content = response.split('@@@!SPLIT!@@@');
            var margin = oldItem.find('.pbuilder_column').css('border-left-width');
            margin = margin.replace('px', '');
            var width = oldItem.width() + 2 * parseInt(margin, 10);
            nav.remove();
            oldItem.before(content[0]);
            oldItem.after(content[1]);
            var newItem = oldItem.next();
            if (direction.indexOf('next') >= 0) {
                newItem.css({'position': 'absolute', 'width': 'inherit', 'left': width.toString() + 'px', 'top': '51px'});
                newItem.show();
                TweenLite.to(oldItem, 0.5, {left: '-=' + width.toString(), onComplete: function () {
                        oldItem.remove();
                    }});
                TweenLite.to(newItem, 0.5, {left: '-=' + width.toString(), onComplete: function () {
                        newItem.css({'position': 'relative', 'left': '0', 'top': '0'});
                        ajaxLoading = false;
                    }});
                jQuery('body').animate({
                    scrollTop: newItem.offset().top - 208
                });
            }
            else {
                newItem.css({'position': 'absolute', 'width': 'inherit', 'left': '-' + width.toString() + 'px', 'top': '51px'});
                newItem.show();
                TweenLite.to(oldItem, 0.5, {left: width.toString(), onComplete: function () {
                        oldItem.remove();
                    }});

                TweenLite.to(newItem, 0.5, {left: 0, onComplete: function () {
                        newItem.css({'position': 'relative', 'left': '0', 'top': '0'});
                        ajaxLoading = false;
                    }});
                jQuery('body').animate({
                    scrollTop: newItem.offset().top - 208
                });
            }
        } else {
            alert('fail');
        }
    });
}

// Ajax Load
var ajaxLoading = false;
function pbtheme_ajaxload_portfolio(currentItem) {

    if (ajaxLoading) {
        return;
    }
    ajaxLoading = true;
    "use strict";
    var data_cat = currentItem.attr('data-cat');

    if (data_cat === undefined) {
        var oldItem = currentItem.parents().eq(4).prev().parent();
    }
    else {
        var oldItem = currentItem.parents().eq(3);
    }

//
//		oldItem.css('opacity', '0.33');
    var string = oldItem.attr('data-string');

    var shortData = oldItem.attr('data-shortcode').split('|');

    if (data_cat !== undefined) {
        string = string.substring(0, string.indexOf("&cat="));
        if (data_cat == '-1') {
            string = string + '&cat=' + shortData[1];
        }
        else {
            string = string + '&cat=' + data_cat;
        }
    }
    else {
        data_cat = shortData[1];
    }

    var stringClass = oldItem.attr('class');
    if (stringClass.indexOf('portfolio_content') >= 0) {
        var stringClassType = stringClass.replace('portfolio_content ', '');
        var actionSend = 'pbtheme_ajaxload_send_portfolio';
    }
    else {
        var stringClassType = stringClass.replace('div_portfolio_slider ', '');
        var actionSend = 'pbtheme_ajaxload_send_portfolio_alt';
    }

    var ajaxPage = currentItem.children('.pbtheme_page').text();

    var data = {
        action: actionSend,
        page: ajaxPage,
        type: stringClassType,
        data: string,
        ajax: 'yes',
        margin: shortData[0],
        category: shortData[1],
        top_pagination: shortData[2],
        top_align: shortData[3],
        trans_effect: shortData[4],
        data_cat: data_cat,
        pagination: shortData[5]
    };

    jQuery.post(pbtheme.ajaxurl, data, function (response) {
        if (response) {
            oldItem.after(response);
            var newItem = oldItem.next();
            var colNumb = parseInt(newItem.attr('data-columns'));
            newItem.find('.div_ajax_col').css({'display': 'none', 'opacity': 0});
            newItem.css({'display': 'none'});
            oldItem.children(':not(.div_top_nav_wrap)').fadeOut(function () {
                portfolioColumnsInit(newItem);
                jQuery('body').animate({
                    scrollTop: newItem.offset().top - 208
                }, 500);
                oldItem.remove();

            });
            ajaxLoading = false;
            //		swipebox
            swipeboxInstance = newItem.find('a[rel="lightbox"]').swipebox();
            newItem.find('.pbuilder_module').trigger('refresh');
        } else {
            alert('fail');
        }
    });

}