(function($){
    $(document).ready(function(){$('.custom-zoom, .custom-zoom-gallery').CustomZoom()});
	function format(str){
		for(var i=1;i<arguments.length;i++){str=str.replace('%'+(i-1),arguments[i])}
		return str}
	function CustomZoom(joomwindow,opts)
	{var smallImg=$('img',joomwindow);
	var img_first;
	var img_sec;
	var customZoomBlock=null;
	var $mouseTrap=null;
	var lens=null;
	var $shade=null;
	var softFocus=null;
	var $ie6Fix=null;		   
	var zoomImage;
	var controlTimer=0;
	var cw,ch;
	var destU=0;
	var destV=0;
	var currV=0;
	var currU=0;
	var filesLoaded=0;
	var mx,my;
	var ctx=this,zw;
	setTimeout(function()
	{if($mouseTrap===null)
	{
		var w=joomwindow.width();
	joomwindow.parent().append(format('<div style="width:%0px;position:absolute;top:75%;left:%1px;text-align:center" class="custom-zoom-loading" >Loading...</div>',w/3,(w/2)-(w/6))).find(':last').css('opacity',0.5)}},200);
	var ie6FixRemove=function()
	{
		if($ie6Fix!==null)
		{
			$ie6Fix.remove();
			$ie6Fix=null}
		};
	this.removeBits=function()
	{
		if(lens)
		{
			lens.remove();
			lens=null
		}
		if($shade)
		{
			$shade.remove();
			$shade=null}
		if(softFocus)
		{
			softFocus.remove();
			softFocus=null
		}
		ie6FixRemove();
		$('.custom-zoom-loading',joomwindow.parent()).remove()};
		this.destroy=function()
		{
			joomwindow.data('zoom',null);
		if($mouseTrap)
		{
			$mouseTrap.unbind();
			$mouseTrap.remove();
			$mouseTrap=null}
		if(customZoomBlock)
		{
			customZoomBlock.remove();
			customZoomBlock=null
		}
		this.removeBits()};
		this.fadedOut=function()
		{
			if(customZoomBlock)
			{
				customZoomBlock.remove();
				customZoomBlock=null}this.removeBits()};
				this.controlLoop=function()
			{
				if(lens)
				{
					var x=(mx-smallImg.offset().left-(cw*0.5))>>0;
					var y=(my-smallImg.offset().top-(ch*0.5))>>0;
				
					if(x<0)
					{
						x=0
					}
					else if(x>(smallImg.outerWidth()-cw))
					{
						x=(smallImg.outerWidth()-cw)
					}
					if(y<0)
					{
						y=0
					}
					else if(y>(smallImg.outerHeight()-ch))
					{
						y=(smallImg.outerHeight()-ch)
					}lens.css({left:x,top:y});
					lens.css('background-position',(-x)+'px '+(-y)+'px');
					destU=(((x)/smallImg.outerWidth())*zoomImage.width)>>0;destV=(((y)/smallImg.outerHeight())*zoomImage.height)>>0;currU+=(destU-currU)/opts.smoothMove;currV+=(destV-currV)/opts.smoothMove;customZoomBlock.css('background-position',(-(currU>>0)+'px ')+(-(currV>>0)+'px'))}
					controlTimer=setTimeout(function()
					{
						ctx.controlLoop()},30)
					};
					this.init2=function(img,id)
					{
					filesLoaded++;
					if(id===1)
						{
							zoomImage=img
						}
					if(filesLoaded===2)
					{
						this.init()}
					};
					this.init=function(){$('.custom-zoom-loading',joomwindow.parent()).remove();
					$('.mousetrap').remove();
					$mouseTrap=joomwindow.parent().append(format("<div class='mousetrap' style='background-image:url(\".\");z-index:999;position:absolute;width:%0px;height:%1px;left:%2px;top:%3px;\'></div>",
					smallImg.outerWidth(),smallImg.outerHeight(),0,0)).find(':last');
					$mouseTrap.bind('mousemove',this,function(event)
					{
						mx=event.pageX;my=event.pageY});
						$mouseTrap.bind('mouseleave',this,function(event)
					{
						clearTimeout(controlTimer);
					if(lens){lens.fadeOut(250)}
					if($shade){$shade.fadeOut(250)}
					if(softFocus){softFocus.fadeOut(250)}customZoomBlock.fadeOut(251,function()
					{
						ctx.fadedOut()
					});
					return false
					});
					$mouseTrap.bind('mouseenter',this,function(event){mx=event.pageX;my=event.pageY;zw=event.data;
					if(customZoomBlock){customZoomBlock.stop(true,false);customZoomBlock.remove()}
					var xPos=opts.adjustX,yPos=opts.adjustY;
					var siw=smallImg.outerWidth();
					var sih=smallImg.outerHeight();
					var w=opts.zoomWidth;
					var h=opts.zoomHeight;
					if(opts.zoomWidth=='auto')
					{
						w=siw
					}
					if(opts.zoomHeight=='auto')
					{
						h=sih
					}
					if(h>zoomImage.height)
						h=zoomImage.height;
					if(w>zoomImage.width)
						w=zoomImage.width;
					var appendTo=joomwindow.parent();
					switch(opts.position)
					{
						case'top':yPos-=h;break;
						case'right':xPos+=siw;break;
						case'bottom':yPos+=sih;break;
						case'left':xPos-=w;break;
						case'inside':w=siw;h=sih;break;
						default:appendTo=$('#'+opts.position);
					if(!appendTo.length)
					{
						appendTo=joomwindow;xPos+=siw;yPos+=sih
					}
					else
					{
						w=appendTo.innerWidth();
						h=appendTo.innerHeight()
						}
						}
						customZoomBlock=appendTo.append(format('<div id=" " class="custom-zoom-big" style="display:none;position:absolute;left:%0px;top:%1px;width:%2px;height:%3px;background-image:url(\'%4\');z-index:99999 !important;"></div>',
						xPos,
						yPos,
						w,
						h,
						zoomImage.src)).find(':last');
					if(smallImg.attr('title')&&opts.showTitle)
					{
						customZoomBlock.append(format('<div class="custom-zoom-title">%0</div>',
						smallImg.attr('title'))).find(':last').css('opacity',opts.titleOpacity)
					}
					if($.browser.msie&&$.browser.version<7)
						{
							$ie6Fix=$('<iframe frameborder="0" src="#"></iframe>').css({position:"absolute",
							left:xPos,
							top:yPos,
							zIndex:99,
							width:w,
							height:h}).insertBefore(customZoomBlock)}customZoomBlock.fadeIn(495);
					if(lens)
					{
						lens.remove();
						lens=null}
						cw=(smallImg.outerWidth()/zoomImage.width)*w;ch=(smallImg.outerHeight()/zoomImage.height)*h;
						lens=joomwindow.append(format("<div class = 'custom-zoom-lens' style='display:none;z-index:99999;position:absolute;width:%0px;height:%1px;'></div>",cw,ch)).find(':last');
						$mouseTrap.css('cursor',lens.css('cursor'));
						var noTrans=false;
					if(opts.shade)
					{
						lens.css('background','url("'+smallImg.attr('src')+'")');
						$shade=joomwindow.append(format('<div style="display:none;position:absolute; left:0px; top:0px; width:%0px; height:%1px; background-color:%2;" />',
						smallImg.outerWidth(),
						smallImg.outerHeight(),
						opts.shade)).find(':last');
						$shade.css('opacity',opts.shadeOpacity);noTrans=true;
						$shade.fadeIn(500)
					}
					if(opts.softFocus)
					{
						lens.css('background','url("'+smallImg.attr('src')+'")');
						softFocus=joomwindow.append(format('<div style="position:absolute;display:none;top:2px; left:2px; width:%0px; height:%1px;" />',
						smallImg.outerWidth()-2,
						smallImg.outerHeight()-2,
						opts.shade)).find(':last');
						softFocus.css('background','url("'+smallImg.attr('src')+'")');
						softFocus.css('opacity',0.4);
						noTrans=true;softFocus.fadeIn(495)
					}
					if(!noTrans)
					{
						lens.css('opacity',opts.lensOpacity)
					}
					if(opts.position!=='inside')
					{
						lens.fadeIn(495)}zw.controlLoop();
						return})
					};
					img_first=new Image();$(img_first).load(function(){ctx.init2(this,0)});
					img_first.src=smallImg.attr('src');img_sec=new Image();
					$(img_sec).load(function()
					{
						ctx.init2(this,1)});
						img_sec.src=joomwindow.attr('href')}
						$.fn.CustomZoom=function(options)
					{
						try{document.execCommand("BackgroundImageCache",false,true)}
					catch(e){}
						this.each(function()
					{
						var relOpts,opts;
					eval('var	a = {'+$(this).attr('rel')+'}');relOpts=a;
					if($(this).is('.custom-zoom')){$(this).css({'position':'relative','display':'block'});
						$('img',
						$(this)).css({'display':'block'});
					if($(this).parent().attr('id')!='wrap')
						{
							$(this).wrap('<div id="wrap" style="top:0px;z-index:1;position:relative;"></div>')}opts=$.extend({},$.fn.CustomZoom.defaults,options);
							opts=$.extend({},
							opts,relOpts);
							$(this).data('zoom',new CustomZoom($(this),opts))
						}
					else if($(this).is('.custom-zoom-gallery'))
					{
						opts=$.extend({},relOpts,options);$(this).data('relOpts',opts);$(this).bind('click',$(this),
					function(event)
					{var data=event.data.data('relOpts');
					$('#'+data.useZoom).data('zoom').destroy();
					$('#'+data.useZoom).attr('href',event.data.attr('href'));$('#'+data.useZoom+' img').attr('src',event.data.data('relOpts').smallImage);
					$('#'+event.data.data('relOpts').useZoom).CustomZoom();
					return false})
					}
					}
					);
					return this};
					$.fn.CustomZoom.defaults={zoomWidth:'auto',zoomHeight:'auto',position:'right',shade:false,shadeOpacity:0.4,lensOpacity:0.4,softFocus:false,smoothMove:3,showTitle:true,titleOpacity:0.4,adjustX:0,adjustY:0}})
					(jQuery);