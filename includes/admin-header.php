<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

ini_set('session.gc_maxlifetime', 86400);
session_start();

require_once("../classes/db-class.php");
require_once("functions.php");

if(!isset($_SESSION["admin_login"])){
redirect("{$directory}{$admin}login/");
}

if(isset($_REQUEST["logout"])){
unset($_SESSION["admin_login"]);
unset($_SESSION["name"]);
unset($_SESSION["email"]);
unset($_SESSION["id"]);
$_SESSION["msg"] = "<div class='success'>You are successfully loged out. Kindly log in to continue...</div>";
redirect("{$directory}{$admin}login/");
}

$blocked = in_table("blocked","admin_data","WHERE id = '$id'","blocked");

if($blocked == 1){
unset($_SESSION["admin_login"]);
unset($_SESSION["name"]);
unset($_SESSION["email"]);
unset($_SESSION["id"]);
$_SESSION["msg"] = "<div class='not-success'>Hi {$user_name}! Your account is declined. Kindly contact the admin <a href='{$privates}contact-us/'>HERE</a>.</div>";
redirect("{$directory}{$admin}login/");
}

function detectCurrUserBrowser($a,$b,$c){
$msie = stripos($_SERVER["HTTP_USER_AGENT"], "msie") ? true : false;
if($msie){
$msiePosition = stripos($_SERVER["HTTP_USER_AGENT"], "msie");
$msiePositionNew = $msiePosition+5;
$versionNumber = substr($_SERVER["HTTP_USER_AGENT"],$msiePositionNew,1);
if($versionNumber <= $c){
echo $a;
}
else{
echo $b;
}
}
else{
echo $b;
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<base href="<?php directory(); ?>" target="_top">
<meta charset="UTF-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<title><?php echo (basename($_SERVER["PHP_SELF"]) == "index.php")?"Dashboard":title_link(basename($_SERVER["PHP_SELF"],".php")); ?> - <?php echo $full_gen_name; ?></title>
<link rel="shortcut icon" href="images/favicon.png"/>
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/font-awesome.css">
<link rel="stylesheet" href="css/portal.css">
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" href="css/select2.min.css">
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>
</head>
<?php detectCurrUserBrowser('<table width="100%"><tr><td>','',7); ?>
<body>
<div class="header-wrapper" id="bodyDiv">
<div class="header">
<a href="<?php directory(); ?>" class="logo-link"><i class="fa fa-home" aria-hidden="true" style="font-size:70px;"></i></a>
<span>

<?php
$file_array = glob("../images/admin/{$id}pic*.*");
$file_name = ($file_array)?"images/" . $file_array[0]:"images/post.jpg";
?><a href="<?php echo $admin; ?>profile/"><img src="<?php echo $file_name; ?>" ><br>
<i class="fa fa-user" aria-hidden="true"></i> <?php echo $username; ?></a>
</span>
<button class="collapse"><span></span><span></span><span></span></button>
</div>
</div>

<div class="portal-wrapper">

<div class="portal-nav portal-content">

<a href="<?php echo $admin; ?>" class="main-menu <?php echo current_page("index"); ?>"><i class="fa fa-dashboard" aria-hidden="true"></i> Dashboard</a>

<a id="setup-menu" class="main-menu <?php echo (!empty(current_page("manage-categories")) || !empty(current_page("manage-items")) || !empty(current_page("events-videos")) || !empty(current_page("manage-members")) || !empty(current_page("manage-banners")))?"main-current":""; ?>"><i class="fa fa-diamond" aria-hidden="true"></i> Setup</a>
<div id="setup-menu-div" class="sub-menu">
<a href="<?php echo $admin; ?>manage-categories/" class="<?php echo current_page("manage-categories"); ?>"><i class="fa fa-tag" aria-hidden="true"></i> Manage Categories</a>
<a href="<?php echo $admin; ?>manage-items/" class="<?php echo current_page("manage-items"); ?>"><i class="fa fa-list" aria-hidden="true"></i> Manage Items</a>
<a href="<?php echo $admin; ?>events-videos/" class="<?php echo current_page("events-videos"); ?>"><i class="fa fa-calendar" aria-hidden="true"></i> Events Videos</a>
<a href="<?php echo $admin; ?>manage-members/" class="<?php echo current_page("manage-members"); ?>"><i class="fa fa-users" aria-hidden="true"></i> Manage Members</a>
<a href="<?php echo $admin; ?>manage-banners/" class="<?php echo current_page("manage-banners"); ?>"><i class="fa fa-file-image-o" aria-hidden="true"></i> Manage Banners</a>
</div>

<a id="messages-menu" class="main-menu <?php echo (!empty(current_page("inbox")) || !empty(current_page("new-message")) || !empty(current_page("newsletter")) || !empty(current_page("newsletter-subscribers")))?"main-current":""; ?>"><i class="fa fa-envelope" aria-hidden="true"></i> Messages</a>
<div id="messages-menu-div" class="sub-menu">
<a href="<?php echo $admin; ?>inbox/" class="<?php echo current_page("inbox"); ?>"><i class="fa fa-inbox" aria-hidden="true"></i> Inbox</a>
<a href="<?php echo $admin; ?>new-message/" class="<?php echo current_page("new-message"); ?>"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Message</a>
<a href="<?php echo $admin; ?>newsletter/" class="<?php echo current_page("newsletter"); ?>"><i class="fa fa-envelope" aria-hidden="true"></i> Newsletters</a>
<a href="<?php echo $admin; ?>newsletter-subscribers/" class="<?php echo current_page("newsletter-subscribers"); ?>"><i class="fa fa-users" aria-hidden="true"></i> Subscribers</a>
</div>

<a id="settings-menu" class="main-menu <?php echo (!empty(current_page("profile")) || !empty(current_page("reset-password")))?"main-current":""; ?>"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a>
<div id="settings-menu-div" class="sub-menu">
<a href="<?php echo $admin; ?>profile/" class="<?php echo current_page("profile"); ?>"><i class="fa fa-user" aria-hidden="true"></i> Profile</a>
<a href="<?php echo $admin; ?>reset-password/" class="<?php echo current_page("reset-password"); ?>"><i class="fa fa-lock" aria-hidden="true"></i> Reset Password</a>
</div>

<a class="main-menu" onClick="javascript:my_confirm('Logout Confirmation','Are you sure you want to log out?','<?php echo $directory . $admin; ?>index/logout/1/');"><i class="fa fa-sign-out"></i> Log Out</a>
</div>

<div class="portal-body portal-content">
<div class="<?php echo (basename($_SERVER["PHP_SELF"],".php") == "index")?"portal-body-wrapper":"body-content form-div"; ?>">