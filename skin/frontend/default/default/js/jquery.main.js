jQuery.noConflict();

function globalEval(src) {
	var realGlobal = this;
	if (window.execScript) {
		window.execScript(src);
	} else {
		realGlobal.eval(src);
	}
}

function initCartDrop(){
	var $cartMenu = jQuery('#header ul.menu > li:has(div.drop-cart)');
	if ($cartMenu.length) {
		var $dropCart = jQuery('div.drop-cart', $cartMenu);
		var $dropInner = jQuery('div.drop-cart-holder', $dropCart);
		var activeClass = 'active-cart';
		var duration = 500;
		
		$cartMenu.find('> a.my-cart').bind('mouseenter', function(){
			if ($cartMenu.hasClass(activeClass)) {
				var h = $dropInner.innerHeight();
				$dropInner.stop().animate({marginTop:0}, duration);
			} else {
				$cartMenu.addClass(activeClass);
				$dropCart.css({display: 'block'});
				var h = $dropInner.innerHeight();
				$dropInner.css({marginTop: -h}).stop().animate({marginTop: 0}, duration);
			}
		});
		
		$cartMenu.bind('mouseleave', function(){
			var h = $dropInner.innerHeight();
			$dropInner.stop().animate({marginTop: -h}, duration, function(){
				$dropCart.css({display: 'none'});
				$cartMenu.removeClass(activeClass);
			});
		});
	}
}

