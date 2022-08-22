// Save a patient
$('#updatevariant').click(function(_e) {
  let ipdiv = document.getElementById("ipaddress");
  let ip = ipdiv.textContent.replace( /\s+/g, '');

  let m = new Date();
  let datesystem =
  m.getUTCFullYear() + "-" +
  ("0" + (m.getUTCMonth()+1)).slice(-2) + "-" +
  ("0" + m.getUTCDate()).slice(-2) + "-" +
  ("0" + m.getUTCHours()).slice(-2) + ":" +
  ("0" + m.getUTCMinutes()).slice(-2) + ":" +
  ("0" + m.getUTCSeconds()).slice(-2);

  let emaildiv = document.getElementById("email");
  let email = emaildiv.textContent.replace( /\s+/g, '');
  let userdiv = document.getElementById("username");
  let username = userdiv.textContent.replace( /\s+/g, '');
  let rolesdiv = document.getElementById("roles");
  let roles = rolesdiv.textContent;
  let patientid = document.getElementById("patientidsource").value.replace( /\s+/g, '');
  let date = document.getElementById("variantdate").value;
  let test = document.getElementById("testvariants").value;
  let gene = document.getElementById("idgenevariant").value;
  let cdna = document.getElementById("cdnavariant").value;
  let protein = document.getElementById("proteinvariant").value;
  let mutationid = document.getElementById("idhgvsvariant").value;
  let mutationhgvs = document.getElementById("hgvsvariant").value;
  let interpretation= $("input[name='mutinterpvar']:checked + label").text();
  let source= $("input[name='gen_ori']:checked + label").text();
  let comment = document.getElementById("variantcom").value;
  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

  if (cellData === null || cellData['recordtype'].toLowerCase() !== 'variant')
  {
    swal("Error!", "You must select a genetic variant to update!", "error");
    return false;
  }

  if(patientid !="" && date !="" && test !="" && gene !="" && interpretation != null && source != null){
    $.ajax({
      url: "update/variant.php",
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
        tracking: tracking,
        olddata: cellData
      },
      success:function(data){
        if(data=="Success") {
          setTimeout(function() {
              swal({
                  title: "Genetic variant saved!",
                  text: "You can browse the database records to visualise the updated data! Your tracking ID is: " + tracking,
                  type: "success",
                  confirmButtonText: "Close"
              }, function() {
                  load_variants($('#genomicidsource').val());
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
