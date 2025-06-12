<link rel="stylesheet" href="./style.css">

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.98">
	<meta name="theme-color" content="#190f13" />
	<meta name="keywords" content="bboy, bboymusic, bboy music, breakdance music, find bboy music, recognize bboy music, hiphop, hiphopmusic, breakbeat, find breakbeat, recognize breakbeat, find hiphop music, recognize hiphop music, find breakdance music, recoginze breakdance music, music finder, music recognition, find music, find songs, bboyzone">
	<link rel="icon" href="favicon.png" sizes="16x16 32x32" type="image/png">
<title>BBoy Music Recognition - Identify Songs Online - Find Bboy Music - BBoyZone - BBoyZone.ORG</title>
</head>
<body>
<div class="loading" id="load" style="color:whitesmoke; font-size:100%; visibility: hidden" ></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br><h1>Uploading...</h1></div>
<div class="box" id="main">
	<h1><p style="color: whitesmoke; font-family:graf; font-size: 135%; margin:-10px auto">BBoyZone Music Recognition</p></h1>
	<img src="logo.png" alt="logo" width="150" height=auto id="logob">
	</br></br></br>
	<p style="color: whitesmoke; font-family:Copperplate; font-size: 135%; margin:-10px auto">Record an audio...</p>
	</br>
	<button class="recordbtn btn waves-light js-start" style="font-family:Copperplate; font-size:130%" id="startrec" ><i class="fas fa-microphone"></i> Record</button>

    <div class="stopbtn waves-light js-stop" style="font-family:Copperplate; color: white;" onclick="load_progress()" id="stoprec"></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br><h1>Stop & Identify</h1></div>
	</br></br></br></br>

		<p style="color: whitesmoke; font-family:Copperplate; font-size: 135%; margin:-10px auto">Or Upload a file...</p>
		</br>
    	<label for="upload" class="custom-file-upload" style="color:whitesmoke; font-family:Copperplate">Select <i class="fas fa-file-audio"></i> <i class="fas fa-file-video"></i> ...</label>
		<span id="file-chosen" style="color:whitesmoke; font-family:Copperplate; font-size: 80%">No file chosen</span>
    	<input type="file" name="uploaded_file" id="upload"  accept="audio/*, video/*"/></input></br></br></br>
		<button class="custom-file-upload" style="color:whitesmoke; font-family:Copperplate" onclick="upload_file()"><i class="fas fa-record-vinyl"></i> Identify</br>Max File Size: 100MB</button>
		</br></br></br>
		<p style="color:#FFFFFF; font-family:Copperplate"><a href="./guide" style="color:#FFFFFF; font-family:Copperplate">The Comprehensive ‘How to B-Boy’ Guide</a></br></br>
		<p style="color:#FFFFFF; font-family:Copperplate">It might take up to 90 seconds to match the track.</br>
</div>
</body>
</html>

<script src="./js/fun.js"></script>
<script src="./js/upload.js"></script>
<script src="./js/recorder.js"></script>
<script src="./js/MediaStreamRecorder.js"> </script>
<script src="./js/jquery.min.js"></script>
<script src="./js/materialize.min.js"></script>
<link rel="stylesheet" href="./fontawesome/css/all.css">


<?PHP
echo "</br></br></br>";
$err_notified=false;
function identify($ff)
{
		passthru('ffmpeg -i ./tmp/"' . $ff . '" -filter:a loudnorm -ar 44100 -ss 0 -t 6 ./tmp/"' . $ff . '".wav');
        $output = passthru('python3 ../dejavu/dejavu.py -c ../config/music_db_config.json -r file ./tmp/"' . $ff . '".wav',$return_val);
		exec('rm -f ./tmp/"' . $ff . '"');
		exec('rm -f ./tmp/"' . $ff . '".wav');
	    if($return_val != 0)
		{
			echo '<script type="text/JavaScript"> ident_err(); </script>';
		}
		else
		{
			echo $output;
		}
}

