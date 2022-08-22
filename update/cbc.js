// Save a patient
$('#updatecbc').click(function(_e) {
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
  let date = document.getElementById("cbcdate").value;
  let type = $("#cbctype :selected").text();
  let count = document.getElementById("cbccount").value;
  let comment = document.getElementById("cbccom").value;
  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

  if (isNaN(count) || count < 0 || count=="")
    {
      swal("Error!", "The CBC count must be a positive number!", "error");
      return false;
    }

    if (cellData === null || cellData['recordtype'].toLowerCase() !== 'cbc')
    {
      swal("Error!", "You must select a CBC test to update!", "error");
      return false;
    }

  if(patientid !="" && date !="" && type !="" && count !=""){
    $.ajax({
      url: "update/cbc.php",
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
        tracking: tracking,
        olddata: cellData
      },
      success:function(data){
        if(data=="Success") {
          setTimeout(function() {
              swal({
                  title: "CBC test saved!",
                  text: "You can browse the database records to visualise the updated data! Your tracking ID is: " + tracking,
                  type: "success",
                  confirmButtonText: "Close"
              }, function() {
                  load_cbc($('#labidsource').val());
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate\nCBC type\nCBC count", "error");
    }

});