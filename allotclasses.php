
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>TimeTable Management System</title>
    <!-- BOOTSTRAP CORE STYLE CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet"/>
    <!-- FONT AWESOME CSS -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet"/>
    <!-- FLEXSLIDER CSS -->
    <link href="assets/css/flexslider.css" rel="stylesheet"/>
    <!-- CUSTOM STYLE CSS -->
    <link href="assets/css/style.css" rel="stylesheet"/>
    <!-- Google	Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'/>
</head>
<body>

<div class="navbar navbar-inverse navbar-fixed-top " id="menu">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

        </div>
        <div class="navbar-collapse collapse move-me">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="addteachers.php">ADD TEACHERS</a></li>
                <li><a href="addsubjects.php">ADD SUBJECTS</a></li>
                <li><a href="addclassrooms.php">ADD CLASSROOMS</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">ALLOTMENT
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                       <li>
                            <a href=allotsubjects.php?type=THEORY>THEORY COURSES</a>
                        </li>
                        <li>
                            <a href=allotsubjects.php?type=PRATICAL>PRACTICAL COURSES</a>
                        </li>
                        <li>
                            <a href=allotclasses.php>CLASSROOMS</a>
                        </li>
                    </ul>
                </li>
                <li><a href="generatetimetable.php">GENERATE TIMETABLE</a></li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="index.php">LOGOUT</a></li>
            </ul>

        </div>
    </div>
</div>
<!--NAVBAR SECTION END-->
<br>

<?php
if (isset($_POST['class'])) {
    include 'connection.php';
    // $year = $_POST['course'];
    $day = $_POST['day'];
    $class = $_POST['class'];
    $period = $_POST['period'];
    $teacher  = $_POST['teacher'];
    $semester = $_POST['semester'];
    $subject = $_POST['subject_id'];

      $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
        "SELECT * FROM
         timetable where
         day='$day' and 
         periods='$period' and 
         classroom_id='$class' and
         semester_id = '$semester' and 
         teacher_id = '$teacher'  and 
         subject_id = '$subject'
         ");
         $row_count = mysqli_num_rows($q);

         if ($row_count > 0 ) {
            echo "<script type='text/javascript'> alert('alredy exist') </script>";
         }else{
       $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
           "INSERT INTO
           timetable (day, periods,classroom_id, semester_id,teacher_id, subject_id) 
           VALUES ('$day','$period','$class','$semester','$teacher', '$subject')
           ");
          }

    
}

if (isset($_GET['delete'])) {
   $id =  $_GET['delete'];
    $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
           "DELETE from 
           timetable where id='$id' ");
}
?>


<form action="allotclasses.php" method="post" style="margin-top: 100px">

   
    <div class="row" style="display: flex; flex-direction: row; justify-content: center; align-content: center;">

            <div align="center" style="margin-top: 5px; margin-right: 10px">
                <select name="semester" class="list-group-item">
                    <option selected disabled>Select Semester</option>
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                </select>
            </div>


            <div align="center" style="margin-top: 5px; margin-right: 15px">
                <select name="class" class="list-group-item">
                    <?php
                    include 'connection.php';
                    $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
                        "SELECT * FROM classrooms");
                    $row_count = mysqli_num_rows($q);
                    if ($row_count) {
                        $mystring = '
                     <option selected disabled>Select Classroom</option>';
                        while ($row = mysqli_fetch_assoc($q)) {
                            if ($row['status'] != 0)
                                continue;
                            $mystring .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                        }
                        echo $mystring;
                    }
                    ?>
                </select>
            </div>

          

         

             <div align="center" style="margin-top: 5px; margin-right: 15px">
                <select name="teacher" class="list-group-item">
                    <?php
                    include 'connection.php';
                    $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
                        "SELECT * FROM teachers");
                    $row_count = mysqli_num_rows($q);
                    if ($row_count) {
                        $mystring = '
                     <option selected disabled>Select Teachers</option>';
                        while ($row = mysqli_fetch_assoc($q)) {
                            $mystring .= '<option value="' . $row['name'] . '">' . $row['name'] . '</option>';
                        }
                        echo $mystring;
                    }
                    ?>
                </select>
            </div>


               <div align="center" style="margin-top: 5px; margin-right: 15px">
                <select name="subject_id" class="list-group-item">
                    <?php
                    include 'connection.php';
                    $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
                        "SELECT * FROM subjects");
                    $row_count = mysqli_num_rows($q);
                    if ($row_count) {
                        $mystring = '
                     <option selected disabled>Select Course</option>';
                        while ($row = mysqli_fetch_assoc($q)) {
                            $mystring .= '<option value="' . $row['subject_code'] . '">' . $row['subject_name'] . '</option>';
                        }
                        echo $mystring;
                    }
                    ?>
                </select>
            </div>


          <div align="center" style="margin-top: 5px; margin-right: 10px">
                <select name="day" class="list-group-item">
                     <option selected disabled>Select Day</option>
                     <option value="MONDAY">MONDAY</option>
                     <option value="TUESDAY">TUESDAY</option>
                     <option value="WEDNESDAY">WEDNESDAY</option>
                     <option value="THURSDAY">THURSDAY</option>
                     <option value="FRIDAY">FRIDAY</option>
                     <option value="SATURDAY">SATURDAY</option>
                </select>
            </div>

                <div align="center" style="margin-top: 5px; margin-right: 10px">
                <select name="period" class="list-group-item">
                     <option selected disabled>Select Period</option>
                     <option value="8:00-8:50am">8:00-8:50am</option>
                     <option value="8:55-9:45am">8:55-9:45am</option>
                     <option value="9:50-10:40am">9:50-10:40am</option>
                     <option value="10:45-11:35am">10:45-11:35am</option>
                     <option value="11:40-12:30pm">11:40-12:30pm</option>
                     <option value="1:30-4:00pm">1:30-4:00pm</option>
                </select>
            </div>


             <div align="center" style="">
               <button type="submit" class="btn btn-success btn-lg">Allot</button>
            </div>

        </div>


   
