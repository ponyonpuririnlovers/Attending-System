<?php
    session_start();
    include('server.php');

            $password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

            /*-------------------------------- GET data from approve.php ---------------------------*/

            date_default_timezone_set("Asia/Bangkok");
            $approven_date = date("d/m/Y") ;
            $approven_time = date("h:i:s") ;

            $checkbox = $_POST['approven_studentid'];
            foreach ($checkbox as $allstudentid => $info_array) {
                if (count($info_array)==5) {
                foreach ($info_array as $key => $value) {
                    if ($key == 'student_ID') {
                        $student_ID[] = $value;
                    }
                    if ($key == "course_ID"){
                        $course_ID = $value;
                    } 
                    if ($key == "section"){
                        $section = $value;
                    } 
                    if ($key == "student_number"){
                        $current_student = $value;
                    }
                    else { #username teacher
                        $username = $value;
                    }
                }
                }
            }

            $approven_student_num = sizeof ($student_ID);
            $updated_current_students = $current_student + $approven_student_num;


            /*---------------------------------- INSERT to database ----------------------------------*/
            $errors = array();
            if ($_POST["submit"]=="ยืนยันการอนุมัติ")  { 

                if (empty($password)) {
                    array_push($errors, "Password is required");
                    $_SESSION['error'] = "Password is required";

                    # link กลับไปหน้าก่อน approve.php !!! \(;-;)/ #
                    header("location: approve.php ?id=$course_ID &sec=$section");
                    
                }

                if (count($errors) == 0) {
                    $query_users = "SELECT * FROM teacher_users WHERE username = '$username' AND password = '$password' ";
                    $result_users = mysqli_query($conn, $query_users);

                    /*--------- username & password ถูกก!!! ----------*/
                    if (mysqli_num_rows($result_users) > 0) {

                        # เก็บข้อมูลการอนุมัติเพิ่มรายวิชาลง database [tabel="student_approven"] #
                        for ($i=0; $i< sizeof ($student_ID) ;$i++) {  
                            $query="INSERT INTO student_approven(student_ID, course_ID, section, approven_time, approven_date, updated_current_students, approven_student_num) 
                                    VALUES ('".$student_ID[$i]."','".$course_ID."','".$section."','".$approven_time."','".$approven_date."','".$updated_current_students."','".$approven_student_num."') ";
                            mysqli_query($conn, $query) ;
                        }
                        # link ไปยังหน้า finish_approve.php !!! \(^-^)/ #
                        header("location: finish_approve.php");
                        
                        

                    }  else { /*--------- username & password ผิดด!!! ----------*/
                        array_push($errors, "Wrong Password");
                        $_SESSION['error'] = "Wrong Password!";
                        
                        # link กลับไปหน้าก่อน approve.php !!! \(;-;)/ #
                        header("location: approve.php ?id=$course_ID &sec=$section");
                    

                    } 
                }
                
            }
                
?> 