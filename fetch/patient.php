<!DOCTYPE html>
<html lang="en" >

<?php
include('../configuration/db.php');
include('../configuration/mcode.php');
include('../configuration/key.php');

//Encryption
//$encryption_key = base64_decode($key);
$encryption_key = hex2bin($key);

// initialization vector
$iv_query= mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv=$iv_query['riv'];

mysqli_close($connect);

// User roles
$roles=rtrim(trim($_POST["roles"]), ",");
$hasAdminRole = in_array("admin", explode(",", strtolower($roles)));
$output = '';
if (isset($_POST["query"])) {
 $search = mysqli_real_escape_string($conn, $_POST["query"]);

 // ID encrypted
 $enc_search = bin2hex(openssl_encrypt($search, $cipher, $encryption_key, 0, $iv));


 $query = "
  SELECT
    HEX(id),
    HEX(birth),
    HEX(gender),
    HEX(race),
    HEX(zip),
    HEX(institution),
    study,
    HEX(family)
  FROM Patient
  WHERE id = UNHEX(?)
  AND FIND_IN_SET(study, ?) > 0
 ";
 $stmt = $clinical_data_pdo->prepare($query);
  $stmt->bindParam(1, $enc_search, PDO::PARAM_STR);
  $stmt->bindParam(2, $roles);
} else {
  $query = "
  SELECT * FROM Patient
  WHERE id LIKE '%ZZZZZZZZZZZZZZZ%'
  AND INSTR(?, study) > 0
  ORDER BY id
  ";
  $stmt = $clinical_data_pdo->prepare($query);
  $stmt->bindParam(1, $roles);
}
$stmt->execute();
if ($stmt->rowCount() > 0) {

?>

   <head>
      <meta charset="UTF-8">
      <title></title>

      <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
      <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
      <link rel="stylesheet" href="../css/bootstrap/css/boostrap-iso.css" /> -->

      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
      <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
      <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

      <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">



      <script type="text/javascript" class="init">


      $(document).ready(function() {



        // Setup - add a text input to each footer cell
$('#patientdata tfoot th').each( function () {
    var title = $(this).text();
    $(this).html( '<input type="text" placeholder="'+title+'" />' );
} );

          let table = $('#patientdata').DataTable({
            dom: 'Bfrtip',
            buttons: [
              'copy', {
                extend: 'csv',
                filename: '<?php echo $search; ?>_patient'
              }, {
                extend: 'excel',
                filename: '<?php echo $search; ?>_patient'
              }, {
                extend: 'pdf',
                filename: '<?php echo $search; ?>_patient'
              }, 'print'
            ],
            initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;

                $( 'input', this.footer() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );
        }
    });


    <?php if (!$hasAdminRole) { ?>
      for (let i = 0; i < 5; i++) {
        table.button(i).enable(false);
      }
    <?php } else { ?>
      table.on('buttons-action', function (e, buttonApi, dataTable, node, config) {
        const buttonText = buttonApi.text()
        if (
          buttonText.toLowerCase() === 'csv'
          || buttonText.toLowerCase() === 'excel'
          || buttonText.toLowerCase() === 'pdf'
          || buttonText.toLowerCase() === 'print'
        ) {
          const m = new Date();
          const datesystem =
          m.getUTCFullYear() + "-" +
          ("0" + (m.getUTCMonth()+1)).slice(-2) + "-" +
          ("0" + m.getUTCDate()).slice(-2) + "-" +
          ("0" + m.getUTCHours()).slice(-2) + ":" +
          ("0" + m.getUTCMinutes()).slice(-2) + ":" +
          ("0" + m.getUTCSeconds()).slice(-2);

          const ipdiv = document.getElementById("ipaddress");
          const ip = ipdiv.textContent.replace( /\s+/g, '');
          const emaildiv = document.getElementById("email");
          const email = emaildiv.textContent.replace( /\s+/g, '');
          const userdiv = document.getElementById("username");
          const username = userdiv.textContent.replace( /\s+/g, '');
          const trackspace = datesystem+"_"+ip+"_"+email;
          const tracking = trackspace.replace( /\s+/g, '');

          const tableData = dataTable.data().toArray()
          const keys = [
            'id',
            'birth',
            'gender',
            'race',
            'zip',
            'institution',
            'study',
            'family'
          ]
          const data = []
          for (let i = 0; i < tableData.length; i++) {
            const row = tableData[i];
            const rowObject = keys.reduce((obj, key, index) => {
              return { ...obj, [key]: row[index] }
            }, {})
            data.push(rowObject)
          }

          $.ajax({
            url: "table_export.php",
            method: 'POST',
            data: {
              data: JSON.stringify(data),
              format: buttonText,
              roles: "<?php echo $roles ?>",
              table: "patient",
              tracking: tracking
            }
          })
        }
      })
    <?php } ?>

          $('#patientdata tbody')
              .on( 'mouseenter', 'td', function () {
                  var colIdx = table.cell(this).index().column;

                  $( table.cells().nodes() ).removeClass( 'highlight' );
                  $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
              } );

          $('#patientdata tbody tr').on('click', function() {
            let cells = $(this).children('td');
            cellData = {
              'birth': cells[1].innerText,
              'gender': cells[2].innerText,
              'race': cells[3].innerText,
              'zip': cells[4].innerText,
              'institution': cells[5].innerText,
              'study': cells[6].innerText,
              'family': cells[7].innerText,
              'recordtype': 'patient'
            }
            $('#birthday').val(cellData['birth']);
            for (let gender of $('input[name="gender"]')) {
              if ($(gender).val() === cellData['gender']) {
                $(gender).prop('checked', true);
              } else {
                $(gender).prop('checked', false);
              }
            }
            switch (cellData['race']) {
              case 'White':
                $('#white').prop('checked', true);
                $('#asian').prop('checked', false);
                $('#black').prop('checked', false);
                $('#filipino').prop('checked', false);
                $('#latin').prop('checked', false);
                $('#other').prop('checked', false);
                otherRaceHide();
                break;
              case 'Asian':
                $('#white').prop('checked', false);
                $('#asian').prop('checked', true);
                $('#black').prop('checked', false);
                $('#filipino').prop('checked', false);
                $('#latin').prop('checked', false);
                $('#other').prop('checked', false);
                otherRaceHide();
                break;
              case 'Black-African':
                $('#white').prop('checked', false);
                $('#asian').prop('checked', false);
                $('#black').prop('checked', true);
                $('#filipino').prop('checked', false);
                $('#latin').prop('checked', false);
                $('#other').prop('checked', false);
                otherRaceHide();
                break;
              case 'Filipino':
                $('#white').prop('checked', false);
                $('#asian').prop('checked', false);
                $('#black').prop('checked', false);
                $('#filipino').prop('checked', true);
                $('#latin').prop('checked', false);
                $('#other').prop('checked', false);
                otherRaceHide();
                break;
              case 'Latin-American':
                $('#white').prop('checked', false);
                $('#asian').prop('checked', false);
                $('#black').prop('checked', false);
                $('#filipino').prop('checked', false);
                $('#latin').prop('checked', true);
                $('#other').prop('checked', false);
                otherRaceHide();
                break;
              default:
                $('#white').prop('checked', false);
                $('#asian').prop('checked', false);
                $('#black').prop('checked', false);
                $('#filipino').prop('checked', false);
                $('#latin').prop('checked', false);
                $('#other').prop('checked', true);
                $('#otherRace').val(cellData['race']);
                otherRaceShow();
            }
            $('#zip').val(cellData['zip']);
            $('button[data-id="institution"]')
              .children()
              .first()
              .children()
              .first()
              .children()
              .first()
              .html(cellData['institution']);
            for(let option of $('#institution option')) {
              if($(option).text() === cellData['institution']) {
                $(option).attr('selected', 'selected');
                break;
              }
            }
            for (let study of $('input[name="study"]')) {
              if ($(study).val() === cellData['study']) {
                $(study).prop('checked', true);
              } else {
                $(study).prop('checked', false);
              }
            }
            $('#family').val(cellData['family']);
          });

      } );

    	</script>

      <style>
      td.highlight {
    background-color: whitesmoke !important;
}
</style>

    </head>

    <body>


