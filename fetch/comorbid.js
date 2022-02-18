function load_comorbid(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/comorbid.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listcomorbid').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_comorbid(search);
 }
 else
 {
  load_comorbid();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_comorbid(search);
 }
 else
 {
  load_comorbid();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_comorbid(search);
 }
 else
 {
  load_comorbid();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_comorbid(search);
 }
 else
 {
  load_comorbid();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_comorbid(search);
 }
 else
 {
  load_comorbid();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_comorbid(search);
 }
 else
 {
  load_comorbid();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_comorbid(search);
 }
 else
 {
  load_comorbid();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_comorbid(search);
 }
 else
 {
  load_comorbid();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_comorbid(search);
 }
 else
 {
  load_comorbid();
 }
});
