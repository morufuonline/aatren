<?php if(!isset($_REQUEST["gh"])){ include_once("../includes/admin-header.php"); 
}else{ 
include_once("../includes/gen-header.php");
} ?>

<?php
$edit = nr_input("edit");
$add = nr_input("add");
$pn = nr_input("pn");

$cat_type = np_input("cat_type");
$cat_name = tp_input("cat_name");
$cat_slug = tp_input("cat_slug");
$cat_order = np_input("cat_order");
$cat_order = (!empty($cat_order))?$cat_order:0;
$home_display = np_input("home_display");
$home_display = (!empty($home_display))?$home_display:0;

////////////// Add or Update Category //////////////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && (!empty($add) || !empty($edit)) && !empty($cat_type) && !empty($cat_name) && !empty($cat_slug)){

$cat_slug_det = in_table("COUNT(id) AS Total","items_categories","WHERE cat_slug = '{$cat_slug}'","Total");
$cat_slug_det_id = in_table("id","items_categories","WHERE cat_slug = '{$cat_slug}'","id");
$cat_slug_det_name = in_table("cat_name","items_categories","WHERE cat_slug = '{$cat_slug}'","cat_name");

///////////////////
if(!empty($cat_slug_det) && !empty($add)){

echo "<div class='not-success'>This category slug ({$cat_slug}) already exists for {$cat_slug_det_name}.</div>";

}else if(!empty($cat_slug_det) && !empty($edit) && $cat_slug_det_id != $edit){

echo "<div class='not-success'>This category slug ({$cat_slug}) already exists for {$cat_slug_det_name}.</div>";

}else{

$data_array = array(
"cat_type" => $cat_type,
"cat_name" => $cat_name,
"cat_slug" => $cat_slug,
"cat_order" => $cat_order,
"home_display" => $home_display
);

if(!empty($add)){
$act = $db->insert2($data_array, "items_categories");
}else if(!empty($edit)){
$act = $db->update($data_array, "items_categories", "id = '$edit'");
}

if($act){

$error = 0;

echo "<div class='success'>Category successfully saved.</div>";
}else{
echo "<div class='not-success'>Error occured.</div>";
}

}

}

if($_SERVER['REQUEST_METHOD'] == "POST" && (!empty($add) || !empty($edit)) && (empty($cat_type) || empty($cat_name) || empty($cat_slug))){
echo "<div class='not-success'>Not submitted! All the fields are required.</div>";
}

/////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST["delete"]) && isset($_POST["del"])){
$i = 0;
if(is_array($_POST["del"])){
foreach ($_POST["del"] as $k => $c) {
if($c != ""){ 
$c = testQty($c);
$act = $db->delete("items_categories", "id = '$c'");	
$i++;			
}else{
continue;
}
}

if($act){
echo "<div class='success'>{$i} category(s) successfully deleted.</div>";
}else{
echo "<div class='not-success'>Error. Unable to delete category(s).</div>";
}

}else{
echo "<div class='not-success'>Atleast one category must be selected.</div>";
}
}

////////////////////////////////////////////////////******************************//////////////

$result = $db->select("items_categories", "", "*", "ORDER BY id DESC");

$per_view = 20;
$page_link = "{$admin}manage-categories/pn/";
$link_suffix = "/";
$style_class = "general-link";
page_numbers();
?>

<?php
if((empty($edit)||(!empty($edit)&&$error==0)) && (empty($add)||(!empty($add)&&$error==0)) ){
?>

<div class="page-title">Manage Categories <a href="<?php echo $admin; ?>manage-categories/add/1/" class="btn gen-btn general-link float-right">New Category</a></div>

<?php
$d = 0;

$offset = ($per_view * $pn) - $per_view;

$result = $db->select("items_categories", "", "*", "ORDER BY id DESC", "LIMIT {$offset},{$per_view}");

if(count_rows($result) > 0){
?>
<form action="<?php echo $admin; ?>manage-categories/" class="general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data" style="overflow-x:auto;">
<input type="hidden" name="pn" value="<?php echo $pn; ?>"> 
<input type="hidden" name="gh" value="1"> 
<input type="hidden" name="delete" value="1"> 
<table class="table table-striped table-hover">
<thead>
<tr class="gen-title">
<th>#ID</th>
<th>Type</th>
<th>Name</th>
<th>Slug</th>
<th>Order</th>
<th>Home Display</th>
<th>Items Uploaded</th>
<th>Action</th>
<th style="width:30px;"><input type="checkbox" name="sel_all" id="delG" class="sel-group" value=""></th>
</tr>
</thead>
<tbody>
<?php
while($row = fetch_data($result)){
$cat_id = $row["id"];
$cat_type = $row["cat_type"];
$cat_count = in_table("COUNT(id) AS Total","items","WHERE cat_id = '{$cat_id}'","Total");
$cat_type_title = in_table("type","category_types","WHERE id = '{$cat_type}'","type");
$cat_name = $row["cat_name"];
$cat_slug = $row["cat_slug"]; 
$cat_order = $row["cat_order"]; 
$home_display = (!empty($row["home_display"]))?"Yes":"No";
?>
<tr>
<td><?php echo $cat_id; ?></td>
<td><?php echo $cat_type_title; ?></td>
<td><?php echo $cat_name; ?></td>
<td><?php echo $cat_slug; ?></td>
<td><?php echo $cat_order; ?></td>
<td><?php echo $home_display; ?></td>
<td><?php echo formatQty($cat_count); ?></td>
<td><a href="<?php echo $admin; ?>manage-categories/edit/<?php echo $cat_id; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link" title="Edit category #<?php echo $cat_id; ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>
<td><!--<input type="checkbox" name="del[<?php echo $d; ?>]" id="del<?php echo $d; ?>" class="delG" value="<?php echo $cat_id; ?>">--></td>
</tr>
<?php 
$d++;
}
?>
<!--<tr><td colspan="9"><input class="sub-del" type="submit" value=" "><button type="button" class="btn del-btn gen-btn float-right"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete selected category(s)</button></td></tr>-->
</tbody>
</table>
</form>
<?php
echo ($last_page>1)?"<div class=\"page-nos\">" . $center_pages . "</div>":"";
}else{
echo "<div class='not-success'>No categories found at the moment.</div>";
}

}

