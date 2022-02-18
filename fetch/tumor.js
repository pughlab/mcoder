function load_tumor(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/tumor.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listtumors').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_tumor(search);
 }
 else
 {
  load_tumor();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_tumor(search);
 }
 else
 {
  load_tumor();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_tumor(search);
 }
 else
 {
  load_tumor();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_tumor(search);
 }
 else
 {
  load_tumor();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_tumor(search);
 }
 else
 {
  load_tumor();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_tumor(search);
 }
 else
 {
  load_tumor();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_tumor(search);
 }
 else
 {
  load_tumor();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_tumor(search);
 }
 else
 {
  load_tumor();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_tumor(search);
 }
 else
 {
  load_tumor();
 }
});
