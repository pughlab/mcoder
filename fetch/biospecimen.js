function load_biospecimen(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;

 $.ajax({
  url:"fetch/biospecimen.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listbiospecimens').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_biospecimen(search);
 }
 else
 {
  load_biospecimen();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_biospecimen(search);
 }
 else
 {
  load_biospecimen();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_biospecimen(search);
 }
 else
 {
  load_biospecimen();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_biospecimen(search);
 }
 else
 {
  load_biospecimen();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_biospecimen(search);
 }
 else
 {
  load_biospecimen();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_biospecimen(search);
 }
 else
 {
  load_biospecimen();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_biospecimen(search);
 }
 else
 {
  load_biospecimen();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_biospecimen(search);
 }
 else
 {
  load_biospecimen();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_biospecimen(search);
 }
 else
 {
  load_biospecimen();
 }
});
