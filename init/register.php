

<?php

session_start();

$nl = "<br>";

$dbconn = pg_connect("host=localhost dbname=project user=rishabh password=arbit")
        or die("Could not connect" . pg_last_error());
// there gthere
if( (!isset($_POST["name"]) && !isset($_POST["loginname"])) && empty($_SESSION['user']))
{
        echo "Form";
        echo "
        <form action = \"register.php\" method = \"POST\">        
                Name: <input type=\"text\" name = \"name\"> <br>
                Program: <input type=\"text\" name = \"program\"> <br>
                Password: <input type=\"password\" name = \"password\"> <br>
                Username: <input type=\"text\" name = \"sname\"> <br>
                Department: <input type=\"text\" name = \"dept\"> <br>
                Emailid: <input type=\"text\" name = \"emailid\"> <br>
                Rollno: <input type=\"text\" name = \"rollno\"> <br>
                <input type=\"submit\" value = \"Register\">               
        </form>
        Or Login
        <form action = \"register.php\" method = \"POST\">        
                Username: <input type=\"text\" name = \"loginname\"> <br>
                Password: <input type=\"password\" name = \"password\"> <br>                
                <input type=\"submit\" value = \"Register\">                
        </form>
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

setcookie("user", $sname, time()+3600);
//echo $name."\n";

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
        echo "here";
        echo $row['cid'].$nl;
}

// select all courses from Course_offering



?>
<a href = "/dbms/add.php"> Add courses to your template </a> <br>
<a href = "/dbms/"> Home </a>
<?php


end:
?>
