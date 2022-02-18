function load_labs(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/labs.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listlabs').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_labs(search);
 }
 else
 {
  load_labs();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_labs(search);
 }
 else
 {
  load_labs();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_labs(search);
 }
 else
 {
  load_labs();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_labs(search);
 }
 else
 {
  load_labs();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_labs(search);
 }
 else
 {
  load_labs();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_labs(search);
 }
 else
 {
  load_labs();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_labs(search);
 }
 else
 {
  load_labs();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_labs(search);
 }
 else
 {
  load_labs();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_labs(search);
 }
 else
 {
  load_labs();
 }
});
