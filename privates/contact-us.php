<?php require_once("../includes/header.php"); ?>
<style>
<!--
.page-title-banner{
background:#eee url(images/contact-us-banner.jpg) no-repeat right top;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
}
.special-title{
padding:20px;
font-size:25px;
background:#f33;
margin-bottom:10px;
margin-top:10px;
}
.form-group i{
color:#333;
}
.special{
background:#f55;
color:#fff;
font-size:#18px;
margin-bottom:10px;
text-align:center;
}
.special:hover{
color:#fff;
background:#f33;
}
.content-vission input[type="text"], .content-vission input[type="email"], .content-vission textarea{
color:#333 !important;
}
-->
</style>

<div class="home-body-wrapper"> 
<div class="container container2">
<div class="container-bg">
<div class="body-header2">Contact Us</div>
</div>
</div>
</div>

<?php
$error = 1;
$service = title_link(tr_input("service"));
$name = tp_input("name");
$email = tp_input("email");
$phone = np_input("phone");
$subject = tp_input("subject");
$subject2 = $subject;
$message = tp_input("message");
$message2 = $message;

if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($phone) && strlen($phone) >= 5 && !empty($subject) && !empty($message)){

$to = "{$email}";
$subject = "Ticket #{$ticket_id}: Inquiry on {$subject2}";
$message = "<p>Thank you for using our customer support service.</p>
<p>We will get back to you as soon as possible.</p>";
$message = message_template();
$headers = "{$gen_name} <no-reply@{$domain}>";

$act1 = send_mail();

$to = "{$gen_email}";
$subject = "Ticket #{$ticket_id}: Inquiry on {$subject2}";
$message = "<p><b>Email:</b> {$email}</p><p><b>Phone Number:</b> {$phone}</p><p>{$message2}</p>";
$foot_note = $regards = "";
$message = message_template();
$headers = "{$name} <{$email}>";
$act2 = send_mail(1);

$error = 0;

$admin_data_array = array(
"ticket_id" => "'$ticket_id'",
"sender_name" => "'$name'",
"sender_email" => "'$email'",
"sender_phone" => "'$phone'",
"recipient_name" => "'$gen_name'",
"recipient_email" => "'$gen_email'",
"subject" => "'$subject2'",
"message" => "'$message2'",
"inbox" => "'1'",
"date_time" => "'$date_time'"
);
$db->insert($admin_data_array, "admin_messages");

$admin_ticket_id = $ticket_id . in_table("id","admin_messages","WHERE sender_email = '{$email}' AND date_time = '{$date_time}'","id");
$db->query("UPDATE admin_messages SET ticket_id = '{$admin_ticket_id}', date_time = '{$date_time}' WHERE sender_email = '{$email}' AND date_time = '{$date_time}'");

$_SESSION["msg"] = "<div class='success'>Your message was successfully sent. We will get back to you shortly.</div>";
redirect("{$directory}{$privates}contact-us/");
}

if($_SERVER['REQUEST_METHOD'] == "POST" && (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message))){
echo "<div class='not-success'>Not Successful. All fields must be properly filled.</div>";
}else if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
echo "<div class='not-success'>Not Successful. Invalid email format.</div>";
}else if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($phone) && strlen($phone) < 5){
echo "<div class='not-success'>Not Successful. Phone number must not be less than 5 digits.</div>";
}

if(isset($_SESSION["msg"]) && !isset($_POST["mail"])){
echo $_SESSION["msg"];
unset($_SESSION["msg"]);
}
?>

<div class="home-body-wrapper"> 
<div class="container white-bg shadow"> 

<div class="col-md-5 content-body">

<p>&nbsp;</p>
<div class="body-header"><i class="fa fa-home" aria-hidden="true"></i> Office Address</div>
<p>90, Freeman/Kano Street, Ebute-Metta, Lagos.</p>
<p>20, Oba Yekini Adeniyi Elegushi Road, Kilometer 13, Lekki-Epe Express way, Ikate Elegushi, Eti-Osa Local Government, Lagos.</p>

<div class="body-header"><i class="fa fa-envelope" aria-hidden="true"></i> Email</div>
<p><a href="mailto:<?php echo $gen_email; ?>"><?php echo $gen_email; ?></a></p>

<div class="body-header"><i class="fa fa-phone" aria-hidden="true"></i> Phone</div>
<p><?php echo $gen_phone; ?>, +234 (0)803 321 7614, +234 (0)803 307 8477, +234 (0)806 246 2840</p>

</div>
<div class="col-md-7 content-vission">
<form action="<?php echo $privates ?>contact-us/" method="post" class="special-form" id="contact-result" runat="server" name="send_mail" autocomplete="off" enctype="multipart/form-data">  

<div class="special-title"><i class="fa fa-envelope"></i> Send us a mail</div>

<input type="hidden" name="mail" value="1">

<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
<input type="text" name="name" id="name" class="form-control" placeholder="Your Full Name" required value="<?php check_inputted("name"); ?>">
</div>

<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
<input type="email" name="email" id="email" class="form-control" placeholder="Your E-mail Address" required value="<?php check_inputted("email"); ?>">
</div>

<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
<input type="text" name="phone" id="phone" class="form-control only-no" placeholder="Your Phone Number" required value="<?php check_inputted("phone"); ?>">
</div>

<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-file-text" aria-hidden="true"></i></span>
<input type="text" name="subject" id="subject" class="form-control" placeholder="Subject (Make it short)" required value="<?php check_inputted("subject"); ?>">
</div>

<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
<textarea type="text" name="message" id="message" class="form-control" placeholder="Details" required value=""><?php check_inputted("message"); ?></textarea>
</div>

<div style="text-align:right;">
<button class="btn special" name="send"><i class="fa fa-send"></i> Send</button>
</div>

</form>

<script>
<!--
$(document).ready(function () {

$(".only-no").keyup(function(){
var this_val = this.value;
if(isNaN(this_val)){
this.value = this_val.replace(/[^0-9.]/gi, "");
}	
}).change(function(){
var this_val = this.value;
if(isNaN(this_val)){
this.value = this_val.replace(/[^0-9.]/gi, "");
}	
});

});
//-->
</script>

</div>
<div class="col-md-12" style="padding:0px;">

<div>

<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.3020372389688!2d3.382534413981026!3d6.4833803953099345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8c8226563113%3A0xf20ddd14f0d48c16!2s90+Freeman+St%2C+Adekunle%2C+Lagos!5e0!3m2!1sen!2sng!4v1533307911725" frameborder="0" style="border:0px; height:400px;width:100%;" allowfullscreen></iframe>

</div>

</div>


</div>
</div>

<?php require_once("../includes/footer.php"); ?>