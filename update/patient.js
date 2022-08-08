// Save a patient
$('#updatepatient').click(function(_e) {
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
  let birth = document.getElementById("birthday").value;
  let gender = $("input[name='gender']:checked").val();
  let zip = document.getElementById("zip").value;
  let institution = $("#institution :selected").text();
  let study = $("input[name='study']:checked").val();
  let family = document.getElementById("family").value;
  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

  let race = "";
  if ($("input[name='race']:checked").val() != "Other") {
    race = $("input[name='race']:checked").val();
  }
  else {
    race = document.getElementById("otherRace").value;
  }

  if(patientid !="" && birth !="" && birth !="YYYY-MM" && gender != null && race != null && zip !="" && institution !="" && study != null){
    $.ajax({
      url: "update/patient.php",
      type: "POST",
      data: {
        id: patientid,
        birth: birth,
        gender: gender,
        race: race,
        zip: zip,
        institution: institution,
        study: study,
        family: family,
        ip : ip,
        datesystem : datesystem,
        email: email,
        username: username,
        roles: roles,
        tracking: tracking
      },
      success:function(data){
        if(data=="Success") {
          // swal("Patient saved!", "You can continue with the form!", "success");
          // if (!swal.isOpened()) {
          //   window.location.reload();
          // }

          setTimeout(function() {
              swal({
                  title: "Patient saved!",
                  text: "You can browse the database records to visualise the updated data! Your tracking ID is: " + tracking,
                  type: "success",
                  confirmButtonText: "Close"
              }, function() {
                  // window.open("index.php","_self");
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate of birth\nGender\nRace\nRecruitment location\nInstitution\nStudy", "error");
    }

});
