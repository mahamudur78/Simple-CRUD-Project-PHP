<?php
session_start();
require_once ('function.php');
$task = $_GET['task'] ?? 'report';
$error = $_GET['error'] ?? '0';
$info = '';
if('seed' == $task){
  seed();
  $info = 'Seedin is Complete';
}

$fname = '';
$lname = '';
$roll = '';
$id ='';

if(isset($_POST['submit'])){
  $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
  $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
  $roll = filter_input(INPUT_POST, 'roll', FILTER_SANITIZE_STRING);
  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);



  if(isset($id)){
    if($fname !='' && $lname !='' && $roll != ''){
      $result = updateStudent($id, $fname, $lname, $roll);

      if($result){
        header('location: /index.php?task=report');
      }else{
        $error = 1;
      }      
    }
  }else{
    if($fname !='' && $lname != '' && $roll != ''){
      $result = addStudent($fname, $lname, $roll);
      if($result){
        header('location: /index.php?task=report');
      }else{
        $error = 1;
      }
    }
  }
}

if('edit' == $task){
  if(!hasPrivilege()){
    header('location: /index.php?task=report');
    return;
  }
}

if('add' == $task){
  if(!hasPrivilege()){
    header('location: /index.php?task=report');
    return;
  }
}

if('delete' == $task){
  if(isAdmin()){
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    var_dump($id);
    studentDelete($id);
    $info = "Student Delete Seccessful";
    header('location: /index.php?task=report');
  }else{
    header('location: /index.php?task=report');
  }

}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Project 2 - CRUD</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>

    <?php include_once 'inc/templates/nav.php'; ?>
    <div class="container">
        <div class="row">
            <h3>Project 2 - CRUD</h3>
            <p>A Sample project to perfom CRUD operations using plain file and PHP</p>
            <hr />
            <?php 
              if($info != ''){
                echo "<h3>".$info."</h3>"; 
              }
            ?>
        </div>
        <!-- Student Report View -->
        <!-- Error Report-->
        <?php if('1' == $error):?>
          <div>
            <blockquote>
              Duplicate Roll number. Please Try again.
            </blockquote>
          </div>
        <?php endif; ?>
        <!-- End error Report --> 

        <!-- Student Report View -->
        <?php if('report' == $task):?>
        <div class="row">
            <div class="table-responsive width:100%">
                <?php generateReport(); ?>
            </div>
        </div>
        <?php endif?>
        <!-- End Student Report View -->

        <!-- Add Student-->
        <?php if('add' == $task):?>
        <div class="table-responsive width:100%">
            <form action="/index.php?task=add" method="POST">
                <div class="form-group">
                    <label for="id"><?php echo "ID: ". recentID(); ?></label>
                </div>
                <div class="form-group">
                    <label for="fname">Fist Name:</label>
                    <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $fname; ?>">
                </div>
                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" class="form-control" name="lname" id="lname" value="<?php echo $lname; ?>">
                </div>
                <div class="form-group">
                    <label for="roll">Roll:</label>
                    <input type="number" class="form-control" name="roll" id="roll" value="<?php echo $roll; ?>">
                </div>
                <button type="submit" name="submit" class="btn btn-default">Submit</button>
            </form>

        </div>

        <?php endif ?>
        <!-- End Add Student -->

         <!-- Edit Student-->
         <?php if('edit' == $task):
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            $student = getStudent($id);
            if($student):
              ?>
              <div class="table-responsive width:100%">
                  <form action="" method="POST">
                      <div class="form-group">
                        
                      </div>
                      <div class="form-group">
                      <input type="hidden" value="<?php echo $id ?>" name="id">
                          <label for="fname">Fist Name:</label>
                          <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $student['fname']; ?>">
                      </div>
                      <div class="form-group">
                          <label for="lname">Last Name:</label>
                          <input type="text" class="form-control" name="lname" id="lname" value="<?php echo $student['lname']; ?>">
                      </div>
                      <div class="form-group">
                          <label for="roll">Roll:</label>
                          <input type="number" class="form-control" name="roll" id="roll" value="<?php echo $student['roll']; ?>">
                      </div>
                      <button type="submit" name="submit" class="btn btn-default">Update</button>
                  </form>

              </div>
         <?php 
          endif; 
        endif;
        ?>
        <!-- End Edit Student -->

    </div>
</body>

</html>