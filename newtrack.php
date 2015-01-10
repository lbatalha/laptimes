<!DOCTYPE html>
<html lang="en-US">

<?php

include 'dbinfo.php';

if(isset($_POST['create']))
{
	try 
	{
		$db = new PDO("mysql:dbname=$dbname;host=$dbhost","$dbuser","$dbpass");
	}
	catch(PDOException $e)
    {
		echo $e->getMessage();
    }
	
	$create_uid = 1;
	$active = 1;
	$display = TRUE;

	
	$query = $db->prepare("INSERT INTO tracks (track_name, length, country, type, start_coord, end_coord, start_heading, active, created_uid) VALUES (:track_name, :length,:country, :type, :start_coord, :end_coord, :start_heading, :active, :created_uid)");
	
	
	$query->bindParam(':track_name', $_POST['track_name']);
	$query->bindParam(':length', $_POST['length']);
	$query->bindParam(':country', $_POST['country']);
	$query->bindParam(':type', $_POST['type']);
	$query->bindParam(':start_coord', $_POST['start_coord']);
	$query->bindParam(':end_coord', $_POST['end_coord']);
	$query->bindParam(':start_heading', $_POST['start_heading']);
	$query->bindParam(':active', $active);
	$query->bindParam(':created_uid', $create_uid);
	
	try
	{		
		$query->execute();
		echo "Entered data successfully\n";
	}
	catch (PDOException $e2)
	{	
		echo $e2->getMessage();
	}
	
	

	$db = null;
}
?>

<div align="center" style="color:#eeeeeeeee">
	Create new Track
	<form method="post" action="<?php $_PHP_SELF ?>">
	
	<table width="500" border="1" cellspacing="1" cellpadding="6">

	<tr>
		<td width="128">Track Name</td>
		<td><input name="track_name" type="text" id="track_name" size=50 maxlength=64 value="<?php echo (isset($_POST['track_name']) && $display == FALSE) ? $_POST['track_name'] : '';?>" ></td>
	</tr>
	
	<tr>
		<td width="100">Length(m)</td>
		<td><input name="length" type="text" id="length" size=50 maxlength=16></td>
	</tr>
	
	<tr>
		<td width="128">Start Coordinate</td>
		<td><input name="start_coord" type="text" id="start_coord" size=25 maxlength=11></td>
		<td width="100">End Coordinate</td>
		<td><input name="end_coord" type="text" id="end_coord" size=25 maxlength=11></td>
	</tr>
	
	<tr>
		
	</tr>
	
	<tr>
		<td width="100">Start Heading(°)</td>
		<td><input name="start_heading" type="text" id="start_heading" size=25 maxlength=11></td>
	</tr>
	<tr>
		<td> </td>
		<td>
			<input type="text" name='country' id="country" list="someCountries" />
			<datalist id="someCountries">
					<?php include ("countrydropdown.html"); ?>
			</datalist>
		</td>
	</tr>
	<tr>
		<td> </td>
		<td>
		<select name="type">
			<option value="0" selected>Track Type</option>
			<option value="1">Circuit</option>
			<option value="2">Sprint</option>
		</select>
		</td>
	</tr>
	
	<td><input name="create" type="submit" id="create" value="Create"></td>
	
	</table>
	
	</form>
</div>

</html>