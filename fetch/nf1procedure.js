function load_nf1procedure(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/nf1procedure.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listinvestigation').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1procedure(search);
 }
 else
 {
  load_nf1procedure();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1procedure(search);
 }
 else
 {
  load_nf1procedure();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1procedure(search);
 }
 else
 {
  load_nf1procedure();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1procedure(search);
 }
 else
 {
  load_nf1procedure();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1procedure(search);
 }
 else
 {
  load_nf1procedure();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1procedure(search);
 }
 else
 {
  load_nf1procedure();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1procedure(search);
 }
 else
 {
  load_nf1procedure();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1procedure(search);
 }
 else
 {
  load_nf1procedure();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1procedure(search);
 }
 else
 {
  load_nf1procedure();
 }
});
