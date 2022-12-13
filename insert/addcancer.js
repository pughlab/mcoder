// Save a patient
$('#savecancer').click(function(e) {
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
  let date = document.getElementById("cancerdate").value;
  let type = $("input[name='cancer_type']:checked").val();
  let histology = $("input[name='cancer_histology']:checked").val();
  let status = $("input[name='cl_disease']:checked").val();
  let location = document.getElementById("location").value;
  let side = $("input[name='location_side']:checked").val();
  let oncotree = document.getElementById("info").value;
  let clinicalsg = $("#stagegp :selected").text();
  let clinicalss = $("#stages :selected").text();
  let pathologicsg = $("#pstagegp :selected").text();
  let pathologicss = $("#pstages :selected").text();
  let comment = document.getElementById("cancercom").value;
  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

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
          setTimeout(function() {
              swal({
                  title: "Cancer saved!",
                  text: "You can browse the database records to visualise the newly added data! Your tracking ID is: " + tracking,
                  type: "success",
                  confirmButtonText: "Close"
              }, function() {
                  load_cancer($('#diseaseidsource').val());
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
