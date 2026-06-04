<?php
// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");

ini_set('session.gc_maxlifetime', 86400);
session_start();

require_once("classes/db-class.php");
require_once("includes/functions.php");

require_once("includes/mobile-detect.php");
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
?>

<!DOCTYPE html>
<html lang="en-US" dir="ltr">
<head>
<base href="<?php directory(); ?>" target="_top">
<meta charset="UTF-8" />
<meta name="description" content="<?php echo $full_gen_name; ?>"/>
<meta name="robots" content="noodp"/>
<meta name="keywords" content="<?php echo $full_gen_name; ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<title>Home - <?php echo $full_gen_name; ?></title>

<meta property="og:url" content="<?php directory(); ?>" /> 
<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo $full_gen_name; ?>" /> 
<meta property="og:description" content="<?php echo $full_gen_name; ?>" /> 
<meta property="og:image" content="<?php directory(); ?>images/aatren-logo.png" />
<meta property="og:image:type" content="image/jpg" />
<meta property="og:image:width" content="210" />
<meta property="og:image:height" content="210" />

<link rel="shortcut icon" href="images/favicon.png"/>
<link type="text/css" rel="stylesheet" href="css/bootstrap.css" />
<link type="text/css" rel="stylesheet" href="css/font-awesome.css" />
<link type="text/css" rel="stylesheet" href="css/style.css" />
<link type="text/css" rel="stylesheet" href="css/owl.carousel.css" />
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script src="js/owl.carousel.js"></script>
<script>
<!--
var img1 = new Image();
img1.src = "images/home_bg.jpg";
//-->
</script>

</head>
<?php detectCurrUserBrowser('<table width="100%"><tr><td>','',7); ?>
<body>

<?php if(isset($_SESSION["admin_login"])){  ?>
<div class="header-wrapper header-wrapper1" id="bodyDiv">
<div class="header header1">
<a onClick="javascript:my_confirm('Logout Confirmation','Are you sure you want to log out?','<?php echo $directory . $admin; ?>index/logout/1/');"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
<a href="<?php echo $admin; ?>profile/"><i class="fa fa-user" aria-hidden="true"></i> My Profile</a>
</div>
</div>
<?php } ?>

<div class="header-wrapper header-wrapper2">
<div class="header header2">
<a href="<?php directory(); ?>"><img src="images/aatren-logo.png"></a>
<button class="collapse"><span></span><span></span><span></span></button>
<ul class="main-list">
<?php
$result = $db->select("items_categories", "", "*", "ORDER BY cat_name ASC", "");
if(count_rows($result) > 0){
while($row = fetch_data($result)){
$cat_name = $row["cat_name"];
$cat_slug = $row["cat_slug"];
?>
<li><a href="cat/<?php echo $cat_slug; ?>/"><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $cat_name; ?></a></li>
<?php
}
}
?>
<li><a href="<?php echo $privates; ?>events-videos/"><i class="fa fa-file-video-o" aria-hidden="true"></i> Event Videos</a></li>
<li><a href="<?php echo $privates; ?>about-us/"><i class="fa fa-university" aria-hidden="true"></i> About Us</a></li>
<li><a href="<?php echo $privates; ?>members/"><i class="fa fa-users" aria-hidden="true"></i> Members</a></li>
<li><a href="<?php echo $privates; ?>contact-us/"><i class="fa fa-phone" aria-hidden="true"></i> Contact Us</a></li>
</ul>
</div>
</div>

<div class="header-wrapper header-wrapper3" style="overflow:visible">
<div class="slider-container">

<link rel="stylesheet" href="css/swiper.min.css">

<style>.swiper-container, .swiper-container div{overflow:visible;}.swiper-container{width:100%;height:100%;}.swiper-container{width:100%;height:100%;}.swiper-slide{text-align:center;font-size:14px;background:#fff;display:-webkit-box;display:-ms-flexbox;display:-webkit-flex;display:flex;-webkit-box-pack:center;-ms-flex-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;-webkit-align-items:center;align-items:center;}</style>

<div class="swiper-container">
<div class="swiper-wrapper">

<?php
$result = $db->select("banners", "", "*", "ORDER BY order_id ASC", "");
if(count_rows($result) > 0){
while($row = fetch_data($result)){
$get_id = $row["id"];
$file_array = glob("images/banners/{$get_id}pic*.*");
$file_name = ($file_array)?$file_array[0]:"images/member.jpg";
?>
<div class="swiper-slide"><img src="<?php echo $file_name; ?>"></div>
<?php 
}
} 
?>
</div>
 
<div class="swiper-pagination"></div>
 
<div class="swiper-button-next"></div>
<div class="swiper-button-prev"></div>
</div>

<script src="js/swiper.min.js"></script>

<script>
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        spaceBetween: 0,
        centeredSlides: true,
        autoplay: 5000,
        autoplayDisableOnInteraction: false
    });
