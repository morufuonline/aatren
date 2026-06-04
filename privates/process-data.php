<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
ini_set('session.gc_maxlifetime', 86400);
session_start();

require_once("../classes/db-class.php");
require_once("../includes/functions.php");
require_once("../includes/resize-image.php");

$name = tp_input("name");
$email = tp_input("email");
$newsletter = tp_input("newsletter");

$parameter = tp_input("parameter");
$parameter_value = tp_input("parameter_value");

$parameter2 = tp_input("parameter2");
$parameter_value2 = tp_input("parameter_value2");

$my_item_img = tp_input("my_item_img");
$edit_my_item_img = tp_input("edit_my_item_img");
$edit_my_item_img2 = tp_input("edit_my_item_img2");
$session_item_img = tp_input("session_item_img");

$item_user_id = tp_input("item_user_id");
$item_id = tp_input("item_id"); 

$save_item = np_input("save_item"); 
$add_to_cart = np_input("add_to_cart"); 
$add_to_cart_val = np_input("add_to_cart_val"); 
$update_cart = np_input("update_cart"); 
$update_cart_val = np_input("update_cart_val"); 
$load_cart_total = np_input("load_cart_total"); 
$load_cart_items = np_input("load_cart_items"); 
$clear_cart = np_input("clear_cart"); 

///////////////Newsletter///////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($newsletter) && !empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){

$result = $db->select("newsletter", "Where email = '{$email}'", "*", "");

if(count_rows($result) < 1){

$data_array = array(
"name" => "'$name'",
"email" => "'$email'"
);

$act = $db->insert($data_array, "newsletter");

if($act){

$to = "{$email}";
$subject = "Newsletter Subscription";
$message = "<p>Thank you for signing up for subscribing for our newsletters.</p>
<p>We will keep you updated as soon as possible.</p>";
$message = message_template();
$headers = "{$gen_name} <no-reply@{$domain}>";
send_mail();

echo "<div class='success'>Newsletter subscription was successful.</div>";
}else{
echo "<div class='not-success'>Error occured.</div>";
}
}else{
echo "<div class='not-success'>Not Successful. Email already exists.</div>";
}

}

if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($newsletter) && (empty($name) || empty($email))){
echo "<div class='not-success'>Not Successful. All fields are required.</div>";
}else if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($newsletter) && !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
echo "<div class='not-success'>Not Successful. Invalid email format.</div>";
}
///////////////////////////////////////////

////////////// Upload item image //////////////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_FILES["item_img"]["tmp_name"]) && !empty($my_item_img)){ 

$file_name = $_FILES["item_img"]["name"]; 
$file_temp_name = $_FILES["item_img"]["tmp_name"];
$info   = getimagesize($file_temp_name);
$file_size = $_FILES["item_img"]["size"];
$file_error_message = $_FILES["item_img"]["error"];
$file_name_2_array = explode(".", $file_name);
$file_extension = end($file_name_2_array);

if(!isset($_SESSION["item_img"]) || empty($_SESSION["item_img"])){
foreach (glob("../images/items-temp/{$id}_item_*.*") as $filename) {
if(file_exists($filename)){
unlink($filename);
}
}	
}

if (!$file_temp_name) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Please browse for a file before clicking the upload button.</div>";
    exit();
} 
else if($file_size > 20971520) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Your file was larger than 20 Megabytes in size.</div>";
    unlink($file_temp_name);
    exit();
}
else if (!preg_match("/.(gif|GIF|jpg|JPG|png|PNG|jpeg|JPEG)$/i", $file_name) ) {
     echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Your image was not .gif, .jpg, .jpeg, or .png.</div>";
     unlink($file_temp_name);
     exit();
}
else if ($file_error_message == 1) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: An error occured while processing the file. Try again.</div>";
    exit();
}
else if ($info[2] != 1 && $info[2] != 2 && $info[2] != 3) {
     echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Your image was not .gif, .jpg, .jpeg, or .png.</div>";
     exit();
}

$file_name = "1_{$id}_item_displayed_{$ticket_id}_{$rand}.{$file_extension}";
$move_file = move_uploaded_file($file_temp_name, "../images/items-temp/{$file_name}");
if ($move_file != true) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: File not uploaded. Try again.</div>";
    unlink($file_temp_name);
    exit();
}

$target_file = "../images/items-temp/{$file_name}";
$resized_file = "../images/items-temp/{$id}_item_displayed_{$ticket_id}_{$rand}.{$file_extension}";
image_resize($target_file, $resized_file, $file_extension, "650", "500");

$resized_file = "../images/items-temp/{$id}_item_featured_{$ticket_id}_{$rand}.{$file_extension}";
image_resize($target_file, $resized_file, $file_extension, "400", "400");

unlink($target_file);

