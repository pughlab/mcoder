// Save a patient
$('#savebiospecimens').click(function(e) {
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
  var date = document.getElementById("biodate").value;
  var type = $("#specimentype :selected").text();
  var cellularity = document.getElementById("cellularity").value;
  var collection = document.getElementById("collection").value;
  var storage = document.getElementById("storage").value;
  var bankingid = document.getElementById("bankingid").value;
  var paired = $("input[name='pairedopts']:checked").val();
  var imaging = $("input[name='imagingopts']:checked").val();
  var comment = document.getElementById("biospecimencom").value;
  var trackspace = datesystem+"_"+ip+"_"+email;
  var tracking = trackspace.replace( /\s+/g, '');

  if ( (cellularity !="") &&  (isNaN(cellularity) || cellularity < 0 || cellularity > 100) )
    {
      swal("Error!", "The cellularity must be a number between 0 and 100!", "error");
      return false;
    }


  if(patientid !="" && date !="" && type !="" && collection !="" && storage !="" && paired != null && imaging != null){
    $.ajax({
      url: "insert/addbiospecimen.php",
      type: "POST",
      data: {
        id: patientid,
        date: date,
        type: type,
        cellularity: cellularity,
        collection: collection,
        storage: storage,
        bankingid: bankingid,
        paired: paired,
        imaging: imaging,
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
                  title: "Biospecimen saved!",
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
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate of collection\nSpecimen type\nLocation of collection\nLocation of storage", "error");
    }

});
