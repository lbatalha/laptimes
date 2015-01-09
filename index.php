<html>

<body>

<?php
if(isset($_POST['add']))
{
$conn = mysql_connect("localhost:3306","root","toplel");
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}

if(! get_magic_quotes_gpc() )
{
   $track_id = addslashes ($_POST['track_id']);
   $laptime = addslashes ($_POST['laptime']);
}
else
{
   $track_id = $_POST['track_id'];
   $laptime = $_POST['laptime'];
}
mysql_select_db('laps');
$sql = "INSERT INTO laps ".
       "(track_id,laptime) ".
       "VALUES('$track_id','$laptime')";

$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}
echo "Entered data successfully\n";
mysql_close($conn);
}
else
{
?>
<div align="center" style="color:#cccccc">
Add new lap
<form method="post" action="<?php $_PHP_SELF ?>">
<tr>
<td width="100">Track ID</td>
<td><input name="track_id" type="text" id="id"></td>
</tr>
<tr>
<td width="100">Lap Time (ms)</td>
<td><input name="laptime" type="text" id="laptime"></td>
</tr>
<td>
<input name="add" type="submit" id="add" value="Submit">
</td>
</div>

<div align="center" style="color:#cccccc">
List Laps

Track ID:


</div>
<?php
}
?>



<body>
















</html>