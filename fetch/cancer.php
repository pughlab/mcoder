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
  $search = mysqli_real_escape_string($conn, $_POST["query"]);

  // ID encrypted
  $enc_search = "0x" . bin2hex(openssl_encrypt($search, $cipher, $encryption_key, 0, $iv));

  $query = "
  SELECT 
    HEX(Diseases.id),
    Diseases.date,
    Diseases.type,
    Diseases.histology,
    Diseases.status,
    Diseases.code,
    Diseases.side,
    Diseases.oncotree,
    Diseases.clinicalsg,
    Diseases.clinicalss,
    Diseases.pathologicsg,
    Diseases.pathologicss,
    Diseases.comments 
  FROM Diseases
  JOIN Patient ON Diseases.id = Patient.id
  WHERE Diseases.id = {$enc_search} 
  AND FIND_IN_SET(Patient.study, '" . $roles . "') > 0
  ";
} else {
  $query = "
  SELECT * FROM Diseases, Patient WHERE Diseases.id LIKE '%ZZZZZZZZZZZZZZ%' AND Diseases.id = Patient.id AND INSTR('" . $roles . "', Patient.study) > 0 ORDER BY Patient.id
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

    <style>
      div.dataTables_wrapper {

        width: 1110px;
        overflow-x: auto;
      }
    </style>

    <script type="text/javascript" class="init">
      $(document).ready(function() {

        // Setup - add a text input to each footer cell
        $('#cancerdata tfoot th').each(function() {
          var title = $(this).text();
          $(this).html('<input type="text" placeholder="' + title + '" />');
        });

        var table = $('#cancerdata').DataTable({
          dom: 'Bfrtip',
          buttons: [
            'copy', 
            {
              extend: 'csv',
              filename: '<?php echo $search; ?>_cancer',
              customize: function(csv) {
                let rows = csv.split('\n');
                $.each(rows.slice(1), function(index, row) { // check all rows except the header
                  let cells = row.split('","');
                  cells[0] = cells[0].replace(/"/g, '');
                  cells[cells.length - 1] = $(`#cancerdata input[name=rowComments${index + 1}]`).val();
                  row = '"' + cells.join('","') + '"';
                  rows[index + 1] = row;
                });
                csv = rows.join('\n');
                return csv;
              }
            },
            {
              extend: 'excel',
              filename: '<?php echo $search; ?>_cancer'
            },
            {
              extend: 'pdf',
              filename: '<?php echo $search; ?>_cancer'
            },
            'print'
          ],
          "scrollX": true,
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



        $('#cancerdata tbody')
          .on('mouseenter', 'td', function() {
            var colIdx = table.cell(this).index().column;

            $(table.cells().nodes()).removeClass('highlight');
            $(table.column(colIdx).nodes()).addClass('highlight');
          });

        $('#cancerdata tbody tr').on('click', function() {
          let cells = $(this).children('td');
          cellData = {
            'date': cells[1].innerText,
            'type': cells[2].innerText,
            'histology': cells[3].innerText,
            'status': cells[4].innerText,
            'location': cells[5].innerText,
            'side': cells[6].innerText,
            'oncotree': cells[7].innerText,
            'clinicalsg': cells[8].innerText,
            'clinicalss': cells[9].innerText,
            'pathologicsg': cells[10].innerText,
            'pathologicss': cells[11].innerText,
            'comment': $(this).children('input[name^="rowComments"]').first().val(),
            'recordtype': 'cancer'
          };
          $('#cancerdate').val(cellData['date']);
          for (let type of $('input[name="cancer_type"]')) {
            if ($(type).val() === cellData['type']) {
              $(type).prop('checked', true);
            } else {
              $(type).prop('checked', false);
            }
          }
          for (let hist of $('input[name="cancer_histology"]')) {
            if ($(hist).val() === cellData['histology']) {
              $(hist).prop('checked', true);
            } else {
              $(hist).prop('checked', false);
            }
          }
          for (let status of $('input[name="cl_disease"]')) {
            if ($(status).val() === cellData['status']) {
              $(status).prop('checked', true);
            } else {
              $(status).prop('checked', false);
            }
          }
          $('#location').val(cellData['location']);
          for (let side of $('input[name="location_side"]')) {
            if ($(side).val() === cellData['side']) {
              $(side).prop('checked', true);
            } else {
              $(side).prop('checked', false);
            }
          }
          $('#info').val(cellData['oncotree']);
          $('#stagegp').val(cellData['clinicalsg']);
          $('button[data-id="stagegp"]').children().first().children().first().children().first().html(cellData['clinicalsg']);
          for(let stage of $('#stages option')) {
            if($(stage).text() === cellData['clinicalss']) {
              $(stage).attr('selected', 'selected');
              break;
            }
          }
          $('button[data-id="stages"]').children().first().children().first().children().first().html(cellData['clinicalss']);
          $('#pstagegp').val(cellData['pathologicsg']);
          $('button[data-id="pstagegp"]').children().first().children().first().children().first().html(cellData['pathologicsg']);
          for(let stage of $('#pstages option')) {
            if($(stage).text() === cellData['pathologicss']) {
              $(stage).attr('selected', 'selected');
              break;
            }
          }
          $('button[data-id="pstages"]').children().first().children().first().children().first().html(cellData['pathologicss']);
          $('#cancercom').val(cellData['comment']);
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

    echo '<span style="color:#143de4;text-align:center;"><i class="glyphicon glyphicon-info-sign"></i><b> Tumors have been registered for this patient:</b></span>';
    $output .= '
 <br><br>
<table id="cancerdata" class="row-border hover order-column" style="width:100%">
<thead>
<tr>
<th>Patient Identifier</th>
<th>Date of diagnosis</th>
<th>Tumor type</th>
<th>Tumor histology</th>
<th>Clinical status</th>
<th>Body location code</th>
<th>Body location side</th>
<th>Oncotree code</th>
<th>Clinical stage group</th>
<th>Clinical stage system</th>
<th>Pathologic stage group</th>
<th>Pathologic stage system</th>
<th>Comments</th>
</tr>
</thead>
  <tbody>

 ';
    $nb = 1;
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
    <td>' . $row[9] . '</td>
    <td>' . $row[10] . '</td>
    <td>' . $row[11] . '</td>
    <td align="center"><a href="#" role="button" class="btn btn-info" data-toggle="modal" data-target="#comment_cancer_' . $nb . '" > <i class="glyphicon glyphicon-zoom-in"></i> </a></td>
    <input type="hidden" name="rowComments' . $nb . '" value="' . $row[12]. '" />
  </tr>
  ';
    ?>

      <div id="comment_cancer_<?php echo $nb; ?>" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Comment</h4>
            </div>
            <div class="modal-body">
              <?php echo $row[12]; ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
          </div>

        </div>
      </div>

    <?php
      $nb++;
    }
    $output .= '
 </tbody>
 <tfoot>
 <tr>
 <th>Patient Identifier</th>
 <th>Date of diagnosis</th>
 <th>Tumor type</th>
 <th>Tumor histology</th>
 <th>Clinical status</th>
 <th>Body location code</th>
 <th>Body location side</th>
 <th>Oncotree code</th>
 <th>Clinical stage group</th>
 <th>Clinical stage system</th>
 <th>Pathologic stage group</th>
 <th>Pathologic stage system</th>
 <th>Comments</th>
 </tr>
 </tfoot>
</table>';
    echo $output;
  } else if (isset($_POST["query"])) {

    ?>

    <body>
    <?php

    echo '<span style="color:#349A0A;text-align:center;"><i class="glyphicon glyphicon-ok"></i><b> No tumors have been registered yet for this patient.</b></span>';
  }

  mysqli_close($conn);

    ?>



    </body>


</html>