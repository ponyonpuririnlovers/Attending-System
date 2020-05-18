<?php 
    session_start();
    include('server.php');

    /*!-- course_ID from course user choose --*/
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
        <a href="course.php"><i class="fas fa-table"></i><span>อนุมัติเพิ่มรายวิชา</span></a>
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

    <!--head info coruse START-->
    <div class="content">
        <?php
            $id = $_SESSION['username'];
            $query = "  SELECT c.course_ID, c.course_name, c.section, c.department, c.semester, c.academic_year, c.level, c.student_number
                        FROM course c, teacher_users t 
                        WHERE t.username = '$id' AND c.course_ID = '".$_GET["id"]."' AND c.course_ID = t.course_ID AND c.section = '".$_GET["sec"]."' AND c.section = t.section ";
            $result = mysqli_query($conn, $query);
                    
            if (mysqli_num_rows($result) > 0) {

                while($rowpost = mysqli_fetch_array($result)) { 
                    $course_name = $rowpost['course_name'];
                    $academic_year = $rowpost['academic_year'];
                    $semester = $rowpost['semester'];
                    $section = $rowpost['section']; 
                    $student_number = $rowpost['student_number'];
                }
            }    
        ?>

        <h1><?php echo $course_ID; ?> <?php echo $course_name; ?></h1>
        <div class="head_course">
            <p>
                <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
                <aa>ตอนเรียน</aa> <w><?php echo $section; ?></w>
                <aa>จำนวนนักเรียนปัจจุบัน</aa> <w><?php echo $student_number; ?></w>
            </p>   
        </div>
    </div>
    <!--head info coruse END-->
    
    <!--student request list table START-->
    <div class="content" style="padding-top: 1px;">
        <table border="1" class="table" id="student_request_table" >
            <thead>
                <tr>
                    <th>รหัสนิสิต</th>
                    <th>ชื่อนิสิต</th> 
                    <th>อนุมัติ</th> 
                </tr>
            </thead>        
        <?php
            $query = "  SELECT sr.student_ID, su.name
                        FROM student_request sr, student_users su
                        WHERE $course_ID = sr.course_ID AND $section = sr.section AND sr.student_ID = su.student_ID ";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {

                $row_count=0;
                $col_count=0;
                while($rowpost = mysqli_fetch_array($result)) { 
                    if($row_count%2!=0){
                        echo "<tbody>";
                        echo "<tr class='active-row'>";
                    } 
                    else {
                        echo "<tbody>";
                        echo "<tr>";
                    }   
        ?>          
                    <td><center><?php echo $rowpost['student_ID']; ?></center></td>
                    <td><?php echo $rowpost['name']; ?></td>
                    <td><input type="checkbox" name="student[]" id="$rowpost['student_ID']" value="$rowpost['student_ID']" 
                    onClick="if(frm.chk1.checked==false){frm.submit.disabled=true;}else{frm.submit.disabled=false;}"></td>
                    
        <?php
                    $row_count++; 
                    $col_count++;
                    echo "</tr>"; 
                    echo "</tbody>"; 
                }
            } else {
                echo "ไม่มีนิสิตที่ขอเพิ่มรายวิชานี้";
                echo "<script> document.getElementById('student_request_table').deleteRow(0); </script>";
            }
        ?>   

        <input name="submit" id="submit" type=submit value="ยืนยัน" disabled> 
        
</body>
</html>