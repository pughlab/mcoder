// Save a patient
$('#savelab').click(function(e) {
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
  var date = document.getElementById("blooddate").value;
  var location = document.getElementById("bloodlocation").value;
  var height = document.getElementById("height").value;
  var weight = document.getElementById("weight").value;
  var diastolic = document.getElementById("diastolic").value;
  var systolic = document.getElementById("systolic").value;
  var comment = document.getElementById("labcom").value;
  var trackspace = datesystem+"_"+ip+"_"+email;
  var tracking = trackspace.replace( /\s+/g, '');

  var regex=/^[0-9]+$/;
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
          //swal("Clinical evaluation saved!", "You can continue with the form!", "success");
          setTimeout(function() {
              swal({
                  title: "General lab metrics saved!",
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate of evaluation\nLocation", "error");
    }

});