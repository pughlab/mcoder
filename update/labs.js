// Save a patient
$('#updatelab').click(function(_e) {
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

  let regex=/^[0-9]+$/;
  if ( (height !="") &&  (isNaN(height) || height < 0 ))
    {
      swal("Error!", "The height must be a positive number!", "error");
      return false;
    }

  if ( (weight !="") &&  (isNaN(weight) || weight < 0 ))
    {
      swal("Error!", "The weight must be a positive number!", "error");
      return false;
    }

  if ( (diastolic !="") &&  (isNaN(diastolic) || diastolic < 0 ))
    {
      swal("Error!", "The blood pressure diastolic must be a positive number!", "error");
      return false;
    }

  if ( (systolic !="") &&  (isNaN(systolic) || systolic < 0 ))
    {
      swal("Error!", "The blood pressure systolic must be a positive number!", "error");
      return false;
    }


  if(patientid !="" && date !="" && location !=""){
    $.ajax({
      url: "update/labs.php",
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
        tracking: tracking,
        olddata: cellData
      },
      success:function(data){
        if(data=="Success") {
          //swal("Clinical evaluation saved!", "You can continue with the form!", "success");
          setTimeout(function() {
              swal({
                  title: "General lab metrics saved!",
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate of evaluation\nLocation", "error");
    }

});
