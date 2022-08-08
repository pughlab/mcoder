$('#biospecimendata tbody').on('updated', 'tr', function(_event, newData) {
  $('#biodate').val(newData['collectionDate']);
  $('button[data-id="specimentype"]').children().first().children().first().children().first().html(newData['specimenType']);
  $('#cellularity').val(newData['cellularity']);
  $('#collection').val(newData['collection']);
  $('#storage').val(newData['storage']);
  $('#bankingid').val(newData['bankingID']);
  $('#biospecimencom').val(newData['comments']);
  if (newData['withPairing']) {
    $('#matched_y').prop('checked', true);
    $('#matched_y').parent().addClass('active');
    $('#matched_n').prop('checked', false);
    $('#matched_n').parent().removeClass('active');
  } else {
    $('#matched_n').prop('checked', true);
    $('#matched_n').parent().addClass('active');
    $('#matched_y').prop('checked', false);
    $('#matched_y').parent().removeClass('active');
  }
  if (newData['withImaging']) {
    $('#imaging_y').prop('checked', true);
    $('#imaging_y').parent().addClass('active');
    $('#imaging_n').prop('checked', false);
    $('#imaging_n').parent().removeClass('active');
  } else {
    $('#imaging_n').prop('checked', true);
    $('#imaging_n').parent().addClass('active');
    $('#imaging_y').prop('checked', false);
    $('#imaging_y').parent().removeClass('active');
  }
});

// Save a patient
$('#updatebiospecimens').click(function(_e) {
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
  let date = document.getElementById("biodate").value;
  let type = $("#specimentype :selected").text();
  let cellularity = document.getElementById("cellularity").value;
  let collection = document.getElementById("collection").value;
  let storage = document.getElementById("storage").value;
  let bankingid = document.getElementById("bankingid").value;
  let paired = $("input[name='pairedopts']:checked").val();
  let imaging = $("input[name='imagingopts']:checked").val();
  let comment = document.getElementById("biospecimencom").value;
  let trackspace = datesystem+"_"+ip+"_"+email;
  let tracking = trackspace.replace( /\s+/g, '');

  if ( (cellularity !="") &&  (isNaN(cellularity) || cellularity < 0 || cellularity > 100) )
    {
      swal("Error!", "The cellularity must be a number between 0 and 100!", "error");
      return false;
    }

  if (cellData === null || cellData['recordtype'].toLowerCase() !== 'biospecimen')
  {
    swal("Error!", "You must select a biospecimen to update!", "error");
    return false;
  }


  if(
    patientid !=="" 
    && date !=="" 
    && type !=="" 
    && collection !=="" 
    && storage !=="" 
    && paired !== null 
    && imaging != null
  ){
    $.ajax({
      url: "update/biospecimen.php",
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
        tracking: tracking,
        olddata: cellData
      },
      success:function(data){
        if(data=="Success") {
          //swal("Clinical evaluation saved!", "You can continue with the form!", "success");
          setTimeout(function() {
              swal({
                  title: "Biospecimen saved!",
                  text: "You can browse the database records to visualise the updated data! Your tracking ID is: " + tracking,
                  type: "success",
                  confirmButtonText: "Close"
              }, function() {
                  // window.open("index.php","_self");
              }, 1000);
          });
          $('#biospecimendata tbody tr').trigger('updated',[{
            'collectionDate': date,
            'specimenType': type,
            'cellularity': cellularity,
            'collection': collection,
            'storage': storage,
            'bankingID': bankingid,
            'withPairing': paired.toLowerCase() == 'yes',
            'withImaging': imaging.toLowerCase() == 'yes',
            'comments': comment
          }]);
        } else {
          swal("Error!", data, "error");
        }
        cellData = null;
      },
      cache: false
    });

    }
    else{
      swal("Error!", "Some fields are missing! Please make sure to fill all the following fields:\nPatient Identifier\nDate of collection\nSpecimen type\nLocation of collection\nLocation of storage", "error");
    }

});
