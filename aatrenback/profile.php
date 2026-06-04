<?php if(!isset($_REQUEST["gh"])){ require_once("../includes/admin-header.php"); 
}else{ 
require_once("../includes/gen-header.php");
require_once("../includes/resize-image.php");
} ?>

<?php
$error = 1;
$name = tp_input("name");

$profile = tp_input("profile");
$upload = tp_input("upload");
$edit = nr_input("edit");

////////////// Upload image //////////////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($upload) && !empty($_FILES["ufile"]["tmp_name"])){ 

upload_single_image("ufile", "{$id}pic", "../images/admin/", "250", "250");

echo "<div class='success'>Picture successfully updated.</div>";
}

////////////// Update Profile //////////////////////////////
if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($profile) && !empty($name)){

$data_array = array(
"name" => $name
);
$act = $db->update($data_array, "admin_data", "email = '$user_email'");
$_SESSION["name"] = $name;

if($act){

$error = 0;

echo "<div class='success'>Profile successfully updated.</div>";
}else{
echo "<div class='not-success'>Error occured.</div>";
}

}

if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($profile) && empty($name)){
echo "<div class='not-success'>Not submitted! All the fields are required.</div>";
}
?>

<?php if(empty($edit) or (!empty($edit) && $error == 0)){ 
?>

<div class="page-title">My Profile</div>

<?php
$result = $db->select("admin_data", "Where email = '{$user_email}'", "*", "");

if(count_rows($result) == 1){
$row = fetch_data($result);
$id = $row["id"];
$user_id = admin_id($id);
$name = $row["name"];
$email = $row["email"];
$date_time = full_date($row["date_time"]);
?>
<style>
<!--
div table thead tr th, div table tr th, div table tbody tr td, div table tr td{
text-align:left !important;
}
-->
</style>
<table class="table table-striped table-hover">

<tr><td style="width:160px;" class="gen-title">
<?php
$file_array = glob("../images/admin/{$id}pic*.*");
$file_name = ($file_array)?"images/" . $file_array[0]:"images/post.jpg";
?>
<img src="<?php echo $file_name; ?>" >
</td><td>
<form action="<?php echo $admin; ?>profile/" class="img-form general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">  
<input type="hidden" name="gh" value="1">
<input type="hidden" name="upload" value="1">                      
<p><b>Format: </b></p>
<p>.jpg, .gif, .png, .jpeg, Not more than 5MB<br /><br /></p>
<input type="file" name="ufile" id="ufile" required>
<label for="ufile" id="pic-label" class="btn gen-btn" ><i class="fa fa-upload" aria-hidden="true"></i> Change picture</label>
</form></td><td>
<p><b>User ID:</b> <?php echo $user_id; ?></p>
<p><b>Last Login:</b> <?php echo $date_time; ?></p>
</td></tr>
<tr><td class="gen-title"><i class="fa fa-user" aria-hidden="true"></i> Full Name</td><td colspan="2"><?php echo $name; ?></td></tr>
<tr><td class="gen-title"><i class="fa fa-envelope" aria-hidden="true"></i> Email</td><td colspan="2"><?php echo $email; ?></td></tr>
</table>
<div class="bottom-edit"><a href="<?php echo $admin; ?>profile/edit/<?php echo $id; ?>/" class="btn gen-btn general-link float-right">Edit Profile</a></div>
<?php
}
} 
?>

<?php if(!empty($edit) && $error == 1){ 
$edit = testQty($_REQUEST["edit"]);
$result = $db->select("admin_data", "Where id = '{$edit}'", "*", "");

if(count_rows($result) == 1){
$row = fetch_data($result);
$name = $row["name"];
?>

<div><a href="<?php echo $admin; ?>profile/" class="btn gen-btn general-link"><i class="fa fa-arrow-left"></i> Back to profile</a></div>

<form action="<?php echo $admin; ?>profile/" class="general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">  
<div class="gen-title">Edit Your Profile</div>    
<input type="hidden" name="gh" value="1">
<input type="hidden" name="edit" value="<?php echo $edit; ?>">
<input type="hidden" name="profile" value="1">
     
<div class="col-sm-12">
<label for="name">Full Nam</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-user"></i></span>
<input type="text" name="name" id="name" class="form-control" placeholder="Type your full name" value="<?php check_inputted("name", $name); ?>" required>
</div>
</div>
                     
<div class="submit-div col-sm-12">
<button class="btn gen-btn float-right" name="update"><i class="fa fa-upload"></i> Update</button>
</div>
</form>
<?php
}
} 
?>

<script src="js/general-form.js"></script>

<?php  if(!isset($_REQUEST["gh"])){?>

</div>
</div>

</div>

<?php require_once("../includes/portal-footer.php"); } ?>