// Save a patient
$('#savetumors').click(function(e) {
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
  let date = document.getElementById("tumordate").value;
  let test = document.getElementById("testcode").value;
  let result = $("input[name='testresults']:checked").val();
  let comment = document.getElementById("tumorcom").value;
  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

  if(patientid !="" && date !="" && test !="" && result != null){
    $.ajax({
      url: "insert/addtumor.php",
      type: "POST",
      data: {
        id: patientid,
        date: date,
        test: test,
        result: result,
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
                  title: "Tumor test saved!",
                  text: "You can browse the database records to visualise the newly added data! Your tracking ID is: " + tracking,
                  type: "success",
                  confirmButtonText: "Close"
              }, function() {
                  load_tumor($('#labidsource').val());
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
