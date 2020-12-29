<?php

define('DB_NAME', 'C:\php\CRUD\data\database.txt');

function dataUnserialize(){
    $serializeData = file_get_contents(DB_NAME);
    return unserialize($serializeData);;
}

function dataSerializeAndPush($data){
    $serializeData = serialize($data);
    file_put_contents(DB_NAME, $serializeData, LOCK_EX);
}

function recentID(){
    return count(dataUnserialize())+1;
}

function seed(){
    $data = array(
        array(
            'id' => 1,
            'fname' => 'Kamal',
            'lname' => 'Ahamed',
            'roll' => '9'
        ),
        array(
            'id' => 2,
            'fname' => 'Rony',
            'lname' => 'Islam',
            'roll' => '8'
        ),
        array(
            'id' => 3,
            'fname' => 'Fajly',
            'lname' => 'Rabby',
            'roll' => '7'
        ),
        array(
            'id' => 4,
            'fname' => 'Anny',
            'lname' => 'Khatun',
            'roll' => '6'
        ),
        array(
            'id' => 5,
            'fname' => 'Tonny',
            'lname' => 'Khatun',
            'roll' => '5'
        ),
        array(
            'id' => 6,
            'fname' => 'Mamun',
            'lname' => 'Khan',
            'roll' => '4'
        ),
    );

    $serializeData = serialize($data);
    file_put_contents(DB_NAME, $serializeData, LOCK_EX);
}

function generateReport(){
    $serializeData = file_get_contents(DB_NAME);
    $students = dataUnserialize() ?? array();
    ?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Roll</th>
            <?php if(isAdmin() || isEditor()):?>
                <th>Action</th>
            <?php endif; ?>
        </tr>
    </thead>
    <?php
            foreach($students as $student){
            ?>
    <tbody>
        <tr>
            <td><?php printf('%s', $student['id']);?></td>
            <td><?php printf('%s %s', $student['fname'], $student['lname']);?></td>
            <td><?php printf('%s', $student['roll'])?></td>
            <?php if(isAdmin()): ?>
                <td><?php printf('<a href="/index.php?task=edit&id=%s">Edit</a> | <a href="index.php?task=delete&id=%s">Delete</a>', $student['id'],$student['id'])?>
            <?php endif; ?>

            <?php if(isEditor()): ?>
                <td><?php printf('<a href="/index.php?task=edit&id=%s">Edit</a>', $student['id'])?>
            <?php endif; ?>

            </td>
        </tr>
    </tbody>
    <?php } ?>
</table>
<?php
}

function addStudent($fname, $lname, $roll){
    
    $GLOBALS['info'] = "";
    $found = false;
    //$_roll = 0;
    $students = dataUnserialize();


    foreach($students as $_student){
        if($_student['roll'] == $roll){
            $found = true;
            //$_roll = $_student['roll'];
        break;
        }
    }

    if(!$found){
        
        $newID = count($students)+1;
        
        $student = array(
            'id' => $newID,
            'fname' => $fname,
            'lname' => $lname,
            'roll' => $roll
        );

        array_push($students,$student);
        dataSerializeAndPush($students);
        return true;
    }
    return false;
}

function getStudent($id){
    $Students = dataUnserialize() ?? "";

    foreach($Students as $student){
       if($student['id'] == $id){
           return $student;
       }
    }
    return false;
}

function updateStudent($id, $fname, $lname, $roll){
    $students = dataUnserialize();
    $duplicateRoll = false;
    var_dump($id." - ". $roll);

    foreach($students as $student){
        if($student['roll'] == $roll && $student['id'] != $id){
            var_dump($student['roll']." - ". $student['id']);
            $duplicateRoll = true;
        break;
        }
    }
    var_dump($duplicateRoll);

    if(!$duplicateRoll){

        $students[$id-1]['fname'] = $fname;
        $students[$id-1]['lname'] = $lname;
        $students[$id-1]['roll'] = $roll;
        dataSerializeAndPush($students);

    }else{
        return false;
    }
    return true;

    
}

function studentDelete($id){
    print_r($id);
    $students = dataUnserialize();
    $data = array();

   $i = 0;
    foreach($students as $student){
        if($student['id'] != $id){
            $data[$i] = $student;
            $i++;
        }

    }

    $serializeData = serialize($data);
    file_put_contents(DB_NAME, $serializeData, LOCK_EX);
}

function isAdmin(){
    if(isset($_SESSION['role'])){
        return ("Admin" == $_SESSION['role']);
    }
    return false;
    
}

function isEditor(){
    if(isset($_SESSION['role'])){
        return ("Editor" == $_SESSION['role']);
    }
    return false;   
}

function hasPrivilege(){
    if(isset($_SESSION['role'])){
        return (isAdmin() || isEditor());
    }
    return false;   
}