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
<?php
// Article Spotlight

if($spotlight == 1){
    require JModuleHelper::getLayoutPath('mod_articles_news_load_more', '_teaser');
}
?>
<div id="results<?php echo $module->id; ?>">
</div>

<?php // Article Spotlight Ende ?>

<script type="text/javascript">
    
jQuery(document).ready(function(){

    <?php if($loadingType == 0): ?>
        var scrollerTrigger = jQuery( ".scrollerTrigger<?php echo $module->id; ?>" );
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
                data_url 		: '/modules/mod_articles_news_load_more/tmpl/ajax.php', //url to PHP page
                menuItem: '<?php echo $menuItem; ?>',
                moduleID: '<?php echo $moduleID; ?>',
                start_page 		: 1 //initial page
            }, options);

            var el = this;	
            loading  = false; 
            end_record = false;
            contents(el, settings); //initial data load

            <?php if($loadingType == 1): ?>
            jQuery( ".loadMoreButton<?php echo $module->id; ?>" ).click(function() {
                contents(el, settings); //load content chunk
            });
            <?php else: ?>


            jQuery(window).scroll(function() { //detact scroll
                var scrollerTrigger = jQuery( ".scrollerTrigger<?php echo $module->id; ?>" );
                var position = scrollerTrigger.offset();
                var offsetTop = position.top;
                var scrollerHeight = jQuery( '.blogInner' ).height();
                console.log(scrollerHeight);
                console.log('Trig ' + offsetTop);
                var offsetter = parseInt(jQuery(window).scrollTop()) + parseInt(scrollerHeight*3);

                console.log('Scroll ' + offsetter + '> Offset Top' + offsetTop );

                if( offsetter >= offsetTop){ //scrolled to bottom of the page
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
                        'menuItem': settings.menuItem,
                        'moduleID':settings.moduleID

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

jQuery("#results<?php echo $module->id; ?>").loaddata();
});
</script>
<?php if($loadingType == 1): ?>
<div class="row loadMoreButtonRow loadMoreButtonRow<?php echo $module->id; ?>">
	 <div class="col-md-12">
		<button id="loadMoreButton" class="loadMoreButton<?php echo $module->id; ?>"><span><?php echo JText::_("MOD_ARTICLES_NEWS_LOAD_MORE_LOAD_RECORDS"); ?></span></button>
	</div>
</div>
<?php else: ?>
<div class="scrollerTrigger<?php echo $module->id; ?> scrollerTrigger" style="visibility: hidden;">Trigger</div>
<?php endif; ?>

<div class="row endRecordRow<?php echo $module->id; ?> endRecordRow" style="display: none;">
    <div class="col-md-12">
        <?php echo JText::_("MOD_ARTICLES_NEWS_LOAD_MORE_NO_MORE_RECORDS"); ?>
    </div>
</div>
