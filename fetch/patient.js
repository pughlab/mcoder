function load_patient(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/patient.php",
  method:"POST",
  data:{query:query,
    roles:roles},
  success:function(data)
  {
   $('#listpatient').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_patient(search);
 }
 else
 {
  load_patient();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_patient(search);
 }
 else
 {
  load_patient();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_patient(search);
 }
 else
 {
  load_patient();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_patient(search);
 }
 else
 {
  load_patient();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_patient(search);
 }
 else
 {
  load_patient();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_patient(search);
 }
 else
 {
  load_patient();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_patient(search);
 }
 else
 {
  load_patient();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_patient(search);
 }
 else
 {
  load_patient();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_patient(search);
 }
 else
 {
  load_patient();
 }
});
