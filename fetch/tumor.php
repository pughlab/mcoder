<!DOCTYPE html>
<html lang="en" >
<?php
include('../configuration/db.php');
include('../configuration/mcode.php');
include('../configuration/key.php');

//Encryption
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
 $enc_search="0x".bin2hex(openssl_encrypt($search, $cipher, $encryption_key, 0, $iv));

 $query = "
  SELECT
    DISTINCT HEX(Tumor.id),
    Tumor.date,
    Tumor.test,
    Tumor.result,
    Tumor.comment
  FROM Tumor
  JOIN Patient ON Tumor.id = Patient.id
  WHERE Tumor.id = {$enc_search}
  AND FIND_IN_SET(Patient.study, '".$roles."') > 0
 ";
} else {
 $query = "
  SELECT * FROM Tumor, Patient WHERE Tumor.id LIKE '%ZZZZZZZZZZZZZZ%' AND Tumor.id = Patient.id AND INSTR('".$roles."', Patient.study) > 0 ORDER BY Patient.id
 ";
}
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
  ?>
   <head>
      <meta charset="UTF-8">
      <title></title>

      <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
      <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
      <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
      <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

      <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
      <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

      <script type="text/javascript" class="init">


      $(document).ready(function() {

        // Setup - add a text input to each footer cell
$('#tumordata tfoot th').each( function () {
    var title = $(this).text();
    $(this).html( '<input type="text" placeholder="'+title+'" />' );
} );

          var table = $('#tumordata').DataTable({
            dom: 'Bfrtip',
            buttons: [
              'copy', {
                extend: 'csv',
                filename: '<?php echo $search; ?>_tumor',
                exportOptions: {
                  columns: ':not(.no-export)'
                }
              }, {
                extend: 'excel',
                filename: '<?php echo $search; ?>_tumor',
                exportOptions: {
                  columns: ':not(.no-export)'
                }
              }, {
                extend: 'pdf',
                filename: '<?php echo $search; ?>_tumor',
                exportOptions: {
                  columns: ':not(.no-export)'
                }
              }, 'print'
            ],
            columnDefs: [
              {
                visible: false,
                targets: 4
              }
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
    <?php } ?>

          $('#tumordata tbody')
              .on( 'mouseenter', 'td', function () {
                  var colIdx = table.cell(this).index().column;

                  $( table.cells().nodes() ).removeClass( 'highlight' );
                  $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
              } );

          $('#tumordata tbody tr').on('click', function() {
            let cells = $(this).children('td');
            cellData = {
              'date': cells[1].innerText,
              'test': cells[2].innerText,
              'result': cells[3].innerText,
              'comment': $(this).children('input[name^=rowComments]').first().val(),
              'recordtype': 'tumor'
            };
            $('#tumordate').val(cellData['date']);
            $('#testcode').val(cellData['test']);
            for (let result of $('input[name="testresults"]')) {
              if ($(result).val() === cellData['result']) {
                $(result).prop('checked', true);
              } else {
                $(result).prop('checked', false);
              }
            }
            $('#tumorcom').val(cellData['comment']);
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
      <span style="color:#143de4;text-align:center;">
        <em class="glyphicon glyphicon-info-sign"></em>&nbsp;
        <strong>Tumor tests have been registered for this patient:</strong>
      </span>
      <br><br>
      <table id="tumordata" class="row-border hover order-column" style="width:100%">
        <thead>
          <tr>
            <th>Patient Identifier</th>
            <th>Date</th>
            <th>Tumor test code</th>
            <th>Tumor test result</th>
            <th>Comments</th>
            <th class="no-export">Comments</th>
            <th class="no-export">Delete</th>
          </tr>
        </thead>
        <tbody>
<?php

 $output .= '';
 $rowNumber = 1;
 while ($row = mysqli_fetch_array($result)) {
  $decrypted_id = openssl_decrypt(hex2bin($row[0]), $cipher, $encryption_key, 0, $iv);

  $output .= '
  <tr>
   <td>'.$decrypted_id.'</td>
   <td>'.$row[1].'</td>
   <td>'.$row[2].'</td>
   <td>'.$row[3].'</td>
   <td>'.$row[4].'</td>
   <td align="center"><a href="#" role="button" class="btn btn-info" data-toggle="modal" data-target="#comment_tumour_'.$rowNumber.'" > <i class="glyphicon glyphicon-zoom-in"></i> </a></td>
   <input type="hidden" name="rowComments' . $rowNumber . '" value="' . $row[4]. '" />
   <td align="center">
      <a href="#" role="button" class="btn btn-danger" id="delete_tumour_'. $rowNumber .'_btn" data-toggle="modal" data-target="#delete_tumour_' . $rowNumber . '">
        <em class="glyphicon glyphicon-trash"></em>
      </a>
    </td>
  </tr>
  ';
  ?>

  <div id="comment_tumour_<?php echo $rowNumber;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Comment</h4>
      </div>
      <div class="modal-body">
        <?php echo $row[4];?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="delete_tumour_<?php echo $rowNumber; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Delete tumor test</h4>
      </div>
      <div class="modal-body">
        <span>Are you sure? This operation cannot be undone.</span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button
          type="button"
          class="btn btn-danger"
          onclick="deleteTumor(document.getElementById('delete_tumour_<?php echo $rowNumber; ?>_btn'))">
            Delete
        </button>
      </div>
    </div>
  </div>
</div>

  <?php
  $rowNumber++;
 }
 $output .= '
 </tbody>
 <tfoot>
 <tr>
 <th>Patient Identifier</th>
 <th>Date</th>
 <th>CMP type</th>
 <th>CMP count</th>
 <th>Comments</th>
 <th class="no-export">Comments</th>
 <th class="no-export">Delete</th>
 </tr>
 </tfoot>
</table>';
 echo $output;
} elseif (isset($_POST["query"])) {
  ?>
  <body>
  <?php
 echo '<span style="color:#349A0A;text-align:center;"><i class="glyphicon glyphicon-ok"></i><b> No tumor tests have been registered yet for this patient.</b></span>';
}

mysqli_close($conn);

?>



</body>


</html>
