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
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($conn, $_POST["query"]);

 // ID encrypted
 $enc_search="0x".bin2hex(openssl_encrypt($search, $cipher, $encryption_key, 0, $iv));

 $query = "
  SELECT
    DISTINCT HEX(Variant.id),
    Variant.date,
    Variant.test,
    Variant.gene,
    Variant.cdna,
    Variant.protein,
    Variant.variantid,
    Variant.varianthgvs,
    Variant.interpretation,
    Variant.source,
    Variant.comment
  FROM Variant
  JOIN Patient ON Variant.id = Patient.id
  WHERE Variant.id = {$enc_search}
  AND FIND_IN_SET(Patient.study, '".$roles."') > 0
 ";
}
else
{
 $query = "
  SELECT * FROM Variant, Patient WHERE Variant.id LIKE '%ZZZZZZZZZZZZZZ%' AND Variant.id = Patient.id AND INSTR('".$roles."', Patient.study) > 0 ORDER BY Patient.id
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

  <!-- <style>
  div.dataTables_wrapper {
          width: 1110px;

      }
    </style> -->

      <script type="text/javascript" class="init">


      $(document).ready(function() {

        // Setup - add a text input to each footer cell
$('#variantdata tfoot th').each( function () {
    var title = $(this).text();
    $(this).html( '<input type="text" placeholder="'+title+'" />' );
} );
          //table.columns.adjust();

          var table = $('#variantdata').DataTable({
            dom: 'Bfrtip',
            buttons: [
              'copy', {
                extend: 'csv',
                filename: '<?php echo $search; ?>_variant',
                exportOptions: {
                  columns: ':not(.no-export)'
                }
              }, {
                extend: 'excel',
                filename: '<?php echo $search; ?>_variant',
                exportOptions: {
                  columns: ':not(.no-export)'
                }
              }, {
                extend: 'pdf',
                filename: '<?php echo $search; ?>_variant',
                exportOptions: {
                  columns: ':not(.no-export)'
                } 
              }, 'print'
            ],
            columnDefs: [
              {
                visible: false,
                targets: 10
              }
            ],
            "scrollX": true,
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

          $('#variantdata tbody')
              .on( 'mouseenter', 'td', function () {
                  var colIdx = table.cell(this).index().column;

                  $( table.cells().nodes() ).removeClass( 'highlight' );
                  $( table.column( colIdx ).nodes() ).addClass( 'highlight' );
              } );

          $('#variantdata tbody tr').on('click', function() {
            let cells = $(this).children('td');
            cellData = {
              'date': cells[1].innerText,
              'test': cells[2].innerText,
              'gene': cells[3].innerText,
              'cdna': cells[4].innerText,
              'protein': cells[5].innerText,
              'mutationid': cells[6].innerText,
              'mutationhgvs': cells[7].innerText,
              'interpretation': cells[8].innerText,
              'source': cells[9].innerText,
              'comment': $(this).children('input[name^=rowComments]').first().val(),
              'recordtype': 'variant'
            };
            $('#variantdate').val(cellData['date']);
            $('#testvariants').val(cellData['test']);
            $('#idgenevariant').val(cellData['gene']);
            $('#cdnavariant').val(cellData['cdna']);
            $('#proteinvariant').val(cellData['protein']);
            $('#idhgvsvariant').val(cellData['mutationid']);
            $('#hgvsvariant').val(cellData['mutationhgvs']);
            for (let interpretation of $('input[name="mutinterpvar"]')) {
              let label = $(`label[for="${$(interpretation).attr('id')}"]`);
              if($(label).text() === cellData['interpretation']) {
                $(interpretation).prop('checked', true);
              } else {
                $(interpretation).prop('checked', false);
              }
            }
            for (let source of $('input[name="gen_ori"]')) {
              let label = $(`label[for="${$(source).attr('id')}"]`);
              if ($(label).text() === cellData['source']) {
                $(source).prop('checked', true);
              } else {
                $(source).prop('checked', false);
              }
            }
            $('#variantcom').val(cellData['comment']);
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

  echo '<span style="color:#143de4;text-align:center;"><i class="glyphicon glyphicon-info-sign"></i><b> Genetic variants have been registered for this patient:</b></span>';
 $output .= '
 <br><br>
<table id="variantdata" class="row-border hover order-column" style="width:100%">
<thead>
<tr>
<th>Patient Identifier</th>
<th>Date</th>
<th>Test name</th>
<th>Gene</th>
<th>cDNA</th>
<th>Protein</th>
<th>Variant ID</th>
<th>Variant NM</th>
<th>Variant interpretation</th>
<th>Genomic source class</th>
<th>Comments</th>
<th class="no-export">Comments</th>
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
   <td>'.$row[9].'</td>
   <td>'.$row[10].'</td>
   <td align="center"><a href="#" role="button" class="btn btn-info" data-toggle="modal" data-target="#comment_variant_'.$nb.'" > <i class="glyphicon glyphicon-zoom-in"></i> </a></td>
   <input type="hidden" name="rowComments' . $nb . '" value="' . $row[10]. '" />
  </tr>
  ';
  ?>

  <div id="comment_variant_<?php echo $nb;?>" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Comment</h4>
      </div>
      <div class="modal-body">
        <?php echo $row[10];?>
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
 <th>Date</th>
 <th>Test name</th>
 <th>Gene</th>
 <th>cDNA</th>
 <th>Protein</th>
 <th>Variant ID</th>
 <th>Variant NM</th>
 <th>Variant interpretation</th>
 <th>Genomic source class</th>
 <th>Comments</th>
 <th class="no-export">Comments</th>
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
 echo '<span style="color:#349A0A;text-align:center;"><i class="glyphicon glyphicon-ok"></i><b> No genetic variants have been registered yet for this patient.</b></span>';
}

mysqli_close($conn);

?>



</body>


</html>
