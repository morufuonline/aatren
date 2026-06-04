<script src="js/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/sweetalert.css">

<div class="general-fade"></div>

<div class="general-result"></div>

<?php if(empty(current_page("login"))){ ?>

<div class="footer-container">
<div class="footer-wrapper">
<div class="footer container">

<div class="col-sm-4 nav-link share">
<div class="title btn">LOCATE US</div>
<a><i class="fa fa-map-marker" aria-hidden="true"></i> 90, Freeman/Kano Street, Ebute-Metta, Lagos.</a>
<div class="title btn">CONTACT US</div>
<a><i class="fa fa-envelope" aria-hidden="true"></i> <?php echo $gen_email; ?></a>
<a><i class="fa fa-phone" aria-hidden="true"></i> 01-8181726, 7737423, <?php echo $gen_phone; ?>, +234 (0)802 330 3126</a>
</div>

<div class="col-sm-4 nav-link">
<div class="title btn">QUIK LINKS</div>
<a href="<?php directory(); ?>" class="<?php echo current_page("index"); ?>"><i class="fa fa-home" aria-hidden="true"></i> Home</a>
<a href="<?php echo $privates; ?>about-us/" class="<?php echo current_page("about-us"); ?>"><i class="fa fa-university" aria-hidden="true"></i> About Us</a>
<a href="<?php echo $privates; ?>members/" class="<?php echo current_page("members"); ?>"><i class="fa fa-users" aria-hidden="true"></i> Members</a>
<a href="<?php echo $privates; ?>contact-us/" class="<?php echo current_page("contact-us"); ?>"><i class="fa fa-phone" aria-hidden="true"></i> Contact Us</a>
</div>

<div class="col-sm-4 subscribe">
<div class="title btn">NEWSLETTER</div>
<form  action="<?php directory(); ?>privates/process-data/" class="newsletter" method="post" runat="server" autocomplete="off" enctype="multipart/form-data">

<input type="hidden" name="newsletter" value="1">

<div class="form-group input-group">
<span class="input-group-addon"><i class="fa"><label for="name">Name</label></i></span>
<input type="text" name="name" id="name" class="form-control" value="" placeholder="Your name" required>
</div>

<div class="form-group input-group">
<span class="input-group-addon"><i class="fa"><label for="email">Email</label></i></span>
<input type="text" name="email" id="email" class="form-control" value="" placeholder="Your email" required>
</div>
<div style="text-align:right">
<button  name="subscribe" id="subscribe"><i class="fa fa-send"></i> Subscribe</button>
</div>	
</form>
<div class="footer-social">
<a href="javascript:void(0);" title="Facebook" class="fa fa-facebook btn" target="_blank"></a>
<a href="javascript:void(0);" title="Twitter" class="fa fa-twitter btn"></a>
<a href="javascript:void(0);" title="Google +" class="fa fa-google-plus btn"></a>
<a href="javascript:void(0);" title="Pinterest" class="fa fa-pinterest-p btn"></a>
<a href="javascript:void(0);" title="Instagram" class="fa fa-instagram btn"></a>
</div>
</div>

</div>
</div>
</div>

<div class="copyright">Copyright &copy; <?php echo date("Y") . " " . $full_gen_name; ?>. All Rights Reserved.<br />Developed by: <a href="http://reliancewisdom.com" target="_blank">Reliance Wisdom Digital.</a></div>

<?php } ?>

<script type="text/javascript" src="js/general.js"></script>
</body>
<?php
$db->disconnect();
detectCurrUserBrowser('</td></tr></table>','',7); ?>
</html>