<?php

  echo '<span style="color:#143de4;text-align:center;">
    <i class="glyphicon glyphicon-info-sign"></i>
    <b> Similar patient IDs have been curated:</b>
  </span>';
 $output .= '
 <br><br>
<table id="patientdata" class="row-border hover order-column" style="width:100%">
<thead>
<tr>
 <th>Patient Identifier</th>
 <th>Date of birth</th>
 <th>Gender</th>
 <th>Race</th>
 <th>Recruitment location</th>
 <th>Institution</th>
 <th>Study</th>
 <th>Family</th>
</tr>
</thead>
  <tbody>

 ';
 while ($row = $stmt->fetch()) {
   //openssl_decrypt(hex2bin(substr($row["id"], 2)), $cipher, $encryption_key, 0, $iv);
   $decrypted_id = openssl_decrypt(hex2bin($row[0]), $cipher, $encryption_key, 0, $iv);
   $decrypted_birth = openssl_decrypt(hex2bin($row[1]), $cipher, $encryption_key, 0, $iv);
   $decrypted_gender = openssl_decrypt(hex2bin($row[2]), $cipher, $encryption_key, 0, $iv);
   $decrypted_race = openssl_decrypt(hex2bin($row[3]), $cipher, $encryption_key, 0, $iv);
   $decrypted_zip = openssl_decrypt(hex2bin($row[4]), $cipher, $encryption_key, 0, $iv);
   $decrypted_institution = openssl_decrypt(hex2bin($row[5]), $cipher, $encryption_key, 0, $iv);
   $decrypted_family = openssl_decrypt(hex2bin($row[7]), $cipher, $encryption_key, 0, $iv);

  $output .= '
   <tr>
    <td>'.$decrypted_id.'</td>
    <td>'.$decrypted_birth.'</td>
    <td>'.$decrypted_gender.'</td>
    <td>'.$decrypted_race.'</td>
    <td>'.$decrypted_zip.'</td>
    <td>'.$decrypted_institution.'</td>
    <td>'.$row[6].'</td>
    <td>'.$decrypted_family.'</td>
   </tr>
  ';
 }

 $output .= '
 </tbody>
 <tfoot>
 <tr>
  <th>Patient Identifier</th>
  <th>Date of birth</th>
  <th>Gender</th>
  <th>Race</th>
  <th>Recruitment location</th>
  <th>Institution</th>
  <th>Study</th>
  <th>Family</th>
 </tr>
 </tfoot>
</table>';
 echo $output;
} elseif (isset($_POST["query"])) {

  ?>
  <body>
  <?php

 echo '<span style="color:#349A0A;text-align:center;">
    <i class="glyphicon glyphicon-ok"></i>
    <b> This patient ID has not been curated yet.</b>
  </span>';
}

mysqli_close($conn);
$clinical_data_pdo = $mcode_db_pdo = null;
?>



</body>


</html>
