function load_mutations(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/mutation.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listmutations').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_mutations(search);
 }
 else
 {
  load_mutations();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_mutations(search);
 }
 else
 {
  load_mutations();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_mutations(search);
 }
 else
 {
  load_mutations();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_mutations(search);
 }
 else
 {
  load_mutations();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_mutations(search);
 }
 else
 {
  load_mutations();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_mutations(search);
 }
 else
 {
  load_mutations();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_mutations(search);
 }
 else
 {
  load_mutations();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_mutations(search);
 }
 else
 {
  load_mutations();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_mutations(search);
 }
 else
 {
  load_mutations();
 }
});
