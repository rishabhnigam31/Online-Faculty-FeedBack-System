<?php
session_start();

$nl = "<br>";
$dbconn = pg_connect("host=localhost dbname=project user=rishabh password=arbit")
        or die("Could not connect" . pg_last_error());
        
$sname = $_SESSION["user"];        
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
<select id = "courseno" name = "courseno"> 

<?php 

while($row = pg_fetch_assoc($result))
{
echo "
<option value=\"".$row['courseno']."\">".$row['courseno']."</option>
";
}
?>
</select>
<br>
<input type="submit" value ="Select Course" name = "submit"> <br>

</form>
<?php

//echo "hre";
if(isset($_POST['courseno']))
{
	//echo "here" ;
	$courseno = $_POST['courseno'];    	
	//echo $courseno.$nl; 
	echo "Please give your feedback for the course ".$courseno.$nl.$nl;
	$query = "SELECT qid, question, question_type from Cq NATURAL JOIN Course_offering NATURAL JOIN Question where courseno = '$courseno'";
	$query1 = "SELECT qid, question, question_type from Question where qid <=10 ";
	//echo $query1.$nl;    
	$result= pg_query($query);
	$result1= pg_query($query1);
	$num = 65;
        echo "<form action=\"feed.php\" method = \"POST\">";
        
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
                                echo "<textarea cols=\"50\" row = \"3\" id =" .$row['qid']."> </textarea>" ."<br>\n";
                        }
	}
	
	while($row = pg_fetch_assoc($result1))
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
				echo "<textarea cols=\"50\" row = \"3\" id =" .$row['qid']."> </textarea>" ."<br>\n";
			}
	}
	// get fbid from feedback by using sname and cid
    
	echo '<input type = "submit" name = "StoreinDB" value = "send the feedback"> <br>';
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
                //echo $s;
                if( isset($_POST[$s]) )
                {
                        //echo $s. $_POST[$s].$nl;
                        $query = "Insert into Response values('$fbid', '$s', '$_POST[$s]' ) ";
                        //echo $query.$nl;
                        $res = pg_query($query);
                }
        }
        if($res)
                echo "FEEDBACK RECEIVED";
        else
                echo  "Some error happened, try again or contact rishabhn@iitk.ac.in" ;
}
	

        
?>        