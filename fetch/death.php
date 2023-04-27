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
$hasAdminRole = in_array("admin", explode(",", strtolower($roles)));
$output = '';
if (isset($_POST["query"])) {
  $search = mysqli_real_escape_string($conn, $_POST["query"]);

  // ID encrypted
  $enc_search = bin2hex(openssl_encrypt($search, $cipher, $encryption_key, 0, $iv));

  $query = "
  SELECT
    HEX(Death.id),
    Death.date,
    Death.comment
  FROM Death
  JOIN Patient ON Death.id = Patient.id
  WHERE Death.id = UNHEX(?)
  AND FIND_IN_SET(Patient.study, ?) > 0
 ";
  $stmt = $clinical_data_pdo->prepare($query);
  $stmt->bindParam(1, $enc_search, PDO::PARAM_STR);
  $stmt->bindParam(2, $roles);
} else {
  $query = "
  SELECT * FROM Death, Patient
  WHERE Death.id LIKE '%ZZZZZZZZZZZZZZ%'
  AND Death.id = Patient.id
  AND INSTR(?, Patient.study) > 0
  ORDER BY Patient.id
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
        $('#deathdata tfoot th').each(function() {
          var title = $(this).text();
          $(this).html('<input type="text" placeholder="' + title + '" />');
        });

        var table = $('#deathdata').DataTable({
          dom: 'Bfrtip',
          buttons: [
            'copy', {
              extend: 'csv',
              filename: '<?php echo $search; ?>_death',
              exportOptions: {
                columns: ':not(.no-export)'
              }
            }, {
              extend: 'excel',
              filename: '<?php echo $search; ?>_death',
              exportOptions: {
                columns: ':not(.no-export)'
              }
            }, {
              extend: 'pdf',
              filename: '<?php echo $search; ?>_death',
              exportOptions: {
                columns: ':not(.no-export)'
              }
            }, 'print'
          ],
          columnDefs: [{
            visible: false,
            targets: 2
          }],
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

        <?php if (!$hasAdminRole) { ?>
          for (let i = 0; i < 5; i++) {
            table.button(i).enable(false);
          }
        <?php } else { ?>
          table.on('buttons-action', function(e, buttonApi, dataTable, node, config) {
            const buttonText = buttonApi.text()
            if (
              buttonText.toLowerCase() === 'csv' ||
              buttonText.toLowerCase() === 'excel' ||
              buttonText.toLowerCase() === 'pdf' ||
              buttonText.toLowerCase() === 'print'
            ) {
              const m = new Date();
              const datesystem =
                m.getUTCFullYear() + "-" +
                ("0" + (m.getUTCMonth() + 1)).slice(-2) + "-" +
                ("0" + m.getUTCDate()).slice(-2) + "-" +
                ("0" + m.getUTCHours()).slice(-2) + ":" +
                ("0" + m.getUTCMinutes()).slice(-2) + ":" +
                ("0" + m.getUTCSeconds()).slice(-2);

              const ipdiv = document.getElementById("ipaddress");
              const ip = ipdiv.textContent.replace(/\s+/g, '');
              const emaildiv = document.getElementById("email");
              const email = emaildiv.textContent.replace(/\s+/g, '');
              const userdiv = document.getElementById("username");
              const username = userdiv.textContent.replace(/\s+/g, '');
              const trackspace = datesystem + "_" + ip + "_" + email;
              const tracking = trackspace.replace(/\s+/g, '');

              const tableData = dataTable.data()[0]
              const [
                id,
                date,
                comment
              ] = tableData
              $.ajax({
                url: "table_export.php",
                method: "POST",
                data: {
                  id: id,
                  date: date,
                  comment: comment,
                  format: buttonText,
                  roles: "<?php echo $roles ?>",
                  table: "death",
                  tracking: tracking
                }
              })
            }
          })
        <?php } ?>


        $('#deathdata tbody')
          .on('mouseenter', 'td', function() {
            var colIdx = table.cell(this).index().column;

            $(table.cells().nodes()).removeClass('highlight');
            $(table.column(colIdx).nodes()).addClass('highlight');
          });

        $('#deathdata tbody tr').on('click', function() {
          let cells = $(this).children('td');
          cellData = {
            'date': cells[1].innerText,
            'comments': $(this).children('input[name^=rowComments]').first().val(),
            'recordtype': 'death'
          }
          $('#death').val(cellData['date']);
          $('#deathcom').val(cellData['comments']);
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
    <span style="color:#143de4;text-align:center;">
      <em class="glyphicon glyphicon-info-sign"></em>
      <strong> This patient has passed away:</strong>
    </span>
    <br><br>
    <table id="deathdata" class="row-border hover order-column" style="width:100%">
      <thead>
        <tr>
          <th>Patient Identifier</th>
          <th>Date of death</th>
          <th>Comments</th>
          <th class="no-export">Comments</th>
          <?php if ($hasAdminRole) { ?><th class="no-export">Delete</th><?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php

        $output .= '';
        $rowNumber = 1;
        while ($row = $stmt->fetch()) {
          $decrypted_id = openssl_decrypt(hex2bin($row[0]), $cipher, $encryption_key, 0, $iv);

          $output .= '
  <tr>
    <td>' . $decrypted_id . '</td>
    <td>' . $row[1] . '</td>
    <td>' . $row[2] . '</td>
    <td align="center"><a href="#" role="button" class="btn btn-info" data-toggle="modal" data-target="#comment_death_' . $rowNumber . '" > <i class="glyphicon glyphicon-zoom-in"></i> </a></td>
    <input type="hidden" name="rowComments' . $rowNumber . '" value="' . $row[2] . '"/>';
          if ($hasAdminRole) {
            $output .= '<td align="center">
      <a href="#" role="button" class="btn btn-danger" id="delete_death_' . $rowNumber . '_btn" data-toggle="modal" data-target="#delete_death_' . $rowNumber . '">
        <em class="glyphicon glyphicon-trash"></em>
      </a>
    </td>';
          }
          $output .= '</tr>';
        ?>

          <div id="comment_death_<?php echo $rowNumber; ?>" class="modal fade" role="dialog">
            <div class="modal-dialog">

              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Comment</h4>
                </div>
                <div class="modal-body">
                  <?php echo $row[2]; ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
              </div>

            </div>
          </div>
          <?php if ($hasAdminRole) { ?>
            <div id="delete_death_<?php echo $rowNumber; ?>" class="modal fade" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete death</h4>
                  </div>
                  <div class="modal-body">
                    <span>Are you sure? This operation cannot be undone.</span>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="deleteDeath(document.getElementById('delete_death_<?php echo $rowNumber; ?>_btn'))">
                      Delete
                    </button>
                  </div>
                </div>
              </div>
            </div>

        <?php
          }
          $rowNumber++;
        }
        $output .= '
 </tbody>
 <tfoot>
 <tr>
 <th>Patient Identifier</th>
 <th>Date of death</th>
 <th>Comments</th>
 <th class="no-export">Comments</th>';
        if ($hasAdminRole) {
          $output .= '<th class="no-export">Delete</th>';
        }
        $output .= '</tr>
 </tfoot>
</table>';
        echo $output;
      } elseif (isset($_POST["query"])) {
        ?>

        <body>
        <?php
        echo '<span style="color:#349A0A;text-align:center;"><i class="glyphicon glyphicon-ok"></i><b> No date of death has been registered yet for this patient.</b></span>';
      }

      mysqli_close($conn);
      $clinical_data_pdo = $mcode_db_pdo = null;
        ?>



        </body>


</html>