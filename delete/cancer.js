// Delete a Cancer
function deleteCancer(button) {
  let cells = $(button).parent().parent().children('td');
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
  
  let date = cells[1].innerText;
  let type = cells[2].innerText;
  let histology = cells[3].innerText;
  let status = cells[4].innerText;
  let location = cells[5].innerText;
  let side = cells[6].innerText;
  let oncotree = cells[7].innerText;
  let clinicalsg = cells[8].innerText;
  let clinicalss = cells[9].innerText;
  let pathologicsg = cells[10].innerText;
  let pathologicss = cells[11].innerText;
  let comment = $(button).parent().parent().children('input[name^="rowComments"]').first().val();

  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

  $.ajax({
    url: "delete/cancer.php",
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
      tracking: tracking,
      olddata: cellData
    },
    success:function(data){
      if(data=="Success") {
        setTimeout(function() {
            swal({
                title: "Cancer deleted!",
                text: "The cancer record was successfully deleted.",
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
