<?php if(!isset($_REQUEST["gh"])){ include_once("../includes/admin-header.php"); 
}else{ 
include_once("../includes/gen-header.php");
} ?>

<?php
$view = nr_input("view");
$edit = nr_input("edit");
$add = nr_input("add");
$change = nr_input("change");
$pn = nr_input("pn");

$cat_id = tp_input("cat_id");
$item_date = tp_input("item_date");
$item_location = tp_input("item_location");
$item_name = tp_input("item_name");
$item_slug = tp_input("item_slug");
$item_details = tp_input("item_details");

////////////// Add or Update item //////////////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && (!empty($add) || !empty($edit)) && !empty($cat_id) && !empty($item_date) && !empty($item_location) && !empty($item_name) && !empty($item_slug) && !empty($item_details)){

$item_slug_det = in_table("COUNT(id) AS Total","items","WHERE item_slug = '{$item_slug}'","Total");
$item_slug_det_id = in_table("id","items","WHERE item_slug = '{$item_slug}'","id");
$item_slug_det_name = in_table("item_name","items","WHERE item_slug = '{$item_slug}'","item_name");

///////////////////
if(!empty($add) && (!isset($_SESSION["item_img"]) || empty($_SESSION["item_img"]))){

echo "<div class='not-success'>Atleast, one item picture must be uploaded.</div>";

}else if(!empty($item_slug_det) && !empty($add)){

echo "<div class='not-success'>This item slug ({$item_slug}) already exists for {$item_slug_det_name}.</div>";

}else if(!empty($item_slug_det) && !empty($edit) && $item_slug_det_id != $edit){

echo "<div class='not-success'>This item slug ({$item_slug}) already exists for {$item_slug_det_name}.</div>";

}else{

$data_array = array(
"cat_id" => $cat_id,
"item_date" => $item_date,
"item_location" => $item_location,
"item_name" => $item_name,
"item_slug" => $item_slug,
"item_details" => $item_details
);

if(!empty($add)){
$data_array += array("added_by" => $id, "date_added" => $date_time); 
$act = $db->insert2($data_array, "items");
}else if(!empty($edit)){
$act = $db->update($data_array, "items", "id = '$edit'");
}

if($act){
$error = 0;

$posted_id = "";
if(!empty($add)){
$posted_id = in_table("id","items","WHERE added_by = '{$id}' AND date_added = '{$date_time}'","id");
}else if(!empty($edit)){
$posted_id = $edit;
}

if(isset($_SESSION["item_img"]) && !empty($_SESSION["item_img"]) && count($_SESSION["item_img"]) > 0){
foreach($_SESSION["item_img"] as $val){
$img_array1 = glob("../images/items-temp/{$id}_item_displayed_{$val}_*.*");
$img1 = $img_array1[0];
$img_array2 = glob("../images/items-temp/{$id}_item_featured_{$val}_*.*");
$img2 = $img_array2[0];
$img1_array = explode(".",$img2);
$file_ext = end($img1_array);
copy($img1,"../images/items-displayed/{$posted_id}_{$id}_item_displayed_{$val}_{$rand}.{$file_ext}");
copy($img2,"../images/items-featured/{$posted_id}_{$id}_item_featured_{$val}_{$rand}.{$file_ext}");
unlink($img1);
unlink($img2);
}
$_SESSION["item_img"] = NULL;
unset($_SESSION["item_img"]);
}

$_SESSION["msg"] = "<div class='success'>Item successfully saved.</div>";

if(!empty($add)){
redirect("{$directory}{$admin}manage-items/");
}else if(!empty($edit)){
redirect("{$directory}{$admin}manage-items/pn/{$pn}/");
}

}else{
echo "<div class='not-success'>Error occured.</div>";
}

}
//////////////

}

if($_SERVER['REQUEST_METHOD'] == "POST" && (!empty($add) || !empty($edit)) && (empty($cat_id) || empty($item_date) || empty($item_location) || empty($item_name) || empty($item_slug) || empty($item_details))){
echo "<div class='not-success'>Not submitted! All the fields are required.</div>";
}else if($_SERVER['REQUEST_METHOD'] == "POST" && (!empty($add) || !empty($edit)) && !date_valid($date)){
echo "<div class='not-success'>Not submitted! Invalid date format. Required Format: YYYY-MM-DD.</div>";
}

