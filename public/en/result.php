<link rel="stylesheet" href="../../style.css">
<script src="./../js/fun.js"></script>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.98">
	<meta name="theme-color" content="#190f13" />
	<link rel="icon" href="../../favicon.png" sizes="16x16 32x32" type="image/png">
<title>Results From BBoyZone Music Recognition</title>
</head>

<body>
</body>
</html>
<link rel="stylesheet" href="../../fontawesome/css/all.css">

<script>
	function copyToClipboard(text) {
		var inputc = document.body.appendChild(document.createElement("input"));
		inputc.value = window.location.href;
		inputc.focus();
		inputc.select();
		document.execCommand('copy');
		inputc.parentNode.removeChild(inputc);
		alert("URL Copied to Clipboard.");
}
	
	function share()
	{
		if (navigator.share) {
  		navigator.share({
    		title: 'Results From BBoyZone Music Recognition',
    		text: '',
    		url: window.location.href,
  		})
    		.then(() => console.log('Successful share'))
    	.catch((error) => console.log('Error sharing', error));
		}
	}
</script>

<?PHP
$file_text = file_get_contents('../../config/query_db_config.json');
$serverinfo = json_decode($file_text);
$servername = $serverinfo -> {'host'};
$username = $serverinfo -> {'user'};
$password = $serverinfo -> {'password'};
$dbname = $serverinfo -> {'database'};
echo '<div class="box"><p style="color: whitesmoke; font-family:Copperplate">';

