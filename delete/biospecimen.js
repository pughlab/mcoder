// delete a biospecimen
function deleteBiospecimen(button) {
  try {
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
    let cellularity = cells[3].innerText
    let collection = cells[4].innerText
    let storage = cells[5].innerText;
    let bankingid = cells[6].innerText;
    let paired = cells[7].innerText;
    let imaging = cells[8].innerText;
    let comment = $(button).parent().parent().children('input[name^="rowComments"]').first().val();
    let trackspace = datesystem+"_"+ip+"_"+email;
    let tracking = trackspace.replace( /\s+/g, '');

    $.ajax({
      url: "delete/biospecimen.php",
      type: "POST",
      data: {
        id: patientid,
        date: date,
        type: type,
        storage: storage,
        cellularity: cellularity,
        collection: collection,
        bankingid: bankingid,
        paired: paired,
        imaging: imaging,
        comment: comment,
        ip : ip,
        datesystem : datesystem,
        email: email,
        username: username,
        roles: roles,
        tracking: tracking
      },
      success:function(data){
        if(data=="Success") {
          setTimeout(function() {
              swal({
                  title: "Biospecimen deleted!",
                  text: "The biospecimen record was successfully deleted.",
                  type: "success",
                  confirmButtonText: "Close"
              }, function() {
                  load_biospecimen($('#biospecimensidsource').val());
              }, 1000);
          });
        } else {
          swal("Error!", data, "error");
        }
      },
      cache: false,
      error: (error) => {
        swal("Error!", error, "error");
      }
    });

  } catch (error) {
    swal("Error!", error, "error");
  }

}
