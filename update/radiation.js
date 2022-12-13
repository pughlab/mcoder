// Save a patient
$('#updateradiation').click(function(_e) {
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
  let date = document.getElementById("radiationdate").value;
  let location = document.getElementById("radiationlocation").value;
  let type = $("#radiationpro :selected").text();
  let site = document.getElementById("radiobodysite").value;
  let intent = $("input[name='treatment_intent_radio']:checked").val();
  let comment = document.getElementById("radiationcom").value;
  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

  if (cellData === null || cellData['recordtype'].toLowerCase() !== 'radiation')
  {
    swal("Error!", "You must select a radiation to update!", "error");
    return false;
  }

  if(patientid !="" && date !="" && location !="" && type !="" && site !="" && intent != null){
    $.ajax({
      url: "update/radiation.php",
      type: "POST",
      data: {
        id: patientid,
        date: date,
        location: location,
        type: type,
        site: site,
        intent: intent,
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
                  title: "Radiation saved!",
                  text: "You can browse the database records to visualise the updated data! Your tracking ID is: " + tracking,
                  type: "success",
                  confirmButtonText: "Close"
              }, function() {
                load_radiation($('#treatmentidsource').val());
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate\nLocation\nProcedure\nBody site\nTreatment intent", "error");
    }

});
