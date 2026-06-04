<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

ini_set('session.gc_maxlifetime', 86400);
session_start();

require_once("../classes/db-class.php");
require_once("functions.php");

if(!isset($_SESSION["login"])){
redirect("{$directory}{$privates}login.php");
$_SESSION["msg"] = "<div class='success'>You are not logged in. Kindly log in to continue...</div>";
}

if(isset($_REQUEST["logout"])){
unset($_SESSION["login"]);
unset($_SESSION["name"]);
unset($_SESSION["email"]);
unset($_SESSION["id"]);
$_SESSION["msg"] = "<div class='success'>You are successfully logged out. Kindly log in to continue...</div>";

$db->query("UPDATE reg_users SET logged_in = '0', last_login = '{$date_time}' WHERE id = '{$id}'");

redirect("{$directory}{$privates}login/");
}

$last_login = in_table("last_login","reg_users","WHERE id = '$id'","last_login");
$blocked = in_table("blocked","reg_users","WHERE id = '$id'","blocked");

if($blocked == 1){
unset($_SESSION["login"]);
unset($_SESSION["name"]);
unset($_SESSION["email"]);
unset($_SESSION["id"]);
$_SESSION["msg"] = "<div class='not-success'>Hi {$user_name}! Your account is declined. Kindly contact the admin <a href='{$privates}contact-us/'>HERE</a>.</div>";
redirect("{$directory}{$privates}login/");
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
<a href="<?php directory(); ?>" class="logo-link"><img src="images/aatren-logo.png" style="max-height:70px;"></a>
<span>
<?php
$file_array = glob("../images/users/{$id}pic*.*");
$file_name = ($file_array)?"images/" . $file_array[0]:"images/post.jpg";
?><a href="<?php echo $users; ?>profile/"><img src="<?php echo $file_name; ?>" ><br>
<i class="fa fa-user" aria-hidden="true"></i> <?php echo $username; ?></a>
</span>
<button class="collapse"><span></span><span></span><span></span></button>
</div>
</div>

<div class="portal-wrapper">

<div class="portal-nav portal-content">
<a href="<?php echo $users; ?>" class="<?php echo current_page("index"); ?>"><i class="fa fa-dashboard" aria-hidden="true"></i> Dashboard</a>
<a href="<?php echo $users; ?>manage-orders/" class="<?php echo current_page("manage-orders"); ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Manage Orders</a>
<a href="<?php echo $users; ?>saved-items/" class="<?php echo current_page("saved-items"); ?>"><i class="fa fa-heart" aria-hidden="true"></i> Saved Items</a>
<a href="<?php echo $users; ?>inbox/" class="<?php echo current_page("inbox"); ?>"><i class="fa fa-inbox" aria-hidden="true"></i> Inbox</a>
<a href="<?php echo $users; ?>sent-messages/" class="<?php echo current_page("sent-messages"); ?>"><i class="fa fa-send" aria-hidden="true"></i> Sent Messages</a>
<a href="<?php echo $users; ?>profile/" class="<?php echo current_page("profile"); ?>"><i class="fa fa-user" aria-hidden="true"></i> Profile</a>
<a href="<?php echo $users; ?>reset-password/" class="<?php echo current_page("reset-password"); ?>"><i class="fa fa-lock" aria-hidden="true"></i> Reset Password</a>
<a onClick="javascript:my_confirm('Logout Confirmation','Are you sure you want to log out?','<?php echo $directory . $users; ?>?logout=1');"><i class="fa fa-sign-out"></i> Log Out</a>
</div>

<div class="portal-body portal-content">
<div class="<?php echo (basename($_SERVER["PHP_SELF"],".php") == "index")?"portal-body-wrapper":"body-content form-div"; ?>">