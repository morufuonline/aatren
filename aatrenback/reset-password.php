<?php if(!isset($_REQUEST["gh"])){ include_once("../includes/admin-header.php"); 
}else{ 
include_once("../includes/gen-header.php");
} ?>

<?php
$password = tp_input("password");
$password2 = $password;
$conf_password = tp_input("conf_password");

if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($password) && !empty($conf_password) && $password == $conf_password && strlen($password) >= 5){

$password = sha1($password);

$data_array = array(
"password" => $password
);
$act = $db->update($data_array, "admin_data", "email = '$user_email'");

if($act){
$error = 0;
echo "<div class='success'>Password successfully updated.</div>";
}else{
echo "<div class='not-success'>Error occured.</div>";
}

}

if($_SERVER['REQUEST_METHOD'] == "POST" && (empty($password2) or empty($conf_password))){
echo "<div class='not-success'>Not updated! All the fields are required.</div>";
}else if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($password2) && $password2 != $conf_password){
echo "<div class='not-success'>Not updated! Passwords do not match.</div>";
}else if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($password2) && strlen($password2) < 5){
echo "<div class='not-success'>Not updated! Password must be at least 5 characters.</div>";
}
?>

<div class="page-title">Change Your Password</div>

<form action="<?php echo $admin; ?>reset-password/" class="general-form" id="form-div" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">  
<input type="hidden" name="gh" value="1">

<div>
<label for="password">New Password (atleast 5 characters)</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-lock"></i></span>
<input type="password" name="password" id="password" class="form-control" placeholder="Your password for login" required value="<?php check_inputted("password"); ?>">
</div>
</div>

<div>
<label for="conf_pass">Retype Password</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-lock"></i></span>
<input type="password" name="conf_password" id="conf_password" class="form-control" placeholder="Retype your password" required value="<?php check_inputted("conf_password"); ?>">
</div>
</div>
                     
<div class="submit-div">
<button class="btn gen-btn float-right" name="update"><i class="fa fa-upload"></i> Update</button>
</div>
</form>

<script src="js/general-form.js"></script>

<?php if(!isset($_REQUEST["gh"])){ ?>

</div>
</div>

</div>
<?php require_once("../includes/portal-footer.php"); } ?>