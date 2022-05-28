<?php
// Start the session
session_start();
if (isset($_GET['success'])) {
    echo "<script type='text/javascript'>alert('Time Table Generated');</script>";
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>TimeTable Management System</title>
    <script type="text/javascript" src="assets/jsPDF/dist/jspdf.min.js"></script>
    <script type="text/javascript" src="assets/js/html2canvas.js"></script>
    <!-- BOOTSTRAP CORE STYLE CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet"/>
    <!-- FONT AWESOME CSS -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet"/>
    <!-- FLEXSLIDER CSS -->
    <link href="assets/css/flexslider.css" rel="stylesheet"/>
    <!-- CUSTOM STYLE CSS -->
    <link href="assets/css/style.css" rel="stylesheet"/>
    <!-- Google Fonts -->
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
                            <a href=allotsubjects.php>THEORY COURSES</a>
                        </li>
                        <li>
                            <a href=allotpracticals.php>PRACTICAL COURSES</a>
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


<!--Algorithm Implementation-->
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <span class="close">&times</span>
            <h2 id="popupHead">Assign Substitute</h2>
        </div>
        <div class="modal-body" id="AssignSubstitute">
            <!--Admin Login Form-->

            <div style="display:block" id="assignSubstituteForm">
                <form method="post" action="assignSubstituteFormValidation.php">
                    <div class="form-group">
                        <label for="substitute">Substitute</label>
                        <select class="form-control" id="substitute" name="SB">

                        </select>
                        <input type="hidden" id="cell_number" class="btn btn-default" name="CN">

                    </div>
                    <div align="right" class="form-group">

                        <input type="submit" id="submit" class="btn btn-default" name="ADD" value="CHECK">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var assignsubstitueForm = document.getElementById("assignSubstitueForm");
    // Get the <span> element that closes the modal
    var modal = document.getElementById('myModal');
    var span = document.getElementsByClassName("close")[0];
    span.onclick = function () {
        modal.style.display = "none";
        assignsubstitueForm.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
            assignsubstitueForm.style.display = "none";
        }
    }
</script>
<!-- <form action="algo.php" method="post">
   
</form> -->
<form action="generatetimetable.php" method="post">

    <div style="display: flex; flex-direction: row; justify-content: center; margin-top: 80px; padding: 10px">
            <div align="center" style="margin-right: 10px">
                <select name="selected_teacher" class="list-group-item">
                    <option selected disabled>Select Teacher</option>
                    <?php
                    $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
                        "SELECT * FROM teachers ");
                    while ($row = mysqli_fetch_assoc($q)) { ?>
                       <option
                        <?php echo isset($_POST['selected_teacher']) && $_POST['selected_teacher'] == $row['name'] ? 'selected' : ''?>

                        value="<?php echo $row['name']?>"><?php echo $row['name']?></option>
                 <?php     } ?>

                </select>
               
            </div>

             <div align="center" style="margin-right: 10px">
                <select name="selected_semester" class="list-group-item">
                    <option selected disabled>Select Semester</option>
                    <option value="1"
                     <?php echo isset($_POST['selected_semester']) && $_POST['selected_semester'] == 1 ? 'selected' : ''?> > 1</option>
                    <option value="2" <?php echo isset($_POST['selected_semester']) && $_POST['selected_semester'] == 2 ? 'selected' : ''?>>2</option>
                </select>
             
            </div>


             <div align="center" >
                <button type="submit"
                style="max-height: 45px"
                        id="generatebutton" class="btn btn-success btn-lg">GENERATE
                </button>
            </div>
        </div>
</form>

<script>
    var index = -1;
    function Substitute() {
        var table = document.getElementById("timetable");
        var cells = table.getElementsByTagName("td");
        // window.alert(cells[3].innerHTML.toString());
        for (i = 0; i < cells.length; i++) {
            if (i % 8 == 6 || i % 8 == 7 || parseInt(i / 8) == 0 || i % 8 == 0) {
                continue;
            }
            var currentCell = cells[i];
            //var b = currentRow.getElementsByTagName("td")[0];
            var createSubstituteHandler =
                function (cell, i) {
                    return function () {

                        document.getElementById('cell_number').value = i;
                        index = i;
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function () {
                            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                var modal = document.getElementById('myModal');
                                modal.style.display = "block";
                                document.getElementById("substitute").innerHTML = this.responseText;

                            }
                        };
                        xmlhttp.open("GET", "getcellindex.php?i=" + i, false);
                        xmlhttp.send();
                    };
                };
            currentCell.onclick = createSubstituteHandler(currentCell, i);
        }
    }
</script>

