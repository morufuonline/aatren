<div class="body-header">Upcoming <span>Events</span></div>
<?php 
$result = $db->select("upcoming_events", "WHERE date >= '$date'", "*", "ORDER BY date ASC", "LIMIT 2");
if(count_rows($result) > 0){
?>
<div id="owl-content" class="owl-carousel">
<?php
while($row = fetch_data($result)){
$item_id = $row["id"];
$user_id = $row["user_id"];
$title = $row["title"];
$side_date1 = sub_date($row["date"]);
$file_array1 = glob("images/upcoming-events-featured/{$item_id}_{$user_id}_item_featured_*.jpg");
$file_array2 = ($file_array1)?$file_array1:glob("../images/upcoming-events-featured/{$item_id}_{$user_id}_item_featured_*.jpg");
$file_name = "";
if($file_array1){
$file_name = $file_array1[0];
}else if($file_array2){
$file_name = "images/" . $file_array2[0];
}else{
$file_name = "images/post.jpg";
}
?>
<div class="item">
<div class="item-picture"><a href="<?php echo $privates; ?>upcoming-event-details/view/<?php echo $item_id; ?>/pn/1/"><img src="<?php echo $file_name; ?>" /></a></div>
<div class="event-title"><a href="<?php echo $privates; ?>upcoming-event-details/view/<?php echo $item_id; ?>/pn/1/"><?php echo "{$title} on {$side_date1}"; ?></a></div>
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
<div style="padding-bottom:20px; padding-top:10px;"><a href="<?php echo $privates; ?>upcoming-events/" class="float-right gen-btn">View more <i class="fa fa-arrow-right"></i></a></div>


<div class="body-header">Programme <span>Schedule</span></div>

<?php 
$result = $db->select("programme_schedule", "WHERE date >= '$date'", "*", "ORDER BY date ASC");
if(count_rows($result) > 0){
?>
<div class="custom-container vertical">
<div class="carousel2">
<ul class="scolling-schedule">
<?php
while($row = fetch_data($result)){
$title = $row["title"];
$side_date2 = min_sub_date($row["date"]);
$time = $row["time"];
?>
<li><b><?php echo "{$side_date2} {$time}"; ?></b> <?php echo $title; ?><br /></li>
<?php
}
?>
</ul>
</div>
<div class="clear"></div>
</div>
<?php
}else{
echo "<div class=\"not-success\">Coming soon...</div>";
}
?>
