<?php
include "Nebulous.php";
function getAveragePrice($bikeMake, $bikeModel, $bikeYear) {
    $i = 0;
    $total = 0;
    $minPrice = 0;
    $maxPrice = 0;
    $minYear = 0;
    $maxYear = 0;
    $categories = array();
    $arParams = array(
        "makeDisplayName" => $bikeMake,
        "modelDisplayName" => $bikeModel,
        "view" => "full"
    );
    if ($bikeYear != null) {
        $arParams['minYear'] = $bikeYear;
        $arParams['maxYear'] = $bikeYear;
    }
    $testing = (TOL_Nebulous::getInstance()->getCycles($arParams));
    foreach ($testing["result"] as $cycle) {
        if (!empty($cycle["price"])) {
            if ($minPrice == 0) {
                $minPrice = $cycle["price"];
                $maxPrice = $cycle["price"];
            }
            if ($minYear == 0) {
                $minYear = $cycle['year'];
                $maxYear = $cycle['year'];
            }
            if ($cycle["price"] < $minPrice) $minPrice = $cycle["price"];
            if ($cycle["price"] > $maxPrice) $maxPrice = $cycle["price"];
            if ($cycle["year"] < $minYear) $minYear = $cycle["year"];
            if ($cycle["year"] > $maxYear) $maxYear = $cycle["year"];
            $total = $total + $cycle["price"];
            $i++;
        }
        if (isset($cycle['categoryName']) && $cycle['categoryName'] != '') {
            if (isset($categories[$cycle['categoryName']])) {
                $categories[$cycle['categoryName']]++;
            } else {
                $categories[$cycle['categoryName']] = 1;
            }
        }
    }
    if ($i == 0) {
        if ($bikeYear != null) {
            getAveragePrice($bikeMake, $bikeModel, null);
        }
        return;
    }
    echo json_encode(
        array(
            "Make" => $bikeMake,
            "Model" => $bikeModel,
            "Average Cycletrader.com Price" => "$" . number_format(round($total / $i)),
            "Min Price" => $minPrice,
            "Max Price" => $maxPrice,
            "Min Year" => $minYear,
            "Max Year" => $maxYear,
            "Categories" => $categories,
       )
   );
}

function tagify($objects)
{
	$tags = array();
	foreach($objects as $object)
	{
		$tag = preg_replace('/\s+/', '', strtolower( $object['make'] . $object['model'] ));
		$tag = str_replace('-', '', $tag);
		$tags[] = $tag;
	}

	//$tags[] = strtolower( $object['model'] );
	return $tags;
}

/*
Simulates Object received from Cycletrader query
*/
$objects = 
	array(
		array(
			'title'=>'Example',
			'model'=>'Yzf600r',
			'make'=>'Yamaha',
			'type'=>'Sportbike',
		),
		array(
			'title'=>'Example',
			'model'=>'kx450',
			'make'=>'kawasaki',
			'type'=>'Cruiser',
		),
		array(
			'title'=>'Example',
			'model'=>'gsx-r1000',
			'make'=>'Suzuki',
			'type'=>'sportbike',
			),
);

foreach($objects as $object)
{
	$userBikeMake = $object['make'];
	$userBikeModel = $object['model'];
	if ($userBikeMake != NULL || $userBikeModel != NULL) {
    	//echo json_encode(getAveragePrice($userBikeMake, $userBikeModel,''));
    	
	} else {
    	echo json_encode(array());
	}
}

$tags = array();

$tags = tagify($objects);


require( 'views/_header.php' );

$instagram = new Instagram\Instagram;

$instagram->setAccessToken( $_SESSION['instagram_access_token'] );

?>

<div id="slides">
<?php
	foreach($tags as $tag){
		$tagee = $instagram->getTag($tag);
		$media = $tagee->getMedia( isset( $_GET['max_tag_id'] ) ? array( 'max_tag_id' => $_GET['max_tag_id'] ) : null );
?>
<?php
$randomItem = rand(0, count($media));
?>
<img src="<?php echo $media[$randomItem]->getStandardResImage()->url ?>" title="Posted by <?php echo $media[$randomItem]->getUser() ?> on <?php echo $media[$randomItem]->getCreatedTime( 'M jS Y @ g:ia' ) ?>"><?php if( $media[$randomItem]->getType() == 'video' ): ?><img src="system/lib/PHP-Instagram-API/Examples/_images/play.png" class="play"><?php endif; ?>

<? } ?>
</div>
<?
require( 'views/_footer.php' );
?>