</form>

<script>
    function deleteHandlers() {
        var table = document.getElementById("allotedsubjectstable");
        var rows = table.getElementsByTagName("tr");
        for (i = 0; i < rows.length; i++) {
            var currentRow = table.rows[i];
            //var b = currentRow.getElementsByTagName("td")[0];
            var createDeleteHandler =
                function (row) {
                    return function () {
                        var cell = row.getElementsByTagName("td")[0];
                        var id = cell.innerHTML;
                        var x;
                        if (confirm("Are You Sure?") == true) {
                            window.location.href = "deleteallotment.php?name=" + id;

                        }

                    };
                };

            currentRow.cells[2].onclick = createDeleteHandler(currentRow);
        }
    }
</script>
<div align="center">
    <style>
        table {
            margin-top: 70px;
            margin-bottom: 50px;
            font-family: arial, sans-serif;
            border-collapse: collapse;
            margin-left: 80px;
            width: 90%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

    <table id=allotedclassroomstable>
        <caption><strong>CLASSROOMS ALLOTMENT</strong></caption>
        <br>
        <thead>
            <tr>
                 <th width="250">Semester</th>
                <th width="250">Classroom</th>
                 <th width="250">Teacher</th>
                  <th width="250">Course</th>
                   <th width="250">Day</th>
                <th width="400">Alloted To Period</th>
                <th width="60">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        include 'connection.php';
        $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
            "SELECT * FROM timetable ");
        while ($row = mysqli_fetch_assoc($q)) {
            $semester = '';
           if ($row['semester_id'] == 1) {
             $semester = '1st semester';
           }else{
            $semester = '2nd semester';
           }

           $subject_id = $row['subject_id'];


             $course = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
            "SELECT * FROM subjects where subject_code='$subject_id' ");
             $course_row = mysqli_fetch_row($course);
             $id = $row['id'];
            

            echo "<tr>
                <td>{$semester}</td>
                <td>{$row['classroom_id']}</td>
                <td>{$row['teacher_id']}</td>
                <td>{$course_row[1]}</td>
                <td>{$row['day']}</td>
                <td>{$row['periods']}</td>
                <td><button>
                  <a href='allotclasses.php?delete=$id'>Delete</a>
                </button></td>
                    </tr>\n";
        }
        echo "<script>deleteHandlers();</script>";
        ?>
        </tbody>
    </table>

</div>




<!--  Jquery Core Script -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!--  Core Bootstrap Script -->
<script src="assets/js/bootstrap.js"></script>
<!--  Flexslider Scripts -->
<script src="assets/js/jquery.flexslider.js"></script>
<!--  Scrolling Reveal Script -->
<script src="assets/js/scrollReveal.js"></script>
<!--  Scroll Scripts -->
<script src="assets/js/jquery.easing.min.js"></script>
<!--  Custom Scripts -->
<script src="assets/js/custom.js"></script>
</body>
</html>
