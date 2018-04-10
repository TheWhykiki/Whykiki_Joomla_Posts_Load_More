<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


?>
<style>


</style>

<?php 

// Article Spotlight

if($spotlight == 1){
    require JModuleHelper::getLayoutPath('mod_articles_news_load_more', '_teaser');
}
?>


<div id="results">
</div>

<?php // Article Spotlight Ende ?>

<script type="text/javascript">
    
jQuery(document).ready(function(){
    <?php if($loadingType == 0): ?>
        var scrollerTrigger = jQuery( ".scrollerTrigger" );
        var position = scrollerTrigger.offset();
        var offsetTop = position.top;
    <?php endif; ?>
    <?php if($animationFlag == 1): ?>
    new WOW().init();
    <?php endif; ?>

    (function(jQuery){
        jQuery.fn.loaddata = function(options) {// Settings
            var settings = jQuery.extend({
                loading_gif_url	: '/modules/mod_articles_news_load_more/images/ajax-loader.gif', //url to loading gif
                //end_record_text	: 'No more records found!', //no more records to load
                data_url 		: '/modules/mod_articles_news_load_more/ajax.php', //url to PHP page
                ordering: '<?php echo $ordering; ?>',
                direction: '<?php echo $orderingDirection; ?>',
                spotlight: '<?php echo $spotlight; ?>',
                category: '<?php echo $categoryID; ?>',
                articleCount: '<?php echo $count; ?>',
                baseLink: '<?php echo $baseLink; ?>',
                titleFlag: '<?php echo $titleFlag; ?>',
                imageFlag: '<?php echo $imageFlag; ?>',
                linkTitles: '<?php echo $link_titles; ?>',
                textLength: '<?php echo $textLength; ?>',
                animationFlag: '<?php echo $animationFlag; ?>',
                animationPosts: '<?php echo $animationPosts; ?>',
                columnsMobile: '<?php echo $columnsMobile; ?>',
                columnsDesktop: '<?php echo $columnsDesktop; ?>',
                animationSpeedPost: '<?php echo $animationSpeedPost; ?>',
                animationDelayPost: '<?php echo $animationDelayPost; ?>',
                readMoreStylePost: '<?php echo $readMoreStylePost; ?>',
                readMoreIconSize: '<?php echo $readMoreIconSize; ?>',
                readMoreText: '<?php echo $readMoreText; ?>',
                start_page 		: 1 //initial page
            }, options);

            var el = this;	
            loading  = false; 
            end_record = false;
            contents(el, settings); //initial data load

            <?php if($loadingType == 1): ?>
            jQuery( "#loadMoreButton" ).click(function() {
                contents(el, settings); //load content chunk
            });
            <?php else: ?>
            function getOffset(el) {
              el = el.getBoundingClientRect();
              return {
                top: el.top + window.scrollY
              }
            }

            jQuery(window).scroll(function() { //detact scroll

                /*
                console.log('scrolltop' + jQuery(window).scrollTop());
                console.log('winheight' + jQuery(window).height());
                console.log('aDDED' + (jQuery(window).scrollTop() + jQuery(window).height()));
                console.log('docHeight' + jQuery(document).height()); */

                if(parseInt(jQuery(window).scrollTop()+100) >= offsetTop){ //scrolled to bottom of the page
                    contents(el, settings); //load content chunk
                }
            });
            <?php endif; ?>

        }; 
        //Ajax load function
        function contents(el, settings){
            var load_img = jQuery('<img/>').attr('src',settings.loading_gif_url).addClass('loading-image'); //create load image


            if(loading == false && end_record == false){
                loading = true; //set loading flag on
                el.append(load_img); //append loading image
                jQuery.post( settings.data_url, 
                    {
                        'page': settings.start_page,
                        'category': settings.category,
                        'count':settings.articleCount,
                        'ordering': settings.ordering,
                        'direction': settings.direction,
                        'spotlight': settings.spotlight,
                        'baseLink': settings.baseLink,
                        'titleFlag': settings.titleFlag,
                        'linkTitles': settings.linkTitles,
                        'imageFlag': settings.imageFlag,
                        'textLength': settings.textLength,
                        'animationFlag': settings.animationFlag,
                        'animationPosts': settings.animationPosts,
                        'animationDelayPost': settings.animationDelayPost,
                        'animationSpeedPost': settings.animationSpeedPost,
                        'columnsDesktop': settings.columnsDesktop,
                        'readMoreStylePost': settings.readMoreStylePost,
                        'readMoreText': settings.readMoreText,
                        'readMoreIconSize': settings.readMoreIconSize

                    }, 
                            
                function(data){ //jQuery Ajax post
                    if(data.trim().length == 0){ //no more records

                        jQuery('.loadMoreButtonRow').css('display', 'none');
                        jQuery('.endRecordRow').css('display', 'block');
                        load_img.remove(); //remove loading img

                        end_record = true; //set end record flag on
                        if(end_record = true){
                            //alert('jopp');
                        }
                        return; //exit
                    }
                    loading = false;  //set loading flag off
                    load_img.remove(); //remove loading img
                    el.append(data);  //append content 
                    settings.start_page ++; //page increment
                })
            }
        }

    })(jQuery);

jQuery("#results").loaddata();
    
    
});
</script>
<?php if($loadingType == 1): ?>
<div class="row row-fluid loadMoreButtonRow">
	 <div class="col-md-12 span12">
		<button id="loadMoreButton"><span><?php echo JText::_("MOD_ARTICLES_NEWS_LOAD_MORE_LOAD_RECORDS"); ?></span></button>
	</div>
</div>
<?php else: ?>
<div class="scrollerTrigger" style="visibility: hidden;">Trigger</div>
<?php endif; ?>

<div class="row row-fluid endRecordRow" style="display: none;">
    <div class="col-md-12">
        <?php echo JText::_("MOD_ARTICLES_NEWS_LOAD_MORE_NO_MORE_RECORDS"); ?>
    </div>
</div>
