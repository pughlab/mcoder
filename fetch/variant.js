function load_variants(query)
{
  const rolesdiv = document.getElementById("roles");
  const roles = rolesdiv.textContent;
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

 const search = $(this).val();
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

 const search = $(this).val();
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

 const search = $(this).val();
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

 const search = $(this).val();
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

 const search = $(this).val();
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

 const search = $(this).val();
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

 const search = $(this).val();
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

 const search = $(this).val();
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

 const search = $(this).val();
 if(search != '')
 {
  load_variants(search);
 }
 else
 {
  load_variants();
 }
});
