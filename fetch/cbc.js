function load_cbc(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/cbc.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listcbc').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cbc(search);
 }
 else
 {
  load_cbc();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cbc(search);
 }
 else
 {
  load_cbc();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cbc(search);
 }
 else
 {
  load_cbc();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cbc(search);
 }
 else
 {
  load_cbc();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cbc(search);
 }
 else
 {
  load_cbc();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cbc(search);
 }
 else
 {
  load_cbc();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cbc(search);
 }
 else
 {
  load_cbc();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cbc(search);
 }
 else
 {
  load_cbc();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cbc(search);
 }
 else
 {
  load_cbc();
 }
});
