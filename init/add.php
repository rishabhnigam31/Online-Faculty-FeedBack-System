<?php

session_start();

$nl = "<br>";
$dbconn = pg_connect("host=localhost dbname=project user=rishabh password=arbit")
        or die("Could not connect" . pg_last_error());
        

if(empty($_SESSION['user']))
{
        echo "Please login to continue";
        goto end;
}        
$sname = $_SESSION['user'];


        
$query = "SELECT cid,courseno, title FROM Course_offering NATURAL JOIN Course_name except select cid, courseno, title from Registration NATURAL JOIN Course_name NATURAL JOIN Course_offering where sname = '$sname'";
//echo $query.$nl;

//echo $query.$nl;
$result = pg_query($query);
$result1 = $result;


if($_GET["submit"])
{
//         if(isset($_GET["CS315"]))
//                 echo "set";
//         else
//                 echo "not set";
//         if(isset($_GET["CS\ 350"]))
//                 echo "set";
//         else
//                 echo "not set";
        while($row = pg_fetch_assoc($result1))
        {
                if(isset($_GET[$row['courseno']]))
                {
                        echo "done".$nl ;
                        // insert in the Registration table
                        //var_dump($_COOKIE);
                        $sname  = $_SESSION['user'];
                        //echo $sname;
                        $cid = $row['cid'];
                        $query1 = "INSERT into Registration VALUES ('$sname','$cid');";
                        pg_query($query1);
                        $check = "SELECT * from feedback where sname = '$sname' and cid = '$cid'";
                        //echo $check;
                        $res = pg_query($check);
                        if(!pg_fetch_assoc($res))
                        {
                                echo "set";
                                $query1 = "INSERT into feedback VALUES ('$sname','$cid');";
                                pg_query($query1);
                        }
                        
                        //echo $query1.$nl;
                }
                //echo $row['courseno'].$_GET["CS 315"];
        }
}
echo "<form action = \"add.php\" method = \"GET\">";
$result = pg_query($query);
echo "<table >";
while($row = pg_fetch_assoc($result))
{               
        echo "<tr> <td> <input type = \"checkbox\" name = \"".$row['courseno']."\" value = \"YES\" >". "</td><td>". $row['courseno']. "</td><td>". $row['title']."</td></tr>";        
}
echo "</table>";
echo "<input type = \"submit\" name = \"submit\" value = \"Add Courses to your template\">"; 
echo "</form>";
echo "<br><br>You are already registered for these courses <br>";
$query = "select cid, courseno, title from Registration NATURAL JOIN Course_name NATURAL JOIN Course_offering where sname = '$sname'";
$result = pg_query($query);
echo "<table>";
while($row = pg_fetch_assoc($result))
{
        echo "<tr><td>".$row['courseno']."</td><td>".$row['title']."</td></tr>";
}
echo "</table>";
//echo $query;
end:
?>