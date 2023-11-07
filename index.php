<!DOCTYPE html>
<html>
   <head>
     <title>PHP Excel Data Import System</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
   </head>
   <body>
     <div class="container" style="padding: 50px 50px">
        <div class="panel panel-default">
          <div class="panel-heading">PHP Excel Data Import System</div>
          <div class="panel-body">
          <div class="table-responsive">
           <span id="message"></span>
              <form method="post" id="import_excel_form" enctype="multipart/form-data">
                <table class="table">
                  <tr>
                    <td width="25%" align="right">Select Excel File</td>
                    <td width="50%"><input type="file" name="import_excel" /></td>
                    <td width="25%"><input type="submit" name="import" id="import" class="btn btn-primary" value="Import" /></td>
                  </tr>
                </table>
              </form>
              <button id="explodefields" class="btn btn-primary" >Explode fields</button>
              <br /><br />
              <button id="merge" class="btn btn-primary" >Merge events</button>
              <br /><br />
              <button id="export" class="btn btn-primary" >Export to JSON</button>
          </div>
          </div>
        </div>
     </div>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  </body>
</html>
<script>
$(document).ready(function(){
  $('#import_excel_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"controller.php?action=import",
      method:"POST",
      data:new FormData(this),
      contentType:false,
      cache:false,
      processData:false,
      beforeSend:function() {
        $('#import').attr('disabled', 'disabled');
        $('#import').val('Importing...');
      },
      success:function(data) {
        $('#message').html(data);
        $('#import_excel_form')[0].reset();
        $('#import').attr('disabled', false);
        $('#import').val('Import');
      }
    })
  });

  $('#explodefields').on('click', function(event){
    event.preventDefault();
    $.ajax({
      url:"controller.php?action=explodefields",
      method:"POST",
      beforeSend:function(){
        $('#explodefields').text('Processing...');
      },
      success:function(data) {
        $('#message').html(data);
        $('#explodefields').text('Explode fields');
      }
    })
  });

  $('#merge').on('click', function(event){
    event.preventDefault();
    $.ajax({
      url:"controller.php?action=merge",
      method:"POST",
      beforeSend:function(){
        $('#merge').text('Processing...');
      },
      success:function(data) {
        $('#message').html(data);
        $('#merge').text('Merge events');
      }
    })
  });

  $('#export').on('click', function(event){
    event.preventDefault();
    $.ajax({
      url:"controller.php?action=export",
      method:"POST",
      beforeSend:function(){
        $('#export').text('Processing...');
      },
      success:function(data) {
        $('#message').html(data);
        $('#export').text('Export to JSON');
      }
    })
  });
});
</script>
