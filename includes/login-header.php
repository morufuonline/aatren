<?php
/// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

ini_set('session.gc_maxlifetime', 86400);
session_start();

require_once("../classes/db-class.php");
require_once("../includes/functions.php");

require_once("../includes/mobile-detect.php");
$detect = new Mobile_Detect;

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

$captured_title = basename($_SERVER["PHP_SELF"],".php");
$page_title = $det_cat_slug = $det_item_slug = "";
if($captured_title == "item-details"){
$det_item_slug = tr_input("item_slug");
$page_title = in_table("item_name","items","WHERE item_slug = '{$det_item_slug}'","item_name");
}else if($captured_title == "items-cat"){
$det_cat_slug = tr_input("cat_slug");
$page_title = in_table("cat_name","items_categories","WHERE cat_slug = '{$det_cat_slug}'","cat_name");
}else{
$page_title = title_link($captured_title);
}
?>


<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<head>
<base href="<?php directory(); ?>" target="_top">
<meta charset="UTF-8" />
<meta name="description" content="<?php echo html_entity_decode($page_title) . " - " . $full_gen_name; ?>"/>
<meta name="robots" content="noodp"/>
<meta name="keywords" content="<?php echo html_entity_decode($page_title) . " - " . $full_gen_name; ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<title><?php echo html_entity_decode($page_title) . " - " . $full_gen_name; ?></title>

<meta property="og:url" content="<?php directory(); ?>" /> 
<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo $page_title . " - " . $full_gen_name; ?>" /> 
<meta property="og:description" content="<?php echo $page_title . " - " . $full_gen_name; ?>" /> 
<meta property="og:image" content="<?php directory(); ?>images/aatren-logo.png" />
<meta property="og:image:type" content="image/jpg" />
<meta property="og:image:width" content="210" />
<meta property="og:image:height" content="210" />

<link rel="shortcut icon" href="images/favicon.png"/>
<link type="text/css" rel="stylesheet" href="css/bootstrap.css" />
<link type="text/css" rel="stylesheet" href="css/font-awesome.css" />
<link type="text/css" rel="stylesheet" href="css/style.css" />
<link type="text/css" rel="stylesheet" href="css/owl.carousel.css" />
<link type="text/css" rel="stylesheet" href="css/special-form.css" />
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/owl.carousel.js"></script>

</head>
<?php detectCurrUserBrowser('<table width="100%"><tr><td>','',7); ?>
<body class="home-body-wrapper <?php echo det_browser("jarallax"); ?>" style="background:url(images/ace-maths-exams-login.jpg); <?php echo det_browser("-webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;"); ?>">

<div class="header-wrapper header-wrapper1" id="bodyDiv">
<div class="header header1">
<a><i class="fa fa-search" aria-hidden="true"></i> Search</a>
<a href="<?php echo $privates; ?>cart/" class="cart-counter-display"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Cart (<span class="cart-items-counter"><?php echo count_cart_items(); ?></span>)
<div class="cart-items-display"><?php cart_items_display(); ?></div>
</a>
<?php if(isset($_SESSION["login"])){  ?>
<a onClick="javascript:my_confirm('Logout Confirmation','Are you sure you want to log out?','<?php echo $directory . $users; ?>index/logout/1/');"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
<a href="<?php echo $users; ?>profile/"><i class="fa fa-user" aria-hidden="true"></i> My Profile</a>
<?php }else if(isset($_SESSION["admin_login"])){ ?>
<a onClick="javascript:my_confirm('Logout Confirmation','Are you sure you want to log out?','<?php echo $directory . $admin; ?>index/logout/1/');"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
<a href="<?php echo $admin; ?>profile/"><i class="fa fa-user" aria-hidden="true"></i> My Profile</a>
<?php }else{ ?>
<a href="<?php echo $privates; ?>login/"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</a>
<a href="<?php echo $privates; ?>register/"><i class="fa fa-laptop" aria-hidden="true"></i> Register</a>
<?php } ?>
</div>
</div>

<div class="header-wrapper header-wrapper2">
<div class="header header2">
<a href="<?php directory(); ?>"><img src="images/aatren-logo.png"></a>
<button class="collapse"><span></span><span></span><span></span></button>
<ul class="main-list">
<li><a href="<?php echo $privates; ?>about-us/" class="<?php echo current_page("about-us"); ?>"><i class="fa fa-university" aria-hidden="true"></i> About Us</a></li>
<li><a href="<?php echo $privates; ?>members/" class="<?php echo current_page("members"); ?>"><i class="fa fa-users" aria-hidden="true"></i> Members</a></li>
<li><a href="<?php echo $privates; ?>contact-us/" class="<?php echo current_page("contact-us"); ?>"><i class="fa fa-phone" aria-hidden="true"></i> Contact Us</a></li>
</ul>
</div>
</div>
