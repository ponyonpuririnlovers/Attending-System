<?php 
    session_start();
    include('server.php');

    /*!-- logged in user information --*/
    $id = $_SESSION['username'];
    $query = " SELECT * FROM teacher_users WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['name'];
        $faculty = $row['faculty'];
        $department = $row['department'];
        $course_ID = $row['course_ID'];
    }
    $currentDate = date("jS F Y h:i A") . "<br>";

    /*!-- no username --*/
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: login.php');
    }

    /*!-- logout --*/
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
    <title>Home Page</title>

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
        <a href="course.php"><i class="fas fa-table"></i><span>Opening Course</span></a>
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
        <h1>Welcome to <span>Attending System</span></h1>
        <p>please choose the label what do you want to do \ (^-^) /</p>
        <a>this page will be close when you not connect belong in 10 minutes</a>
    </div>
    


</body>
</html>