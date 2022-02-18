// Save a patient
$('#savetumors').click(function(e) {
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
  var date = document.getElementById("tumordate").value;
  var type = document.getElementById("testcode").value;
  var count = $("input[name='testresults']:checked").val();
  var comment = document.getElementById("tumorcom").value;
  var trackspace = datesystem+"_"+ip+"_"+email;
  var tracking = trackspace.replace( /\s+/g, '');

  if(patientid !="" && date !="" && type !="" && count != null){
    $.ajax({
      url: "insert/addtumor.php",
      type: "POST",
      data: {
        id: patientid,
        date: date,
        type: type,
        count: count,
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
                  title: "Tumor test saved!",
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate\nTumor test code\nTumor test result", "error");
    }

});
