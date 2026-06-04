<?php if(!isset($_REQUEST["gh"])){ include_once("../includes/admin-header.php"); 
}else{ 
include_once("../includes/gen-header.php");
} ?>

<?php
$view = nr_input("view");
$edit = nr_input("edit");
$add = nr_input("add");
$pn = nr_input("pn");

$event_date = tp_input("event_date");
$event_location = tp_input("event_location");
$event_title = tp_input("event_title");
$event_link = tp_input("event_link");
$event_details = tp_input("event_details");

////////////// Add or Update Schedule //////////////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && (!empty($add) || !empty($edit)) && !empty($event_date) && !empty($event_location) && !empty($event_title) && !empty($event_link) && !empty($event_details)){

$data_array = array(
"event_date" => $event_date,
"event_location" => $event_location,
"event_title" => $event_title,
"event_link" => $event_link,
"event_details" => $event_details,
"added_by" => $id,
"date_added" => $date_time
);

if(!empty($add)){
$act = $db->insert2($data_array, "events_videos");
}else if(!empty($edit)){
$act = $db->update($data_array, "events_videos", "id = '$edit'");
}

if($act){

$error = 0;

$_SESSION["msg"] = "<div class='success'>Video successfully saved.</div>";
redirect("{$directory}{$admin}events-videos/");
}else{
echo "<div class='not-success'>Error occured.</div>";
}

}

if($_SERVER['REQUEST_METHOD'] == "POST" && (!empty($add) || !empty($edit)) && (empty($event_date) || empty($event_location) || empty($event_title) || empty($event_link) || empty($event_details))){
echo "<div class='not-success'>Not submitted! All the fields are required.</div>";
}

/////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST["delete"]) && isset($_POST["del"])){
$i = 0;
if(is_array($_POST["del"])){
foreach ($_POST["del"] as $k => $c) {
if($c != ""){ 
$c = testQty($c);
$act = $db->delete("events_videos", "id = '$c'");	
$i++;			
}else{
continue;
}
}

if($act){
echo "<div class='success'>{$i} video(s) successfully deleted.</div>";
}else{
echo "<div class='not-success'>Error. Unable to delete video(s).</div>";
}

}else{
echo "<div class='not-success'>Atleast one video must be selected.</div>";
}
}

////////////////////////////////////////////////////******************************//////////////

$result = $db->select("events_videos", "", "*", "");

$per_view = 20;
$page_link = "{$admin}events-videos/pn/";
$link_suffix = "/";
$style_class = "general-link";
page_numbers();

$offset = ($per_view * $pn) - $per_view;

$result = $db->select("events_videos", "", "*", "ORDER BY id DESC", "LIMIT {$offset},{$per_view}");

if(isset($_SESSION["msg"]) && !empty($_SESSION["msg"]) && empty($add) && empty($edit)){
echo $_SESSION["msg"];
unset($_SESSION["msg"]);
}
?>

<?php
if(empty($view) && (empty($edit)||(!empty($edit)&&$error==0)) && (empty($add)||(!empty($add)&&$error==0)) ){
?>

<div class="page-title">Past Events Videos <a href="<?php echo $admin; ?>events-videos/add/1/" class="btn gen-btn float-right">Add New Video</a></div>

<?php
$d = 0;
if(count_rows($result) > 0){
?>
<form action="<?php echo $admin; ?>events-videos/" class="general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data" style="overflow-x:auto;">
<input type="hidden" name="pn" value="<?php echo $pn; ?>"> 
<input type="hidden" name="gh" value="1"> 
<input type="hidden" name="delete" value="1"> 
<table class="table table-striped table-hover">
<thead>
<tr class="gen-title">
<th>Date</th>
<th>#ID</th>
<th>Title</th>
<th>Location</th>
<th>Option</th>
<th>Action</th>
<th style="width:30px;"><input type="checkbox" name="sel_all" id="delG" class="sel-group" value=""></th>
</tr>
</thead>
<tbody>
<?php
while($row = fetch_data($result)){
$event_date = min_sub_date($row["event_date"]);
$video_id = $row["id"];
$event_date = min_sub_date($row["event_date"]);
$event_location = $row["event_location"];
$event_title = $row["event_title"];
$event_link = $row["event_link"];
$event_details = $row["event_details"];
?>
<tr>
<td><?php echo $event_date; ?></td>
<td><?php echo $video_id; ?></td>
<td><?php echo $event_title; ?></td>
<td><?php echo $event_location; ?></td>
<td><a href="<?php echo $admin; ?>events-videos/view/<?php echo $video_id; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link" title="View video #<?php echo $video_id; ?>"><i class="fa fa-eye" aria-hidden="true"></i> View</a></td>
<td><a href="<?php echo $admin; ?>events-videos/edit/<?php echo $video_id; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn" title="Edit video #<?php echo $video_id; ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></td>
<td><input type="checkbox" name="del[<?php echo $d; ?>]" id="del<?php echo $d; ?>" class="delG" value="<?php echo $video_id; ?>"></td>
</tr>
<?php 
$d++;
}
?>
<tr><td colspan="7"><input class="sub-del" type="submit" value=" "><button type="button" class="btn del-btn gen-btn float-right"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete selected video(s)</button></td></tr>
</tbody>
</table>
</form>
<?php
echo ($last_page>1)?"<div class=\"page-nos\">" . $center_pages . "</div>":"";
}else{
echo "<div class='not-success'>No videos found at the moment.</div>";
}

}


