<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the news functions only once
JLoader::register('ModArticlesNewsLoadMoreHelper', __DIR__ . '/helper.php');

$document = JFactory::getDocument();
$document->addStyleSheet('/modules/mod_articles_news_load_more/css/animate.css');
$document->addStyleSheet('https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');


// Load LESS Compiler and define variables
if ( !class_exists( 'lessc' ) ) {
	require "less.php";
}

// Define hover styles for load more button

if($params->get('button_hover_style') == 'spin'){
	$buttonHoverStyle = $params->get('button_hover_style')."(@color_button,".$params->get('button_hover_value').")";

}
else{
	$buttonHoverStyle = $params->get('button_hover_style')."(@color_button,".$params->get('button_hover_value')."%)";
}

if($params->get('image_filters') == "none"){
	$imageFilters = "none";
}
else{
	$imageFilters = $params->get('image_filters')."(".$params->get('image_filters_value').")";
}
$less = new lessc;
$less->setVariables(array(
	"box_height" => $params->get('box_height'),
	"border_radius" => $params->get('border_radius'),
	"teaser_background" => $params->get('teaser_background'),
	"post_background" => $params->get('post_background'),
	"post_font_color" => $params->get('post_font_color'),
	"post_font_size_text" => $params->get('post_font_size_text'),
	"post_font_size_headline" => $params->get('post_font_size_headline'),
	"post_font_color_headline" => $params->get('post_font_color_headline'),
	"teaser_font_color" => $params->get('teaser_font_color'),
	"teaser_font_size_text" => $params->get('teaser_font_size_text'),
	"teaser_font_size_headline" => $params->get('teaser_font_size_headline'),
	"teaser_font_color_headline" => $params->get('teaser_font_color_headline'),
	"border_color" => $params->get('border_color'),
	"border_width" => $params->get('border_width'),
	"border_style" => $params->get('border_style'),
	"color_button" => $params->get('color_button'),
	"font_color_button" => $params->get('font_color_button'),
	"button_font_size" => $params->get('button_font_size'),
	"border_color_button" => $params->get('border_color_button'),
	"border_width_button" => $params->get('border_width_button'),
	"border_radius_button" => $params->get('border_radius_button'),
	"button_hover_style" => $buttonHoverStyle,
	"hover_color" => $params->get('hover_color'),
	'image_filters' => $imageFilters
));

$less->compileFile("modules/mod_articles_news_load_more/less/loadMore.less", "modules/mod_articles_news_load_more/css/loadMore.css");
//$less->checkedCompile("modules/mod_articles_news_load_more/less/loadMore.less", "modules/mod_articles_news_load_more/css/loadMore.css");


$document->addStyleSheet('/modules/mod_articles_news_load_more/css/loadMore.css');
$document->addScript('/modules/mod_articles_news_load_more/js/wow.min.js');
//$document->addScript('https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js');

// Get params

$count    = $params->get('count');
$categoryID = $params->get('catid');
$ordering = $params->get('ordering');
$orderingDirection = $params->get('direction');
$spotlight = $params->get('article_spotlight');
$link_titles = $params->get('link_titles');
$imageFlag = $params->get('image');
$titleFlag = $params->get('item_title');
$textLength = $params->get('text_length');
$textLengthTeaser = $params->get('text_length_teaser');
$animationFlag = $params->get('animation');
$animationTeaser = $params->get('animation_teaser');
$animationPosts = $params->get('animation_posts');
$columnsMobile = $params->get('columns_mobile');
$columnsDesktop = $params->get('columns_desktop');
$loadingType = $params->get('loading_type');
$boxHeight = $params->get('box_height');
$animationSpeedTeaser = $params->get('animation_speed');
$animationDelayTeaser = $params->get('animation_delay_teaser');
$animationSpeedPost = $params->get('animation_speed_posts');
$animationDelayPost = $params->get('animation_delay_posts');
$readMoreStyle = $params->get('readmore_style');
$readMoreText = $params->get('readmore_text');
$readMoreIconSize = $params->get('readmore_icon_size');

if($readMoreText != ''){
	$readMoreIconSize = '';
}

if($readMoreStyle == 'none'){
	$readMoreStylePost = "";
	$readMoreStyleTeaser = "";
}
else{
	$readMoreStylePost = '<i class="fas '.$readMoreIconSize.' fa fa-'.$readMoreStyle.'"></i>';
	$readMoreStyleTeaser = '<i class="fas '.$readMoreIconSize.' fa fa fa-'.$readMoreStyle.'"></i>';
}
// Get list of items

$list            = ModArticlesNewsLoadMoreHelper::getList($params,$count);

// Get base link
$item = $list[0];
$id = $item->catid;
$replaceSlug = "?id=".$id;
$baseLink = str_replace($replaceSlug, "", $item->link);
$slug = $item->id . '-' . $item->alias;
$link = $baseLink."/".$slug;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_articles_news_load_more', $params->get('layout', 'default'));
