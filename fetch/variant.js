function load_variants(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/variant.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listvariants').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_variants(search);
 }
 else
 {
  load_variants();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_variants(search);
 }
 else
 {
  load_variants();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_variants(search);
 }
 else
 {
  load_variants();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_variants(search);
 }
 else
 {
  load_variants();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_variants(search);
 }
 else
 {
  load_variants();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_variants(search);
 }
 else
 {
  load_variants();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_variants(search);
 }
 else
 {
  load_variants();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_variants(search);
 }
 else
 {
  load_variants();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_variants(search);
 }
 else
 {
  load_variants();
 }
});
