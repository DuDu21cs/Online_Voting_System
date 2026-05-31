<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1 class="page-header">Voters List</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Voters</li>
      </ol>
    </section>

    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>

      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat">
                <i class="fa fa-plus"></i> New
              </a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                  <th>Lastname</th>
                  <th>Firstname</th>
                  <th>Photo</th>
                  <th>Voter ID</th>
                  <th>Region</th>
                  <th>Tools</th>
                </thead>
                <tbody>
                  <?php
                    $sql = "SELECT voters.*, positions.description AS region_name, votes.candidate_id 
                            FROM voters 
                            LEFT JOIN positions ON positions.id = voters.region
                            LEFT JOIN votes ON votes.voters_id = voters.id";
                    $query = $conn->query($sql);
                    while($row = $query->fetch_assoc()){
                      $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                      echo "<tr>
                              <td>".$row['lastname']."</td>
                              <td>".$row['firstname']."</td>
                              <td>
                                <img src='".$image."' width='35px' height='35px' class='profile-img'>
                                <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['id']."'>
                                  <span class='fa fa-edit edit-icon'></span>
                                </a>
                              </td>
                              <td>".$row['voters_id']."</td>
                              <td>".$row['region_name']."</td>";
                      
                      if(!empty($row['candidate_id'])){
                        echo "<td><span class='badge bg-success'>Voted</span></td>";
                      } else {
                        echo "<td>
                                <button class='btn btn-success btn-sm edit btn-flat' data-id='".$row['id']."'>
                                  <i class='fa fa-edit'></i> Edit
                                </button>
                                <button class='btn btn-danger btn-sm delete btn-flat' data-id='".$row['id']."'>
                                  <i class='fa fa-trash'></i> Delete
                                </button>
                              </td>";
                      }
                      echo "</tr>";
                    }
                  ?>
                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
    </section>   
  </div>

  <!-- Add New Voter Modal -->
  <div class="modal fade" id="addnew">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="voters_add.php" enctype="multipart/form-data">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Add New Voter</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="voters_id">Voter ID</label>
              <input type="text" class="form-control" name="voters_id" required>
            </div>
            <div class="form-group">
              <label for="firstname">Firstname</label>
              <input type="text" class="form-control" name="firstname" required>
            </div>
            <div class="form-group">
              <label for="lastname">Lastname</label>
              <input type="text" class="form-control" name="lastname" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            <div class="form-group">
              <label for="photo">Photo</label>
              <input type="file" class="form-control" name="photo">
            </div>
            <div class="form-group">
              <label for="region">Region</label>
              <select class="form-control" name="region" required>
                <option value="" selected>- Select -</option>
                <?php
                  $sql_region = "SELECT * FROM positions";
                  $query_region = $conn->query($sql_region);
                  while($r = $query_region->fetch_assoc()){
                    echo "<option value='".$r['id']."'>".$r['description']."</option>";
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="add" class="btn btn-primary">Add</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

 <!-- Edit Voter Modal -->
<div class="modal fade" id="edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="voters_edit.php" enctype="multipart/form-data">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit Voter</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" class="id" name="id">
          
          <div class="form-group">
            <label for="edit_firstname">Firstname</label>
            <input type="text" class="form-control" id="edit_firstname" name="firstname" required>
          </div>
          <div class="form-group">
            <label for="edit_lastname">Lastname</label>
            <input type="text" class="form-control" id="edit_lastname" name="lastname" required>
          </div>
          <div class="form-group">
            <label for="edit_password">Password</label>
            <input type="password" class="form-control" id="edit_password" name="password" required>
          </div>
          <div class="form-group">
            <label for="edit_photo">Photo</label>
            <input type="file" class="form-control" name="photo">
          </div>
          <div class="form-group">
            <label for="edit_region">Region</label>
            <select class="form-control" id="edit_region" name="region" required>
              <?php
                $sql_region = "SELECT * FROM positions";
                $query_region = $conn->query($sql_region);
                while($r = $query_region->fetch_assoc()){
                  echo "<option value='".$r['id']."'>".$r['description']."</option>";
                }
              ?>
            </select>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" name="edit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


  <!-- Delete Voter Modal -->
  <div class="modal fade" id="delete">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="voters_delete.php">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Delete Voter</h4>
          </div>
          <div class="modal-body">
            <input type="hidden" class="id" name="id">
            <p>Are you sure you want to delete <strong class="fullname"></strong>?</p>
          </div>
          <div class="modal-footer">
            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
</div>
<?php include 'includes/scripts.php'; ?>

<script>
$(function(){
  $(document).on('click', '.edit', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
    $('#edit').modal('show');
  });

  $(document).on('click', '.delete', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
    $('#delete').modal('show');
  });

  $(document).on('click', '.photo', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });
});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'voters_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.id').val(response.id);
      $('#edit_firstname').val(response.firstname);
      $('#edit_lastname').val(response.lastname);
      $('#edit_password').val(response.password);
      $('.fullname').html(response.firstname+' '+response.lastname);
    }
  });
}
</script>

</body>
</html>
