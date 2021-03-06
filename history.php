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
    }
    date_default_timezone_set("Asia/Bangkok");
    $currentDate = date("j F Y h:i A") . "<br>";
    

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
    <title>ประวัติการอนุมัติ</title>

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
      <div class="right_area">
        <a class="right_head"><?php echo $name; ?></a>
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
        <div class="choose"><a href="history.php"><i class="fas fa-history"></i><span>ประวัติการอนุมัติ</span></a></div>
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
        <h1>ประวัติการอนุมัติ</h1>
    
        <table class="table" id="course_table">
            <thead>
                <tr>
                    <th>รหัสรายวิชา</th>
                    <th>ชื่อรายวิชา</th>
                    <th style="padding: 12px 5px;">ตอนเรียน</th>
                    <th>จำนวนนิสิตที่อนุมัติ</th>  
                </tr>
            </thead>

        <?php
            $id = $_SESSION['username'];
            $query = "  SELECT DISTINCT c.*
                        FROM course c, teacher_users t, student_status ss
                        WHERE t.username = '$id' AND c.course_ID = t.course_ID AND c.section = t.section
                        AND ss.course_ID = t.course_ID AND ss.section = t.section AND (status = 'อนุมัติแล้ว' OR status = 'ดำเนินการแล้ว')
                        ORDER BY c.course_ID ASC ";
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

                    #ไว้ใช้ตอนหา $total_approven_student
                    $course_ID = $rowpost['course_ID'];
                    $section = $rowpost['section'];
        ?>
        
        <?php # จำนวนนิสิตที่อนุมัติ[ทั้งหมด!!!]
            $query_total = "    SELECT  student_ID
                                FROM    student_status
                                WHERE   course_ID = $course_ID  AND section = $section AND (status = 'อนุมัติแล้ว' OR status = 'ดำเนินการแล้ว')
                            ";
            $result_total = mysqli_query($conn, $query_total);
            $total_approven_student = mysqli_num_rows($result_total); 
        ?>

                    <td><center><?php echo $rowpost['course_ID']; ?></center></td>
                    <td><?php echo $rowpost['course_name']; ?></td>
                    <td><center><?php echo $rowpost['section']; ?></center></td>
                    <td style="padding: 10px 5px;"><center><a href="history_course.php?id=<?php echo $rowpost['course_ID'];?>&sec=<?php echo $rowpost['section'];?>" role="button" class="btn2">
                                <?php echo $total_approven_student; ?></a><center></td> 
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
                echo "ไม่มีประวัติการอนุมัติเพิ่มรายวิชา";
                echo "<script> document.getElementById('course_table').deleteRow(0); </script>";
            }
        ?>

        </table>
    </div>

</body>
</html>