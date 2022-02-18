// Save a patient
$('#savevariant').click(function(e) {
  var ipdiv = document.getElementById("ipaddress");
  var ip = ipdiv.textContent.replace( /\s+/g, '');

  var m = new Date();
  var datesystem =
  m.getUTCFullYear() + "-" +
  ("0" + (m.getUTCMonth()+1)).slice(-2) + "-" +
  ("0" + m.getUTCDate()).slice(-2) + "-" +
  ("0" + m.getUTCHours()).slice(-2) + ":" +
  ("0" + m.getUTCMinutes()).slice(-2) + ":" +
  ("0" + m.getUTCSeconds()).slice(-2);

  var emaildiv = document.getElementById("email");
  var email = emaildiv.textContent.replace( /\s+/g, '');
  var userdiv = document.getElementById("username");
  var username = userdiv.textContent.replace( /\s+/g, '');
  var rolesdiv = document.getElementById("roles");
  var roles = rolesdiv.textContent;
  var patientid = document.getElementById("patientidsource").value.replace( /\s+/g, '');
  var date = document.getElementById("variantdate").value;
  var test = document.getElementById("testvariants").value;
  var gene = document.getElementById("idgenevariant").value;
  var cdna = document.getElementById("cdnavariant").value;
  var protein = document.getElementById("proteinvariant").value;
  var mutationid = document.getElementById("idhgvsvariant").value;
  var mutationhgvs = document.getElementById("hgvsvariant").value;
  var interpretation= $("input[name='mutinterpvar']:checked + label").text();
  var source= $("input[name='gen_ori']:checked + label").text();
  var comment = document.getElementById("variantcom").value;
  var trackspace = datesystem+"_"+ip+"_"+email;
  var tracking = trackspace.replace( /\s+/g, '');

  if(patientid !="" && date !="" && test !="" && gene !="" && interpretation != null && source != null){
    $.ajax({
      url: "insert/addvariant.php",
      type: "POST",
      data: {
        id: patientid,
        date: date,
        test: test,
        gene: gene,
        cdna: cdna,
        protein: protein,
        mutationid: mutationid,
        mutationhgvs: mutationhgvs,
        interpretation: interpretation,
        source: source,
        ip : ip,
        datesystem : datesystem,
        email: email,
        username: username,
        roles: roles,
        comment: comment,
        tracking: tracking
      },
      success:function(data){
        if(data=="Success") {
          //swal("Clinical evaluation saved!", "You can continue with the form!", "success");
          setTimeout(function() {
              swal({
                  title: "Genetic variant saved!",
                  text: "You can browse the database records to visualise the newly added data! Your tracking ID is: " + tracking,
                  type: "success",
                  confirmButtonText: "Close"
              }, function() {
                  window.open("index.php","_self");
              }, 1000);
          });
        } else {
          swal("Error!", data, "error");
        }
      },
      cache: false
    });

    }
    else{
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate\nTest name\nGene\ncDNA\nProtein\nVariant found interpretation\nGenomic source class", "error");
    }

});
