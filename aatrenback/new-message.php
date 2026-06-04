<?php include_once('../includes/admin-header.php');  ?> 

<?php
$subscribers = in_table("COUNT(id) AS Total","newsletter","","Total");

$error = 1;
$category = tp_input("category");
$subject2 = tp_input("subject");
$subject = html_entity_decode($subject2);
$message = tp_input("message");
$newsletter = tp_input("newsletter");

if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($newsletter) && !empty($subject2) && !empty($message)){

$result = $email = "";

$result = $db->select("newsletter", "", "*");

if(count_rows($result) > 0){

while($row = fetch_data($result)){
$email .= $row["email"] . ", ";
}

$email = substr($email,0,-2);
$email_array = explode(", ",$email);
$email_array = array_unique($email_array);
$email = "";

$message = "<p>Dear +*/-+*/-,</p><p>{$message}</p>";
$message2 = $message;
$message = html_entity_decode($message);
$foot_note .= "<br>You are getting this mail because you have subscribed to {$gen_name}&#039;s newsletter.";
$message3 = message_template();
$headers2 = "{$gen_name} <no-reply@{$domain}>";

foreach($email_array as $value){
if(!empty($value)){
$receivers_name = $message = $to = $headers = "";
$receivers_name = in_table("name","newsletter","WHERE email = '{$value}'","name");
$message = str_replace("+*/-+*/-", $receivers_name, $message3);
$to = "{$value}";
$headers = $headers2;
$act = send_mail();
}
}

if($act){

$message4 = str_replace("+*/-+*/-", "Newsletter Subscribers", $message2);

$user_data_array = array(
"subject" => "'$subject2'",
"message" => "'$message4'"
);
$db->insert($user_data_array, "messages");

$_SESSION["msg"] = "<div class='success'>Mail successfully sent.</div>";
redirect("{$directory}{$admin}new-message/");
}else{
echo "<div class='not-success'>Not successful! Unable to send mail.</div>";
}


}else{
echo "<div class='not-success'>Not sent. No records found.</div>";
}
}

if($_SERVER['REQUEST_METHOD'] == "POST" && !empty($newsletter) && (empty($subject2) or empty($message))){
echo "<div class='not-success'>Not successful! All the fields are required.</div>";
}

if(isset($_SESSION["msg"]) && empty($newsletter)){
echo $_SESSION["msg"];
unset($_SESSION["msg"]);
}
?>

<div class="page-title">Send a Mail to Newsletter Subscribers (<?php echo formatQty($subscribers); ?>)</div>

<?php
if($subscribers < 1){
echo "<div class='not-success'>No subscribers to send mail to.</div>";
}else{
?>
<form action="<?php echo $admin; ?>new-message/" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">  

<input type="hidden" name="newsletter" value="1">

<div>
<label for="subject">Subject</label>
<div class="form-group input-group">
<span class="input-group-addon"><i class="fa fa-file-text"></i></span>
<input type="text" name="subject" id="subject" class="form-control" placeholder="Subject of the message" value="<?php check_inputted("subject"); ?>" required>
</div>
</div>

<div>
<label for="message">Message</label>
<textarea class="ckeditor" name="message" id="message" required placeholder="Type your message here" rows="3" cols="40" style="overflow: auto" ><?php check_inputted("message"); ?></textarea>
</div>
                  
<div class="submit-div">
<button class="btn gen-btn float-right" name="update"><i class="fa fa-upload"></i> Send</button>
</div>
</form>
<?php } ?>

</div>
</div>

</div>

<script src="js/text_plugin/ckeditor.js"></script>
<script src="js/general-form.js"></script>

<?php require_once("../includes/portal-footer.php"); ?>
