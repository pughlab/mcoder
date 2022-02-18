function load_medication(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/medication.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listmedication').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_medication(search);
 }
 else
 {
  load_medication();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_medication(search);
 }
 else
 {
  load_medication();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_medication(search);
 }
 else
 {
  load_medication();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_medication(search);
 }
 else
 {
  load_medication();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_medication(search);
 }
 else
 {
  load_medication();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_medication(search);
 }
 else
 {
  load_medication();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_medication(search);
 }
 else
 {
  load_medication();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_medication(search);
 }
 else
 {
  load_medication();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_medication(search);
 }
 else
 {
  load_medication();
 }
});
