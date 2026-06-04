<?php if(!isset($_REQUEST["gh"])){ include_once("../includes/admin-header.php"); 
}else{ 
include_once("../includes/gen-header.php"); require_once("../includes/resize-image.php");
} ?>

<?php

//////////=============Add New User===================///////////////////////////////////////
$error = 1;

$upload = np_input("upload");
$member_id = np_input("member_id");

$add = nr_input("add");
$edit = nr_input("edit");

$description = tp_input("description");
$position = tp_input("position");
$order_id = np_input("order_id");

////////////// Upload image //////////////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($upload) && !empty($member_id) && !empty($_FILES["ufile"]["tmp_name"])){ 

upload_single_image("ufile", "{$member_id}pic", "../images/banners/", "1400", "500");

echo "<div class='success'>Picture successfully updated.</div>";
}
////////////// Ends Upload image //////////////////////////////

////////////// Add or Update item //////////////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && (!empty($add) || !empty($edit)) && !empty($description) && !empty($order_id)){

$data_array = array(
"description" => $description,
"order_id" => $order_id
);

if(!empty($add)){
$data_array += array("date_time" => $date_time); 
$act = $db->insert2($data_array, "banners");
}else if(!empty($edit)){
$act = $db->update($data_array, "banners", "id = '$edit'");
}

if($act){
$error = 0;
echo "<div class='success'>Profile successfully saved.</div>";
}else{
echo "<div class='not-success'>Error occured.</div>";
}

}
//////////////

if($_SERVER['REQUEST_METHOD'] == "POST" && (!empty($add) || !empty($edit)) && (empty($description) || empty($order_id))){
echo "<div class='not-success'>Not submitted! All the fields are required.</div>";
}

//////////////////////*******Delete Banner********///////////////
if(isset($_POST["delete"]) && isset($_POST["del"])){
$i = 0;
if(is_array($_POST["del"])){
foreach ($_POST["del"] as $k => $c){
if($c != ""){ 
$c = testQty($c);

$filename = glob("../images/banners/{$c}pic*.*");
foreach($filename as $val){
unlink($val);
}

$act = $db->delete("banners", "id = '$c'");	
$i++;			
}else{
continue;
}
}

if($act){
echo "<div class='success'>{$i} banner(s) successfully deleted.</div>";
}else{
echo "<div class='not-success'>Error. Unable to delete banner(s).</div>";
}

}else{
echo "<div class='not-success'>Atleast one banner must be selected.</div>";
}
}

////////////////////////////////////////////////////******************************//////////////

$new_name = search_option("new_name");
$no_of_rows = search_option("no_of_rows");

$where = "WHERE id > '0'";
$where .= (!empty($new_name))?" AND description LIKE '%{$new_name}%'":"";

$table = "banners";
$result = $db->select("$table", "$where", "*", "ORDER BY id DESC");
$count = count_rows($result);

$per_view = 10;
$per_view = (!empty($no_of_rows))?$no_of_rows:$per_view;
$page_link = "{$admin}manage-banners?pn=";
$link_suffix = "/";
$style_class = "general-link";
page_numbers();

$offset = ($per_view * $pn) - $per_view;

$result = $db->select("$table", "$where", "*", "ORDER BY id DESC", "LIMIT {$offset},{$per_view}");

