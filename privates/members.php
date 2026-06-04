<?php require_once("../includes/header.php"); 

$result = $db->select("members", "", "*", "");

$per_view = 15;
$page_link = "{$privates}members/pn/";
$link_suffix = "/";
$style_class = "general-link";
page_numbers();
?>

<div class="home-body-wrapper"> 
<div class="container container2">
<div class="container-bg">
<div class="body-header2">AATREN Members</div>
</div>
</div>
</div>

<div class="home-body-wrapper"> 
<div class="container"> 

<?php
$offset = ($per_view * $pn) - $per_view;

$result = $db->select("members", "", "*", "ORDER BY order_id ASC", "LIMIT {$offset},{$per_view}");

if(count_rows($result) > 0){
?>
<div class="item-wrapper">
<?php
while($row = fetch_data($result)){
$member_id = $row["id"];
$name = $row["name"];
$position = $row["position"];
$file_array = glob("../images/members/{$member_id}pic*.*");
$file_name = ($file_array && file_exists($file_array[0]))?$file_array[0]:"member.jpg";
?>
<div class="item-inner white-bg shadow <?php echo det_browser("fly"); ?>">
<div class="item-picture"><img src="images/<?php echo $file_name; ?>" /></div>
<div class="item-title"><?php echo $name; ?>
<div class="item-price"><?php echo $position; ?></div>
</div>
</div>
<?php
}
?>
</div>
<?php
echo ($last_page>1)?"<div class=\"page-nos\">" . $center_pages . "</div>":"";
}else{
?>
<div class="not-success">No members available for now.</div>
<?php
}  
?>


</div>
</div>

<?php require_once("../includes/footer.php"); ?>