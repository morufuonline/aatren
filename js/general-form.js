<!--

var loading_selected_notification = "<option value=\"\">Loading...</option>";

$(document).ready(function () {
							
$(".general-fade").hide();

$("input").focus(function(){
$(".success").hide("fold");
});

//////////////////////////////////////////////////////////
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

//////////////////////////////////////////////////
$(".general-form").submit(function(e){
e.preventDefault();  
var formdata = new FormData(this);
$(".general-fade").show();
var page_url = $(this).attr("action");
var page_result = $(this).attr("id");

$.ajax({
url: page_url,
type: "POST",
data: formdata,
mimeTypes:"multipart/form-data",
contentType: false,
cache: false,
processData: false,
success: function(data){
$("." + page_result).html(data);
$("html, body").animate({scrollTop:0}, "slow");
$(".general-fade").hide();
},error: function(){
alert("Error occured!");
}
});

});

//////////////////////////////////////////////////
$(".general-link").click(function(e){
e.preventDefault();  
$(".general-fade").show();
var page_url = $(this).attr("href") + "gh/1/";

$.get(page_url,function(data){
$(".form-div").html(data);
$("html, body").animate({scrollTop:0}, "slow");
});

});


$(".general-link-conf").click(function(e){
e.preventDefault();  
$(".general-fade").show();
var page_url = $(this).attr("href") + "gh/1/";
var conf_title = $(this).attr("name");
var conf_text = $(this).attr("lang");

swal({
  title: conf_title,
  text: conf_text,
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Yes",
  closeOnConfirm: true
},
function(isConfirm){
  if (isConfirm) {  
$.get(page_url,function(data){
$(".form-div").html(data);
$("html, body").animate({scrollTop:0}, "slow");
});
  } else {
$(".general-fade").hide();
return false;
  }
});

});

//////////////////////////////////////////////////
$(".change-picture-label").click(function(){
var this_id = $(this).attr("id");
$(".special-member").val(this_id);
});

///////////////////////////////////////////////

$("input:checkbox:not(.sel-group)").change(function () {
var checked_class = $(this).attr("class");
var  det_unchecked = $("input:checkbox."+checked_class+":not(:checked)").length;
var  det_unchecked_all = $("input:checkbox:not(:checked)").length;

if(det_unchecked > 0){
$("input:checkbox#"+checked_class).prop("checked", false);
}else if(det_unchecked == 0 && det_unchecked_all == 1){
$("input:checkbox#"+checked_class).prop("checked", true);
}else{
$("input:checkbox#"+checked_class).prop("checked", true);
}
});

$("input.sel-group").change(function(){
var group_id = $(this).attr("id");
$("input:checkbox."+group_id).prop("checked", $(this).prop("checked"));
var  det_unchecked_all = $("input:checkbox:not(:checked)").length;
});

///////////////////////////////////////////////
$(".del-btn").click(function(){
var  det_checked_all = $("input:checkbox:not(.sel-group):checked").length;
if(det_checked_all > 0){
swal({
  title: "Confirmation",
  text: "Are you sure you want to delete " + det_checked_all + " " + conf_text + "(s)?",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Yes",
  closeOnConfirm: true
},
function(isConfirm){
  if (isConfirm) {
$(".sub-del").click();
  } else {
return false;
  }
});
}else{
sweetAlert("Notice", "Atleast one " + conf_text + " must be selected.", "error");
}
});

///////////////////////////////////////////
$( ".gen-date" ).datepicker({
dateFormat: "yy-mm-dd",
changeMonth: true,
changeYear: true,
yearRange: "1901:2100"
});

$(".js-example-basic-single").select2();

///////////////////////////////////////////
$("#ufile").change(function(){
$(".img-form").submit();
});

///////////////////////////////
$("body").find( ".general-form2" ).on( "submit", function(e) {
e.preventDefault();  
var formdata = new FormData(this);
var page_url = $(this).attr("action");
var page_result = $(this).attr("id");
var this_name = $(this).attr("name");
var this_lang = $(this).attr("lang");
var this_title = $(this).attr("title");

document.getElementById(this_name).style.display = "none";
document.getElementById(this_lang).style.display = "inline-block";
$.ajax({
url: page_url,
type: "POST",
data: formdata,
mimeTypes:"multipart/form-data",
contentType: false,
cache: false,
processData: false,
success: function(data){
document.getElementById(this_lang).style.display = "none";
document.getElementById(this_name).style.display = "inline-block";
if(this_title == "add"){
$("." + page_result).append(data);
}else if(this_title == "edit"){
$("." + page_result).html(data);
}
},error: function(){
alert("Error occured!");
document.getElementById(this_lang).style.display = "none";
document.getElementById(this_name).style.display = "inline-block";
}
});

});
////////////////////////////////


});
/////////////////////////////////

function delete_file(url, par, val, del_loader, del_result){

document.getElementById(del_loader).style.display = "inline-block";

if(val != ""){
$.post(url, {parameter : par, parameter_value : val}, function(data){ 
if(data == 1){
document.getElementById(del_result).outerHTML = "";
}
 }).error(function() { 
alert("An error occured!");
document.getElementById(del_loader).style.display = "none";
 });	
}
}

///////////////////////////////////////////
function gen_load(url, par, val, result, loading_data){
if(val != ""){
document.getElementById(result).innerHTML = loading_data;
$.post(url, {parameter2 : par, parameter_value2 : val}, function(data){ document.getElementById(result).innerHTML = data; })
.error(function() { 
sweetAlert("Notice", "An error occured!", "error");
document.getElementById(result).innerHTML = "";
 });	
}else{
document.getElementById(result).innerHTML = "";
}
}

//-->