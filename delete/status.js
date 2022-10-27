// Delete a status
function deleteStatus(button) {
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

  let patientid = cells[0].innerText;
  let date = cells[1].innerText;
  let ecog = cells[2].innerText;
  let karnofsky = cells[3].innerText;
  let comment = $(button).parent().parent().children('input[name^="rowComments"]').first().val();

  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

  $.ajax({
    url: "delete/status.php",
    type: "POST",
    data: {
      id: patientid,
      date: date,
      ecog: ecog,
      karnofsky: karnofsky,
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
                title: "Clinical evaluation deleted!",
                text: "The clinical evaluation has been deleted.",
                type: "success",
                confirmButtonText: "Close"
            }, function() {
                load_status($('#patientidsource').val());
            }, 1000);
        });
      } else {
        swal("Error!", data, "error");
      }
    },
    cache: false
  });

}
