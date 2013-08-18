<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Mini-Break    
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20131203

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Student Faculty Feedback</title>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css">
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
        <div id="menu-wrapper">
                <div id="menu">
                        <ul>
                                <li class="current_page_item"><a href="fac.php">Faculty Review Page</a></li>                                                                                                
                        </ul>
                </div>
                <!-- end #menu -->
        </div>

<div id="wrapper">
        <div id="header-wrapper">
                <div id="header">
                        <div id="logo">
                                <h1><a href="#"> Course Feedback </a></h1>
                                <p>- by Rishabh Nigam</a></p>
                        </div>
                </div>
        </div>
        <!-- end #header -->
        <div id="page">
                <div id="page-bgtop">
                        <div id="page-bgbtm">
                                <div id="content">
                                        <div class="post">
                                                <h2 class="title"><a href="#">Welcome to the site  </a></h2>
                                                
                                                <div class="entry">
                                                
<?php
session_start();

$nl = "<br>";
$dbconn = pg_connect("host=localhost dbname=project user=rishabh password=arbit")
        or die("Could not connect" . pg_last_error());
        

$query = "SELECT cid,courseno, title from Course_offering NATURAL JOIN  Course_name where semyr = '201220132'";
//echo $query;
$result = pg_query($query);

?>

<form name="form1" method ="POST">
<select id = "courseno" class = "button" name = "courseno"> 

<?php 

while($row = pg_fetch_assoc($result))
{
echo "
<option value=\"".$row['courseno']."\">".$row['courseno']."</option>
";
}
?>
</select>

<input type="submit" value ="Select Course" class="button" name = "submit"> <br>

</form>
<?php

//echo "hre";
if(isset($_POST['courseno']))
{
        //echo "here" ;
        $courseno = $_POST['courseno'];         
        //echo $courseno.$nl; 
        
        //$query = "Select SUM(cast(response as NUMERIC))/COUNT(qid) as avg,qid from Response NATURAL JOIN feedback NATURAL JOIN Course_offering NATURAL JOIN Question where courseno = '$courseno' and question_type = 'numeric' GROUP BY qid";
        //echo $query.$nl;
        //$query1 = "SELECT qid, question, question_type from Question where qid <=10 ";
        //echo $query1.$nl;    
        //$result= pg_query($query);
        //$result1= pg_query($query1);
        $query = "SELECT * from Question NATURAL JOIN (Select SUM(cast(response as NUMERIC))/COUNT(qid) as avg,qid from Response NATURAL JOIN feedback NATURAL JOIN Course_offering NATURAL JOIN Question where courseno = '$courseno' and question_type = 'numeric' GROUP BY qid) t1 ORDER BY avg";
        //echo $query.$nl;
        $result = pg_query($query);
        
        
        echo "<br><br> The feedback results for the course <b>".$courseno." </b>are shown below. These results are the average of the feedback, the maximum rating being 5 and the minimum being 1.<br><br>";
        
        $num = 65;
        
        echo "<table  width=\"100%\" class = \"table\"> 
        <col width=\"5%\">
        <col width=\"70%\">
        <col width=\"25%\">
        ";
        while($row = pg_fetch_assoc($result))
        {
                echo "<tr><td>".chr($num).") </td><td>";
                $num ++;
                echo $row['question']."</td><td>";
                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".round($row['avg'],2)."</td></tr>";                
        }
         
        // get fbid from feedback by using sname and cid
        echo "</table>";
                
}



        
?>        
                                                </div>
                                        </div>
                                        
                                        <div style="clear: both;">&nbsp;</div>
                                </div>
                                <!-- end #content -->
                                <div id="sidebar">
                                        <ul>
                                                <li>
                                                        <h2>About the site</h2>
                                                        <p>Its free and always will be</p>
                                                </li>
                                                <li>
                                                        <h2>Categories</h2>
                                                        <ul>
                                                                
                                                                
                                                        </ul>
                                                </li>
                                                
                                        </ul>
                                </div>
                                <!-- end #sidebar -->
                                <div style="clear: both;">&nbsp;</div>
                        </div>
                </div>
        </div>
        <!-- end #page -->
</div>
<div id="footer">
<p>Copyright (c) 2013 @rishabh. All rights reserved. Source code available <a href= "">here</a></p>
</div>
<!-- end #footer -->
</body>
</html>










