<?php require_once('../includes/admin-header.php'); ?> 

<style>
<!--
.body-content2{
background:#ddd;
padding:10px;
}
.portal-body-wrapper{
width:100%;
margin-bottom:20px;
}
.portal-body-wrapper .home-nav{
width:25%;
float:left;
padding:10px;
}
.portal-body-wrapper .home-nav a{
text-decoration:none !important;
}

@media(max-width:1200px){
.portal-body-wrapper .home-nav{
width:50%;
}
}
@media(max-width:900px){
.portal-body-wrapper .home-nav{
width:100%;
}
}
@media(max-width:800px){
.portal-body-wrapper .home-nav{
width:50%;
}
}
@media(max-width:500px){
.portal-body-wrapper .home-nav{
width:100%;
}
}
.details{
margin:10px;
}
.details p{
font-size:30px;
overflow:hidden;
padding:10px;
}
.portal-body-wrapper .body-content{
display:table;
width:100%;
}
.portal-body-wrapper .body-content div.inner-content{
display:table-cell;
}
.portal-body-wrapper .body-content div.icon-div{
width:50px;
padding-left:5px;
text-align:right;
vertical-align:bottom;
}
.portal-body-wrapper .body-content div.icon-div i{
font-size:70px;
}
.portal-body-wrapper .body-content:hover{
background:#000;
color:#fff;
}
.portal-body-wrapper .body-content:hover *{
color:#fff;
}
.red{
color:#f33;
}
.green{
color:#5cb85c;
}
.purple{
color:#966;
}
-->
</style>

<div class="page-title">Dashboard</div>

<div class="home-nav">
<a href="<?php echo $admin; ?>newsletter-subscribers/"><div class="body-content body-content2">
<div class="inner-content">
<p>Subscribers: <b><?php echo formatQty(in_table("COUNT(id) AS Total","newsletter","","Total")); ?></b></p>
<div style="background:#ddd;"><div style="width:70%; padding:7px; background:#966;"></div></div>
</div>
<div class="inner-content icon-div">
<i class="fa fa-users" aria-hidden="true"></i>
</div>
</div></a>
</div>

<div class="home-nav">
<a href="<?php echo $admin; ?>inbox/"><div class="body-content body-content2">
<div class="inner-content">
<p>Inbox: <b><?php echo formatQty(in_table("COUNT(id) AS Total","admin_messages","WHERE recipient_email = '$gen_email' AND inbox = '1' AND viewed = '0'","Total")); ?></b></p>
<div style="background:#ddd;"><div style="width:70%; padding:7px; background:#5cb85c;"></div></div>
</div>
<div class="inner-content icon-div">
<i class="fa fa-inbox red" aria-hidden="true"></i>
</div>
</div></a>
</div>

<div class="home-nav">
<a href="<?php echo $admin; ?>profile/"><div class="body-content body-content2">
<div class="inner-content">
<p>Profile</p>
<div style="background:#ddd;"><div style="width:70%; padding:7px; background:#5bc0de;"></div></div>
</div>
<div class="inner-content icon-div">
<i class="fa fa-user purple" aria-hidden="true"></i>
</div>
</div></a>
</div>

<div class="home-nav">
<a href="<?php echo $admin; ?>reset-password/"><div class="body-content body-content2">
<div class="inner-content">
<p>Change Password</p>
<div style="background:#ddd;"><div style="width:70%; padding:7px; background:#f33;"></div></div>
</div>
<div class="inner-content icon-div">
<i class="fa fa-lock green" aria-hidden="true"></i>
</div>
</div></a>
</div>

</div>

<?php 
$result = $db->select("newsletter", "", "*", "ORDER BY id DESC","LIMIT 20");
if(count_rows($result) > 0){
?>
<div class="body-content details form-div">
<p>Newsletter Subscribers</p>
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
$c = 0;
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
</div>
<?php
}
?>
</div>

</div>

<?php require_once("../includes/portal-footer.php"); ?>