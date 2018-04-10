<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php
$images = json_decode($item->images);
$introImage = $images->image_intro;
$introText = $item->introtext;
$textLength = $params->get('text_length');
$date = $item->publish_up;
$date = date('d.m.Y',strtotime($date));

if($animationFlag == 1){
	$animationClass = "wow animate ".$animationTeaser;
}
else{
	$animationClass = "";
}
?>

<div <?php if($animationFlag == 1 && $animationSpeedTeaser != ""): ?>data-wow-duration="<?php echo $animationSpeedTeaser; ?>"<?php endif; ?> <?php if($animationFlag == 1 && $animationDelayTeaser != ""): ?>data-wow-delay="<?php echo $animationDelayTeaser; ?>"<?php endif; ?> class="<?php echo $animationClass; ?> row teaserRow">
	<?php if($imageFlag == 1): ?>
    <div class="col-md-5 teaserImage">
        <img class="blogImage blogImageLarger" src="/<?php echo $introImage; ?>" />
    </div>
    <div class="col-md-7 teaserText">
		<?php else: ?>
        <div class="col-md-12 teaserText">
			<?php endif; ?>
			<?php if ($params->get('item_title')) : ?>
				<?php if ($params->get('link_titles','1')) : ?>
                    <span class="teaserDate"><?php echo $date; ?></span>
                    <a href="<?php echo $link; ?>">
                        <h3 class="blogHeadlineTeaser"><?php echo $item->title; ?></h3>
                    </a>
				<?php else : ?>
                    <span class="teaserDate"><?php echo $date; ?></span>
                    <h3 class="blogHeadlineTeaser"><?php echo $item->title; ?></h3>
				<?php endif; ?>
			<?php endif; ?>
			<?php echo substr($introText,0, $textLengthTeaser); ?> ...
        </div>

		<?php if($link_titles == 1): ?>
            <a href="<?php echo $link; ?>">
                <img class="teaserWeiter" src="/modules/mod_articles_news_load_more/images/weiter.png" />
            </a>
		<?php endif; ?>

    </div>