/////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST["delete"]) && isset($_POST["del"])){
$i = 0;
if(is_array($_POST["del"])){
foreach ($_POST["del"] as $k => $c) {
if($c != ""){ 
$c = testQty($c);

$added_by = in_table("added_by","items","WHERE id = '{$c}'","added_by");
$pic_array1 = glob("../images/items-displayed/{$c}_{$added_by}_item_displayed_*.*");
$pic_array2 = glob("../images/items-featured/{$c}_{$added_by}_item_featured_*.*");

if(!empty($pic_array1) && count($pic_array1) > 0){
$incre = 0;
foreach ($pic_array1 as $filename) {
if(file_exists($filename)){
unlink($filename);
}
if(file_exists($pic_array2[$incre])){
unlink($pic_array2[$incre]);
}
$incre++;
}	
}

$act = $db->delete("items", "id = '$c'");	
$i++;			
}else{
continue;
}
}

if($act){
echo "<div class='success'>{$i} item(s) successfully deleted.</div>";
}else{
echo "<div class='not-success'>Error. Unable to delete item(s).</div>";
}

}else{
echo "<div class='not-success'>Atleast one item must be selected.</div>";
}
}

////////////////////////////////////////////////////******************************//////////////

$result = $db->select("items", "", "*", "ORDER BY id DESC");

$per_view = 20;
$page_link = "{$admin}manage-items/pn/";
$link_suffix = "/";
$style_class = "general-link";
page_numbers();

if(isset($_SESSION["msg"]) && empty($cat_id)){
echo $_SESSION["msg"];
unset($_SESSION["msg"]);
}
?>

<?php
if(empty($change) && empty($view) && (empty($edit)||(!empty($edit)&&$error==0)) && (empty($add)||(!empty($add)&&$error==0)) ){
?>

<div class="page-title">Manage Items <a href="<?php echo $admin; ?>manage-items/add/1/" class="btn gen-btn float-right">New Item</a></div>

<?php
$d = 0;

$offset = ($per_view * $pn) - $per_view;

$result = $db->select("items", "", "*", "ORDER BY id DESC", "LIMIT {$offset},{$per_view}");

if(count_rows($result) > 0){
?>
<form action="<?php echo $admin; ?>manage-items/" class="general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data" style="overflow-x:auto;">
<input type="hidden" name="pn" value="<?php echo $pn; ?>"> 
<input type="hidden" name="gh" value="1"> 
<input type="hidden" name="delete" value="1"> 
<table class="table table-striped table-hover">
<thead>
<tr class="gen-title">
<th>Date</th>
<th>ID</th>
<th>Category</th>
<th>Title</th>
<th>Location</th>
<th>Pictures</th>
<th>Update</th>
<th>More</th>
<th style="width:30px;"><input type="checkbox" name="sel_all" id="delG" class="sel-group" value=""></th>
</tr>
</thead>
<tbody>
<?php
while($row = fetch_data($result)){
$item_id = $row["id"];
$cat_id = $row["cat_id"];
$item_date = min_sub_date($row["item_date"]);
$cat_title = in_table("cat_name","items_categories","WHERE id = '{$cat_id}'","cat_name");
$item_name = $row["item_name"];
$item_location = $row["item_location"];
$date_added = min_sub_date($row["date_added"]);
?>
<tr>
<td><?php echo $item_date; ?></td>
<td><?php echo $item_id; ?></td>
<td><?php echo $cat_title; ?></td>
<td><?php echo $item_name; ?></td>
<td><?php echo $item_location; ?></td>
<td><a href="<?php echo $admin; ?>manage-items/change/<?php echo $item_id; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link" title="Change item #<?php echo $item_id; ?> picture"><i class="fa fa-refresh" aria-hidden="true"></i> Change</a></td>
<td><a href="<?php echo $admin; ?>manage-items/edit/<?php echo $item_id; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn" title="Edit item #<?php echo $item_id; ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>
<td><a href="<?php echo $admin; ?>manage-items/view/<?php echo $item_id; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link" title="View message #<?php echo $item_id; ?>"><i class="fa fa-eye" aria-hidden="true"></i> View</a></td>
<td><input type="checkbox" name="del[<?php echo $d; ?>]" id="del<?php echo $d; ?>" class="delG" value="<?php echo $item_id; ?>"></td>
</tr>
<?php 
$d++;
}
?>
<tr><td colspan="8"><input class="sub-del" type="submit" value=" "><button type="button" class="btn del-btn gen-btn float-right"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete selected item(s)</button></td></tr>
</tbody>
</table>
</form>
<?php
echo ($last_page>1)?"<div class=\"page-nos\">" . $center_pages . "</div>":"";
}else{
echo "<div class='not-success'>No items found at the moment.</div>";
}

}

