// Save a patient
$('#savediag').click(function(e) {
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
  let date = document.getElementById("nf1date").value;
  let diagnosis = $("#nf1diag :selected").text();
  let mode = $("input[name='mode']:checked").val();
  let criteria = $("#nf1diagcri :selected").text();
  let severity = $("input[name='severity']:checked").val();
  let visibility = $("input[name='visibility']:checked").val();
  let age = document.getElementById("puberty").value;
  let head = document.getElementById("circumference").value;
  let comment = document.getElementById("nf1com").value;
  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

  if ( (age !="") &&  (isNaN(age) || age < 0))
    {
      swal("Error!", "The age of puberty must be a positive number!", "error");
      return false;
    }

    if ( (head !="") &&  (isNaN(head) || head < 0))
      {
        swal("Error!", "The head circumference must be a positive number!", "error");
        return false;
      }


  if(patientid !="" && date !="" && mode != null && criteria !="" && severity != null && visibility != null){
    $.ajax({
      url: "insert/adddiagnostic.php",
      type: "POST",
      data: {
        id: patientid,
        date: date,
        diagnosis: diagnosis,
        mode: mode,
        criteria: criteria,
        severity: severity,
        visibility: visibility,
        age: age,
        head: head,
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
                  title: "Diagnostic saved!",
                  text: "You can browse the database records to visualise the newly added data! Your tracking ID is: " + tracking,
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate of diagnosis\nClinical diagnosis\nMode of transmission\nDiagnostic criteria\nSeverity\nVisibility", "error");
    }

});
