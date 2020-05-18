<?php 
    session_start();
    include('server.php');

    /*!-- course_ID from course user choose --*/
    $course_ID = $_GET['id'];

    /*!-- logged in user information --*/
    $id = $_SESSION['username'];
    $query = " SELECT * FROM teacher_users WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['name'];
        $faculty = $row['faculty'];
        $department = $row['department'];
    }
    $currentDate = date("jS F Y h:i A") . "<br>";

    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
    }

    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header('location: login.php');
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attending Approve</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    
</head>
<body>

    <input type="checkbox" id="check">
    <!--header area start-->
    <header>
      <label for="check">
        <i class="fas fa-bars" id="sidebar_btn"></i>
      </label>
      <div class="left_area">
        <h3>Attending System <span> Teacher</span></h3>
      </div>
    </header>
    <!--header area end-->
    
    <!--sidebar start -->
    <div class="sidebar">
        <center>
            <img src="chula_logo_index.jpg" class="profile_image" alt="">
            <h4>Chulalongkorn University</h4>
        </center>
        <a href="index.php"><i class="fas fa-home"></i><span>Home</span></a>
        <a href="course.php"><i class="fas fa-table"></i><span>Approve</span></a>
        <a href="history.php"><i class="fas fa-history"></i><span>History</span></a>
        <a href="index.php?logout='1'" style="color: #e37aa1;"><i class="fas fa-power-off"></i><span>Logout</span></a>
        
        <div class="sidebar_info_user">
            <p><?php echo $currentDate; ?></p>
            <p><?php echo $name; ?></p>
            <p><?php echo $department; ?></p>
            <p><?php echo $faculty; ?></p>
        </div>

    </div>    
    <!--sidebar end-->
    
    <div class="content">
    
        <div class="table">
            <table border="1" align='center'>
                <tr>
                    <th>Section</th>
                    <th>Student Number</th>
                    <th>Attending Students</th> 
                </tr>

        <?php
            $id = $_SESSION['username'];
            $query = "SELECT * FROM course WHERE course_ID = '".$_GET["id"]."' ";
            $result = mysqli_query($conn, $query);
                    
            if (mysqli_num_rows($result) > 0) {

                while($rowpost = mysqli_fetch_array($result)) { 
                    $course_name = $rowpost['course_name'];
                    $academic_year = $rowpost['academic_year'];
                    $semester = $rowpost['semester'];
                    echo "<tr>";
        ?>
                    <td><center><?php echo $rowpost['section']; ?></center></td>
                    <td><center><?php echo $rowpost['student_number']; ?></center></td>
                    <td><center><a href="approve.php?id=<?php echo $rowpost['course_ID'];?>" role="button" class="btn2">Click</a></center></td>
        <?php
                    echo "</tr>"; 
                }
        
            } else {
                echo "You are not having course in this term";
            }
        ?>

            <h1><?php echo $course_ID; ?> <?php echo $course_name; ?></h1>

            <div class="head_course">
                <p>
                    <a>Academic Year</a> <w><?php echo $academic_year; ?></w>
                    <a>Semester</a> <w><?php echo $semester; ?></w>
                </p>
                
            </div>

            </table>
        </div>
    </div>

</body>
</html>