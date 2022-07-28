<?php

    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'php1';

    $conn = new mysqli($host, $user, $pass, $db);
    if(!$conn){
        die('connection failed' . msqli_connect_error());
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($_POST['sub'] == 'Add'){
            $todo = htmlspecialchars($_POST['todo']);
            if($todo == ''){
                echo '
                <div class="container mt-4">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Please fill the field
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                </div>
                ';
            } else{
                $insert_sql = "INSERT INTO todo(todo) VALUES (\"$todo\")";
                if($conn->query($insert_sql) == true){
                    echo '
                    <div class="container mt-4">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Todo added successfully
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    </div>
                    ';
                } else{
                    echo '
                    <div class="container mt-4">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . $conn->error . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div></div>
                    ';
                }
            }
        }
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($_POST['sub'] == 'Update'){
            $updated_todo = $_POST['todo'];
            $update_id = $_POST['updateid'];
            if($updated_todo == ''){
                echo 'todo field is blank';
            } else{
                $update_sql = "UPDATE `todo` SET todo = '$updated_todo' WHERE id = $update_id";
                if($conn->query($update_sql) == true){
                    echo '
                    <div class="container mt-4">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Todo updated successfully 
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> </div>
                    ';
                } else{
                    echo '
                    <div class="container mt-4">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    . ' . $conn->error . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div> </div>
                    ';
                }
            }
        }
    }

    if(isset($_GET['deleteid'])){
        $id = $_GET['deleteid'];
        $delete_sql = "DELETE FROM `todo` WHERE id = $id";
        if($conn->query($delete_sql) == true){
            header('location:index.php');
        } else{
            echo '
            <div class="container mt-4">
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
				' . $conn->error . '
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div> </div>
            ';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\bootstrap-5.1.3-dist\css\bootstrap.min.css">
    <script defer src=".\bootstrap-5.1.3-dist\js\bootstrap.min.js"></script>
    <title>Todo</title>
</head>
<body>
    <div class="container my-5">
        <h3 class="mb-4">PHP Todo App</h3>
        <form action="index.php" method="post">
            <?php
                if(isset($_GET['updateid'])){
                    $up_id = $_GET['updateid'];
                    $check_sql = "SELECT todo FROM todo WHERE id = $up_id LIMIT 1";
                    $prev_todo = $conn->query($check_sql)->fetch_assoc()['todo'];
                    echo '
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" name="updateid" readonly value="'.$up_id .'">
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Enter todo" name="todo" value="'.$prev_todo .'">
                        </div>
                        <div class="col">
                            <input type="submit" class="btn btn-primary" value="Update" name="sub">
                        </div>
                    </div>
                    ';
                } else{
                    echo '
                    <div class="row">
                        <div class="col">
                        <input type="text" class="form-control" placeholder="Enter todo" name="todo">
                        </div>
                        <div class="col">
                        <input type="submit" class="btn btn-primary" value="Add" name="sub">
                        </div>
                    </div>
                    ';
                }
            ?>
        </form>

        <table class="table mt-5 table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Todo</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <?php 
                    $select_sql = "SELECT * FROM todo";
                    $result = $conn->query($select_sql);
                    $count = 1;
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            $todo = $row['todo'];
                            echo '<tr>';
                            echo "<th scope=\"row\">$count</th>";
                            echo "<td>$todo</td>";
                            echo '<td><a class="btn btn-primary" href="index.php?updateid='.$row['id'] .'">Update</a> <a class="btn btn-danger" href="index.php?deleteid='.$row['id'] .'">Delete</a></td>';
                            echo '</tr>';
                            // echo $row['todo'] . ' <button><a href="index.php?updateid='.$row['id'] .'">Update</a></button> <button><a href="index.php?deleteid='.$row['id'] .'">Delete</a></button> <br>';
                            $count++;
                        }
                    } else{
                        echo 'no todos to display';
                    }

                ?>
                
                
                
              </tr>
            </tbody>
        </table>
    </div>
</body>
</html>