if((!empty($add) || !empty($edit)) && $error == 1){

$cat_id = $cat_type = $cat_name = $cat_slug = $home_display = "";
if(!empty($edit)){
$result = $db->select("items_categories", "WHERE id='$edit'", "*", "");
if(count_rows($result) == 1){
$row = fetch_data($result);
$cat_id = $row["id"];
$cat_type = $row["cat_type"];
$cat_name = $row["cat_name"];
$cat_slug = $row["cat_slug"];
$cat_order = $row["cat_order"];
$home_display = $row["home_display"];
}
}

$field_name = (!empty($edit))?"edit":"add";
$field_value = (!empty($edit))?$cat_id:1;
$action_title = (!empty($edit))?"Edit":"Add New";
?>

<div><a href="<?php echo $admin; ?>manage-categories/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back to categories list</a></div>

<div class="page-title"><?php echo $action_title; ?> Category</div>

<form action="<?php echo $admin; ?>manage-categories/" class="general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data" style="border:1px solid #eee;">
<input type="hidden" name="gh" value="1"> 
<input type="hidden" name="<?php echo $field_name; ?>" value="<?php echo $field_value; ?>"> 
<?php if(!empty($edit)){ ?>
<input type="hidden" name="pn" value="<?php echo $pn; ?>"> 
<?php
}
?>

<div class="col-sm-6">
<label for="cat_type">Category</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-tag"></i></span>
<select name="cat_type" id="cat_type" title="Select a category type" class="form-control js-example-basic-single" style="width:100%" required>
<option value="">**Select a category type**</option>
<?php 
$result2 = $db->select("category_types", "", "DISTINCT *", "ORDER BY type ASC");
if(count_rows($result2) > 0){
while($row2 = fetch_data($result2)){
$cat_id2 = $row2["id"];
$cat_type2 = $row2["type"];
echo "<option value='{$cat_id2}'";
check_selected("cat_type", $cat_id2, $cat_type); 
echo ">{$cat_type2}</option>";
}
}
?>
</select>
</div>
</div>

<div class="col-sm-6">
<label for="cat_name">Name</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-tag"></i></span>
<input type="text" name="cat_name" id="cat_name" class="form-control" placeholder="Name of the category" value="<?php check_inputted("cat_name", $cat_name); ?>" required>
</div>
</div>

<div class="col-sm-6">
<label for="cat_slug">Slug</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-globe"></i></span>
<input type="text" name="cat_slug" id="cat_slug" class="form-control" placeholder="Slug of the category. E.g. phone-accessories" value="<?php check_inputted("cat_slug", $cat_slug); ?>" required>
</div>
</div>

<div class="col-sm-6">
<label for="cat_order">Order</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-sort"></i></span>
<input type="number" name="cat_order" id="cat_order" class="form-control" placeholder="Order of the category. E.g. 2" value="<?php check_inputted("cat_order", $cat_order); ?>" required>
</div>
</div>

<div class="col-sm-6">
<label for="home_display">
<p>&nbsp;</p>
<div class="form-group input-group">
<span class="input-group-addon"><input type="checkbox" name="home_display" id="home_display" value="1" <?php check_checked("home_display", "1", $home_display); ?>></span>
&nbsp;&nbsp;&nbsp;Add to Home page
</div>
</label>
</div>
                     
<div class="submit-div col-sm-6">
<button class="btn gen-btn float-right" name="update"><i class="fa fa-upload"></i> Save</button>
</div>

</form>
<?php
}
?>

<script>
<!--
var conf_text = "category";
//-->
</script>

<script src="js/general-form.js"></script>

<?php  if(!isset($_REQUEST["gh"])){?>

</div>
</div>

</div>

<?php require_once("../includes/portal-footer.php"); } ?>