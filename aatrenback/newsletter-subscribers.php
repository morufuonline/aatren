<?php if(!isset($_REQUEST["gh"])){ include_once("../includes/admin-header.php"); 
}else{ 
include_once("../includes/gen-header.php");
} ?>

<div class="page-title">Sales Letter Subscribers</div>

<?php
$result = $db->select("newsletter", "", "*", "ORDER BY id DESC");
$count = count_rows($result);

$per_view = 50;
$page_link = "{$admin}newsletter-subscribers/pn/";
$link_suffix = "/";
$style_class = "general-link";
page_numbers();
$c = 0;

$offset = ($per_view * $pn) - $per_view;

$result = $db->select("newsletter", "", "*", "ORDER BY id DESC", "LIMIT {$offset},{$per_view}");

if($count > 0){
?>
<div class="overflow">
<table class="table table-striped table-hover">
<thead>
<tr class="gen-title">
<th>S/N</th>
<th>Name</th>
<th>Email</th>
<th>Date Subscribed</th>
</tr>
</thead>
<tbody>
<?php
while($row = fetch_data($result)){
$c++;
$name = $row["name"];
$email = $row["email"];
$date_time = ($row["date_time"] != "0000-00-00 00:00:00")?min_full_date($row["date_time"]):"";
?>
<tr>
<td><?php echo $c; ?></td>
<td><?php echo $name; ?></td>
<td><?php echo $email; ?></td>
<td><?php echo $date_time; ?></td>
</tr>
<?php 
}
?>
</tbody>
</table>
</div>
<?php
echo ($last_page>1)?"<div class=\"page-nos\">" . $center_pages . "</div>":"";
}else{
echo "<div class='not-success'>No subscribers found.</div>";
}
?>

<script src="js/general-form.js"></script>

<?php  if(!isset($_REQUEST["gh"])){?>

</div>
</div>

</div>
<?php require_once("../includes/portal-footer.php"); } ?>