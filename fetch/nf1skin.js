function load_nf1skin(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;

 $.ajax({
  url:"fetch/nf1skin.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listskin').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1skin(search);
 }
 else
 {
  load_nf1skin();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1skin(search);
 }
 else
 {
  load_nf1skin();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1skin(search);
 }
 else
 {
  load_nf1skin();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1skin(search);
 }
 else
 {
  load_nf1skin();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1skin(search);
 }
 else
 {
  load_nf1skin();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1skin(search);
 }
 else
 {
  load_nf1skin();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1skin(search);
 }
 else
 {
  load_nf1skin();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1skin(search);
 }
 else
 {
  load_nf1skin();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1skin(search);
 }
 else
 {
  load_nf1skin();
 }
});
