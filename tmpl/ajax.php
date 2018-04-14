<?php
define( '_JEXEC', 1 );
define('JPATH_BASE', '../../../');

require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );

$app = JFactory::getApplication('site');
$db = JFactory::getDbo();

// Get module params

$moduleID = $app->input->post->get('moduleID');
$moduleQuery = $db->getQuery(true);
$moduleQuery->select('a.*');
$moduleQuery->from($db->quoteName('#__modules', 'a'));
$moduleQuery->where($db->quoteName('id') . ' = '. $db->quote($moduleID));
$db->setQuery($moduleQuery);
$modules = $db->loadObject();
$params = json_decode($modules->params);


$ordering = $params->ordering;
$ordering = str_replace("a.", "", $ordering);

$directionState = $params->direction;
if($directionState == 0){
    $direction = 'ASC';
}
if($directionState == 1){
    $direction = 'DESC';
}
$orderingDirection = $ordering." ".$direction;

$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);

$categoryIDs = $params->catid;
$catsString = "";
foreach($categoryIDs as $catid){
	$catsString .= $catid.",";
}
$catsString=rtrim($catsString,", ");
$categories = $catsString;

$spotlight = $params->article_spotlight;

$linkTitles = $params->link_titles;
$titleFlag = $params->item_title;
$imageFlag = $params->image;
$textLength = $params->text_length;
$animationFlag = $params->animation;
$animationPosts = $params->animation_posts;
$animationDelayPost = $params->animation_delay_posts;
$animationSpeedPost = $params->animation_speed_posts;

if($animationFlag == 1){
    $animationClass = "wow animate ".$animationPosts;
}
else{
    $animationClass = "";
}

$columnsDesktop = $params->columns_desktop;
$readMoreStyle = $params->readmore_style;
$readMoreText  =  $params->readmore_text;
$textTrigger  =  $params->text_trigger;
$dateTrigger  =  $params->date_trigger;
$dateFormat  =  $params->dateformat;
$readMoreIconSize = $params->readmore_icon_size;
if($readMoreText != ''){
	$readMoreIconSize = '';
}

if($readMoreStyle == 'none'){
	$readMoreStylePost = "";
}
else{
	$readMoreStylePost = '<i class="fas '.$readMoreIconSize.' fa fa-'.$readMoreStyle.'"></i>';
}

//throw HTTP error if page number is not valid
if(!is_numeric($page_number)){
    header('HTTP/1.1 500 Invalid page number!');
    exit;
}
$item_per_page = $params->count;

//get current starting point of records
$position = (($page_number-1) * $item_per_page);

//fetch records using page position and item per page.
$query = $db->getQuery(true);
$categories = 'catid IN ('.$categories.')';
if($spotlight == 1){
    $queryID = $db->getQuery(true);
    $queryID->select('id');
    $queryID->from($db->quoteName('#__content'));
	$queryID->where($categories);
    $queryID->order($orderingDirection);
    $db->setQuery($queryID);
    $lastID = $db->loadResult();
}



// When loading with fields
//$query->select('a.*, b.*');
$query->select('a.*');
$query->from($db->quoteName('#__content', 'a'));
// When loading with fields
//$query->join('LEFT', $db->quoteName('#__fields_values', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.item_id') . ')');
if($spotlight == 1){
    $query->where($db->quoteName('id')." != ".$db->quote($lastID));
}
//$query->where($db->quoteName('catid')." = ".$db->quote($categoryID));
$query->where($categories);
$query->order($orderingDirection);
$query->setLimit($item_per_page);
$db->setQuery($query,$position,$item_per_page);

$row = $db->loadAssocList();

$colIndicator = 12/$columnsDesktop;
switch ($colIndicator) {
	case 1:
        $colTablet = 12;
        $colPhone = 12;
		break;
	case 2:
		$colTablet = 6;
		$colPhone = 12;
		break;
	case 3:
		$colTablet = 6;
		$colPhone = 12;
		break;
	case 4:
		$colTablet = 6;
		$colPhone = 12;
		break;
	case 6:
		$colTablet = 6;
		$colPhone = 12;
		break;
	case 12:
		$colTablet = 3;
		$colPhone = 6;
		break;
}

?>
<?php // Getting array from helper, splitting every 4 times
foreach (array_chunk($row, $columnsDesktop) as $items): ?>

<div class="row loadMoreRow">
    <?php foreach ($items as $item): ?>
        <?php
	    // Vars
	    setlocale (LC_ALL, 'de_DE');
	    $images = json_decode($item['images']);
	    $introImage = $images->image_intro;
	    $introText = $item['introtext'];
	    $date = $item['publish_up'];
	    $timestamp = strtotime($date);
	    //setlocale(LC_TIME, "en_GB");
	    $date =  strftime($dateFormat, $timestamp);
	    $slug = $item['id'] . '-' . $item['alias'];
	    $replaceSlug = "?id=".$item['id'];
	    $menuItem  = $app->input->post->get('menuItem');

	    $baseLink = "/".$menuItem;

	    $link = $baseLink."/".$slug;
        ?>
            


        <div <?php if($animationFlag == 1 && $animationSpeedPost != ""): ?>data-wow-duration="<?php echo $animationSpeedPost; ?>"<?php endif; ?> <?php if($animationFlag == 1 && $animationDelayPost != ""): ?>data-wow-delay="<?php echo $animationDelayPost; ?>"<?php endif; ?> class="<?php echo $animationClass; ?> col-xs-<?php echo $colPhone; ?> col-sm-<?php echo $colTablet; ?> col-md-<?php echo $colIndicator; ?>  singleBlogPost">
			<div class="blogInner">
                    <?php if($imageFlag == 1): ?>
					<img class="blogImage blogImageLarger" src="<?php echo $introImage; ?>" />
                    <?php endif; ?>
				<?php if($dateTrigger == 1): ?>
                    <p class="newsDate"><?php echo $date; ?></p>
				<?php endif; ?>
                
					<div class="blogItemText">
                        
                        <?php if($titleFlag == 1): ?>
                            <?php if($linkTitles == 1): ?>
                                <h3 class="blogHeadlinePost">
                                    <a class="<?php echo $item['value']; ?>" href="<?php echo $link; ?>">

                                            <?php echo $item['title']; ?>
                                    </a>
                                </h3>
                            <?php else: ?>
                                    <h3 class="blogHeadlinePost">
                                        <?php echo $item['title']; ?>
                                    </h3>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if($textTrigger == 1): ?>
                            <?php
                                // Ausgabe Introtext gekürzt
                                echo substr($introText,0, $textLength);
                            ?> ...
                            <?php if($linkTitles == 1): ?>
                            <div class="postWeiter">
                                 <a href="<?php echo $link; ?>">
                                    <?php echo $readMoreText; ?> <?php echo $readMoreStylePost; ?>
                                </a>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
					</div>
			</div>
        </div>
        
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