//=======================View Item Details==============================//
if(!empty($view)){
$result = $db->select("items", "WHERE id='$view'", "*", "");

if(count_rows($result) == 1){
$row = fetch_data($result);
$item_id = $row["id"];
$cat_id = $row["cat_id"];
$cat_title = in_table("cat_name","items_categories","WHERE id = '{$cat_id}'","cat_name");
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

<link rel="stylesheet" href="css/fotorama.css">
<script src="js/fotorama.js"></script>
<style>
<!--
div table thead tr th, div table tr th, div table tbody tr td, div table tr td{
text-align:left !important;
}
-->
</style>

<div class="reply-content-wrapper">

<div><a href="<?php echo $admin; ?>manage-items/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back to items</a></div>

<div class="view-wrapper ">

<div class="view-header ">
<div class="header-img"><img src="images/<?php echo $file_name; ?>" ></div>
<div class="header-content">
<div class="view-title"><?php echo "{$item_name} on {$item_date} (#{$item_id})"; ?></div>
<div class="view-title-details">Posted by: <?php echo "{$poster_name} ({$poster_email})"; ?></div>
</div>
</div>

<div class="view-content">


<div class="col-md-8">

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
<div class="col-md-4">
<h3>Description</h3>
<table class="table table-striped table-hover">
<tbody>
<tr><td style="width:90px;"><b>Category</b></td><td><?php echo $cat_title; ?></td></tr>
<tr><td><b>Slug</b></td><td><?php echo $item_slug; ?></td></tr>
<tr><td><b>Location</b></td><td><?php echo $item_location; ?></td></tr>
<tr><td><b>Details</b></td><td><?php echo html_entity_decode($item_details); ?></td></tr>
<tr><td><b>Posted by</b></td><td><?php echo "{$poster_name} ({$poster_email})"; ?></td></tr>
<tr><td><b>Posted on</b></td><td><?php echo $date_added; ?></td></tr>
</tbody>
</table>

<div><a href="<?php echo $admin; ?>manage-items/edit/<?php echo $item_id; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn float-right" title="Edit item #<?php echo $item_id; ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></div>

</div>


</div>

</div>
</div>

<?php
}else{
echo "<div class='not-success'>No items found at the moment.</div>";
}
}


//=======================Change Item Pictures==============================//
if(!empty($change)){
$result = $db->select("items", "WHERE id='$change'", "*", "");

if(count_rows($result) == 1){
$row = fetch_data($result);
$item_id = $row["id"];
$user_id = $row["added_by"];

$file_name_aray = glob("../images/items-featured/{$item_id}_{$user_id}_item_featured_*.*");
?>

<div><a href="<?php echo $admin; ?>manage-items/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back to items</a></div>

<div class="page-title">Change Item #<?php echo $item_id; ?> Picture(s)</div>

<div class="col-md-12">
<p class="img-notice"><b>Format:</b> .jpg, .jpeg, .png, .gif. Not more than 20MB<br /> <b>Note:</b> The first image is your featured image which displays first as grid.</p>

<div class="add-result">
<?php
if(isset($file_name_aray) && !empty($file_name_aray) && count($file_name_aray) > 0){
foreach($file_name_aray as $val){
if(file_exists($val)){
$file_session_no_arr = explode("_",$val);
$file_session_no = $file_session_no_arr[4];
?>
<div>      

<form action="<?php echo $privates; ?>process-data/" class="general-form2 edit-form-<?php echo $file_session_no; ?>" name="my-item-default-<?php echo $file_session_no; ?>" id="result-<?php echo $file_session_no; ?>" lang="my-item-loading-<?php echo $file_session_no; ?>" title="edit" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">  
<input type="hidden" name="edit_my_item_img2" value="1" />
<input type="hidden" name="item_user_id" value="<?php echo $user_id; ?>" />
<input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />
<input type="hidden" name="session_item_img" value="<?php echo $file_session_no; ?>" />

<div class="new-item-pic">
<div class="item-pic-img"> 
<div class="relative-div">
<div class="item-image-wrapper result-<?php echo $file_session_no; ?>"><img src="images/<?php echo $val; ?>" /></div>
<div class="fileupload fileupload-new" data-provides="fileupload">
<span class="btn btn-primary btn-file upload-padding">
<span class="fileupload-new">
<i class="fa fa-spinner fa-spin fa-3x fa-fw gen-spinner" aria-hidden="true" id="my-item-loading-<?php echo $file_session_no; ?>"></i>
<i class="fa fa-refresh" aria-hidden="true" id="my-item-default-<?php echo $file_session_no; ?>"></i> 
Change pic                          
</span>
<input type="file" name="edit_item_img" onchange="javascript: $('.edit-form-<?php echo $file_session_no; ?>').submit();">
</span><span class="fileupload-preview"></span>
</div></div>
</div>
<div class="item-pic-option"> 
<button type="button" class="btn btn-danger delete-item-picture" onclick="javascript: delete_file('<?php echo $privates; ?>process-data/', 'del_item_file2', '<?php echo $val; ?>', 'delete-<?php echo $file_session_no; ?>', 'result-<?php echo $file_session_no; ?>');"><i class="fa fa-trash" aria-hidden="true"></i> Delete <i class="fa fa-spinner fa-spin fa-3x fa-fw gen-spinner" id="delete-<?php echo $file_session_no; ?>" aria-hidden="true"></i></button>
</div>
</div>
</form>
</div>
<?php
}
}
}
?>
</div>
</div>

<?php
}else{
echo "<div class='not-success'>No items found at the moment.</div>";
}
}


