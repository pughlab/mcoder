// Save a patient
$('#savepatient').click(function(e) {
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
  var birth = document.getElementById("birthday").value;
  var gender = $("input[name='gender']:checked").val();
  var zip = document.getElementById("zip").value;
  var institution = $("#institution :selected").text();
  var study = $("input[name='study']:checked").val();
  var family = document.getElementById("family").value;
  var trackspace = datesystem+"_"+ip+"_"+email;
  var tracking = trackspace.replace( /\s+/g, '');

  var race = "";
  if ($("input[name='race']:checked").val() != "Other") {
    race = $("input[name='race']:checked").val();
  }
  else {
    race = document.getElementById("otherRace").value;
  }

  if(patientid !="" && birth !="" && birth !="YYYY-MM" && gender != null && race != null && zip !="" && institution !="" && study != null){
    $.ajax({
      url: "insert/addpatient.php",
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate of birth\nGender\nRace\nRecruitment location\nInstitution\nStudy", "error");
    }

});
