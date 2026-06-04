<!--
if (self!=top)
{
top.location.href=self.location.href;
}

$(document).ready(function(){
						   
$(".general-fade").hide();
$("html, body").animate({scrollTop:0}, "slow");

$(window).resize(function (){
						
if($("button.collapse").css("display") == "none"){
done = 0;
$(".portal-nav").show();
$(".portal-wrapper .portal-content").css({"display":"table-cell"});
}
if($("button.collapse").css("display") == "block"){
if(done == 0){
done = 1;
$(".portal-wrapper .portal-content").css({"display":"block"});
$(".portal-nav").hide();
}
}

});

//////////////////////////////////////////////////////////
$(".portal-nav .main-menu").click(function(){
var mainID = $(this).attr("id");
var subID = mainID + "-div";

$(".portal-nav .sub-menu").not("#"+subID).hide("fold");
$(".portal-nav #"+subID).slideToggle().animate({"margin-top":"-15px"},250).animate({"margin-top":"0px"},250).animate({"margin-top":"-7px"},250).animate({"margin-top":"0px"},250);
});

////////////////////////////
$(".header-wrapper .collapse").click(function(){
$(".portal-wrapper .portal-nav").slideToggle();
});

///////////////////////////////////////////////
$(".newsletter").submit(function(e){
e.preventDefault();  
var formdata = new FormData(this);
$(".general-fade").show();
var page_url = $(this).attr("action");

$.ajax({
url: page_url,
type: "POST",
data: formdata,
mimeTypes:"multipart/form-data",
contentType: false,
cache: false,
processData: false,
success: function(data){
$(".general-result").html(data);
$(".general-fade").hide();
},error: function(){
alert("Error occured!");
}
});

});

////////////////////////////
    //smoothscroll
    $('a.nav_top').on('click', function (e) {
        e.preventDefault();
        $(document).off("scroll");
		
        var target = this.hash,
            menu = target;
        $target = $(target);
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top+2
        }, 500, 'swing', function () {
            window.location.hash = target;
            $(document).on("scroll", onScroll);
        });
    });
////////////////////////////////////////

});

function my_confirm(conf_title,conf_text,conf_link){
swal({
  title: conf_title,
  text: conf_text,
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Yes",
  closeOnConfirm: false
},
function(isConfirm){
  if (isConfirm) {
location.href = conf_link;
  } else {
return false;
  }
});
}

//-->