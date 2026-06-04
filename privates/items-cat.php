<?php require_once("../includes/header.php"); 

$cat_slug = tr_input("cat_slug");
$view = in_table("id","items_categories","WHERE cat_slug = '{$cat_slug}'","id");
$cat_title = in_table("cat_name","items_categories","WHERE id = '{$view}'","cat_name");
$result = $db->select("items", "WHERE cat_id = '{$view}'", "*", "");

$per_view = 12;
$page_link = "cat/{$cat_slug}/pn/";
$link_suffix = "/";
$style_class = "general-link";
page_numbers();
?>

<div class="home-body-wrapper"> 
<div class="container container2">
<div class="container-bg">
<div class="body-header2"><?php echo $cat_title; ?></div>
</div>
</div>
</div>

<div class="home-body-wrapper"> 
<div class="container">

<?php
//=======================View Event Details==============================//
if(!empty($view)){
$offset = ($per_view * $pn) - $per_view;

$result = $db->select("items", "WHERE cat_id = '{$view}'", "*", "ORDER BY item_date DESC, id DESC", "LIMIT {$offset},{$per_view}");

if(count_rows($result) > 0){
?>
<div class="item-wrapper">
<?php
while($row = fetch_data($result)){
$item_id = $row["id"];
$item_date = min_sub_date($row["item_date"]);
$item_name = $row["item_name"];
$item_slug = $row["item_slug"];
$slide_array = glob("../images/items-featured/" . $row["id"] . "_" . $row["added_by"] . "_item_featured_*.*");
$file_name = $slide_array[0];
?>
<div class="item-inner white-bg shadow <?php echo det_browser("fly"); ?>">
<div class="item-picture"><a href="details/<?php echo $item_slug; ?>/pn/1/"><img src="images/<?php echo $file_name; ?>" /></a></div>
<div class="item-title"><a href="details/<?php echo $item_slug; ?>/pn/1/"><?php echo $item_name; ?></a>
<div class="item-price"><?php echo $item_date; ?></div>
<div class="item-options">
<a href="details/<?php echo $item_slug; ?>/pn/1/" class="btn gen-btn float-right"><i class="fa fa-eye" aria-hidden="true"></i> View details</a>
</div>
</div>
</div>
<?php
}
?>
</div>
<?php
echo ($last_page>1)?"<div class=\"page-nos\">" . $center_pages . "</div>":"";
}else{
?>
<div style="padding-bottom:20px;"><a href="<?php directory(); ?>"  onclick="javascript: history.back(1);"class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back</a></div>
<div class="not-success">This category does not exist.</div>
<?php
} 
}else{
?>
<div style="padding-bottom:20px;"><a href="<?php directory(); ?>"  onclick="javascript: history.back(1);"class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back</a></div>
<div class="not-success">Invalid Access.</div>
<?php
} 
?>

</div>
</div>

<?php require_once("../includes/footer.php"); ?>