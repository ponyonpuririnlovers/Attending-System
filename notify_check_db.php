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
    $query = " SELECT * FROM officer_user WHERE username = '$id' ";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $username = $row['username'];
    }

    /*---------------------------------- UPDATE to database ----------------------------------*/

    $errors = array();
    if (isset($_POST['notify_submit'])) {

        $course_ID = $_SESSION['course_ID'];
        $section = $_SESSION['section'];
        $student_ID[] = $_SESSION['student_notify'];

        if (empty($password)) {
            array_push($errors, "กรุณากรอก 'รหัสผ่าน'");
            $_SESSION['error'] = "กรุณากรอก 'รหัสผ่าน'";

            # link กลับไปหน้าก่อน notify_check.php !!! \(;-;)/ #
            header("location: notify_check.php ?id=$course_ID &sec=$section");
            
        }

        if (count($errors) == 0) {

            $query_users = "SELECT * FROM officer_user WHERE username = '$username' AND password = '$password' ";
            $result_users = mysqli_query($conn, $query_users);

            /*--------- username & password ถูกก!!! ----------*/
            if (mysqli_num_rows($result_users) > 0) {
                
                for ($i=0; $i< sizeof ($student_ID) ;$i++) { 

                    # update status in table["student_status"] #
                    $update = " UPDATE student_status SET status='ดำเนินการแล้ว' WHERE student_ID=$student_ID[$i] AND course_ID=$course_ID AND section=$section ";
                    mysqli_query($conn, $update);
                
                }

                $_SESSION['student_ID'] = $student_ID;
                $_SESSION['course_ID'] = $course_ID;
                $_SESSION['section'] = $section;

                header('location: finish_notify.php');
                
            } else { /*--------- username & password ผิดด!!! ----------*/

                array_push($errors, "รหัสผ่าน 'ผิด' กรุณากรอกใหม่อีกครั้ง!");
                $_SESSION['error'] = "รหัสผ่าน 'ผิด' กรุณากรอกใหม่อีกครั้ง!";

                 # link กลับไปหน้าก่อน notify_check.php !!! \(;-;)/ #
                header("location: notify_check.php ?id=$course_ID &sec=$section");

            }
        } 
        
    }

?>