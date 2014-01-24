 jQuery(document).ready(function() {
	jQuery('.products-grid li.item .product-image span.image-box, .products-list li.item .product-image span.image-box').css('opacity', '0');
	jQuery('.products-grid li.item .fancybox, .products-list li.item .fancybox').css({'left': '87%', 'opacity': 0});
	jQuery('.products-grid.small-grid li.item .product-info-box').css({'right': '5px', 'opacity': 0});
		
	var small_grid = false;
	if(jQuery('.products-grid.small-grid').length){
		small_grid = true;
	}
		
	jQuery('.products-grid li.item, .products-list li.item').hover(
		 function(){
			jQuery(this).find('span.image-box').stop(true, true).animate({
				 opacity: 0.8
			 }, 500);
			
			if(!small_grid){
				jQuery(this).find('.fancybox').stop(true, true).animate({
					 left: '87%',
					 opacity: 1
				 }, 500);
			 }else{
				jQuery(this).find('.fancybox').stop(true, true).animate({
					 left: '87%',
					 opacity: 1
				 }, 500);
			 }			 
			jQuery(this).find('.product-info-box').stop(true, true).animate({
				 right: '61px',
				 opacity: 1
			 }, 500);
		 },
		 function(){
			jQuery(this).find('span.image-box').stop(true, true).animate({
				 opacity: 0
			 }, 500);
			 jQuery(this).find('.fancybox').stop(true, true).animate({
				 left: '87%',
				 opacity: 0
			 }, 500);
			 jQuery(this).find('.product-info-box').stop(true, true).animate({
				 right: '5px',
				 opacity: 0
			 }, 500);
		 }
	);
	
	
	if(navigator.userAgent.indexOf('IE 8')!=-1){
         function replacingClass () {
		 
		   if (jQuery(document.body).width() < 480) { //Mobile   
			jQuery('.products-grid:not(".carousel-ul, #upsell-product-table, .widget-grid") > li').removeClass('alpha omega clear-2');
			jQuery('.products-grid#upsell-product-table > li, .widget-grid > li').removeClass('omega clear-2');
			jQuery('.products-grid:not(".carousel-ul") > li:odd').addClass('omega');
			jQuery('.products-grid:not(".carousel-ul") > li:even').addClass('alpha clear-2');
			jQuery('.products-grid.large-grid > li:odd').addClass('alpha');
			jQuery('.products-grid.large-grid > li:even').addClass('omega');
			
			jQuery('.block-related .block-content > div:not(#block-related-slider) li.item').removeClass('clear-2');
			jQuery('.block-related .block-content > div:not(#block-related-slider) li.item:nth-child(2n+2)').next().addClass('clear-2');
			jQuery('.more-views > ul > li').removeClass('clear-2');
			jQuery('.more-views > ul > li:nth-child(2n+2)').next().addClass('clear-2');
		   }

		   if (jQuery(document.body).width() > 479 && jQuery(document.body).width() < 768) { //iPhone
		   
			jQuery('.products-grid:not(".carousel-ul, #upsell-product-table") > li').removeClass('alpha omega clear-2')
			jQuery('.products-grid#upsell-product-table > li, .widget-grid > li').removeClass('omega clear-2')
			jQuery('.products-grid:not(".carousel-ul") > li:odd').addClass('omega')
			jQuery('.products-grid:not(".carousel-ul") > li:even').addClass('alpha clear-2')
			
			jQuery('.block-related .block-content > div:not(#block-related-slider) li.item').removeClass('clear-2');
			jQuery('.block-related .block-content > div:not(#block-related-slider) li.item:nth-child(3n+3)').next().addClass('clear-2');
			jQuery('.more-views > ul > li').removeClass('clear-2');
			jQuery('.more-views > ul > li:nth-child(4n+4)').next().addClass('clear-2');
			
		   }  
		   else if (jQuery(document.body).width() > 767) { //Desktop
			jQuery('.products-grid:not(".carousel-ul, #upsell-product-table") > li').removeClass('alpha omega clear-2')
			jQuery('.products-grid#upsell-product-table > li').removeClass('omega clear-2')
			jQuery('.products-grid#upsell-product-table > li:nth-child(5n+5)').addClass('omega').next().addClass('clear-2');
			
			jQuery('.products-grid:not(".carousel-ul")').each(function(){
			jQuery(this).find('li.item').first().addClass('alpha');
			})
			jQuery('.products-grid:not(".carousel-ul, .widget-grid, .small-grid, .large-grid, #upsell-product-table") > li:nth-child(3n+3)').addClass('omega').next().addClass('alpha clear-2')
			jQuery('.products-grid.widget-grid > li:nth-child(4n+4)').addClass('omega').next().addClass('alpha clear-2')
			jQuery('.products-grid.large-grid > li:odd').addClass('omega')
			jQuery('.products-grid.large-grid > li:even').addClass('alpha clear-2')
			jQuery('.products-grid.small-grid > li:nth-child(4n+4)').addClass('omega').next().addClass('alpha clear-2')
			
			jQuery('.block-related .block-content > div:not(#block-related-slider) li.item').removeClass('clear-2');
			jQuery('.block-related .block-content > div:not(#block-related-slider) li.item:nth-child(5n+5)').next().addClass('clear-2');
			jQuery('.more-views > ul > li').removeClass('clear-2');
			jQuery('.more-views > ul > li:nth-child(3n+3)').next().addClass('clear-2');
		   }
			if (jQuery(document.body).width() > 767 && jQuery(document.body).width() < 977){ //Tablet
				jQuery('.products-grid#upsell-product-table > li').removeClass('omega clear-2');
				jQuery('.products-grid#upsell-product-table:not(".carousel-ul") > li:nth-child(3n+3)').addClass('omega').next().addClass('clear-2');
				jQuery('.block-related .block-content > div:not(#block-related-slider) li.item').removeClass('clear-2');
				jQuery('.block-related .block-content > div:not(#block-related-slider) li.item:nth-child(4n+4)').next().addClass('clear-2');
				jQuery('.more-views > ul > li').removeClass('clear-2');
				jQuery('.more-views > ul > li:nth-child(2n+2)').next().addClass('clear-2');
			}  
			if (jQuery(document.body).width() > 1279){ //Extra Large
				jQuery('.block-related .block-content > div:not(#block-related-slider) li.item').removeClass('clear-2');
				jQuery('.block-related .block-content > div:not(#block-related-slider) li.item:nth-child(4n+4)').next().addClass('clear-2');
				jQuery('.more-views > ul > li').removeClass('clear-2');
				jQuery('.more-views > ul > li:nth-child(4n+4)').next().addClass('clear-2');
				
				jQuery('.products-grid.small-grid li.item').removeClass('alpha clear-2 omega');
				jQuery('.products-grid.small-grid li.item:first').addClass('alpha');
				jQuery('.products-grid.small-grid li.item:nth-child(5n+5)').addClass('omega').next().addClass('clear-2');
			}
		}
		replacingClass();
		jQuery(window).resize(function(){replacingClass();});
    }
});  