$_SESSION["item_img"][$ticket_id] = $ticket_id;
?>
<div>
<form action="<?php echo $privates; ?>process-data/" class="general-form2-<?php echo $ticket_id . $rand; ?> edit-form-<?php echo $ticket_id; ?>" name="my-item-default-<?php echo $ticket_id; ?>" id="result-<?php echo $ticket_id; ?>" lang="my-item-loading-<?php echo $ticket_id; ?>" title="edit" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">  
<input type="hidden" name="edit_my_item_img" value="1" />
<input type="hidden" name="session_item_img" value="<?php echo $ticket_id; ?>" />
<div class="new-item-pic">
<div class="item-pic-img"> 
<div class="relative-div">
<div class="item-image-wrapper result-<?php echo $ticket_id; ?>"><img src="images/<?php echo $resized_file; ?>" /></div>
<div class="fileupload fileupload-new" data-provides="fileupload">
<span class="btn btn-primary btn-file upload-padding">
<span class="fileupload-new">
<i class="fa fa-refresh" aria-hidden="true" id="my-item-default-<?php echo $ticket_id; ?>"></i> 
<i class="fa fa-spinner fa-spin fa-3x fa-fw gen-spinner" aria-hidden="true" id="my-item-loading-<?php echo $ticket_id; ?>"></i>
Change pic
</span>
<input type="file" name="edit_item_img" onchange="javascript: $('.edit-form-<?php echo $ticket_id; ?>').submit();">
</span><span class="fileupload-preview"></span>
</div></div>
</div>
<div class="item-pic-option"> 
<button type="button" class="btn btn-danger delete-item-picture" onclick="javascript: delete_file('<?php echo $privates; ?>process-data/', 'del_item_file', '<?php echo $ticket_id; ?>', 'delete-<?php echo $ticket_id; ?>', 'result-<?php echo $ticket_id; ?>');"><i class="fa fa-trash" aria-hidden="true"></i> Delete <i class="fa fa-spinner fa-spin fa-3x fa-fw gen-spinner" id="delete-<?php echo $ticket_id; ?>" aria-hidden="true"></i></button>
</div>
</div>
</form>
<script>
<!--
$("body").find( ".general-form2-<?php echo $ticket_id . $rand; ?>" ).on( "submit", function(e) {
e.preventDefault();  
var formdata = new FormData(this);
var page_url = $(this).attr("action");
var page_result = $(this).attr("id");
var this_name = $(this).attr("name");
var this_lang = $(this).attr("lang");

document.getElementById(this_name).style.display = "none";
document.getElementById(this_lang).style.display = "inline-block";
$.ajax({
url: page_url,
type: "POST",
data: formdata,
mimeTypes:"multipart/form-data",
contentType: false,
cache: false,
processData: false,
success: function(data){
document.getElementById(this_lang).style.display = "none";
document.getElementById(this_name).style.display = "inline-block";

$("." + page_result).html(data);

},error: function(){
alert("Error occured!");
document.getElementById(this_lang).style.display = "none";
document.getElementById(this_name).style.display = "inline-block";
}
});

});
//-->
</script>
</div>
<?php
}
//////////////////////////////////////////


////////////// Change item image //////////////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_FILES["edit_item_img"]["tmp_name"]) && !empty($edit_my_item_img)){ 

$file_name = $_FILES["edit_item_img"]["name"]; 
$file_temp_name = $_FILES["edit_item_img"]["tmp_name"];
$info   = getimagesize($file_temp_name);
$file_size = $_FILES["edit_item_img"]["size"];
$file_error_message = $_FILES["edit_item_img"]["error"];
$file_name_2_array = explode(".", $file_name);
$file_extension = end($file_name_2_array);

if (!$file_temp_name) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Please browse for a file before clicking the upload button.</div>";
    exit();
} 
else if($file_size > 20971520) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Your file was larger than 20 Megabytes in size.</div>";
    unlink($file_temp_name);
    exit();
}
else if (!preg_match("/.(gif|GIF|jpg|JPG|png|PNG|jpeg|JPEG)$/i", $file_name) ) {
     echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Your image was not .gif, .jpg, .jpeg, or .png.</div>";
     unlink($file_temp_name);
     exit();
}
else if ($file_error_message == 1) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: An error occured while processing the file. Try again.</div>";
    exit();
}
else if ($info[2] != 1 && $info[2] != 2 && $info[2] != 3) {
     echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Your image was not .gif, .jpg, .jpeg, or .png.</div>";
     exit();
}

if(isset($_SESSION["item_img"][$session_item_img]) && !empty($_SESSION["item_img"][$session_item_img])){
foreach (glob("../images/items-temp/{$id}_item_*_{$session_item_img}_*.*") as $filename) {
if(file_exists($filename)){
unlink($filename);
}
}	
}