if(isset($_GET['status']))
{
	if($_GET['status'] == '501')
	{
		http_response_code(501);
		if(isset($_GET['error']))
		{
		if($_GET['error'] == 'identifyerror')
		{
			echo '<script type="text/JavaScript"> change_title("404 Not Found"); </script>';
			echo '<b><i class="fas fa-exclamation-triangle"></i> ERROR</b></br></br>Could not identify the sample provided.</br></br>';
			echo '<a href="./../"><label for="return" class="custom-file-upload" style="color:whitesmoke; font-family:Copperplate">Back</label></a></div>';
			echo '</div>';
			exit();
		}
		}
		else {bad_req();exit();}
	}
	else
	if($_GET['status'] == '400')
	{
		http_response_code(400);
		if(isset($_GET['error']))
		{
		if($_GET['error'] == 'nofilechosen')
		{
			echo '<script type="text/JavaScript"> change_title("ERROR"); </script>';
			echo '<b><i class="fas fa-exclamation-triangle"></i> ERROR</b></br></br>No file chosen.</br></br>';
			echo '<a href="./../"><label for="return" class="custom-file-upload" style="color:whitesmoke; font-family:Copperplate">Back</label></a>';
			echo '</div>';
			exit();
		}
		else if($_GET['error'] == 'filetypeerror')
		{
			echo '<script type="text/JavaScript"> change_title("ERROR"); </script>';
			echo '<b><i class="fas fa-exclamation-triangle"></i> ERROR</b></br></br>Unsupported File Type.</br></br>';
			echo '<a href="./../"><label for="return" class="custom-file-upload" style="color:whitesmoke; font-family:Copperplate">Back</label></a>';
			echo '</div>';
			exit();
		}
		}
		else {bad_req();exit();}
	}
	else {if(!isset($_GET['id'])) {http_response_code(400); bad_req();exit();}}
}
function bad_req()
{
	echo '<script type="text/JavaScript"> change_title("400 Bad Request"); </script>';
	echo '<b><i class="fas fa-exclamation-triangle"></i> ERROR</b></br></br>Invalid parameters.</br></br>';
	echo '<a href="./../"><label for="return" class="custom-file-upload" style="color:whitesmoke; font-family:Copperplate">Back</label></a>';
	echo '</div>';
}
if(isset($_GET['id']))
{
	$id=$_GET['id'];
	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql = 'SELECT result_a, input_confidence_a, fingerprinted_confidence_a, result_b, input_confidence_b, fingerprinted_confidence_b, result_c, input_confidence_c, fingerprinted_confidence_c FROM query WHERE id =' .$id;
	$result = $conn->query($sql);
	$row = mysqli_fetch_assoc($result);
	if($row){
        echo '<script type="text/JavaScript"> change_title("' . $row["result_a"] . ' | ' . $row["result_b"]. ' | ' . $row["result_c"] . ' | Results From BBoyZone Music Recognition"); </script>';
		echo '<i class="fas fa-info-circle"></i> Possible Solution :</br></br>';
		echo '<button class="funcbtn" onclick="copyToClipboard()"><u>Copy Link</u></button>        ';
		echo '<button class="funcbtn" onclick="share()"><u>Share Results</u></button></br></br>';
    	echo '<b>' . $row["result_a"]. '</b></br></br>';
		echo 'Input Confidence:' . $row["input_confidence_a"]. '</br>';
		echo 'Fingerprinted Confidence:'. $row["fingerprinted_confidence_a"]. '</br></br>';
		echo 'Search on:</br>';
		echo '<a href="https://www.youtube.com/results?search_query=', urlencode($row["result_a"]), '"  target="_blank"><label for="youtube" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">YouTube</label></a>';
		echo '<a href="https://www.google.com/search?q=',urlencode($row["result_a"]),'"  target="_blank"><label for="google" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">Google</label></a>';
		echo '<a href="https://soundcloud.com/search?q=',urlencode($row["result_a"]),'"  target="_blank"><label for="sc" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">Soundcloud</label></a>';
		echo '<a href="https://bandcamp.com/search?q=',urlencode($row["result_a"]),'"  target="_blank"><label for="bc" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">BandCamp</label></a>';
		echo '</br></br></br>';
		
		echo '<b>' . $row["result_b"]. '</b></br></br>';
		echo 'Input Confidence:' . $row["input_confidence_b"]. '</br>';
		echo 'Fingerprinted Confidence:'. $row["fingerprinted_confidence_b"]. '</br></br>';
		echo 'Search on:</br>';
		echo '<a href="https://www.youtube.com/results?search_query=', urlencode($row["result_b"]), '"  target="_blank"><label for="youtube" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">YouTube</label></a>';
		echo '<a href="https://www.google.com/search?q=',urlencode($row["result_b"]),'"  target="_blank"><label for="google" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">Google</label></a>';
		echo '<a href="https://soundcloud.com/search?q=',urlencode($row["result_b"]),'"  target="_blank"><label for="sc" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">Soundcloud</label></a>';
		echo '<a href="https://bandcamp.com/search?q=',urlencode($row["result_b"]),'"  target="_blank"><label for="bc" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">BandCamp</label></a>';
		echo '</br></br></br>';
		
		echo '<b>' . $row["result_c"]. '</b></br></br>';
		echo 'Input Confidence:' . $row["input_confidence_c"]. '</br>';
		echo 'Fingerprinted Confidence:'. $row["fingerprinted_confidence_c"]. '</br></br>';
		echo 'Search on:</br>';
		echo '<a href="https://www.youtube.com/results?search_query=', urlencode($row["result_c"]), '"  target="_clank"><label for="youtube" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">YouTube</label></a>';
		echo '<a href="https://www.google.com/search?q=',urlencode($row["result_c"]),'"  target="_clank"><label for="google" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">Google</label></a>';
		echo '<a href="https://soundcloud.com/search?q=',urlencode($row["result_c"]),'"  target="_clank"><label for="sc" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">Soundcloud</label></a>';
		echo '<a href="https://bandcamp.com/search?q=',urlencode($row["result_c"]),'"  target="_clank"><label for="bc" class="custom-file-upload" style="color:#C0C0C0; font-family:Copperplate">BandCamp</label></a>';
		echo '</br></br></br>';

		echo '<a href="https://www.bboyzone.org/details/', urlencode($_GET['id']), '"class="funcbtn">Details</a></br></br>';
	}
	else
	{
		http_response_code(404);
		echo '<script type="text/JavaScript"> change_title("Record Not Found"); </script>';
		echo '<b><i class="fas fa-exclamation-triangle"></i> ERROR</b></br></br>No result matching this record.</br></br>';
	}
}
else
{
	http_response_code(400);
	echo '<script type="text/JavaScript"> change_title("400 Bad Request"); </script>';
	echo '<b><i class="fas fa-exclamation-triangle"></i> ERROR</b></br></br>Result ID is not specified.</br></br>';
}
	echo '<a href="./../"><label for="return" class="custom-file-upload" style="color:whitesmoke; font-family:Copperplate">Back</label></a></div>';
	echo '</br></br></br>';
	echo '</div>';
?>
