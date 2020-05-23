<?php 
    session_start();
    include('server.php');

    $password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    /*-------------------------------- GET data from student_users.php ---------------------------*/

    date_default_timezone_set("Asia/Bangkok");
        $request_date = date("d/m/Y") ;
        $request_time = date("h:i:s") ;

    /*!-- logged in user information --*/
    $id = $_SESSION['username'];
    $query = " SELECT * FROM student_users WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $student_ID = $row['student_ID'];
        $username = $row['username'];
    }


    
    /*---------------------------------- INSERT to database ----------------------------------*/

    $errors = array();
    if (isset($_POST['submit'])) {
        $course_ID = mysqli_real_escape_string($conn, $_POST['course_ID']);
        $section = mysqli_real_escape_string($conn, $_POST['section']);

        $user_check_query = "   SELECT *
                                FROM student_request 
                                WHERE student_ID = '$student_ID' AND course_ID = '$course_ID'
                                LIMIT 1
                            ";
        $query = mysqli_query($conn, $user_check_query);
        $result = mysqli_fetch_assoc($query);

        if ($result) { // if user exists
            if ($result['course_ID'] === $course_ID) {
                array_push($errors, "ท่านได้ขออนุมัติเพิ่มรายวิชานี้แล้ว");
                $_SESSION['error'] = "ท่านได้ขออนุมัติเพิ่มรายวิชานี้แล้ว";
                header("location: student_attend.php");
            }
        }

        if (empty($password)) {
            array_push($errors, "กรุณากรอก'รหัสผ่าน'");
            $_SESSION['error'] = "กรุณากรอก'รหัสผ่าน'";

            # link กลับไปหน้าก่อน student_attend.php !!! \(;-;)/ #
            header("location: student_attend.php");
            
        }

        if (count($errors) == 0) {

            $query_users = "SELECT * FROM student_users WHERE username = '$username' AND password = '$password' ";
            $result_users = mysqli_query($conn, $query_users);

            /*--------- username & password ถูกก!!! ----------*/
            if (mysqli_num_rows($result_users) > 0) {

                $sql = "INSERT INTO student_request (student_ID, course_ID, section, request_time, request_date) VALUES ('$student_ID', '$course_ID', '$section', '$request_time', '$request_date')";
                mysqli_query($conn, $sql);

                $sql = "INSERT INTO student_status (student_ID, course_ID, section, request_time, request_date, status) VALUES ('$student_ID', '$course_ID', '$section', '$request_time', '$request_date', 'waiting')";
                mysqli_query($conn, $sql);

                $_SESSION['course_ID'] = $course_ID;
                $_SESSION['section'] = $section;
                $_SESSION['request_time'] = $request_time;
                $_SESSION['request_date'] = $request_date;

                header('location: finish_attend.php');

            }
        } 
        
        if (empty($course_ID)) {
            array_push($errors, "กรุณากรอก 'รหัสรายวิชา'");
            $_SESSION['error'] = "กรุณากรอก 'รหัสรายวิชา'";
            header("location: student_attend.php");
        }
        if (empty($section)) {
            array_push($errors, "กรุณากรอก 'ตอนเรียน'");
            $_SESSION['error'] = "กรุณากรอก 'ตอนเรียน'";
            header("location: student_attend.php");
        }
        if (empty($course_ID) && empty($section)){
            array_push($errors, "กรุณากรอก 'รหัสรายวิชา' และ 'ตอนเรียน'");
            $_SESSION['error'] = "กรุณากรอก 'รหัสรายวิชา' และ 'ตอนเรียน'";
            header("location: student_attend.php");
        }
    }

?>