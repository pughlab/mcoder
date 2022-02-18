function load_nf1manif(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/nf1manif.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listmanifestations').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1manif(search);
 }
 else
 {
  load_nf1manif();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1manif(search);
 }
 else
 {
  load_nf1manif();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1manif(search);
 }
 else
 {
  load_nf1manif();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1manif(search);
 }
 else
 {
  load_nf1manif();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1manif(search);
 }
 else
 {
  load_nf1manif();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1manif(search);
 }
 else
 {
  load_nf1manif();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1manif(search);
 }
 else
 {
  load_nf1manif();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1manif(search);
 }
 else
 {
  load_nf1manif();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1manif(search);
 }
 else
 {
  load_nf1manif();
 }
});