$file_name = "1_{$id}_item_displayed_{$session_item_img}_{$rand}.{$file_extension}";
$move_file = move_uploaded_file($file_temp_name, "../images/items-temp/{$file_name}");
if ($move_file != true) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: File not uploaded. Try again.</div>";
    unlink($file_temp_name);
    exit();
}

$target_file = "../images/items-temp/{$file_name}";
$resized_file = "../images/items-temp/{$id}_item_displayed_{$session_item_img}_{$rand}.{$file_extension}";
image_resize($target_file, $resized_file, $file_extension, "650", "500");

$resized_file = "../images/items-temp/{$id}_item_featured_{$session_item_img}_{$rand}.{$file_extension}";
image_resize($target_file, $resized_file, $file_extension, "400", "400");

unlink($target_file);

?>
<img src="images/<?php echo $resized_file; ?>" />
<?php
}
//////////////////////////////////////////

////////////// Update Change item pictures //////////////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_FILES["edit_item_img"]["tmp_name"]) && !empty($edit_my_item_img2) && !empty($item_user_id) && !empty($item_id)){ 

$file_name = $_FILES["edit_item_img"]["name"]; 
$file_temp_name = $_FILES["edit_item_img"]["tmp_name"];
$info   = getimagesize($file_temp_name);
$file_size = $_FILES["edit_item_img"]["size"];
$file_error_message = $_FILES["edit_item_img"]["error"];
$file_name_2_array = explode(".", $file_name);
$file_extension = end($file_name_2_array);

if (!$file_temp_name) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Please browse for a file before clicking the upload button.</div>";
    exit();
} 
else if($file_size > 20971520) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Your file was larger than 20 Megabytes in size.</div>";
    unlink($file_temp_name);
    exit();
}
else if (!preg_match("/.(gif|GIF|jpg|JPG|png|PNG|jpeg|JPEG)$/i", $file_name) ) {
     echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Your image was not .gif, .jpg, .jpeg, or .png.</div>";
     unlink($file_temp_name);
     exit();
}
else if ($file_error_message == 1) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: An error occured while processing the file. Try again.</div>";
    exit();
}
else if ($info[2] != 1 && $info[2] != 2 && $info[2] != 3) {
     echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: Your image was not .gif, .jpg, .jpeg, or .png.</div>";
     exit();
}

foreach (glob("../images/items-featured/{$item_id}_{$item_user_id}_item_featured_{$session_item_img}_*.*") as $filename) {
if(file_exists($filename)){
unlink($filename);
}
}
foreach (glob("../images/items-displayed/{$item_id}_{$item_user_id}_item_displayed_{$session_item_img}_*.*") as $filename) {
if(file_exists($filename)){
unlink($filename);
}
}

$file_name = "1_{$item_id}_{$item_user_id}_item_displayed_{$session_item_img}.{$file_extension}";
$move_file = move_uploaded_file($file_temp_name, "../images/items-displayed/{$file_name}");
if ($move_file != true) {
    echo "<div class='alert alert-danger alert-dismissable fade in'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a> ERROR: File not uploaded. Try again.</div>";
    unlink($file_temp_name);
    exit();
}

$target_file = "../images/items-displayed/{$file_name}";
$resized_file = "../images/items-displayed/{$item_id}_{$item_user_id}_item_displayed_{$session_item_img}_{$rand}.{$file_extension}";
image_resize($target_file, $resized_file, $file_extension, "650", "500");

$resized_file = "../images/items-featured/{$item_id}_{$item_user_id}_item_featured_{$session_item_img}_{$rand}.{$file_extension}";
image_resize($target_file, $resized_file, $file_extension, "400", "400");

unlink($target_file);

?>
<img src="images/<?php echo $resized_file; ?>" />
<?php
}
//////////////////////////////////////////

// Delete Event File
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($parameter) && !empty($parameter_value) && $parameter == "del_item_file"){

foreach (glob("../images/items-temp/{$id}_item_*_{$parameter_value}_*.*") as $filename) {
if(file_exists($filename)){
unlink($filename);
}
}

$_SESSION["item_img"][$parameter_value] = NULL;
unset($_SESSION["item_img"][$parameter_value]);

echo 1;
}
///////////////////////////////

// Delete Changed Event File
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($parameter) && !empty($parameter_value) && $parameter == "del_item_file2"){

$file_dir_array = explode("/",$parameter_value);
$item_dir_array = explode("-",$file_dir_array[2]);
$item_dir = $item_dir_array[0];
$file_name = end($file_dir_array);
$file_name_array = explode("_",$file_name);
$item_id = $file_name_array[0];
$item_user_id = $file_name_array[1];
$item_session_no = $file_name_array[4];

foreach (glob("../images/items-featured/{$item_id}_{$item_user_id}_item_featured_{$item_session_no}_*.*") as $val) {
if(file_exists($val)){
unlink($val);
}
}
foreach (glob("../images/items-displayed/{$item_id}_{$item_user_id}_item_displayed_{$item_session_no}_*.*") as $val) {
if(file_exists($val)){
unlink($val);
}
}

echo 1;
}
///////////////////////////////

