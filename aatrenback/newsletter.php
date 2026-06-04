<?php if(!isset($_REQUEST["gh"])){ include_once("../includes/admin-header.php"); 
}else{ 
include_once("../includes/gen-header.php");
} ?>

<?php
/////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST["delete"]) && isset($_POST["del"])){
$i = 0;
if(is_array($_POST["del"])){
foreach ($_POST["del"] as $k => $c) {
if($c != ""){ 
$c = testQty($c);
$act = $db->delete("messages", "id = '$c'");	
$i++;			
}else{
continue;
}
}

if($act){
echo "<div class='success'>{$i} message(s) successfully deleted.</div>";
}else{
echo "<div class='not-success'>Error. Unable to delete message(s).</div>";
}

}else{
echo "<div class='not-success'>Atleast one field must be selected.</div>";
}
}

////////////////////////////////////////////////////******************************//////////////

$result = $db->select("messages", "", "*", "ORDER BY id DESC");

$per_view = 20;
$page_link = "{$admin}newsletter/pn/";
$link_suffix = "/";
$style_class = "general-link";
page_numbers();
?>

<?php
if(!isset($_REQUEST["view"])){
?>

<div class="page-title">Newsletters</div>

<?php
$d = 0;

$offset = ($per_view * $pn) - $per_view;

$result = $db->select("messages", "", "*", "ORDER BY id DESC", "LIMIT {$offset},{$per_view}");

if(count_rows($result) > 0){
?>
<form action="<?php echo $admin; ?>newsletter/" class="general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data" style="overflow-x:auto;">
<input type="hidden" name="pn" value="<?php echo $pn; ?>"> 
<input type="hidden" name="gh" value="1"> 
<input type="hidden" name="delete" value="1"> 
<table class="table table-striped table-hover">
<thead>
<tr class="gen-title">
<th>S/N</th>
<th>Subject</th>
<th>Date Sent</th>
<th>Action</th>
<th style="width:30px;"><input type="checkbox" name="sel_all" id="delG" class="sel-group" value=""></th>
</tr>
</thead>
<tbody>
<?php
while($row = fetch_data($result)){
$id = $row["id"];
$subject = $row["subject"];
$date = min_full_date($row["date_time"]);
?>
<tr>
<td><?php echo $c; ?></td>
<td><?php echo $subject; ?></td>
<td><?php echo $date; ?></td>
<td><a href="<?php echo $admin; ?>newsletter/view/<?php echo $id; ?>/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link" title="View message #<?php echo $id; ?>"><i class="fa fa-eye" aria-hidden="true"></i> View</a></td>
<td><input type="checkbox" name="del[<?php echo $d; ?>]" id="del<?php echo $d; ?>" class="delG" value="<?php echo $id; ?>"></td>
</tr>
<?php 
$d++;
}
?>
<tr><td colspan="5"><input class="sub-del" type="submit" value=" "><button type="button" class="btn del-btn gen-btn float-right"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete selected message(s)</button></td></tr>
</tbody>
</table>
</form>
<?php
echo ($last_page>1)?"<div class=\"page-nos\">" . $center_pages . "</div>":"";
}else{
echo "<div class='not-success'>No messages found at the moment.</div>";
}

}

//=======================View Post==============================//
if(isset($_REQUEST["view"])){
$view = testQty($_REQUEST["view"]);
$result = $db->select("messages", "WHERE id='$view'", "*", "");

if(count_rows($result) == 1){
$row = fetch_data($result);
$subject = $row["subject"];
$message = $row["message"];
$date = min_full_date($row["date_time"]);
?>

<div class="reply-content-wrapper ">

<div><a href="<?php echo $admin; ?>newsletter/pn/<?php echo $pn; ?>/" class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back to sales letter</a></div>

<div class="view-wrapper ">

<div class="view-header ">
<div class="header-img"><img src="images/post.jpg" ></div>
<div class="header-content">
<div class="view-title"><?php echo $subject; ?></div>
<div class="view-title-details">To: <?php echo $date; ?></div>
</div>
</div>

<div class="view-content">
<?php echo html_entity_decode($message); ?>
</div>

</div>
</div>

<?php
}else{
echo "<div class='not-success'>No messages found at the moment.</div>";
}
}
?>

<script>
<!--
var conf_text = "message";
//-->
</script>

<script src="js/general-form.js"></script>

<?php  if(!isset($_REQUEST["gh"])){?>

</div>
</div>

</div>

<?php require_once("../includes/portal-footer.php"); } ?>