//=======================View Event Details==============================//
if(!empty($view)){
$result = $db->select("events_videos", "WHERE id='$view'", "*", "");

if(count_rows($result) == 1){
$row = fetch_data($result);
$event_date = sub_date($row["event_date"]);
$event_location = $row["event_location"];
$event_title = $row["event_title"];
$event_link = $row["event_link"];
$event_details = $row["event_details"];
$added_by = $row["added_by"];
$date_added = full_date($row["date_added"]);

$poster_name = in_table("name","admin_data","WHERE id = '{$added_by}'","name");
$poster_email = in_table("email","admin_data","WHERE id = '{$added_by}'","email");
?>

<style>
<!--
div table thead tr th, div table tr th, div table tbody tr td, div table tr td{
text-align:left !important;
}
-->
</style>

<div class="reply-content-wrapper">

<div><a href="<?php echo $admin; ?>events-videos/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back to eveent videos</a></div>

<div class="view-wrapper ">

<div class="view-header ">
<div class="header-img"><img src="images/post.jpg" ></div>
<div class="header-content">
<div class="view-title"><?php echo "{$event_title} on {$event_date} (#{$view})"; ?></div>
<div class="view-title-details">Posted by: <?php echo "{$poster_name} ({$poster_email})"; ?></div>
</div>
</div>

<div class="view-content">


<div class="col-md-8">

<iframe src="<?php echo $event_link; ?>" frameborder="0" style="border:0px; height:400px;width:100%;" allowfullscreen></iframe>

</div>
<div class="col-md-4">
<h3>Description</h3>
<table class="table table-striped table-hover">
<tbody>
<tr><td><b>Location</b></td><td><?php echo $event_location; ?></td></tr>
<tr><td><b>Details</b></td><td><?php echo html_entity_decode($event_details); ?></td></tr>
<tr><td><b>Posted on</b></td><td><?php echo $date_added; ?></td></tr>
</tbody>
</table>

<div><a href="<?php echo $admin; ?>events-videos/edit/<?php echo $view; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn float-right" title="Edit item #<?php echo $item_id; ?>"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a></div>

</div>


</div>

</div>
</div>

<?php
}else{
echo "<div class='not-success'>No items found at the moment.</div>";
}
}


if((!empty($add) || !empty($edit)) && $error == 1){

$id = $title = $link = "";
if(!empty($edit)){
$result = $db->select("events_videos", "WHERE id='$edit'", "*", "");
if(count_rows($result) == 1){
$row = fetch_data($result);
$id = $row["id"];
$event_date = ($row["event_date"] != "0000-00-00")?$row["event_date"]:"";
$event_location = $row["event_location"];
$event_title = $row["event_title"];
$event_link = $row["event_link"];
$event_details = $row["event_details"];
}
}

$field_name = (!empty($edit))?"edit":"add";
$field_value = (!empty($edit))?$id:1;
$action_title = (!empty($edit))?"Edit":"Add New";
?>

<div><a href="<?php echo $admin; ?>events-videos/pn/<?php echo $pn; ?>/" class="btn gen-btn"><i class="fa fa-arrow-left"></i> Back to event videos</a></div>

<div class="page-title"><?php echo $action_title; ?> Video</div>

<form action="<?php echo $admin; ?>events-videos/" method="post" runat="server" autocomplete="off" enctype="multipart/form-data" style="border:1px solid #eee;">
<input type="hidden" name="<?php echo $field_name; ?>" value="<?php echo $field_value; ?>"> 
<?php if(!empty($edit)){ ?>
<input type="hidden" name="pn" value="<?php echo $pn; ?>"> 
<?php
}
?>

<div class="col-sm-6">
<label for="event_date">Date</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
<input type="text" name="event_date" id="event_date" class="form-control gen-date" onfocus="javascript: $(this).blur();" placeholder="YYYY-MM-DD" value="<?php check_inputted("event_date", $event_date); ?>" required>
</div>
</div>

<div class="col-sm-6">
<label for="event_location">Location</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
<input type="text" name="event_location" id="event_location" class="form-control" placeholder="Location of the event" value="<?php check_inputted("event_location", $event_location); ?>" required>
</div>
</div>

<div class="col-sm-6">
<label for="event_title">Title</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
<input type="text" name="event_title" id="event_title" class="form-control" placeholder="Title of the event" value="<?php check_inputted("event_title", $event_title); ?>" required>
</div>
</div>

<div class="col-sm-6">
<label for="event_link">YouTube Link (WITHOUT the iframe)</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-globe"></i></span>
<input type="text" name="event_link" id="event_link" class="form-control" placeholder="The YouTube link" value="<?php check_inputted("event_link", $event_link); ?>" required>
</div>
</div>

<div class="col-sm-12">
<label for="event_details">Details</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
<textarea name="event_details" id="event_details" class="ckeditor" placeholder="Type in the details" required style="overflow: auto; width:100%"><?php check_inputted("event_details", $event_details); ?></textarea>
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
var conf_text = "video";
//-->
</script>

<script src="js/text_plugin/ckeditor.js"></script>
<script src="js/general-form.js"></script>

<?php  if(!isset($_REQUEST["gh"])){?>

</div>
</div>

</div>

<?php require_once("../includes/portal-footer.php"); } ?>