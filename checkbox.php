<?php
    include('server.php');
            
            /*-------------------------------- GET data from approve.php ---------------------------*/

            date_default_timezone_set("Asia/Bangkok");
            $approven_date = date("d/m/Y") ;
            $approven_time = date("h:i:s") ;

            $checkbox = $_POST['approven_studentid'];
            foreach ($checkbox as $key => $value) {
                $student_ID[] = $key;
                $course_sec = $value;
            }

            foreach ($course_sec as $key => $value) {
                if ($key == "course_ID"){
                    $course_ID = $value;
                } 
                if ($key == "section"){
                    $section = $value;
                } else {
                    $current_student = $value;
                }
            }

            $approven_student_num = sizeof ($student_ID);
            $updated_current_students = $current_student + $approven_student_num;


            /*---------------------------------- INSERT to database ----------------------------------*/

            if ($_POST["submit"]=="submit")  { 

            for ($i=0; $i< sizeof ($student_ID) ;$i++) {  

                $query="INSERT INTO student_approven(student_ID, course_ID, section, approven_time, approven_date, updated_current_students) 
                        VALUES ('".$student_ID[$i]."','".$course_ID."','".$section."','".$approven_time."','".$approven_date."','".$updated_current_students."') ";
                mysqli_query($conn, $query) ;

            }  
            echo "Record is inserted";  
            } else {
                echo "No recording !!!";
            }
                
?> 