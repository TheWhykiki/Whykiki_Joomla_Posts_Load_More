<?php
define( '_JEXEC', 1 );
define('JPATH_BASE', '../../');

require_once ( JPATH_BASE .'/includes/defines.php' );
require_once ( JPATH_BASE .'/includes/framework.php' );

$app = JFactory::getApplication('site');
$db = JFactory::getDbo();



$ordering = $app->input->post->get('ordering');
$ordering = str_replace("a.", "", $ordering);

$directionState = $app->input->post->get('direction');
if($directionState == 0){
    $direction = 'ASC';
}
if($directionState == 1){
    $direction = 'DESC';
}
$orderingDirection = $ordering." ".$direction;

$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
$categoryID = $app->input->post->get('category');
$spotlight = $app->input->post->get('spotlight');
$baseLink = $_POST['baseLink'];
$linkTitles = $app->input->post->get('linkTitles');
$titleFlag = $app->input->post->get('titleFlag');
$imageFlag = $app->input->post->get('imageFlag');
$textLength = $app->input->post->get('textLength');
$animationFlag = $app->input->post->get('animationFlag');
$animationPosts = $app->input->post->get('animationPosts');
$animationDelayPost = $app->input->post->get('animationDelayPost');
$animationSpeedPost = $app->input->post->get('animationSpeedPost');

if($animationFlag == 1){
    $animationClass = "wow animate ".$animationPosts;
}
else{
    $animationClass = "";
}

$columnsDesktop = $app->input->post->get('columnsDesktop');

//throw HTTP error if page number is not valid
if(!is_numeric($page_number)){
    header('HTTP/1.1 500 Invalid page number!');
    exit;
}
$item_per_page = $app->input->post->get('count');

//get current starting point of records
$position = (($page_number-1) * $item_per_page);

//fetch records using page position and item per page.
$query = $db->getQuery(true);

if($spotlight == 1){
    $queryID = $db->getQuery(true);
    $queryID->select('id');
    $queryID->from($db->quoteName('#__content'));
    $queryID->where($db->quoteName('catid')." = ".$db->quote($categoryID));
    $queryID->order($orderingDirection);
    $db->setQuery($queryID);
    $lastID = $db->loadResult();
}


$query->select('a.*, b.*');
$query->from($db->quoteName('#__content', 'a'));
$query->join('LEFT', $db->quoteName('#__fields_values', 'b') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('b.item_id') . ')');
if($spotlight == 1){
    $query->where($db->quoteName('id')." != ".$db->quote($lastID));
}
$query->where($db->quoteName('catid')." = ".$db->quote($categoryID));
$query->order($orderingDirection);
$query->setLimit($item_per_page);
$db->setQuery($query,$position,$item_per_page);

$row = $db->loadAssocList();

$colIndicator = 12/$columnsDesktop;

?>
<?php // Getting array from helper, splitting every 4 times
foreach (array_chunk($row, $columnsDesktop) as $items): ?>

<div class="row loadMoreRow">
    <?php foreach ($items as $item): ?>
        <?php
            // Vars
            $images = json_decode($item['images']);
            $introImage = $images->image_intro;
            $introText = $item['introtext'];
			$date = $item['publish_up'];
			$date = date('d.m.Y',strtotime($date));
            $slug = $item['id'] . '-' . $item['alias'];
            $link = $baseLink."/".$slug;
        ?>
            


        <div <?php if($animationFlag == 1 && $animationSpeedPost != ""): ?>data-wow-duration="<?php echo $animationSpeedPost; ?>"<?php endif; ?> <?php if($animationFlag == 1 && $animationDelayPost != ""): ?>data-wow-delay="<?php echo $animationDelayPost; ?>"<?php endif; ?> class="<?php echo $animationClass; ?> col-sm-<?php echo $colIndicator*2; ?> col-md-<?php echo $colIndicator; ?>  singleBlogPost">
			<div class="blogInner">
                    <?php if($imageFlag == 1): ?>
					<img class="blogImage blogImageLarger" src="/<?php echo $introImage; ?>" />
                    <?php endif; ?>
					<p class="newsDate"><?php echo $date; ?></p>
                
					<div class="blogItemText">
                        
                        <?php if($titleFlag == 1): ?>
                            <?php if($linkTitles == 1): ?>
                                <a class="<?php echo $item['value']; ?>" href="<?php echo $link; ?>">
                                    <h3 class="blogHeadlinePost">
                                        <?php echo $item['title']; ?>
                                    </h3>
                                </a>
                            <?php else: ?>
                                    <h3 class="blogHeadlinePost">
                                        <?php echo $item['title']; ?>
                                    </h3>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                
						<?php 
							// Ausgabe Introtext gekürzt
							echo substr($introText,0, $textLength);
						?> ...
                        <?php if($linkTitles == 1): ?>
                         <a href="<?php echo $link; ?>">
						    <img class="weiter" src="/modules/mod_articles_news_load_more/images/weiter.png" />
                        </a>
                        <?php endif; ?>
					</div>
			</div>
        </div>
        
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