if(!empty($_FILES['audio_data']))
{
	$path = "./tmp/";
	//this will print out the received name, temp name, type, size, etc.
	$input = $_FILES['audio_data']['tmp_name']; //get the temporary name that PHP gave to the uploaded file
	$output = $_FILES['audio_data']['name'].".wav"; //letting the client control the filename is a rather bad idea
	//move the file from temp name to local folder using $output name
	$path = $path . basename($output);
	move_uploaded_file($input, $path);
	$ff=$_FILES['audio_data']['name'].".wav";
	identify($ff);
}
else
if(!empty($_FILES['uploaded_file']))
	{
		if((preg_match('/video\/*/',$_FILES['uploaded_file']['type'])) or (preg_match('/audio\/*/',$_FILES['uploaded_file']['type'])))
		{
			$path = "./tmp/";
    		$path = $path . basename($_FILES['uploaded_file']['name']);
    		if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path))
			{
        		$ff=$_FILES['uploaded_file']['name'];
				identify($ff);
			}
			else
			{
				echo '<script type="text/JavaScript"> load_complete(); </script>';
				echo '<script type="text/JavaScript"> upload_err(); </script>';
			}
    	}
		else
		{
			echo '<meta http-equiv="refresh" content="0; url=./error/filetypeerror">';
    	}
	echo '<script type="text/JavaScript"> load_complete(); </script>';
	}
?>



<script type="text/JavaScript">
const actualBtn = document.getElementById('upload');
const fileChosen = document.getElementById('file-chosen');
actualBtn.addEventListener('change', function(){
  fileChosen.textContent = this.files[0].name
})

</script>

<script class="containerScript">
		let recorder;
		let context;
		var constraints = {
  		audio: {
    		autoGainControl: false,
    		//channelCount: 2,
    		echoCancellation: false,
    		latency: 0,
    		noiseSuppression: false,
    		volume: 1.0
  		},
		video: false
		}

		let audio = document.querySelector('audio');
		let startBtn = document.querySelector('.js-start');
		let stopBtn = document.querySelector('.js-stop');
		let codeBtn = document.querySelector('.js-code');
		let pre = document.querySelector('pre');

		window.URL = window.URL || window.webkitURL;
		/**
		 * Detecte the correct AudioContext for the browser
		 * */
		window.AudioContext = window.AudioContext || window.webkitAudioContext;
		navigator.getUserMedia  = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;

		let onFail = function(e) {
			alert('Error '+e);
			console.log('Rejected!', e);
		};

		let onSuccess = function(s) {
			document.title = "Recording...";
			console.log('Recording...');
			let tracks = s.getTracks();
			startBtn.setAttribute('disabled', true);
			//startBtn.style.display= "none";
			document.getElementById('stoprec').style.visibility="visible";
			//stopBtn.setAttribute('visibility', 'visible');
			//stopBtn.removeAttribute('disabled');
			stopBtn.style.display= "";
			context = new AudioContext();
			let mediaStreamSource = context.createMediaStreamSource(s);
			recorder = new Recorder(mediaStreamSource);
			recorder.record();

			stopBtn.addEventListener('click', ()=>{
				console.log('Stop Recording...');
				document.getElementById('stoprec').style.visibility="hidden";
				//stopBtn.setAttribute('visibility', 'hidden');
				//stopBtn.style.display= "none";
				startBtn.removeAttribute('disabled');
				//startBtn.style.display= "";
				recorder.stop();
				tracks.forEach(track => track.stop());

				recorder.exportWAV(function(blob) {
				url = window.URL.createObjectURL(blob);
				console.log(url);

			//	var hf = document.createElement('a');
			//	hf.href = url;
			//	hf.download = new Date().toISOString() + '.wav';
			//	hf.innerHTML = hf.download;
				console.log("link created");
					upload(blob);
				});
			});
		}

		startBtn.addEventListener('click', ()=>{
			if (navigator.getUserMedia) {
				/**
				 * ask permission of the user for use microphone or camera
				 */
				navigator.getUserMedia(constraints, onSuccess, onFail);
			} else {
				console.warn('navigator.getUserMedia not present');
			}
		});

	//	codeBtn.addEventListener('click', () => {
	//		pre.classList.toggle('hide');
	//		pre.innerHTML = document.querySelector('.containerScript').innerHTML;
	//	});
	</script>
