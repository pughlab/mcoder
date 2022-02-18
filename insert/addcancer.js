// Save a patient
$('#savecancer').click(function(e) {
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
  var date = document.getElementById("cancerdate").value;
  var type = $("input[name='cancer_type']:checked").val();
  var histology = $("input[name='cancer_histology']:checked").val();
  var status = $("input[name='cl_disease']:checked").val();
  var location = document.getElementById("location").value;
  var side = $("input[name='location_side']:checked").val();
  var oncotree = document.getElementById("info").value;
  var clinicalsg = $("#stagegp :selected").text();
  var clinicalss = $("#stages :selected").text();
  var pathologicsg = $("#pstagegp :selected").text();
  var pathologicss = $("#pstages :selected").text();
  var comment = document.getElementById("cancercom").value;
  var trackspace = datesystem+"_"+ip+"_"+email;
  var tracking = trackspace.replace( /\s+/g, '');

  if(patientid !="" && date !="" && type != null && histology != null && status != null && location !="" && side != null && oncotree !=""){
    $.ajax({
      url: "insert/addcancer.php",
      type: "POST",
      data: {
        id: patientid,
        date: date,
        type: type,
        histology: histology,
        status: status,
        location: location,
        side: side,
        oncotree: oncotree,
        clinicalsg: clinicalsg,
        clinicalss: clinicalss,
        pathologicsg: pathologicsg,
        pathologicss: pathologicss,
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
                  title: "Cancer saved!",
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate of diagnosis\nTumnour type\nTumour histology\nClinical status\nBody location code\nBody location side\nOncotree", "error");
    }

});
