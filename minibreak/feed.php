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
<title>Mini-Break  by FCT</title>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css">
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
        <div id="menu-wrapper">
                <div id="menu">
                        <ul>
                                <li><a href="index.php">Home</a></li>
                                <li><a href="add.php">Add your courses here</a></li>
                                <li  class="current_page_item"><a href="feed.php">Give Feedback</a></li>   
                                <li>
                                <form method = "POST" action="index.php">
                                <input type = "submit" value="Logout" class="button" name="logout" style="position:absolute;right:50px;top:10px">                                
                                </form></li>
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
        

if(isset($_POST["logout"]))
{
        //echo "here";
        session_unset(); 
        session_destroy(); 
}        
        
if(empty($_SESSION['user']))
{
        echo "Please login to continue";
        goto end;
}        

$sname = $_SESSION['user'];  
$query = "SELECT cid,courseno, title from Registration NATURAL JOIN Course_offering NATURAL JOIN  Course_name where sname = '$sname'";
//echo $query;
$result = pg_query($query);
// while($row = pg_fetch_assoc($result))
// {
//         echo $row['cid'];
//         echo $row['courseno'];
//         echo $row['title'].$nl;
// }
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
        echo "<br>";
        echo "Please give your feedback for the course ".$courseno.$nl.$nl;
        $query = "SELECT qid, question, question_type from Cq NATURAL JOIN Course_offering NATURAL JOIN Question where courseno = '$courseno'";
        $query1 = "SELECT qid, question, question_type from Question where qid <=10 ";
        //echo $query1.$nl;    
        $result= pg_query($query);
        $result1= pg_query($query1);
        $num = 65;
        echo "<form action=\"feed.php\" method = \"POST\">";
        echo "<table  width=\"100%\"> 
        <col width=\"5%\">
        <col width=\"70%\">
        <col width=\"25%\">
        ";
        while($row = pg_fetch_assoc($result))
        {
                 echo chr($num).") ";
                $num ++;
                echo $row['question'];
                if($row['question_type'] == numeric)
                        {
                                for ($i = 1 ; $i <= 5 ; $i ++){ 
                                        echo "<input type=\"radio\" name =".$row['qid']." value = ".$i.">" .$i." ";}
                                echo "<br>\n";
                        }
                if($row['question_type'] == text)
                        {
                                echo "<textarea cols=\"40\" row = \"6\" name =" .$row['qid']."> </textarea>" ."<br>\n";
                        }
        }
        
        while($row = pg_fetch_assoc($result1))
        {
                echo "<tr> <td>".chr($num).") </td>";
                $num ++;
                echo "<td>".$row['question']."</td><td>";
                
                if($row['question_type'] == numeric)
                        {
                                for ($i = 1 ; $i <= 5 ; $i ++){ 
                                        echo "<input type=\"radio\" name =".$row['qid']." value = ".$i.">" .$i." ";}
                                echo "<br>\n";
                        }
                if($row['question_type'] == text)
                        {
                                echo "<textarea cols=\"40\" row = \"4\" name =" .$row['qid']."> </textarea>" ."<br>\n";
                        }
                echo "</td>";
        }
        // get fbid from feedback by using sname and cid
        echo "</table>";
        echo '<input type = "submit" name = "StoreinDB" class = "button" value = "send the feedback"> <br>';
        echo "</form>";
        $query = "SELECT cid from Course_offering where courseno = '$courseno'" ;
        $result = pg_query($query);
        $row = pg_fetch_assoc($result);
        //echo $row['cid'].$query;
        $_SESSION['cid'] = $row['cid'];
}

if(isset($_POST['StoreinDB']) )
{
        //echo "here"; 
        $cid = $_SESSION['cid'];
        //echo $cid;
        $query1 = "SELECT qid, question, question_type from Question where qid <=10 ";
        $query2 = "SELECT fbid from Feedback where cid = '$cid' and sname = '$sname'";
        //echo $query2.$nl;
        $result2 = pg_query($query2);
        $row = pg_fetch_assoc($result2);
        $fbid = $row['fbid'];
        //echo $fbid.$nl;
        //echo $query1;
        $result = pg_query($query1);
        while($row = pg_fetch_assoc($result))
        {
                $s = $row['qid'];
                
                if( isset($_POST[$s]) )
                {
                        //echo $s.$_POST[$s].$nl;
                        //echo $s. $_POST[$s].$nl;
                        
                        $r = htmlspecialchars($_POST[$s]);
                        $query = "Insert into Response values('$fbid', '$s', '$r' ) ";
                        //echo $query.$nl;
                        $res = pg_query($query);
                }
        }
        if($res)
                echo "FEEDBACK RECEIVED";
        else
                echo  "Some error happened, try again or contact rishabhn@iitk.ac.in" ;
        end:
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
                                                                <li><a href="#">Aliquam libero</a></li>
                                                                <li><a href="#">Consectetuer adipiscing elit</a></li>
                                                                <li><a href="#">Metus aliquam pellentesque</a></li>
                                                                <li><a href="#">Suspendisse iaculis mauris</a></li>
                                                                <li><a href="#">Urnanet non molestie semper</a></li>
                                                                <li><a href="#">Proin gravida orci porttitor</a></li>
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
        <p>Copyright (c) 2013 Sitename.com. All rights reserved. Design by <a href="http://www.freecsstemplates.org">FCT</a>. Photos by <a href="http://fotogrph.com/">Fotogrph</a>.</p>
</div>
<!-- end #footer -->
</body>
</html>






