<!DOCTYPE html>
<html lang="en-US">

<?php

require 'dbinfo.php';
require 'inputcheck.php';


if(isset($_POST['create']))
{
	$valid = FALSE;
	$success = FALSE;

	if( !inputcheck($_POST['track_name'],'string',64, 5) ){ 
		$submit_message = 'Invalid Track Name';
	}elseif( !inputcheck($_POST['country'],'string',64, 3) ){ 
		$submit_message = 'Invalid Country Name';
	}elseif( !inputcheck($_POST['length'], 'numeric', 11, 0) || !checkNumber($_POST['length'], 0, 99999999999) ){ 
		$submit_message = 'Invalid Length';
	}elseif( !inputcheck($_POST['type'], 'numeric', 2, 1) || !checkNumber($_POST['type'], 1, 2) ){ 
		$submit_message = 'Invalid Track type';
	}elseif( !inputcheck($_POST['start_heading'], 'numeric', 2, 1) || !checkNumber($_POST['type'], 1, 2) ){ 
		$submit_message = 'Invalid Track Direction';
	}elseif( !inputcheck($_POST['start_latitude'], 'numeric', 11, 0) || !checkNumber($_POST['start_latitude'], -90, 90) ){ 
		$submit_message = 'Invalid Start Latitude';
	}elseif( !inputcheck($_POST['start_latitude'], 'numeric', 11, 0) || !checkNumber($_POST['start_longitude'], -180, 180) ){ 
		$submit_message = 'Invalid Start Longitude';
	}elseif($_POST['type'] == '2'){
		if( !inputcheck($_POST['end_latitude'], 'numeric', 11, 0) || !checkNumber($_POST['end_latitude'], -90, 90) ){ 
			$submit_message = 'Invalid Finish Latitude';
		}elseif( !inputcheck($_POST['end_longitude'], 'numeric', 11, 0) || !checkNumber($_POST['end_longitude'], -180, 180) ){ 
			$submit_message = 'Invalid Finish Longitude';
		}else{
			$valid = TRUE;
			$end_latitude = $_POST['end_latitude'];
			$end_longitude = $_POST['end_longitude'];
		}
	}else{
		$valid = TRUE;
		$end_latitude = 0;
		$end_longitude = 0;
	}

	if($valid == TRUE)
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


		$query = $db->prepare("INSERT INTO tracks (track_name, length, country, type, start_latitude, start_longitude, end_latitude, end_longitude, start_heading, active, created_uid) VALUES (:track_name, :length,:country, :type, :start_latitude, :start_longitude, :end_latitude, :end_longitude, :start_heading, :active, :created_uid)");
		
		$query->bindParam(':track_name', $_POST['track_name']);
		$query->bindParam(':length', $_POST['length']);
		$query->bindParam(':country', $_POST['country']);
		$query->bindParam(':type', $_POST['type']);
		$query->bindParam(':start_latitude', $_POST['start_latitude']);
		$query->bindParam(':start_longitude', $_POST['start_longitude']);
		$query->bindParam(':end_latitude', $end_latitude);
		$query->bindParam(':end_longitude', $end_longitude);
		$query->bindParam(':start_heading', $_POST['start_heading']);
		$query->bindParam(':active', $active);
		$query->bindParam(':created_uid', $create_uid);
		
		try
		{		
			$query->execute();
			$submit_message = "Track Added Successfully.";
		}
		catch (PDOException $e2)
		{	
			$submit_message = $e2->getMessage();
		}
		$success = TRUE;	
		$db = NULL;
	}
}
?>

<div align="center">
	Create new Track
	<form method="post" action="<?php $_PHP_SELF ?>">
	
	<table id="form_table">

	<tr>
		<td>Track Name</td>
		<td>
			<input name="track_name" type="text" id="track_name" size=50 maxlength=64 autofocus required>
		</td>
	</tr>
	
	<tr>
		<td>Length</td>
		<td>
			<input name="length" type="number" id="length" size=50 maxlength="11" min="0">
			meters
		</td>
	</tr>
	<tr>
		<td>Track Type</td>
		<td>Circuit
			<input name="type" type="radio" id="type" value="1">
			Sprint
			<input name="type" type="radio" id="type" value="2">
		</td>
	</tr>
	<tr>
		<td>
			Start
		</td>
		<td>
			Latitude:
			<input name="start_latitude" type="number" id="start_latitude" size="30" maxlength="11" min="-90" max="90" step="any" placeholder="41.9714451" required>
			<br>
			Longitude:
			<input name="start_longitude" type="number" id="start_longitude" size="30" maxlength=11 min="-180" max="180" step="any" placeholder="-20.6870728" required>
		</td>
	</tr>

	<tr>
		<td>
			Finish 
		</td>
		<td>
			Latitude:
			<input name="end_latitude" type="number" id="end_latitude" size="30" maxlength="11" min="-90" max="90" step="any" placeholder="41.9714451" required>
			<br>
			Longitude:
			<input name="end_longitude" type="number" id="end_longitude" size="30" maxlength=11 min="-180" max="180" step="any" placeholder="-20.6870728" required>
		</td>
	</tr>
</script>
	<tr>
		<td>Running Direction</td>
		<td>Clockwise
					<input name="start_heading" type="radio" id="start_heading" value="1">
			Anti-Clockwise
					<input name="start_heading" type="radio" id="start_heading" value="2">
		</td>

	</tr>
	<tr>
		<td> </td>
		<td>
			<input type="text" name='country' id="country" list="someCountries" placeholder="Country" required>
			<datalist id="someCountries">
				<?php require ("countrydropdown.html"); ?>
			</datalist>
		</td>
	</tr>
	
	
	<tr>
		<td id="form_error">
			<?php echo isset($submit_message) ? $submit_message : ''; ?>
		</td>
		<td>
			<input name="create" type="submit" id="create" value="Create">
		</td>

	</tr>

	</table>
	
	</form>
</div>

</html>