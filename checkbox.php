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
                    if ($key == "current_student"){
                        $current_student = $value;
                    }
                    else { #username teacher
                        $username = $value;
                    }
                }
                }
            }

            # ถ้าไม่ได้checkเลย ให้เก็บ course_ID & section มาจาก approve.php
            $course_ID = $_SESSION['course_ID'];
            $section = $_SESSION['section'];

            # จำนวนนิสิตที่อนุมัติ/ครั้ง
            $approven_student_num = sizeof ($student_ID);
            # จำนวนนิสิตทั้งหมดในตอนเรียน[update]
            $updated_current_students = $current_student + $approven_student_num;


            /*---------------------------------- INSERT to database ----------------------------------*/
            $errors = array();
            if ($_POST["submit"]=="ยืนยันการอนุมัติ")  { 

                if (empty($password)) {
                    array_push($errors, "กรุณากรอก 'รหัสผ่าน'");
                    $_SESSION['error'] = "กรุณากรอก 'รหัสผ่าน'";

                    # link กลับไปหน้าก่อน approve.php !!! \(;-;)/ #
                    header("location: approve.php ?id=$course_ID &sec=$section");
                    
                }

                if (empty($student_ID)) {
                    array_push($errors, "ท่านยังไม่ได้เลือกนิสิตที่ต้องการอนุมัติ");
                    $_SESSION['error'] = "ท่านยังไม่ได้เลือกนิสิตที่ต้องการอนุมัติ";

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

                            # del data student from table["student_request"] #
                            $del = " DELETE FROM student_request WHERE student_ID=$student_ID[$i] AND course_ID=$course_ID ";
                            mysqli_query($conn, $del);

                            # update current_student in table["course"] #
                            $update = " UPDATE course SET current_student='.$updated_current_students.' WHERE course_ID=$course_ID AND section=$section ";
                            mysqli_query($conn, $update);

                            # update status in table["student_status"] #
                            $update = " UPDATE student_status SET status='อนุมัติแล้ว' WHERE student_ID=$student_ID[$i] AND course_ID=$course_ID AND section=$section ";
                            mysqli_query($conn, $update);

                        }

                        $_SESSION['course_ID'] = $course_ID;
                        $_SESSION['section'] = $section;
                        $_SESSION['approven_time'] = $approven_time;
                        $_SESSION['approven_date'] = $approven_date;
                        $_SESSION['updated_current_students'] = $updated_current_students;
                        $_SESSION['approven_student_num'] = $approven_student_num;

                        # link ไปยังหน้า finish_approve.php !!! \(^-^)/ #
                        header("location: finish_approve.php");
                        
                    }  else { /*--------- password ผิดด!!! ----------*/
                        array_push($errors, "รหัสผ่าน 'ผิด' กรุณากรอกใหม่อีกครั้ง!");
                        $_SESSION['error'] = "รหัสผ่าน 'ผิด' กรุณากรอกใหม่อีกครั้ง!";
                        
                        # link กลับไปหน้าก่อน approve.php !!! \(;-;)/ #
                        header("location: approve.php ?id=$course_ID &sec=$section");
                    

                    } 
                }
                
            }
                
?> 