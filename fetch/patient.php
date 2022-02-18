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

//echo $iv;
//echo " ";
//echo bin2hex($iv);

mysqli_close($connect);

// User roles
$roles=$_POST["roles"];

$output = '';
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($conn, $_POST["query"]);

 // ID encrypted
 $enc_search="0x".bin2hex(openssl_encrypt($search, $cipher, $encryption_key, 0, $iv));


 $query = "
  SELECT HEX(id),HEX(birth), HEX(gender), HEX(race), HEX(zip), HEX(institution), study, HEX(family) FROM Patient
  WHERE id LIKE {$enc_search} AND INSTR('".$roles."', study) > 0
 ";
}
else
{
 $query = "
  SELECT * FROM Patient WHERE id LIKE '%ZZZZZZZZZZZZZZZ%' AND INSTR('".$roles."', study) > 0 ORDER BY id
 ";
}
//echo $query;
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0)
{

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

          var table = $('#patientdata').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
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



          $('#patientdata tbody')
              .on( 'mouseenter', 'td', function () {
                  var colIdx = table.cell(this).index().column;

                  $( table.cells().nodes() ).removeClass( 'highlight' );
                  $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
              } );



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

  echo '<span style="color:#143de4;text-align:center;"><i class="glyphicon glyphicon-info-sign"></i><b> Similar patient IDs have been curated:</b></span>';
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
 while($row = mysqli_fetch_array($result))
 {
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
}
else if(isset($_POST["query"]))
{

  ?>
  <body>
  <?php

 echo '<span style="color:#349A0A;text-align:center;"><i class="glyphicon glyphicon-ok"></i><b> This patient ID has not been curated yet.</b></span>';
}

mysqli_close($conn);

?>



</body>


</html>