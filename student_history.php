<?php 
    session_start();
    include('server.php');

    /*!-- logged in user information --*/
    $id = $_SESSION['username'];
    $query = " SELECT * FROM student_users WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $student_ID = $row['student_ID'];
        $name = $row['name'];
        $faculty = $row['faculty'];
        $major = $row['major'];
        $year = $row['year'];
        $level = $row['level'];
    }
    date_default_timezone_set("Asia/Bangkok");
    $currentDate = date("j F Y h:i A") . "<br>";

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
    <title>Home</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet">
    
</head>
<body>

    <input type="checkbox" id="check">
    <!--header area start-->
    <header>
      <label for="check">
        <i class="fas fa-bars" id="sidebar_btn" style="left: 250px;"></i>
      </label>
      <div class="left_area">
        <h3>ระบบเพิ่มรายวิชา <span>นิสิต</span></h3>
      <div class="right_area">
        <a class="right_head"><?php echo $name; ?></a>
      </div>
      </div>
    </header>
    <!--header area end-->
    
    <!--sidebar start -->
    <div class="sidebar">
        <center>
            <img src="chula_logo_index.jpg" class="profile_image" alt="">
            <h4>Chulalongkorn University</h4>
        </center>
        <a href="student_index.php"><i class="fas fa-home"></i><span>หน้าหลัก</span></a>
        <a href="opening_course.php"><i class="fas fa-table"></i><span>รายวิชาที่เปิดสอน</span></a>
        <a href="attend.php"><i class="fas fa-user-plus"></i><span>ขออนุมัติเพิ่มรายวิชา</span></a>
        <a href="student_history.php"><i class="fas fa-history"></i><span>ประวัติการขออนุมัติ</span></a>
        <a href="student_index.php?logout='1'" style="color: #e37aa1;"><i class="fas fa-power-off"></i><span>ออกจากระบบ</span></a>
        <div class="sidebar_info_user" style="margin-top:-50px;">
            <p><?php echo $currentDate; ?></p>
            <p><?php echo $name; ?></p>
            <p><?php echo $major; ?></p>
            <p><?php echo $faculty; ?></p>
        </div>

    </div>    
    <!--sidebar end-->
    
    <div class="content">
        <h1>ประวัติการขออนุมัติเพิ่มรายวิชา</h1>

        <table class="table" id="student_history_table">
            <thead>
                <tr>
                    <th>รหัสรายวิชา</th>
                    <th>ชื่อรายวิชา</th>
                    <th>ตอนเรียน</th>
                    <th>เวลา</th>
                    <th>วันที่</th>
                    <th>สถานะ</th>
                </tr>
            </thead>

        <?php
            $id = $_SESSION['username'];
            $query = "  SELECT  ss.*, c.*
                        FROM course c, student_status ss
                        WHERE $student_ID = ss.student_ID 
                        AND c.course_ID = ss.course_ID AND c.section = ss.section
                        ORDER BY ss.request_date ASC ";
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
                    $academic_year = $rowpost['academic_year'];
                    $semester = $rowpost['semester'];  
        ?>

                    <td><center><?php echo $rowpost['course_ID']; ?></center></td>
                    <td><?php echo $rowpost['course_name']; ?></td>
                    <td><center><?php echo $rowpost['section']; ?></center></td>
                    <td><center><?php echo $rowpost['request_time']; ?></center></td>
                    <td><center><?php echo $rowpost['request_date']; ?></center></td>

                    <?php if ( $rowpost['status'] == 'อนุมัติแล้ว') { ?>
                        <td><center><approven><?php echo $rowpost['status']; ?></approven></center></td> 
                    <?php  } else { ?>
                        <td><center><waiting><?php echo $rowpost['status']; ?></waiting></center></td>
                    <?php } ?>

        <?php
                    $row_count++; 
                    $col_count++;
                    echo "</tr>"; 
                    echo "</tbody>"; 
        }
        ?>
        
            <div class="head_course">
                <p>
                    <a>ปีการศึกษา</a> <w><?php echo $academic_year; ?></w>
                    <a>ภาคการศึกษา</a> <w><?php echo $semester; ?></w>
                </p> 
            </div>

        <?php
            } else {
                echo "ไม่มีประวัติการขออนุมัติเพิ่มรายวิชา";
                echo "<script> document.getElementById('student_history_table').deleteRow(0); </script>";
            }
        ?>

        </table>
    </div>


</body>
</html>