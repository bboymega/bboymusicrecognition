function generateString(length) {
	const characters ='abcdefghijklmnopqrstuvwxyz0123456789';
    let result = '';
    const charactersLength = characters.length;
    for ( let i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}
function load_complete()
	{
		document.getElementById('logob').style.visibility="visible";
		document.getElementById('load').style.visibility="hidden";
	}
function load_progress()
	{
		document.getElementById('logob').style.visibility="hidden";
		document.getElementById('load').style.visibility="visible";
	}
function ident_err()
{
	document.getElementById("main").innerHTML='<p style="color: whitesmoke; font-family:Copperplate; font-size: 135%; margin:-10px auto"><b><i class="fas fa-exclamation-triangle"></i> ERROR</b></br></br>Recognition failed. Please try again later.</br></br><a href="../../"><label for="return" class="custom-file-upload" style="color:whitesmoke; font-family:Copperplate">Back</label></a>';
}
function upload_err()
{
	document.getElementById("main").innerHTML='<p style="color: whitesmoke; font-family:Copperplate; font-size: 135%; margin:-10px auto"><b><i class="fas fa-exclamation-triangle"></i> ERROR</b></br></br>There is an error uploading the file. Please try again later.</br></br><a href="../../"><label for="return" class="custom-file-upload" style="color:whitesmoke; font-family:Copperplate">Back</label></a>';
}

function ident_load()
{
	document.title = "Recognizing...";
	document.getElementById("load").innerHTML='</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br><h1>Recognizing...</h1>';
	console.log('Recognizing...');
}
function upload_progress()
{
	document.title = "Uploading...";
	document.getElementById("load").innerHTML='</br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br></br><h1>Uploading...</h1>';
}

function change_title(title)
{
	document.title = title;
}
function empty_list()
{
	document.getElementById("main").innerHTML='<meta http-equiv="refresh" content="0; url=./error/emptyfilelist">'
}
function type_err()
{
	document.getElementById("main").innerHTML='<meta http-equiv="refresh" content="0; url=./error/filetypeerror">'
}
