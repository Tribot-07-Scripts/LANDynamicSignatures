<?php
require("db.php");

function output($username) {
	global $table_name;
	global $mysqli;
	$username = mysqli_real_escape_string($mysqli, $username);
	$query = "SELECT * FROM $table_name WHERE username = '$username' LIMIT 1";
	$result = mysqli_query($mysqli, $query);
	if(mysqli_num_rows($result)==1) {
		// Vars to write
		$row = mysqli_fetch_assoc($result);
		$human_seconds = convert_seconds($row["runtime"]);
		
		// Path to background image you wish to use
		$image = imageCreateFromPNG("background.png") or die("Cannot select the correct background image. Please contact the script writer."); 
		
		// All coords/data which is going to be written on the image.
		$loc = array(
		
			// string to write,  font size,   x text_area,   text_area_width,     y baseline
			array("User: ".$username,   12,             0,    			   0,            0),
			array($human_seconds,       12,             0,                 0,            0),
			array($row["var1"],         12,             0,                 0,            0),
			array($row["var2"],         12,             0,                 0,            0),
			array($row["var3"],         12,             0,                 0,            0),
			array($row["var4"],         12,             0,                 0,            0)
			
			// leave the text_area_width at 0 if you do not want the text to be centered.
		);
		
		// Path to font you wish to use
		$font = 'Arial.ttf';

		// Text color in RGB values (currently white)
		$color = ImageColorAllocate($image, 255, 255, 255);
		
		// PNG Transparency settings
		imagecolortransparent($image, $color);
		imagesavealpha($image, true);
		
		foreach ($loc as $lineToWrite) {
			// Size of the text we're going to write
			$BoundingBox = imagettfbbox($lineToWrite[1], 0, $font, $lineToWrite[0]);
			
			if ($lineToWrite[3] > 0) {
				$text_width = $BoundingBox[4] - $BoundingBox[6]; // Top right 'x' minus Top left 'x' 
				$x = $lineToWrite[2] + (($lineToWrite[3] - $text_width) / 2 );
			} else
				$x = $lineToWrite[2];
			
			imagettftext($image, $lineToWrite[1], 0, $x, $lineToWrite[4], $color, $font, $lineToWrite[0]);
		}
		
		// now save a copy of the image to the /users/ folder for caching.
		imagepng($image, "users/" .$username. ".png"); 
		
		// send the image to the browser
		header("Content-type: image/png");
 		imagepng($image);
		imagedestroy($image);
	}
	else {
		echo "Username not found";
	}
}

function input($username, $runtime, $var1, $var2, $var3, $var4) {
	global $table_name;
	global $mysqli;
	$username = mysqli_real_escape_string($mysqli, $username);
	$runtime = mysqli_real_escape_string($mysqli, $runtime);
	$var1 = mysqli_real_escape_string($mysqli, $var1);
	$var2 = mysqli_real_escape_string($mysqli, $var2);
	$var3 = mysqli_real_escape_string($mysqli, $var3);
	$var4 = mysqli_real_escape_string($mysqli, $var4);
	$query = "SELECT * FROM $table_name WHERE username = '$username'";
	$result = mysqli_query($mysqli, $query);
	if(mysqli_num_rows($result)==1) {
		if(empty($username) || empty($runtime)) {
			echo "You are missing some parameters.";
		}
		else {
			$row = mysqli_fetch_assoc($result);
			$runtime = $runtime + $row["runtime"];
			$var1 = $var1 + $row["var1"];
			$var2 = $var2 + $row["var2"];
			$var3 = $var3 + $row["var3"];
			$var4 = $var4 + $row["var4"];
			$update_query = "UPDATE $table_name SET runtime='$runtime', var1='$var1', var2='$var2', var3='$var3', var4='$var4' WHERE username='$username'";
			$update_result = mysqli_query($mysqli, $update_query);
			if(!$update_result) {
				echo "Error performing query.";
			}
			else {
				// delete the cached image since the data has been updated now
				if (file_exists("users/".$username.".png")) {
        			unlink("users/".$username.".png");
				}
			}
		}
	}
	else if(mysqli_num_rows($result)==0) {
		if(empty($username) || empty($runtime)) {
			echo "You are missing some parameters.";
		}
		else {
			$insert_query = "INSERT INTO $table_name (username, runtime, var1, var2, var3, var4) VALUES ('$username', '$runtime', '$var1', '$var2', '$var3', '$var4')";
			$insert_result = mysqli_query($mysqli, $insert_query);
			if(!$insert_result) {
				echo "Error performing query.";
			}
		}
	}
	else {
		echo "Error performing query.";
	}
}

function convert_seconds($d)
{
    $periods = array( 'day'    => 86400,
                      'hour'   => 3600,
                      'minute' => 60,
                      'second' => 1 );
    $parts = array();
    foreach ( $periods as $name => $dur )
    {
        $div = floor( $d / $dur );
         if ( $div == 0 )
                continue;
         else if ( $div == 1 )
                $parts[] = $div . " " . $name;
         else
                $parts[] = $div . " " . $name . "s";
         $d %= $dur;
    }
    $last = array_pop( $parts );
    if ( empty( $parts ) )
        return $last;
    else
        return join( ', ', $parts ) . " and " . $last;
}
?>
