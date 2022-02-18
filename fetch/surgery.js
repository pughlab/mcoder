function load_surgery(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/surgery.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listsurgery').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_surgery(search);
 }
 else
 {
  load_surgery();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_surgery(search);
 }
 else
 {
  load_surgery();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_surgery(search);
 }
 else
 {
  load_surgery();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_surgery(search);
 }
 else
 {
  load_surgery();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_surgery(search);
 }
 else
 {
  load_surgery();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_surgery(search);
 }
 else
 {
  load_surgery();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_surgery(search);
 }
 else
 {
  load_surgery();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_surgery(search);
 }
 else
 {
  load_surgery();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_surgery(search);
 }
 else
 {
  load_surgery();
 }
});