if(empty($view) && empty($activities) && (empty($edit)||(!empty($edit)&&$error==0)) && (empty($add)||(!empty($add)&&$error==0)) ){
?>

<div class="page-title">Manage Banners <a href="<?php echo $admin; ?>manage-banners/add/1/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link float-right"><i class="fa fa-upload" aria-hidden="true"></i> New banner</a></div>

<form action="<?php echo $admin; ?>manage-banners" class="img-form general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">  
<input type="hidden" name="gh" value="1">
<input type="hidden" name="upload" value="1">                      
<input type="hidden" name="pn" value="<?php echo $pn; ?>">                      
<input type="hidden" class="special-member" name="member_id" value="">                      
<input type="file" name="ufile" id="ufile" required>
</form>

<form action="<?php echo $admin; ?>manage-banners" class="general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">
<input type="hidden" name="gh" value="1"> 
<div class="search-dates">

<div class="col-md-6">
<label for="new_name">Description</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
<input type="text" name="new_name" id="new_name" class="form-control" placeholder="Banner&#039;s Description" value="<?php check_inputted("new_name", $new_name); ?>">
</div>
</div>

<div class="col-md-3">
<label for="no_of_rows">No. of Rows</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-list" aria-hidden="true"></i></span>
<input type="number" name="no_of_rows" id="no_of_rows" class="form-control only-no" placeholder="No. of rows" value="<?php check_inputted("no_of_rows", $per_view); ?>">
</div>
</div>

<div class="col-md-3">
<br />
<button type="submit" class="btn gen-btn"><i class="fa fa-search"></i> Search</button>
</div>

</div>
</form>

<?php
$d = 0;
if($count > 0){
?>
<form action="<?php echo $admin; ?>manage-banners/" class="general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data" style="overflow-x:auto;">
<input type="hidden" name="pn" value="<?php echo $pn; ?>"> 
<input type="hidden" name="gh" value="1"> 
<input type="hidden" name="delete" value="1"> 
<table class="table table-striped table-hover">
<thead>
<tr class="gen-title">
<th>ID #</th>
<th style="width:50px">Picture</th>
<th>Description</th>
<th>Order ID</th>
<th>Details</th>
<th>Image</th>
<th style="width:30px;"><input type="checkbox" name="sel_all" id="delG" class="sel-group" value=""></th>
</tr>
</thead>
<tbody>
<?php
while($row = fetch_data($result)){
$get_id = $row["id"];
$description = $row["description"];
$order_id = $row["order_id"];

$file_array = glob("../images/banners/{$get_id}pic*.*");
$file_name = ($file_array)?"images/" . $file_array[0]:"images/member.jpg";
?>
<tr>
<td><?php echo $get_id; ?></td>
<td><img src="<?php echo $file_name; ?>" class="img-rounded"></td>
<td><?php echo $description; ?></td>
<td><?php echo $order_id; ?></td>
<td><a href="<?php echo $admin; ?>manage-banners/edit/<?php echo $get_id; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link" title="Edit banner #<?php echo $get_id; ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>
<td><label for="ufile" id="<?php echo $get_id; ?>" class="btn gen-btn change-picture-label" title="Change banner #<?php echo $get_id; ?>. Format: .jpg, .gif, .png, .jpeg, Not more than 5MB"><i class="fa fa-upload" aria-hidden="true"></i> Change</label></td>
<td><input type="checkbox" name="del[<?php echo $d; ?>]" id="del<?php echo $d; ?>" class="delG" value="<?php echo $get_id; ?>"></td>
</tr>
<?php 
$d++;
}
?>
<tr><td colspan="7"><input class="sub-del" type="submit" value=" "><button type="button" class="btn del-btn gen-btn float-right"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete selected banner(s)</button></td></tr>
</tbody>
</table>
</form>
<?php
echo ($last_page>1)?"<div class=\"page-nos\">" . $center_pages . "</div>":"";
}else{
echo "<div class='not-success'>No banners found.</div>";
}

}


if((!empty($add) || !empty($edit)) && $error == 1){

$description = $order_id = "";
if(!empty($edit)){
$result = $db->select("banners", "WHERE id='$edit'", "*", "");
if(count_rows($result) == 1){
$row = fetch_data($result);
$description = $row["description"];
$order_id = $row["order_id"];
}
}

$field_name = (!empty($edit))?"edit":"add";
$field_value = (!empty($edit))?$edit:1;
$action_title = (!empty($edit))?"Edit":"Add New";
?>

<div><a href="<?php echo $admin; ?>manage-banners/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back to banners list</a></div>

<div class="page-title"><?php echo $action_title; ?> Banner&#039;s Details</div>

<form action="<?php echo $admin; ?>manage-banners/" class="general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data" style="border:1px solid #eee;">
<input type="hidden" name="gh" value="1"> 
<input type="hidden" name="<?php echo $field_name; ?>" value="<?php echo $field_value; ?>"> 
<?php if(!empty($edit)){ ?>
<input type="hidden" name="pn" value="<?php echo $pn; ?>"> 
<?php
}
?>

<div class="col-sm-6">
<label for="description">Description</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-user"></i></span>
<input type="text" name="description" id="description" class="form-control" placeholder="Description of the banner" value="<?php check_inputted("description", $description); ?>" required>
</div>
</div>

<div class="col-sm-6">
<label for="order_id">Order ID</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-sort"></i></span>
<input type="text" name="order_id" id="order_id" class="form-control only-no" placeholder="Order ID of the banner. E.g. 2" value="<?php check_inputted("order_id", $order_id); ?>" required>
</div>
</div>
                     
<div class="submit-div col-sm-12">
<button class="btn gen-btn float-right" name="update"><i class="fa fa-upload"></i> Save</button>
</div>

</form>
<?php
}
?>

<script>
<!--
var conf_text = "banner";
//-->
</script>

<script src="js/general-form.js"></script>

<?php  if(!isset($_REQUEST["gh"])){?>

</div>
</div>

</div>
<?php require_once("../includes/portal-footer.php"); } ?>