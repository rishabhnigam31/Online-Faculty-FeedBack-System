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
<!-- <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css"> -->
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
        <div id="menu-wrapper">
                <div id="menu">
                        <ul>
                                <li class="current_page_item"><a href="index.php">Home</a></li>
                                <li><a href="add.php">Add your courses here</a></li>
                                <li><a href="feed.php">Give Feedback</a></li>        
                                <li>
                                <?php
                                session_start();
                                if(isset($_POST["logout"]))
                                {
                                        //echo "here";
                                        session_unset(); 
                                        session_destroy(); 
                                }
                                if(!empty($_SESSION['user']))
                                        echo "
                                        <form method = \"POST\" action=\"index.php\">
                                        <input type = \"submit\" value=\"Logout\" class=\"button\" name=\"logout\" style=\"position:absolute;right:50px;top:10px\"> 
                                        </form>
                                        
                                        ";
                                ?>
                                </li>
                                <li>
                                <?php                                
                                
                                if(!empty($_SESSION['user']))
                                        echo "<span style = \"position:absolute;font-size:150%;color:#ffffff; left:50px;top:14px;\"> ". $_SESSION['user'] ." </span>";
                                ?>
                                </li>
                                
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

                                                

                                                $nl = "<br>";

                                                $dbconn = pg_connect("host=localhost dbname=project user=rishabh password=arbit")
                                                        or die("Could not connect" . pg_last_error());
                                                // there gthere                                               
                                                if( (!isset($_POST["name"]) && !isset($_POST["loginname"])) && empty($_SESSION['user']))
                                                {                                                       
                                                        echo "
                                                        Already a member, just login to the site 
                                                        <form action = \"index.php\" method = \"POST\">        
                                                                <table>
                                                                <tr> <td>Username: </td> <td><input type=\"text\" name = \"loginname\"> </td></tr>
                                                                <tr> <td>Password: </td> <td><input type=\"password\" name = \"password\"></td></tr>                 
                                                                </table>
                                                                <input type=\"submit\" value = \"Login to the site\" class = \"button\">                
                                                        </form>
                                                        <br><br>
                                                        Not a member yet, Register below.
                                                        <form action = \"index.php\" method = \"POST\"> 
                                                                <table>
                                                                <tr> <td> Name: </td> <td><input type=\"text\" name = \"name\"> </td></tr>
                                                                <tr> <td> Program: </td> <td><input type=\"text\" name = \"program\"> </td></tr>
                                                                <tr> <td> Password: </td> <td><input type=\"password\" name = \"password\"> </td></tr>
                                                                <tr> <td> Username: </td> <td><input type=\"text\" name = \"sname\"> </td></tr>
                                                                <tr> <td> Department: </td> <td><input type=\"text\" name = \"dept\"> </td></tr>
                                                                <tr> <td> Emailid: </td> <td><input type=\"text\" name = \"emailid\"> </td></tr>
                                                                <tr> <td> Rollno: </td> <td><input type=\"text\" name = \"rollno\"> </td></tr>                                                                 
                                                                </table>
                                                                <input type=\"submit\" value = \"Register\" class = \"button\">
                                                        </form>
                                                        <br><br>
                                                        ";
                                                        goto end;
                                                }


                                                else if(empty($_SESSION['user']) && isset($_POST["name"]))
                                                {
                                                //echo "FEEDING IN THE DATABASE";

                                                        
                                                $name = $_POST["name"];
                                                $program = $_POST["program"];
                                                $password = $_POST["password"];
                                                $password_hash = md5($password);
                                                $sname = $_POST["sname"];
                                                $dept = $_POST["dept"];
                                                $emailid = $_POST["emailid"];
                                                $rollno = $_POST["rollno"];
                                                
                                                if($_POST["name"] =="" || $_POST["password"] ==""  || $_POST["program"] ==""  || $_POST["sname"]==""  || $_POST["dept"] ==""  || $_POST["emailid"] ==""  || $_POST["rollno"]==""  ){
                                                echo "Some fields are left empty, please fill all the fields.";
                                                goto end;
                                                }
                                                //setcookie("user", $sname, time()+3600);
                                                //echo $name."\n";                                                
                                                $test = "iitk.ac.in";
                                                $str = $emailid;
                                                if($name)
                                                //echo substr( $str, -strlen( $test ) );
                                                if(!(substr( $str, -strlen( $test ) ) == $test)){
                                                        echo "wrong email address must be a iitk email id.<br>. Please try to register again.";
                                                        goto end;
                                                }
                                                $query = "INSERT into Student VALUES ('$name', '$program', '$password_hash', '$sname', '$dept', '$emailid');" ;
                                                //echo $query."\n";
                                                $result1 = pg_query($query) or die('Could not get result'.pg_last_error());
                                                $query = "INSERT into Roll VALUES ('$sname', '$rollno');" ;
                                                //echo $query."\n";
                                                $_SESSION['user'] = $sname;
                                                $result2 = pg_query($query) or die('Could not get result'.pg_last_error());

                                                }

                                                else if(empty($_SESSION['user']) && isset($_POST["loginname"]))
                                                {
                                                //        $dbconn = pg_connect("host=localhost dbname=project user=rishabh password=arbit")
                                                //      or die("Could not connect" . pg_last_error());

                                                        //echo  "TRYING TO LOGIN".$nl;
                                                        $sname = $_POST["loginname"];
                                                        $password = $_POST["password"];
                                                        $query = "SELECT password_hash from Student WHERE sname = '$sname';";
                                                        //echo $query;
                                                        $result = pg_query($query);
                                                        //echo $query.$nl;
                                                        
                                                        $row = pg_fetch_assoc($result);        
                                                        $password_hash2 = $row['password_hash'];             
                                                        //echo "xxx";
                                                        //echo $password_hash2.$nl;
                                                        if($password_hash2 == md5($password))
                                                        {
                                                        //      echo "Match";
                                                                $_SESSION['user'] = $sname;
                                                        //     echo $sname;
                                                                //setcookie("user", $sname, time()+3600);
                                                        }
                                                        else{}
                                                                //echo "Failed";                                    
                                                }



                                                $user = $_SESSION['user'];
                                                /*if(!isset($_COOKIE["user"]))
                                                {
                                                        if(isset($_POST["loginname"]))
                                                        {
                                                                $user = $_POST["loginname"];
                                                        }
                                                        if(isset($_POST["sname"]))
                                                        {
                                                                $user = $_POST["sname"];
                                                        }
                                                } */       
                                                echo "Welcome " . $user."<br>";

                                                // you always have the username in $user beyond this point.

                                                $query = "SELECT cid from registration where sname = '$user'; ";
                                                //echo $query.$nl;
                                                $result = pg_query($query);
                                                if(!$result)
                                                        echo "NULL";
                                                $row = pg_fetch_assoc($result);
                                                {
                                                        //echo "here";
                                                        //echo $row['cid'].$nl;
                                                }

                                                // select all courses from Course_offering



                                                ?>                                                
                                                <?php


                                                end:
                                                ?>
                                                        <p>This is <strong>a student feedback online system  </strong>, built as a course project in the course <b>CS 315</b> under the supervision of Professor Harish Karnick.  The technologies used for this project include postgresql, php , html, css. The css for this site has been taken from a template at <a href="http://www.freecsstemplates.org">FCT</a>.  This free template is released under a <a href="http://creativecommons.org/licenses/by/3.0/">Creative Commons Attributions 3.0</a> license</a>. The complete source code is put up at <a href="/home.iitk.ac.in/~rishabhn/cs315/project">here</a>. You can use parts of it but remember to include the above link. Aside from that, have fun with it :) </p>
                                                </div>
                                        </div>
                                        
                                        <div style="clear: both;">&nbsp;</div>
                                </div>
                                <!-- end #content -->
                                <div id="sidebar">
                                        <ul>
                                                <li>
                                                        <h2>About the site</h2>
                                                        <p>This is an online course feedback system developed to eliminate the manual work involved in the process.</p>
                                                </li>
                                                <li>
                                                        <h2>Categories</h2>
                                                        <ul>
                                                                <li><a href="index.php">Home</a></li>
                                                                <li><a href="add.php">Add courses here</a></li>
                                                                <li><a href="feed.php">Give the feedback</a></li>
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


