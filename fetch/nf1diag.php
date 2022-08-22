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

$output = '';
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($conn, $_POST["query"]);

 // ID encrypted
 $enc_search="0x".bin2hex(openssl_encrypt($search, $cipher, $encryption_key, 0, $iv));

 $query = "
  SELECT 
    DISTINCT HEX(DiagnosisNF1.id),
    DiagnosisNF1.date,
    DiagnosisNF1.diagnosis,
    DiagnosisNF1.mode,
    DiagnosisNF1.criteria,
    DiagnosisNF1.severity,
    DiagnosisNF1.visibility,
    DiagnosisNF1.age,
    DiagnosisNF1.circumference,
    DiagnosisNF1.comment 
  FROM DiagnosisNF1
  JOIN Patient ON DiagnosisNF1.id = Patient.id
  WHERE DiagnosisNF1.id = {$enc_search}
  AND FIND_IN_SET(Patient.study, '".$roles."') > 0
 ";
}
else
{
 $query = "
  SELECT * FROM DiagnosisNF1, Patient WHERE DiagnosisNF1.id LIKE '%ZZZZZZZZZZZZZZ%' AND DiagnosisNF1.id = Patient.id AND INSTR('".$roles."', Patient.study) > 0 ORDER BY Patient.id
 ";
}
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0)
{
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
$('#nf1diagdata tfoot th').each( function () {
    var title = $(this).text();
    $(this).html( '<input type="text" placeholder="'+title+'" />' );
} );

          var table = $('#nf1diagdata').DataTable({
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



          $('#nf1diagdata tbody')
              .on( 'mouseenter', 'td', function () {
                  var colIdx = table.cell(this).index().column;

                  $( table.cells().nodes() ).removeClass( 'highlight' );
                  $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
              } );

          $('#nf1diagdata tbody tr').on('click', function() {
            let cells = $(this).children('td');
            cellData = {
              'date': cells[1].innerText,
              'diagnosis': cells[2].innerText,
              'mode': cells[3].innerText,
              'criteria': cells[4].innerText,
              'severity': cells[5].innerText,
              'visibility': cells[6].innerText,
              'age': cells[7].innerText,
              'head': cells[8].innerText,
              'comment': $(this).children('input[name^=rowComments]').first().val(),
              'recordtype': 'diagnostic'
            };
            $('#nf1date').val(cellData['date']);
            $('button[data-id="nf1diag"]').children().first().children().first().children().first().html(cellData['diagnosis']);
            for(let option of $('#nf1diag option')) {
              if($(option).text() === cellData['diagnosis']) {
                $(option).attr('selected', 'selected');
                break;
              }
            }
            for (let mode of $('input[name="mode"]')) {
              if ($(mode).val() === cellData['mode']) {
                $(mode).prop('checked', true);
              } else {
                $(mode).prop('checked', false);
              }
            }
            $('button[data-id="nf1diagcri"]').children().first().children().first().children().first().html(cellData['criteria']);
            for(let option of $('#nf1diagcri option')) {
              if($(option).text() === cellData['criteria']) {
                $(option).attr('selected', 'selected');
                break;
              }
            }
            for (let severity of $('input[name="severity"]')) {
              if ($(severity).val() === cellData['severity']) {
                $(severity).prop('checked', true);
              } else {
                $(severity).prop('checked', false);
              }
            }
            for (let visibility of $('input[name="visibility"]')) {
              if ($(visibility).val() === cellData['visibility']) {
                $(visibility).prop('checked', true);
              } else {
                $(visibility).prop('checked', false);
              }
            }
            $('#puberty').val(cellData['age']);
            $('#circumference').val(cellData['head']);
            $('#nf1com').val(cellData['comment']);
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

  echo '<span style="color:#143de4;text-align:center;"><i class="glyphicon glyphicon-info-sign"></i><b> Diagnostics have been registered for this patient:</b></span>';
 $output .= '
 <br><br>
<table id="nf1diagdata" class="row-border hover order-column" style="width:100%">
<thead>
<tr>
<th>Patient Identifier</th>
<th>Date of diagnosis</th>
<th>Clinical diagnosis</th>
<th>Mode of transmission</th>
<th>Diagnostic criteria</th>
<th>Severity</th>
<th>Visibility</th>
<th>Age of puberty</th>
<th>Head circumference</th>
<th>Comments</th>
</tr>
</thead>
  <tbody>

 ';
 $nb = 1;
 while($row = mysqli_fetch_array($result))
 {
   $decrypted_id = openssl_decrypt(hex2bin($row[0]), $cipher, $encryption_key, 0, $iv);

  $output .= '
  <tr>
   <td>'.$decrypted_id.'</td>
   <td>'.$row[1].'</td>
   <td>'.$row[2].'</td>
   <td>'.$row[3].'</td>
   <td>'.$row[4].'</td>
   <td>'.$row[5].'</td>
   <td>'.$row[6].'</td>
   <td>'.$row[7].'</td>
   <td>'.$row[8].'</td>
   <td align="center"><a href="#" role="button" class="btn btn-info" data-toggle="modal" data-target="#comment_nf1diag_'.$nb.'" > <i class="glyphicon glyphicon-zoom-in"></i> </a></td>
   <input type="hidden" name="rowComments' . $nb . '" value="' . $row[9] . '"/>
  </tr>
  ';
  ?>

  <div id="comment_nf1diag_<?php echo $nb;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Comment</h4>
      </div>
      <div class="modal-body">
        <?php echo $row[9];?>
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
 <th>Clinical diagnosis</th>
 <th>Mode of transmission</th>
 <th>Diagnostic criteria</th>
 <th>Severity</th>
 <th>Visibility</th>
 <th>Age of puberty</th>
 <th>Head circumference</th>
 <th>Comments</th>
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
 echo '<span style="color:#349A0A;text-align:center;"><i class="glyphicon glyphicon-ok"></i><b> No diagnostics have been registered yet for this patient.</b></span>';
}

mysqli_close($conn);

?>



</body>


</html>
