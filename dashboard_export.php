<?php  //export.php  
    session_start();
    include('server.php');
    
    $output = '';
    if(isset($_POST["export"])) {

        $query = "  SELECT c.* , COUNT(DISTINCT ss.student_ID) as total_student
                    FROM course c , student_status ss
                    WHERE c.course_ID = ss.course_ID
                    GROUP BY c.course_ID
                    ORDER BY COUNT(DISTINCT ss.student_ID) DESC
                    
                ";
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0) {
            $output .= '
                        <table class="table" bordered="1">  
                            <tr>  
                                <th>ลำดับที่</th>  
                                <th>รหัสรายวิชา</th>  
                                <th>ชื่อวิชา</th>  
                                <th>จำนวนนิสิตที่ขอเพิ่มรายวิชา</th>

                            </tr>
                        ';

            $row_count=0;
            $col_count=0;
            while($row = mysqli_fetch_array($result)) {
               
                $row_count_new = $row_count+1 ;

                $output .= '
                            <tr>  
                                <td>'.$row_count_new.'</td>  
                                <td>'.$row["course_ID"].'</td>  
                                <td>'.$row["course_name"].'</td>  
                                <td>'.$row['total_student'].'</td>  

                            </tr>

                            ';
                $row_count++; 
                $col_count++;
            }

            $query = "  SELECT COUNT(ss.student_ID) as total_student_request
                        FROM student_status ss    
                    ";
            $result = mysqli_query($conn, $query); 
            while($rs = mysqli_fetch_array($result)){ 
                $total_student_request = $rs['total_student_request']; 
            }

            $output .= '
                            <tr>  
                                <td></td>  
                                <td></td>  
                                <td>จำนวนการขอเพิ่มรายวิชาทั้งหมด</td>  
                                <td>'.$total_student_request.'</td>  

                            </tr>

                            ';
            
            $output .= '</table>';
            header('Content-Type: application/xls');
            header('Content-Disposition: attachment; filename=dashboard.xls');
            echo $output;
        }
    }
?>