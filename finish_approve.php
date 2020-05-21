<?php 
    session_start();
    include('server.php');

    /*!-- GET! infomation from approven.php --*/
    $course_ID = $_GET['id'];
    $section = $_GET['sec'];  

    /*!-- logged in user information --*/
    $id = $_SESSION['username'];
    $query = " SELECT * FROM teacher_users WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $name = $row['name'];
        $faculty = $row['faculty'];
        $department = $row['department'];
    }
    date_default_timezone_set("Asia/Bangkok");
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
    <title>Approve</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    
</head>
<body>

    <input type="checkbox" id="check">
    <!--header area start-->
    <header>
      <label for="check">
        <i class="fas fa-bars" id="sidebar_btn"></i>
      </label>
      <div class="left_area">
        <h3>ระบบเพิ่มรายวิชา <span> อาจารย์</span></h3>
      </div>
    </header>
    <!--header area end-->
    
    <!--sidebar start -->
    <div class="sidebar">
        <center>
            <img src="chula_logo_index.jpg" class="profile_image" alt="">
            <h4>Chulalongkorn University</h4>
        </center>
        <a href="index.php"><i class="fas fa-home"></i><span>หน้าหลัก</span></a>
        <a href="course.php"><i class="fas fa-table"></i><span style="regu">อนุมัติเพิ่มรายวิชา</span></a>
        <a href="history.php"><i class="fas fa-history"></i><span>ประวัติเพิ่มรายวิชา</span></a>
        <a href="index.php?logout='1'" style="color: #e37aa1;"><i class="fas fa-power-off"></i><span>ออกจากระบบ</span></a>
        
        <div class="sidebar_info_user">
            <p><?php echo $currentDate; ?></p>
            <p><?php echo $name; ?></p>
            <p><?php echo $department; ?></p>
            <p><?php echo $faculty; ?></p>
        </div>

    </div>    
    <!--sidebar end-->
    
    <div class="content">
        <h1>ยืนยันการเพิ่มรายวิชา</h1>

        <?php
            date_default_timezone_set("Asia/Bangkok");
            $approven_date = date("d/m/Y") ;
            $approven_time = date("h:i:s") ;
            print_r($approven_date);

            $id = $_SESSION['username'];

            $query = "  SELECT  c.course_name, c.department, c.semester, c.academic_year, c.student_number,
                                sa.approven_time, sa.updated_current_students, sa.approven_student_num
                        FROM course c, student_approven sa
                        WHERE c.course_ID = $course_ID AND c.section = $section 
                        AND sa.course_ID = $course_ID AND sa.section = $section 
                    ";
            $result = mysqli_query($conn, $query); 
                    
            if (mysqli_num_rows($result) > 0) {
                
                while($rowpost = mysqli_fetch_array($result)) { 
                    $course_name = $rowpost['course_name'];
                    $academic_year = $rowpost['academic_year'];
                    $semester = $rowpost['semester'];  
                    $student_number = $rowpost['student_number'];  
                     
                }
            } 
        ?>
            <h2><?php echo $course_ID; ?> <?php echo $course_name; ?></h2>

            <div class="head_course">
                <p>
                    <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                    <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
                    <aa>ตอนเรียน</aa> <w><?php echo $section; ?></w>
                    <aa>จำนวนนักเรียนปัจจุบัน</aa> <w><?php echo $student_number; ?></w>
                </p> 
            </div>
    </div>

</body>
</html>