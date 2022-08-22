<!DOCTYPE html>
<html lang="en">

<?php
include('../configuration/db.php');
include('../configuration/mcode.php');
include('../configuration/key.php');

//Encryption
$encryption_key = hex2bin($key);
// initialization vector
$iv_query = mysqli_fetch_assoc(mysqli_query($connect, "select riv from norm"));
$iv = $iv_query['riv'];
mysqli_close($connect);

// User roles
$roles = rtrim(trim($_POST["roles"]), ",");

$output = '';
if (isset($_POST["query"])) {
  $search = mysqli_real_escape_string($conn, $_POST["query"]); // mysqli_real_escape_string is not 100% safe against SQL injections. This applies to all .php files in the fetch folder

  // ID encrypted
  $enc_search = "0x" . bin2hex(openssl_encrypt($search, $cipher, $encryption_key, 0, $iv));

  $query = "
  SELECT DISTINCT 
    HEX(Biospecimens.id), 
    Biospecimens.date, 
    Biospecimens.type, 
    Biospecimens.cellularity, 
    Biospecimens.collection, 
    Biospecimens.storage, 
    Biospecimens.bankingid, 
    Biospecimens.paired,
    Biospecimens.imaging,
    Biospecimens.comment
  FROM Biospecimens
  JOIN Patient ON Biospecimens.id = Patient.id
  WHERE Biospecimens.id = {$enc_search}
  AND FIND_IN_SET(Patient.study, '" . $roles . "') > 0
  ";
} else {
  $query = "
  SELECT * FROM Biospecimens, Patient WHERE Biospecimens.id LIKE '%ZZZZZZZZZZZZZZ%' AND Biospecimens.id = Patient.id AND INSTR('".$roles."', Patient.study) > 0 ORDER BY Patient.id
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
        $('#biospecimendata tfoot th').each(function() {
          var title = $(this).text();
          $(this).html('<input type="text" placeholder="' + title + '" />');
        });

        var table = $('#biospecimendata').DataTable({
          dom: 'Bfrtip',
          buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
          ],
          select: true,
          initComplete: function() {
            // Apply the search
            this.api().columns().every(function() {
              var that = this;

              $('input', this.footer()).on('keyup change clear', function() {
                if (that.search() !== this.value) {
                  that
                    .search(this.value)
                    .draw();
                }
              });
            });
          }
        });

        $('#biospecimendata tbody')
          .on('mouseenter', 'td', function() {
            var colIdx = table.cell(this).index().column;

            $(table.cells().nodes()).removeClass('highlight');
            $(table.column(colIdx).nodes()).addClass('highlight');
          });

        $('#biospecimendata tbody tr').on('click', function() {
          let cells = $(this).children('td');
          cellData = {
            'collectionDate': cells[1].innerText,
            'specimenType': cells[2].innerText,
            'cellularity': cells[3].innerText,
            'collection': cells[4].innerText,
            'storage': cells[5].innerText,
            'bankingID': cells[6].innerText,
            'withPairing': cells[7].innerText.toLowerCase() == 'yes',
            'withImaging': cells[8].innerText.toLowerCase() == 'yes',
            'comments': $(this).children('input[name^=rowComments]').first().val(),
            'row': $(this).children('input[name^=rowNumber]').first().val(),
            'recordtype': 'biospecimen'
          };
          $('#biodate').val(cellData['collectionDate']);
          $('button[data-id="specimentype"]').children().first().children().first().children().first().html(cellData['specimenType']);
          $('#cellularity').val(cellData['cellularity']);
          $('#collection').val(cellData['collection']);
          $('#storage').val(cellData['storage']);
          $('#bankingid').val(cellData['bankingID']);
          $('#biospecimencom').val(cellData['comments']);
          $('#matched_y').val("Yes");
          $('#matched_n').val("No");
          $('#imaging_y').val("Yes");
          $('#imaging_n').val("No");
          if (cellData['withPairing']) {
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
          if (cellData['withImaging']) {
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

      });
    </script>

    <style>
      td.highlight {
        background-color: whitesmoke !important;
      }
    </style>

  </head>

  <body>

    <?php
    echo '<span style="color:#143de4;text-align:center;"><i class="glyphicon glyphicon-info-sign"></i><b> Biospecimens have been registered for this patient:</b></span>';
    $output .= '
 <br><br>
<table id="biospecimendata" class="row-border hover order-column" style="width:100%">
<thead>
<tr>
<th>Patient Identifier</th>
<th>Date of collection</th>
<th>Specimen type</th>
<th>Specimen cellularity</th>
<th>Location of collection</th>
<th>Location of storage</th>
<th>Banking ID</th>
<th>Tumor paired with blood sample</th>
<th>Imaging available</th>
<th>Comments</th>
</tr>
</thead>
  <tbody>

 ';
    $rowNumber = 1;
    while ($row = mysqli_fetch_array($result)) {
      $decrypted_id = openssl_decrypt(hex2bin($row[0]), $cipher, $encryption_key, 0, $iv);

      $output .= '
  <tr>
   <td>' . $decrypted_id . '</td>
   <td>' . $row[1] . '</td>
   <td>' . $row[2] . '</td>
   <td>' . $row[3] . '</td>
   <td>' . $row[4] . '</td>
   <td>' . $row[5] . '</td>
   <td>' . $row[6] . '</td>
   <td>' . $row[7] . '</td>
   <td>' . $row[8] . '</td>
   <td align="center"><a href="#" role="button" class="btn btn-info" data-toggle="modal" data-target="#comment_biosp_' . $rowNumber . '" > <i class="glyphicon glyphicon-zoom-in"></i> </a></td>
   <input type="hidden" name="rowComments' . $rowNumber . '" value=' . $row[9] . ' />
  </tr>
  ';
    ?>

      <div id="comment_biosp_<?php echo $rowNumber; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comment</h4>
            </div>
            <div class="modal-body">
              <?php echo $row[9]; ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
 <th>Date of collection</th>
 <th>Specimen type</th>
 <th>Specimen cellularity</th>
 <th>Location of colelction</th>
 <th>Location of storage</th>
 <th>Banking ID</th>
 <th>Tumor paired with blood sample</th>
 <th>Imaging available</th>
 <th>Comments</th>
 </tr>
 </tfoot>
</table>';
    echo $output;
  } else if (isset($_POST["query"])) {

    ?>

    <body>
    <?php

    echo '<span style="color:#349A0A;text-align:center;"><i class="glyphicon glyphicon-ok"></i><b> No biospecimens have been registered yet for this patient.</b></span>';
  }

  mysqli_close($conn);

    ?>



    </body>


</html>