if((!empty($add) || !empty($edit)) && $error == 1){

$item_id = $cat_id = $item_location = $item_name = $item_slug = $item_price = $item_qty = $item_details = "";
if(!empty($edit)){
$result = $db->select("items", "WHERE id='$edit'", "*", "");
if(count_rows($result) == 1){
$row = fetch_data($result);
$item_id = $row["id"];
$item_date = $row["item_date"];
$cat_id = $row["cat_id"];
$item_location = $row["item_location"];
$item_name = $row["item_name"];
$item_slug = $row["item_slug"];
$item_details = $row["item_details"];
}
}

$field_name = (!empty($edit))?"edit":"add";
$field_value = (!empty($edit))?$item_id:1;
$action_title = (!empty($edit))?"Edit":"Add New";
$img_title = (!empty($edit))?"Add More Picture(s)":"Add New Picture(s)*";
?>

<div><a href="<?php echo $admin; ?>manage-items/pn/<?php echo $pn; ?>/" class="btn gen-btn"><i class="fa fa-arrow-left"></i> Back to items list</a></div>

<div class="page-title"><?php echo $action_title; ?> Item</div>

<div class="col-md-6">
<label for="" style="padding-top:20px;"><b><?php echo $img_title; ?></b></label>
<p class="img-notice"><b>Format:</b> .jpg, .jpeg, .png, .gif. Not more than 20MB<br /> <b>Note:</b> The first image is your featured image which displays first as grid.</p>

<div class="add-result">
<?php
if(isset($_SESSION["item_img"]) && !empty($_SESSION["item_img"])){
foreach($_SESSION["item_img"] as $val){
$file_name_aray = glob("../images/items-temp/{$id}_item_featured_{$val}_*.*");
$file_name = $file_name_aray[0];
?>
<div>      

<form action="<?php echo $privates; ?>process-data/" class="general-form2 edit-form-<?php echo $val; ?>" name="my-item-default-<?php echo $val; ?>" id="result-<?php echo $val; ?>" lang="my-item-loading-<?php echo $val; ?>" title="edit" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">  
<input type="hidden" name="edit_my_item_img" value="1" />
<input type="hidden" name="session_item_img" value="<?php echo $val; ?>" />
<div class="new-item-pic">
<div class="item-pic-img"> 
<div class="relative-div">
<div class="item-image-wrapper result-<?php echo $val; ?>"><img src="images/<?php echo $file_name ?>" /></div>
<div class="fileupload fileupload-new" data-provides="fileupload">
<span class="btn btn-primary btn-file upload-padding">
<span class="fileupload-new">
<i class="fa fa-spinner fa-spin fa-3x fa-fw gen-spinner" aria-hidden="true" id="my-item-loading-<?php echo $val; ?>"></i>
<i class="fa fa-refresh" aria-hidden="true" id="my-item-default-<?php echo $val; ?>"></i> 
Change pic                          
</span>
<input type="file" name="edit_item_img" onchange="javascript: $('.edit-form-<?php echo $val; ?>').submit();">
</span><span class="fileupload-preview"></span>
</div></div>
</div>
<div class="item-pic-option"> 
<button type="button" class="btn btn-danger delete-item-picture" onclick="javascript: delete_file('<?php echo $privates; ?>process-data/', 'del_item_file', '<?php echo $val; ?>', 'delete-<?php echo $val; ?>', 'result-<?php echo $val; ?>');"><i class="fa fa-trash" aria-hidden="true"></i> Delete <i class="fa fa-spinner fa-spin fa-3x fa-fw gen-spinner" id="delete-<?php echo $val; ?>" aria-hidden="true"></i></button>
</div>
</div>
</form>
</div>
<?php
}
}
?>
</div>

<form action="<?php echo $privates; ?>process-data/" class="general-form2 add-form" name="my-item-default" id="add-result" lang="my-item-loading" title="add" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">  
<input type="hidden" name="my_item_img" value="1" />
<div class="new-item-pic">
<div class="item-pic-img"> 
<div class="relative-div">
<div class="item-image-wrapper">Add new picture</div>
<div class="fileupload fileupload-new" data-provides="fileupload">
<span class="btn btn-primary btn-file upload-padding">
<span class="fileupload-new">
<i class="fa fa-plus-circle" aria-hidden="true" id="my-item-default"></i> 
<i class="fa fa-spinner fa-spin fa-3x fa-fw gen-spinner my-item-loading" aria-hidden="true" id="my-item-loading"></i>
</span>
<input type="file" name="item_img" onchange="javascript: $('.add-form').submit();">
</span><span class="fileupload-preview"></span>
</div></div>
</div>
<div class="item-pic-option"> 
</div>
</div>
</form>

</div>
<div class="col-md-6">

<form action="<?php echo $admin; ?>manage-items/" method="post" runat="server" autocomplete="off" enctype="multipart/form-data" style="border:1px solid #eee;">
<input type="hidden" name="<?php echo $field_name; ?>" value="<?php echo $field_value; ?>"> 
<?php if(!empty($edit)){ ?>
<input type="hidden" name="pn" value="<?php echo $pn; ?>"> 
<?php
}
?>

<div class="col-sm-12">
<label for="cat_id">Category</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-tag"></i></span>
<select name="cat_id" id="cat_id" title="Select a category" class="form-control js-example-basic-single" style="width:100%" required>
<option value="">**Select a category**</option>
<?php 
$result2 = $db->select("items_categories", "", "DISTINCT *", "ORDER BY cat_name ASC");
if(count_rows($result2) > 0){
while($row2 = fetch_data($result2)){
$cat_id2 = $row2["id"];
$cat_name = $row2["cat_name"];
echo "<option value='{$cat_id2}'";
check_selected("cat_id", $cat_id2, $cat_id); 
echo ">{$cat_name}</option>";
}
}
?>
</select>
</div>
</div>

<div class="col-sm-12">
<label for="item_name">Title</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
<input type="text" name="item_name" id="item_name" class="form-control" placeholder="Name of the item" value="<?php check_inputted("item_name", $item_name); ?>" required>
</div>
</div>

<div class="col-sm-12">
<label for="item_slug">Slug</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
<input type="text" name="item_slug" id="item_slug" class="form-control" placeholder="Slug of the item. E.g. blackberry-10" value="<?php check_inputted("item_slug", $item_slug); ?>" required>
</div>
</div>

<div class="col-sm-12">
<label for="item_date">Date</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
<input type="text" name="item_date" id="item_date" class="form-control gen-date" onfocus="javascript: $(this).blur();" placeholder="YYYY-MM-DD" value="<?php check_inputted("item_date", $item_date); ?>" required>
</div>
</div>

<div class="col-sm-12">
<label for="item_location">Location</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
<textarea name="item_location" id="item_location" class="form-control" rows="2" placeholder="Type in the event location" required><?php check_inputted("item_location", $item_location); ?></textarea>
</div>
</div>

<div class="col-sm-12">
<label for="item_details">Details</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
<textarea name="item_details" id="item_details" class="ckeditor" placeholder="Type in the details" required style="overflow: auto; width:100%"><?php check_inputted("item_details", $item_details); ?></textarea>
</div>
</div>
                     
<div class="submit-div col-sm-12">
<button class="btn gen-btn float-right" name="update"><i class="fa fa-upload"></i> Save</button>
</div>

</form>
</div>
<?php
}
?>

<script>
<!--
var conf_text = "item";
//-->
</script>

<script src="js/text_plugin/ckeditor.js"></script>
<script src="js/general-form.js"></script>

<?php  if(!isset($_REQUEST["gh"])){?>
</div>
</div>

</div>

<?php require_once("../includes/portal-footer.php"); } ?>