// Save a patient
$('#saveskin').click(function(e) {
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
  var date = document.getElementById("nf1skindate").value;
  var type = $("#skin :selected").text();
  var evaluation = $("input[name='skinevaluation']:checked").val();
  var number = document.getElementById("skinnb").value;
  var location = $("input[name='skinlocation']:checked").val();
  var comment = document.getElementById("nf1skincom").value;
  var trackspace = datesystem+"_"+ip+"_"+email;
  var tracking = trackspace.replace( /\s+/g, '');

  if (isNaN(number) || number < 0 || number=="" )
    {
      swal("Error!", "The Number parameter must be a positive number!", "error");
      return false;
    }

  if(patientid !="" && date !="" && type !="" && evaluation != null && number !="" && location != null){
    $.ajax({
      url: "insert/addlesion.php",
      type: "POST",
      data: {
        id: patientid,
        date: date,
        type: type,
        evaluation: evaluation,
        number: number,
        location: location,
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
                  title: "Skin Lesion saved!",
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate of diagnosis\nType\nEvaluation\nNumber\nLocation", "error");
    }

});
