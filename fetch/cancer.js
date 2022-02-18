function load_cancer(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/cancer.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listdiseases').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cancer(search);
 }
 else
 {
  load_cancer();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cancer(search);
 }
 else
 {
  load_cancer();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cancer(search);
 }
 else
 {
  load_cancer();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cancer(search);
 }
 else
 {
  load_cancer();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cancer(search);
 }
 else
 {
  load_cancer();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cancer(search);
 }
 else
 {
  load_cancer();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cancer(search);
 }
 else
 {
  load_cancer();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cancer(search);
 }
 else
 {
  load_cancer();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cancer(search);
 }
 else
 {
  load_cancer();
 }
});