;(function($){

$(function(){
	initTabs();
	//clearInputs();
	initCartDrop();
	initGallery();
	initFancyBox();
	initProductsOverlay();
});

function initFancyBox(){
	$('div.product-box ul.img-list a.fancybox, div.product-box div.img-box a.fancybox').fancybox();
	
	$('a.quickview, a.add-to-cart').not('.simple-product').bind('click', function(event) {
		event.preventDefault();
		$('div.popup').remove();
		$.fancybox.showActivity();
		
		var $link = $(this);
		$.getJSON($link.attr('href'), function(response) {
			$.fancybox.hideActivity();
			
			// Update Facebook meta-tags
			if (response.facebookMeta) {
				$('head meta[property^="fb:"], head meta[property^="og:"]').remove();
				$('head').append($(response.facebookMeta));
			}
			
			// Append HTML and evaluate scripts
			$('body').append(response.content);
			$.each(response.scripts, function(index, source) {
				if ($.trim(source)) {
					globalEval($.trim(source));
				}
			});
			
			// Adjust popup position & dimensions
			var $popup = $('div.popup');
			$popup.css({
				top:	$(document).scrollTop() + (($(window).height() - $popup.outerHeight()) * 0.5),
				left:	$(document).scrollLeft() + (($(window).width() - $popup.outerWidth()) * 0.5)
			});
			
			// Tabs
			var $tabs = $('.tabset-list a', $popup);
			var $tabContent = $('#tab-overview, #tab-description', $popup);
			$tabs.bind('click', function(event) {
				event.preventDefault();
				$tabs.removeClass('active');
				$tabContent.hide();
				var $activeTab = $(this);
				$activeTab.addClass('active');
				$('#' + $activeTab.attr('rel'), $popup).show();
			});
			
			// Images
			$('.product-box .img-list a').bind('click', function(event) {
				event.preventDefault();
				$('#quickview').attr('src', $(this).attr('href'));
			});
			
			$('div.gallery3', $popup).scrollGallery();
			
			// Close link
			$('a.close', $popup).bind('click', function(event) {
				event.preventDefault();
				$popup.remove();
			});
			
			// Submit form if add-to-cart link has been clicked
			if ($link.is('.add-to-cart')) {
				$('div.popup form#product_addtocart_form .btn-cart').trigger('click');
			}
		});
	});
	
	$('a.add-to-cart.simple-product').bind('click', function(event) {
		event.preventDefault();
		$('div.popup').remove();
		$.fancybox.showActivity();
		$.post($(this).attr('href'), function(response) {
			$.fancybox.hideActivity();
			$('div.popup').remove();
			$('#header ul.menu > li:last').replaceWith($(response));
			initCartDrop();
			$('#header ul.menu > li:last a.my-cart').trigger('mouseenter');
		});
	});
}

function initGallery(){
	$('div.gallery').scrollGallery();
	$('div.gallery3').scrollGallery();
}

function initProductsOverlay() {
	$('div.img-overlay-area div.price-box span, div.category-products ul.products-grid div.price-box span, '
		+ 'div.category-products ol.products-list div.price-box span').removeAttr('id');
}

// scrolling gallery plugin
jQuery.fn.scrollGallery = function(_options){
	var _options = jQuery.extend({
		sliderHolder: '>div',
		slider:'>ul',
		slides: '>li',
		pagerLinks:'div.pager a',
		btnPrev:'a.link-prev',
		btnNext:'a.link-next',
		activeClass:'active',
		disabledClass:'disabled',
		generatePagination:'div.pg-holder',
		curNum:'em.scur-num',
		allNum:'em.sall-num',
		circleSlide:true,
		pauseClass:'gallery-paused',
		pauseButton:'none',
		pauseOnHover:true,
		autoRotation:false,
		stopAfterClick:false,
		switchTime:5000,
		duration:350,
		easing:'swing',
		event:'click',
		afterInit:false,
		vertical:false,
		step:false
	},_options);

	return this.each(function(){
		// gallery options
		var _this = jQuery(this);
		var _sliderHolder = jQuery(_options.sliderHolder, _this);
		var _slider = jQuery(_options.slider, _sliderHolder);
		var _slides = jQuery(_options.slides, _slider);
		var _btnPrev = jQuery(_options.btnPrev, _this);
		var _btnNext = jQuery(_options.btnNext, _this);
		var _pagerLinks = jQuery(_options.pagerLinks, _this);
		var _generatePagination = jQuery(_options.generatePagination, _this);
		var _curNum = jQuery(_options.curNum, _this);
		var _allNum = jQuery(_options.allNum, _this);
		var _pauseButton = jQuery(_options.pauseButton, _this);
		var _pauseOnHover = _options.pauseOnHover;
		var _pauseClass = _options.pauseClass;
		var _autoRotation = _options.autoRotation;
		var _activeClass = _options.activeClass;
		var _disabledClass = _options.disabledClass;
		var _easing = _options.easing;
		var _duration = _options.duration;
		var _switchTime = _options.switchTime;
		var _controlEvent = _options.event;
		var _step = _options.step;
		var _vertical = _options.vertical;
		var _circleSlide = _options.circleSlide;
		var _stopAfterClick = _options.stopAfterClick;
		var _afterInit = _options.afterInit;

		// gallery init
		if(!_slides.length) return;
		var _currentStep = 0;
		var _sumWidth = 0;
		var _sumHeight = 0;
		var _hover = false;
		var _stepWidth;
		var _stepHeight;
		var _stepCount;
		var _offset;
		var _timer;
		var visibleWidth;
		
		_slides.each(function(){
			_sumWidth+=$(this).outerWidth(true);
			_sumHeight+=$(this).outerHeight(true);
		});
		
		// calculate gallery offset
		function recalcOffsets() {
			if(_vertical) {
				if(_step) {
					_stepHeight = _slides.eq(_currentStep).outerHeight(true);
					_stepCount = Math.ceil((_sumHeight-_sliderHolder.height())/_stepHeight)+1;
					_offset = -_stepHeight*_currentStep;
				} else {
					_stepHeight = _sliderHolder.height();
					_stepCount = Math.ceil(_sumHeight/_stepHeight);
					_offset = -_stepHeight*_currentStep;
					if(_offset < _stepHeight-_sumHeight) _offset = _stepHeight-_sumHeight;
				}
			} else {
				var visibleWidth = Math.round(_sliderHolder.width()/_slides.eq(0).outerWidth(true))*_slides.eq(0).outerWidth(true);
				if(_step) {
					_stepWidth = _slides.eq(_currentStep).outerWidth(true)*_step;
					_stepCount = Math.ceil((_sumWidth-visibleWidth)/_stepWidth)+1;
					_offset = -_stepWidth*_currentStep;
					if(_offset < _sliderHolder.width()-_sumWidth) _offset = _sliderHolder.width()-_sumWidth;
				} else {
					_stepWidth = visibleWidth;
					_stepCount = Math.ceil(_sumWidth/_stepWidth);
					_offset = -_stepWidth*_currentStep;
					if(_offset < _stepWidth-_sumWidth) _offset = _stepWidth-_sumWidth;
				}
			}
		}

		// gallery control
		if(_btnPrev.length) {
			_btnPrev.bind(_controlEvent,function(){
				if(_stopAfterClick) stopAutoSlide();
				prevSlide();
				return false;
			});
		}
		if(_btnNext.length) {
			_btnNext.bind(_controlEvent,function(){
				if(_stopAfterClick) stopAutoSlide();
				nextSlide();
				return false;
			});
		}
		if(_generatePagination.length) {
			_generatePagination.empty();
			recalcOffsets();
			var _list = $('<ul />');
			for(var i=0; i<_stepCount; i++) $('<li><a href="#">'+(i+1)+'</a></li>').appendTo(_list);
			_list.appendTo(_generatePagination);
			_pagerLinks = _list.children();
		}
		if(_pagerLinks.length) {
			_pagerLinks.each(function(_ind){
				jQuery(this).bind(_controlEvent,function(){
					if(_currentStep != _ind) {
						if(_stopAfterClick) stopAutoSlide();
						_currentStep = _ind;
						switchSlide();
					}
					return false;
				});
			});
		}

		// gallery animation
		function prevSlide() {
			recalcOffsets();
			if(_currentStep > 0) _currentStep--;
			else if(_circleSlide) _currentStep = _stepCount-1;
			switchSlide();
		}
		function nextSlide() {
			recalcOffsets();
			if(_currentStep < _stepCount-1) _currentStep++;
			else if(_circleSlide) _currentStep = 0;
			switchSlide();
		}
		function refreshStatus() {
			if(_pagerLinks.length) _pagerLinks.removeClass(_activeClass).eq(_currentStep).addClass(_activeClass);
			if(!_circleSlide) {
				_btnPrev.removeClass(_disabledClass);
				_btnNext.removeClass(_disabledClass);
				if(_currentStep == 0) _btnPrev.addClass(_disabledClass);
				if(_currentStep == _stepCount-1) _btnNext.addClass(_disabledClass);
			}
			if(_curNum.length) _curNum.text(_currentStep+1);
			if(_allNum.length) _allNum.text(_stepCount);
		}
		function switchSlide() {
			recalcOffsets();
			if(_vertical) _slider.animate({marginTop:_offset},{duration:_duration,queue:false,easing:_easing});
			else _slider.animate({marginLeft:_offset},{duration:_duration,queue:false,easing:_easing});
			refreshStatus();
			autoSlide();
		}

		// autoslide function
		function stopAutoSlide() {
			if(_timer) clearTimeout(_timer);
			_autoRotation = false;
		}
		function autoSlide() {
			if(!_autoRotation || _hover) return;
			if(_timer) clearTimeout(_timer);
			_timer = setTimeout(nextSlide,_switchTime+_duration);
		}
		if(_pauseOnHover) {
			_this.hover(function(){
				_hover = true;
				if(_timer) clearTimeout(_timer);
			},function(){
				_hover = false;
				autoSlide();
			});
		}
		recalcOffsets();
		refreshStatus();
		autoSlide();

		// pause buttton
		if(_pauseButton.length) {
			_pauseButton.click(function(){
				if(_this.hasClass(_pauseClass)) {
					_this.removeClass(_pauseClass);
					_autoRotation = true;
					autoSlide();
				} else {
					_this.addClass(_pauseClass);
					stopAutoSlide();
				}
				return false;
			});
		}

		if(_afterInit && typeof _afterInit === 'function') _afterInit(_this, _slides);
	});
};

function clearInputs()
{
	clearFormFields({
		clearInputs: true,
		clearTextareas: true,
		passwordFieldText: true,
		addClassFocus: "focus",
		filterClass: "default"
	});
}

function clearFormFields(o)
{
	if (o.clearInputs == null) o.clearInputs = true;
	if (o.clearTextareas == null) o.clearTextareas = true;
	if (o.passwordFieldText == null) o.passwordFieldText = false;
	if (o.addClassFocus == null) o.addClassFocus = false;
	if (!o.filter) o.filter = "default";
	if(o.clearInputs) {
		var inputs = document.getElementsByTagName("input");
		for (var i = 0; i < inputs.length; i++ ) {
			if((inputs[i].type == "text" || inputs[i].type == "password") && inputs[i].className.indexOf(o.filterClass)) {
				inputs[i].valueHtml = inputs[i].value;
				inputs[i].onfocus = function ()	{
					if(this.valueHtml == this.value) this.value = "";
					if(this.fake) {
						inputsSwap(this, this.previousSibling);
						this.previousSibling.focus();
					}
					if(o.addClassFocus && !this.fake) {
						this.className += " " + o.addClassFocus;
						this.parentNode.className += " parent-" + o.addClassFocus;
					}
				};
				inputs[i].onblur = function () {
					if(this.value == "") {
						this.value = this.valueHtml;
						if(o.passwordFieldText && this.type == "password") inputsSwap(this, this.nextSibling);
					}
					if(o.addClassFocus) {
						this.className = this.className.replace(o.addClassFocus, "");
						this.parentNode.className = this.parentNode.className.replace("parent-"+o.addClassFocus, "");
					}
				};
				if(o.passwordFieldText && inputs[i].type == "password") {
					var fakeInput = document.createElement("input");
					fakeInput.type = "text";
					fakeInput.value = inputs[i].value;
					fakeInput.className = inputs[i].className;
					fakeInput.fake = true;
					inputs[i].parentNode.insertBefore(fakeInput, inputs[i].nextSibling);
					inputsSwap(inputs[i], null);
				}
			}
		}
	}
	if(o.clearTextareas) {
		var textareas = document.getElementsByTagName("textarea");
		for(var i=0; i<textareas.length; i++) {
			if(textareas[i].className.indexOf(o.filterClass)) {
				textareas[i].valueHtml = textareas[i].value;
				textareas[i].onfocus = function() {
					if(this.value == this.valueHtml) this.value = "";
					if(o.addClassFocus) {
						this.className += " " + o.addClassFocus;
						this.parentNode.className += " parent-" + o.addClassFocus;
					}
				};
				textareas[i].onblur = function() {
					if(this.value == "") this.value = this.valueHtml;
					if(o.addClassFocus) {
						this.className = this.className.replace(o.addClassFocus, "");
						this.parentNode.className = this.parentNode.className.replace("parent-"+o.addClassFocus, "");
					}
				};
			}
		}
	}
	function inputsSwap(el, el2) {
		if(el) el.style.display = "none";
		if(el2) el2.style.display = "inline";
	}
}

function initTabs()
{
	var sets = document.getElementsByTagName("div");
	for (var i = 0; i < sets.length; i++)
	{
		if (sets[i].className.indexOf("tabset") != -1)
		{
			var tabs = [];
			var links = sets[i].getElementsByTagName("a");
			for (var j = 0; j < links.length; j++)
			{
				if (links[j].className.indexOf("tab") != -1)
				{
					tabs.push(links[j]);
					links[j].tabs = tabs;
					var c = document.getElementById(links[j].href.substr(links[j].href.indexOf("#") + 1));

					//reset all tabs on start
					if (c) if (links[j].className.indexOf("active") != -1) c.style.display = "block";
					else c.style.display = "none";

					links[j].onclick = function ()
					{
						var c = document.getElementById(this.href.substr(this.href.indexOf("#") + 1));
						if (c)
						{
							//reset all tabs before change
							for (var i = 0; i < this.tabs.length; i++)
							{
								var tab = document.getElementById(this.tabs[i].href.substr(this.tabs[i].href.indexOf("#") + 1));
								if (tab)
								{
									tab.style.display = "none";
								}
								this.tabs[i].className = this.tabs[i].className.replace("active", "");
							}
							this.className += " active";
							c.style.display = "block";
							return false;
						}
					};
				}
			}
		}
	}
}

})(jQuery);