<div>
    <br>
    <style>
        table {
            margin-top: 20px;
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 2px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #ffffff;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        @media print
{    
    .no-print, .no-print *
    {
        display: none !important;
    }
}
    </style>
    <div id="TT" style="background-color: #FFFFFF">
        <table border="2" cellspacing="3" align="center" id="timetable">
            <caption><strong>
                <h2>
                School timetable for  <?php echo isset($_POST['selected_teacher'])  ? $_POST['selected_teacher']: ''?></h2>

                <br><br></strong></caption>

                       <tr>
                <td style="text-align:center">WEEKDAYS</td>
                <td style="text-align:center">8:00-8:50</td>
                <td style="text-align:center">8:55-9:45</td>
                <td style="text-align:center">9:50-10:40</td>
                <td style="text-align:center">10:45-11:35</td>
                <td style="text-align:center">11:40-12:30</td>
                <td style="text-align:center">12:30-1:30</td>
                <td style="text-align:center">1:30-4:00</td>
            </tr>
         
            <tr>
                <?php
                $table = null;
                $i= -1;
                $days= array('MONDAY','TUESDAY','WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY');
                if (isset($_POST['selected_semester'])) {
                    $selected_semester = $_POST['selected_semester'];
                    $selected_teacher = $_POST['selected_teacher'];

                    for ($i=0; $i < 6 ; $i++) { 
                    ?>
                <tr>
                    <td align="center" style="text-align: center;"><?php echo $days[$i]?></td>
                     <td align="center" style="text-align: center;">


                        <?php 

                             $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
                                "SELECT * FROM timetable  where semester_id='$selected_semester'  and teacher_id='$selected_teacher' and day='$days[$i]' and periods='8:00-8:50am' ");
                            $row_count = mysqli_num_rows($q);
                            if ($row_count > 0) {
                                $row = mysqli_fetch_assoc($q);
                                echo  $row['subject_id'];
                            }  

                        ?> </td>


                         <td align="center" style="text-align: center;">
                              <?php 

                                     $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
                                        "SELECT * FROM timetable  where semester_id='$selected_semester'  and teacher_id='$selected_teacher' and day='$days[$i]' and periods='8:55-9:45am' ");
                                    $row_count = mysqli_num_rows($q);
                                    if ($row_count > 0) {
                                        $row = mysqli_fetch_assoc($q);
                                        echo  $row['subject_id'];
                                    }  
                                 ?> 
                          </td>

                             <td align="center" style="text-align: center;">
                              <?php 

                                     $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
                                        "SELECT * FROM timetable  where semester_id='$selected_semester'  and teacher_id='$selected_teacher' and day='$days[$i]' and periods='9:50-10:40am' ");
                                    $row_count = mysqli_num_rows($q);
                                    if ($row_count > 0) {
                                        $row = mysqli_fetch_assoc($q);
                                       
                                        echo  $row['subject_id'];
                                    }  
                                 ?> 
                          </td>

                              <td align="center" style="text-align: center;">
                              <?php 

                                     $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
                                        "SELECT * FROM timetable  where semester_id='$selected_semester'  and teacher_id='$selected_teacher' and day='$days[$i]' and periods='10:45-11:35am' ");
                                    $row_count = mysqli_num_rows($q);
                                    if ($row_count > 0) {
                                        $row = mysqli_fetch_assoc($q);
                                        echo  $row['subject_id'];
                                    }  
                                 ?> 
                          </td>

                                <td align="center" style="text-align: center;">
                              <?php 

                                     $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
                                        "SELECT * FROM timetable  where semester_id='$selected_semester'  and teacher_id='$selected_teacher' and day='$days[$i]' and periods='11:40-12:30pm' ");
                                    $row_count = mysqli_num_rows($q);
                                    if ($row_count > 0) {
                                        $row = mysqli_fetch_assoc($q);
                                       
                                        echo  $row['subject_id'];
                                    }  
                                 ?> 
                          </td>


                              <td style="text-align:center">LUNCH</td>

                                <td align="center" style="text-align: center;">
                              <?php 

                                     $q = mysqli_query(mysqli_connect("localhost", "root", "", "ttms"),
                                        "SELECT * FROM timetable  where semester_id='$selected_semester'  and teacher_id='$selected_teacher' and day='$days[$i]' and periods='1:30-4:00pm' ");
                                    $row_count = mysqli_num_rows($q);
                                    if ($row_count > 0) {
                                        $row = mysqli_fetch_assoc($q);
                                       
                                        echo  $row['subject_id'];
                                    }  
                                 ?> 
                          </td>




                </tr>
              <?php  }  } ?>





                    <?php
                 
                if (isset($_POST['selected_teacher'])) {
                    echo "<script>Substitute();</script>";
                    $_SESSION['shown_id'] = $_POST['selected_teacher'];
                }
                if (isset($_GET['display'])) {
                    echo "<script>Substitute();</script>";
                    $_SESSION['shown_id'] = $_GET['display'];
                }
                ?>
    </div>

</table>
</div>
    <div align="center" style="margin-top: 10px" class="no-print">
    <button id="saveaspdf" class="btn btn-info btn-lg  no-print" onclick="gendf()">SAVE AS PDF</button>
</div>
</div>
<script type="text/javascript">
    function gendf() {
     
        printDiv();
    }

    function PrintElem(elem)
{
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');

    mywindow.document.write('<html><head><title>' + document.title  + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title  + '</h1>');
    mywindow.document.write(document.getElementById(elem).innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;
}


  function printDiv() {
            var divContents = document.getElementById("TT").innerHTML;
            var a = window.open('', '', '');
            a.document.write('<html>');
            a.document.write('<body >');
            a.document.write(divContents);
            a.document.write('</body></html>');
            a.document.close();
            a.print();
        }
</script>



<!-- FOOTER SECTION END-->

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