</script>

</div>
</div>

<div class="home-body-wrapper"> 
<div class="container"> 

<div class="col-sm-6"> 
<div class="body-header">About <span>AATREN</span></div>

<img src="images/about-aatren-details-img.jpg" class="details-img">

<p>The Association of African TRADITIONAL RELIGION NIGERIA (AATREN) is the umbrella of all Traditional groups in Nigeria. It was formally known as an Ancient Religious Societies of African Descendants Association (ARSADA) registered under companies&#039; ordinances chapter 38 on 20th day of march, 1957 and gazetted in federation of Nigeria official gazette no 22 vol.46 of 2nd April, 1957 known as Rc/37/59/5.</p>

<div><a href="privates/about-us/" class="btn gen-btn float-right">Read more  <i class="fa fa-arrow-right"></i></a></div>

</div>
<div class="col-sm-6"> 
<div class="body-header">Past <span>Event Video</span></div>

<?php 
$result = $db->select("events_videos", "", "*", "ORDER BY event_date DESC, id DESC", "LIMIT 1");
if(count_rows($result) > 0){
$row = fetch_data($result);
$event_date = min_sub_date($row["event_date"]);
$event_title = $row["event_title"];
$event_link = $row["event_link"];
?>
<p class="align-center"><b><?php echo "{$event_title} ({$event_date})"; ?></b></p>
<div>
<iframe src="<?php echo $event_link; ?>" frameborder="0" style="border:0px; height:260px;width:100%;" allowfullscreen></iframe>
</div>
<div style="padding-top:10px;"><a href="<?php echo $privates; ?>events-videos/" class="btn gen-btn float-right">View more videos <i class="fa fa-arrow-right"></i></a></div>
<?php
}
?>

</div>

</div>
</div>

<div class="home-body-wrapper"> 
<div class="container"> 

<?php 
$items_list = "";
$result = $db->select("items_categories", "WHERE home_display = '1'", "*", "ORDER BY cat_order ASC", "");
if(count_rows($result) > 0){
while($row = fetch_data($result)){
$items_list .= $row["id"] . ",";
}
$items_list = substr($items_list,0,-1);
$items_list = explode(",",$items_list);
$count_items = count($items_list);

for($i=0;$i<$count_items;$i++){
if(!empty($items_list[$i])){
?>

<div class="body-header"><span><?php echo in_table("cat_name","items_categories","WHERE id = '" . $items_list[$i] . "'","cat_name"); ?></span></div>

<?php
$order = ($items_list[$i] == 2)?"ASC":"DESC";
$result = $db->select("items", "WHERE cat_id = '" . $items_list[$i] . "'", "*", "ORDER BY item_date {$order}, id DESC", "LIMIT 3");
if(count_rows($result) > 0){
?>
<div class="item-wrapper">
<?php
while($row = fetch_data($result)){
$item_id = $row["id"];
$item_date = min_sub_date($row["item_date"]);
$item_name = $row["item_name"];
$item_slug = $row["item_slug"];
$slide_array = glob("images/items-featured/" . $row["id"] . "_" . $row["added_by"] . "_item_featured_*.*");
$file_name = $slide_array[0];
?>
<div class="item-inner white-bg shadow <?php echo det_browser("fly"); ?>">
<div class="item-picture"><a href="details/<?php echo $item_slug; ?>/pn/1/"><img src="<?php echo $file_name; ?>" /></a></div>
<div class="item-title"><a href="details/<?php echo $item_slug; ?>/pn/1/"><?php echo $item_name; ?></a>
<div class="item-price"><?php echo $item_date; ?></div>
<div class="item-options">
<a href="details/<?php echo $item_slug; ?>/pn/1/" class="btn gen-btn float-right"><i class="fa fa-eye" aria-hidden="true"></i> View details</a>
</div>
</div>
</div>
<?php
}
?>
</div>
<?php
}else{
echo "<div class=\"not-success\">Coming soon...</div>";
}
?>

<?php }} } ?>

</div>
</div>

<?php require_once("includes/footer.php"); ?>