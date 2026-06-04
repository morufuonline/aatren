<?php require_once("../includes/header.php"); ?>

<div class="home-body-wrapper"> 
<div class="container container2">
<div class="container-bg">
<div class="body-header2">Past Events Videos</div>
</div>
</div>
</div>

<div class="home-body-wrapper"> 
<div class="container"> 

<?php 
$pn = nr_input("pn");

$result = $db->select("events_videos", "", "*", "");

$per_view = 5;
$page_link = "{$privates}events-videos/pn/";
$link_suffix = "/";
$style_class = "general-link";
page_numbers();

$offset = ($per_view * $pn) - $per_view;

$result = $db->select("events_videos", "", "*", "ORDER BY event_date DESC, id DESC", "LIMIT {$offset},{$per_view}");

if(count_rows($result) > 0){
?>
<?php
while($row = fetch_data($result)){
$event_date = sub_date($row["event_date"]);
$event_location = $row["event_location"];
$event_title = $row["event_title"];
$event_link = $row["event_link"];
$event_details = $row["event_details"];
?>
<div class="col-md-8">

<div>
<iframe src="<?php echo $event_link; ?>" frameborder="0" style="border:0px; height:400px;width:100%;" allowfullscreen></iframe>
</div>

</div>
<div class="col-md-4">

<div class="body-header"><?php echo $event_title; ?></div>

<h3>Description</h3>
<table class="table table-striped table-hover">
<tbody>
<tr><td><b>Date</b></td><td><?php echo $event_date; ?></td></tr>
<tr><td><b>Location</b></td><td><?php echo $event_location; ?></td></tr>
<tr><td><b>Details</b></td><td><?php echo html_entity_decode($event_details); ?></td></tr>
</tbody>
</table>

</div>
<?php
}
?>
<?php
echo ($last_page>1)?"<div class=\"page-nos\">" . $center_pages . "</div>":"";
}else{
echo "<div class=\"not-success\">Coming soon...</div>";
}
?>


</div>
</div>

<?php require_once("../includes/footer.php"); ?>