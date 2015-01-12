<?php

//This Function validates user input for type and length

function inputcheck($string,$type,$length)
{

	$type = 'is_'.$type;

	if(!$type($string))
	{
		return FALSE;
	}
	// now we see if there is anything in the string
	elseif(empty($string))
	{
		return FALSE;
	}
	// then we check how long the string is
	elseif(strlen($string) > $length)
	{
		return FALSE;
	}
	else
	{
	// if all is well, we return TRUE
		return TRUE;
	}
}

  // check number is >= than 0 and $length digits long
  // returns TRUE on success
function checkNumber($num, $min, $max)
{
	if(strlen($num) >= $min && strlen($num) <= $max)
	{
		return TRUE;
	}
	else{return FALSE;}
}

function checkEmail($email)
{
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,8}$/iU', $email) ? TRUE : FALSE;
}


?>