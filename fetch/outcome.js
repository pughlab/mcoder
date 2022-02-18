function load_outcome(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/outcome.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listoutcome').html(data);
  }
 });
}
$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_outcome(search);
 }
 else
 {
  load_outcome();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_outcome(search);
 }
 else
 {
  load_outcome();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_outcome(search);
 }
 else
 {
  load_outcome();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_outcome(search);
 }
 else
 {
  load_outcome();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_outcome(search);
 }
 else
 {
  load_outcome();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_outcome(search);
 }
 else
 {
  load_outcome();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_outcome(search);
 }
 else
 {
  load_outcome();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_outcome(search);
 }
 else
 {
  load_outcome();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_outcome(search);
 }
 else
 {
  load_outcome();
 }
});
