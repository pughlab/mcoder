function load_death(query)
{
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
 $.ajax({
  url:"fetch/death.php",
  method:"POST",
  data:{query:query, roles:roles},
  success:function(data)
  {
   $('#listdeath').html(data);
  }
 });
}

$('#patientidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_death(search);
 }
 else
 {
  load_death();
 }
});

$('#diseaseidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_death(search);
 }
 else
 {
  load_death();
 }
});

$('#genomicidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_death(search);
 }
 else
 {
  load_death();
 }
});

$('#treatmentidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_death(search);
 }
 else
 {
  load_death();
 }
});

$('#labidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_death(search);
 }
 else
 {
  load_death();
 }
});

$('#biospecimensidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_death(search);
 }
 else
 {
  load_death();
 }
});

$('#outcomeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_death(search);
 }
 else
 {
  load_death();
 }
});

$('#pedigreeidsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_death(search);
 }
 else
 {
  load_death();
 }
});

$('#nf1idsource').keyup(function(){

 var search = $(this).val();
 if(search != '')
 {
  load_death(search);
 }
 else
 {
  load_death();
 }
});