//Save Item
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($save_item)){

$result = $db->select("saved_items", "WHERE user_id='$id' AND item_id='$save_item'", "*", "");

if(count_rows($result) == 0){
$user_data_array = array(
"user_id" => "'$id'",
"item_id" => "'$save_item'",
"date_time" => "'$date_time'"
);
$db->insert($user_data_array, "saved_items");
echo "1";
}else if(count_rows($result) == 1){
$db->delete("saved_items","user_id='$id' AND item_id='$save_item'");
echo "2";
}

}
////////////////////////////////

//Add to Cart
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($add_to_cart) && !empty($add_to_cart_val)){

$result = $db->select("items", "WHERE id='$add_to_cart'", "*", "");

if(count_rows($result) == 1){

if (!isset($_SESSION["cart"][$add_to_cart])){ 
$_SESSION["cart"][$add_to_cart] = $add_to_cart_val;
}else{
$_SESSION["cart"][$add_to_cart] += $add_to_cart_val;
}

$grand_total = 0;
?>
<div class="auto-scroll">
<table class="table table-striped table-hover">
<thead><tr>
<th style="width:70px;"></th>
<th>Description</th>
<th class="align-center" style="width:50px;">Qty</th>
<th class="align-center" style="width:70px;">Amount(&#8358;)</th>
<th class="align-center" style="width:70px;">Total(&#8358;)</th>
</tr></thead>
<tbody>
<?php
////=====For Each Item=======//////
foreach($_SESSION["cart"] as $key => $val){
if($val > 0){
$result = $db->select("items", "WHERE id='$key'", "*", "");
if(count_rows($result) == 1){
$row = fetch_data($result);
$item_condition = $row["item_condition"];
$item_condition_title = in_table("type","items_conditions","WHERE id='$item_condition'","type");
$item_condition_indicator = in_table("indicator","items_conditions","WHERE id='$item_condition'","indicator");
$added_by = $row["added_by"];
$item_name = $row["item_name"];
$item_price = $row["item_price"];
$total_price = $item_price * $val;
$grand_total += $total_price;
$slide_array1 = glob("../images/items-featured/{$key}_{$added_by}_item_featured_*.*");
$file_name = $slide_array1[0];
?>
<tr>
<td><img src="images/<?php echo $file_name; ?>" ></td>
<td><?php echo "<div class=\"btn btn-{$item_condition_indicator}\">{$item_condition_title}</div> {$item_name}"; ?></td>
<td class="align-center"><?php echo formatQty($val); ?></td>
<td class="align-right"><?php echo formatNumber($item_price); ?></td>
<td class="align-right"><?php echo formatNumber($total_price); ?></td>
</tr>
<?php
}
}
}
?>
<tr>
<th class="align-right" colspan="4">Grand Total(&#8358;)</th>
<th class="align-right"><?php echo formatNumber($grand_total); ?></th>
</tr>
<tr>
<th colspan="5"><div><a href="<?php echo $privates; ?>cart/clear/1/" class="btn gen-btn" style="float:left;">Clear cart</a><a class="btn gen-btn float-right" href="<?php echo $privates; ?>checkout/">Checkout</a></div>
</th>
</tr>
</tbody></table>
</div>
<?php
///////////==========================///////

}

}
////////////////////////////////

////======================= Update Cart ====================//////
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($update_cart)){

if(isset($_SESSION["cart"][$update_cart]) && !empty($_SESSION["cart"][$update_cart])){
$_SESSION["cart"][$update_cart] = $update_cart_val;
}
if(empty($update_cart_val)){
unset($_SESSION["cart"][$update_cart]);
}

cart_items_updatable();

}

////======================= Load Cart Total ====================//////
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($load_cart_total)){
echo count_cart_items();
}
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($load_cart_items)){
echo cart_items_display();
}

////======================= Clear Cart ====================//////
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($clear_cart)){
unset($_SESSION["cart"]);
}

// Load Local Governments
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($parameter2) && !empty($parameter_value2) && $parameter2 == "load_local_govt"){
$result = $db->select("location", "WHERE state = '{$parameter_value2}'", "DISTINCT local_government", "ORDER BY local_government ASC");
if(count_rows($result) > 0){
echo "<option value=\"\">[Select a local government]</option>";
while($row = fetch_data($result)){
$local_government = $row["local_government"];
echo "<option value=\"{$local_government}\">{$local_government}</option>";
}
}
}
///////////////////////////////
?>