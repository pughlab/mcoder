function load_nf1diag(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/nf1diag.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listnf1diag').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1diag(search);
 }
 else
 {
  load_nf1diag();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1diag(search);
 }
 else
 {
  load_nf1diag();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1diag(search);
 }
 else
 {
  load_nf1diag();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1diag(search);
 }
 else
 {
  load_nf1diag();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1diag(search);
 }
 else
 {
  load_nf1diag();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1diag(search);
 }
 else
 {
  load_nf1diag();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1diag(search);
 }
 else
 {
  load_nf1diag();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1diag(search);
 }
 else
 {
  load_nf1diag();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_nf1diag(search);
 }
 else
 {
  load_nf1diag();
 }
});
