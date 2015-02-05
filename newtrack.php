<!DOCTYPE html>
<html lang="en-US">

<?php

require 'dbinfo.php';
require 'inputcheck.php';



if(isset($_POST['create']))
{
	$db = NULL;

	$valid = FALSE;
	
	$lat_range = 90;
	$lon_range = 180;

	// inputcheck($string, $type, $length, $minlength, $min, $max)
	if(!inputcheck('track_name', 64, 5))
	{ 
		$submit_message = 'Invalid Track Name';
	}
	elseif(!inputcheck('country', 64, 2))
	{ 
		$submit_message = 'Invalid Country Name';
	}
	elseif(!inputcheck('length', 11, 0, 0, 1000000000))
	{ 
		$submit_message = 'Invalid Length';
	}
	elseif(!inputcheck('type', 2, 1, 1, 2))
	{ 
		$submit_message = 'Invalid Track type';
	}
	elseif(!inputcheck('track_direction', 2, 1, 1, 2))
	{ 
		$submit_message = 'Invalid Track Direction';
	}
	elseif(!inputcheck('start_latitude', 11, 0, -$lat_range, $lat_range))
	{ 
		$submit_message = 'Invalid Start Latitude';
	}
	elseif(!inputcheck('start_longitude', 11, 0, -$lon_range, $lon_range))
	{ 
		$submit_message = 'Invalid Start Longitude';
	}
	elseif($_POST['type'] == '2'){
		if(!inputcheck('end_latitude', 11, 0, -$lat_range, $lat_range))
		{ 
			$submit_message = 'Invalid Finish Latitude';
		}
		elseif(!inputcheck('end_longitude', 11, 0, -$lon_range, $lon_range))
		{ 
			$submit_message = 'Invalid Finish Longitude';
		}
		else
		{
			$valid = TRUE;
			$end_latitude = $_POST['end_latitude'];
			$end_longitude = $_POST['end_longitude'];
		}
	}
	else
	{
		$valid = TRUE;
		$end_latitude = 0;
		$end_longitude = 0;
	}

	if($valid)
	{
		try 
		{
			$db = new PDO("mysql:dbname=$dbname;host=$dbhost","$dbuser","$dbpass");
		}
		catch(PDOException $e)
		{
			$submit_message = "Error Connecting to Database, try again later (".$e->getmessage().").";
		}
		
		$created_uid = 1;
		$active = TRUE;


		$query = $db->prepare("INSERT INTO tracks (track_name, length, country, type, 
                                                   start_latitude, start_longitude, end_latitude, end_longitude, 
                                                   track_direction, active, created_uid) 
                                    VALUES        (:track_name, :length, :country, :type, 
                                                   :start_latitude, :start_longitude, :end_latitude, :end_longitude, 
                                                   :track_direction, :active, :created_uid)");
		
		$query->bindparam(':track_name', $_POST['track_name']);
		$query->bindparam(':length', $_POST['length']);
		$query->bindparam(':country', $_POST['country']);
		$query->bindparam(':type', $_POST['type']);
		$query->bindparam(':start_latitude', $_POST['start_latitude']);
		$query->bindparam(':start_longitude', $_POST['start_longitude']);
		$query->bindparam(':end_latitude', $end_latitude);
		$query->bindparam(':end_longitude', $end_longitude);
		$query->bindparam(':track_direction', $_POST['track_direction']);
		$query->bindparam(':active', $active);
		$query->bindparam(':created_uid', $created_uid);
		
		try
		{		
			$query->execute();
			$submit_message = "Track Added Successfully.";
		}
		catch (PDOException $e2)
		{	
			$submit_message = "Error Connecting to Database, try again later (".$e2->getmessage().").";
		}

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
			<input name="track_name" type="text" id="track_name" size="50" maxlength="64" required>
		</td>
	</tr>
	
	<tr>
		<td>Length</td>
		<td>
			<input name="length" type="number" id="length" size=50 maxlength="11" min="0" max="1000000000">
			meters
		</td>
	</tr>
	<tr>
		<td>Track Type</td>
		<td>Circuit
			<input name="type" type="radio" id="type" value="1" checked>
			Sprint
			<input name="type" type="radio" id="type" value="2">
		</td>
	</tr>
<?php
	$latitude_atributes = 'type="number" size="30" maxlength="11" min="-90" max="90" step="any" placeholder="41.9714451"';
	$longitude_atributes = 'type="number" size="30" maxlength="11" min="-180" max="180" step="any" placeholder="-20.6870728"';

	foreach(array(array('start', 'Start'), array('end', 'Finish')) as list($atribute_name, $coord_title))
	{
?>
		<tr>
			<td>
				<?=$coord_title?>
			</td>
			<td>
				Latitude:
				<input name="<?=$atribute_name.'_latitude'?>" id="<?=$atribute_name.'_latitude'?>" <?=$latitude_atributes?> required>
				<br>
				Longitude:
				<input name="<?=$atribute_name.'_longitude'?>" id="<?=$atribute_name.'_longitude'?>" <?=$longitude_atributes?> required>
			</td>
		</tr>
<?php	
	}
?>
 	
	<tr>
		<td>Running Direction</td>
		<td>
			Clockwise
					<input name="track_direction" type="radio" id="track_direction" value="1" checked>
			Anti-Clockwise
					<input name="track_direction" type="radio" id="track_direction" value="2">
		</td>

	</tr>
	<tr>
		<td> </td>
		<td>
			<input type="text" name="country" id="country" list="countrylist" placeholder="Country" required>
			<datalist id="countrylist">
				<?php require ('countrydropdown.php');?>
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