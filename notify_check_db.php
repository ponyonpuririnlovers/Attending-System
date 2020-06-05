<?php 
    session_start();
    include('server.php');

    $password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    /*-------------------------------- GET data from student_users.php ---------------------------*/

    date_default_timezone_set("Asia/Bangkok");
    $proceed_date = date("d/m/Y") ;
    $proceed_time = date("h:i:s") ;

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
        $current_student = $_SESSION['current_student'];
        # EX student_ID --> { [ [0] => 6140053622 ,  [1] => 6140053633] }
        $student_ID = $_SESSION['student_notify'];
        $proceed_student_num = count($student_ID); 

        # จำนวนนิสิตทั้งหมดในตอนเรียน[update]
        $updated_current_students = $current_student + $proceed_student_num;

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

                    # UPDATE status & proceed_time & date // in table["student_status"] #
                    $update_status = " UPDATE student_status SET status='ดำเนินการแล้ว', proceed_time='$proceed_time', proceed_date='$proceed_date' WHERE student_ID=$student_ID[$i] AND course_ID=$course_ID AND section=$section ";
                    mysqli_query($conn, $update_status);

                    # DELETE data student // from table["student_approven"] #
                    $del = " DELETE FROM student_approven WHERE student_ID=$student_ID[$i] AND course_ID=$course_ID ";
                    mysqli_query($conn, $del);

                    # UPDATE current_student // in table["course"] #
                    $update_course = " UPDATE course SET current_student='$updated_current_students' WHERE course_ID=$course_ID AND section=$section ";
                    mysqli_query($conn, $update_course);

                    # SENT MAIL #
                    $query = "  SELECT  su.username
                                FROM    student_users su
                                WHERE   $student_ID[$i] = su.student_ID 
                             ";
                    $result = mysqli_query($conn, $query);
                    while($rowpost = mysqli_fetch_array($result)) { 
                        $stusername = $rowpost['username'];

                        require($_SERVER['DOCUMENT_ROOT']."/attending_system/phpmailer/PHPMailerAutoload.php");

                        header('Content-Type: text/html; charset=utf-8');

                        $mail = new PHPMailer;
                        $mail->CharSet = "utf-8";
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->Port = 587;
                        $mail->SMTPSecure = 'tls';
                        $mail->SMTPAuth = true;

                        $gmail_username = "khaopoon2543@gmail.com"; // gmail ที่ใช้ส่ง
                        $gmail_password = "0876757648"; // รหัสผ่าน gmail
                        // ตั้งค่าอนุญาตการใช้งานได้ที่นี่ https://myaccount.google.com/lesssecureapps?pli=1


                        $sender = "เจ้าหน้าที่ฝ่ายทะเบียน คณะอักษรศาสตร์"; // ชื่อผู้ส่ง
                        $email_sender = "noreply@officerofarts.com"; // เมล์ผู้ส่ง 
                        $email_receiver = $stusername; // เมล์ผู้รับ ***

                        $subject = "แจ้งการดำเนินการขอเพิ่มรายวิชา"; // หัวข้อเมล์

                        $mail->Username = $gmail_username;
                        $mail->Password = $gmail_password;
                        $mail->setFrom($email_sender, $sender);
                        $mail->addAddress($email_receiver);
                        $mail->Subject = $subject;
                        
                        $email_content = "
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset=utf-8'/>
		</head>
        <body>

            <div>
				<div>				
					<h2>กรุณาตรวจสอบผลการเพิ่มรายวิชาที่ <strong style='color:#0000ff;'></strong></h2>
					<a href='www2.reg.chula.ac.th' target='_blank'>
						<h2><strong style='color:#e37aa1;'> >> www2.reg.chula.ac.th << </strong> </h2>
					</a>
                </div>
                
				<div style='margin-top:30px;'>
					<hr>
					<address>
						<h4>ติดต่อสอบถาม</h4>
						<p>Apple Thailand</p>
						<p>www.facebook.com/apple</p>
					</address>
                </div>
                
            </div>
            
			<div style='background: #3b434c;color: #a2abb7;padding:30px;'>
				<div style='text-align:center'> 
					2016 © Apple Thailand
				</div>
            </div>
            
		</body>
	</html>
";

                        //  ถ้ามี email ผู้รับ
                        if($email_receiver){
	                        $mail->msgHTML($email_content);

	                        if (!$mail->send()) {  // สั่งให้ส่ง email

                                // กรณีส่ง email ไม่สำเร็จ
                                $_SESSION['mail_error'] = "ระบบมีปัญหา กรุณาลองใหม่อีกครั้ง";
                                
	                        } else {

                                // กรณีส่ง email สำเร็จ
                                $_SESSION['mail_success'] = "ระบบได้ส่งข้อความไปเรียบร้อย";
                                
	                        }	
                        }

                    }

                }
                

                $_SESSION['student_ID'] = $student_ID;
                $_SESSION['course_ID'] = $course_ID;
                $_SESSION['section'] = $section;
                $_SESSION['proceed_time'] = $proceed_time;
                $_SESSION['proceed_date'] = $proceed_date;
                $_SESSION['proceed_student_num'] = $proceed_student_num; // จำนวนนิสิตที่แจ้ง(ต่อครั้ง)

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