function load_radiation(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/radiation.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listradiation').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_radiation(search);
 }
 else
 {
  load_radiation();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_radiation(search);
 }
 else
 {
  load_radiation();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_radiation(search);
 }
 else
 {
  load_radiation();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_radiation(search);
 }
 else
 {
  load_radiation();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_radiation(search);
 }
 else
 {
  load_radiation();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_radiation(search);
 }
 else
 {
  load_radiation();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_radiation(search);
 }
 else
 {
  load_radiation();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_radiation(search);
 }
 else
 {
  load_radiation();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_radiation(search);
 }
 else
 {
  load_radiation();
 }
});
