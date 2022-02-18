function load_cmp(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/cmp.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listcmp').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cmp(search);
 }
 else
 {
  load_cmp();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cmp(search);
 }
 else
 {
  load_cmp();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cmp(search);
 }
 else
 {
  load_cmp();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cmp(search);
 }
 else
 {
  load_cmp();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cmp(search);
 }
 else
 {
  load_cmp();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cmp(search);
 }
 else
 {
  load_cmp();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cmp(search);
 }
 else
 {
  load_cmp();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cmp(search);
 }
 else
 {
  load_cmp();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_cmp(search);
 }
 else
 {
  load_cmp();
 }
});
