// Save a patient
$('#savelab').click(function(_e) {
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
  let date = document.getElementById("blooddate").value;
  let location = document.getElementById("bloodlocation").value;
  let height = document.getElementById("height").value;
  let weight = document.getElementById("weight").value;
  let diastolic = document.getElementById("diastolic").value;
  let systolic = document.getElementById("systolic").value;
  let comment = document.getElementById("labcom").value;
  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

  let isValid = function(value, errorMsg) {
    let result = true;
    if ( (value !="") &&  (isNaN(value) || value < 0 ))
    {
      swal("Error!", errorMsg, "error");
      result = false;
    }
    return result;
  };

  if (
        !isValid(height, "The height must be a positive number!")
        || !isValid(weight, "The weight must be a positive number!")
        || !isValid(diastolic, "The blood pressure diastolic must be a positive number!")
        || !isValid(systolic, "The blood pressure systolic must be a positive number!")
  ) {
    return false;
  }


  if(patientid !="" && date !="" && location !=""){
    $.ajax({
      url: "insert/addlabs.php",
      type: "POST",
      data: {
        id: patientid,
        date: date,
        location: location,
        height: height,
        weight: weight,
        diastolic: diastolic,
        systolic: systolic,
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
          setTimeout(function() {
              swal({
                  title: "General lab metrics saved!",
                  text: "You can browse the database records to visualise the newly added data! Your tracking ID is: " + tracking,
                  type: "success",
                  confirmButtonText: "Close"
              }, function() {
                  load_labs($('#labidsource').val());
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate of evaluation\nLocation", "error");
    }

});
