<?php require_once("../includes/header.php"); 

$item_slug = tr_input("item_slug");
$view = in_table("id","items","WHERE item_slug = '{$item_slug}'","id");
$pn = nr_input("pn");

//=======================View Event Details==============================//
if(!empty($view)){
$result = $db->select("items", "WHERE id='$view'", "*", "");

if(count_rows($result) == 1){
$row = fetch_data($result);
$item_id = $row["id"];
$cat_id = $row["cat_id"];
$cat_title = in_table("cat_name","items_categories","WHERE id = '{$cat_id}'","cat_name");
$cat_slug = in_table("cat_slug","items_categories","WHERE id = '{$cat_id}'","cat_slug");
$item_date = sub_date($row["item_date"]);
$item_location = $row["item_location"];
$item_name = $row["item_name"];
$item_slug = $row["item_slug"];
$item_details = $row["item_details"];
$added_by = $row["added_by"];
$date_added = full_date($row["date_added"]);

$poster_name = in_table("name","admin_data","WHERE id = '{$added_by}'","name");
$poster_email = in_table("email","admin_data","WHERE id = '{$added_by}'","email");

$slide_array1 = glob("../images/items-featured/{$item_id}_{$added_by}_item_featured_*.*");
$slide_array2 = glob("../images/items-displayed/{$item_id}_{$added_by}_item_displayed_*.*");
$file_name = $slide_array1[0];
?>

<div class="home-body-wrapper"> 
<div class="container" style="overflow:visible;"> 

<div class="margin-top-down-20"><a href="cat/<?php echo $cat_slug; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back to <?php echo $cat_title; ?> category</a></div>

<div class="col-md-12 white-bg shadow">

<link rel="stylesheet" href="css/fotorama.css">
<script src="js/fotorama.js"></script>

<div class="col-md-7 remove-overflow">

<div class="fotorama" data-width="600" data-ratio="3/2" data-nav="thumbs" data-thumbheight="48">
<?php $c = 0;
foreach($slide_array2 as $val){
if(file_exists($val)){
?>
<a href="images/<?php echo $val; ?>"><img src="images/<?php echo (file_exists($slide_array1[$c]))?$slide_array1[$c]:""; ?>"></a>
<?php
}
$c++;
}
?>
</div> 

</div>
<div class="col-md-5 padding-20">
<div class="item-price item-price2"><?php echo $item_name; ?></div>
<h3>Date</h3>
<p><?php echo $item_date; ?></p>
<h3>Location</h3>
<p><?php echo $item_location; ?></p>
<h3>Details</h3>
<?php echo html_entity_decode($item_details); ?>
</div>



</div>

</div>
</div>

<?php 
}else{
?>
<div class="padding-bottom-0"><a href="<?php directory(); ?>"  onclick="javascript: history.back(1);"class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back</a></div>
<div class="not-success">This item does not exist.</div>
<?php
} 
}else{
?>
<div class="padding-bottom-0"><a href="<?php directory(); ?>"  onclick="javascript: history.back(1);"class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back</a></div>
<div class="not-success">Invalid Access.</div>
<?php
} 

require_once("../includes/footer.php"); ?>