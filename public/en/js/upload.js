function upload(blob){
	  load_progress();
	  upload_progress();
      var xhr=new XMLHttpRequest();
	xhr.upload.onload = function() {
		ident_load();
  		console.log('Upload completed successfully.');
	};
      xhr.onload=function(e) {
         if(this.readyState === 4) {
			  document.querySelector('html').innerHTML = e.target.responseText;
          }
      };
      var fd=new FormData();
	  var filename = generateString(15);
      fd.append("audio_data",blob, filename);
		xhr.open("POST","index.php",true);
      xhr.send(fd);
		console.log("File is being uploaded...");
	return 0;
}

function upload_file()
{
	load_progress();
	upload_progress();
	const vidregex="^video\/.*";
	const audregex="^audio\/.*";
	var filename= document.getElementById("file-chosen").innerHTML;
	var binary= document.getElementById("upload").files;
	console.log(binary);
	if(binary.length == 0)
		{
			load_complete();
			empty_list();
			return -1;
		}
	if(new RegExp(vidregex).test(binary[0].type) || new RegExp(audregex).test(binary[0].type))
	{
	var xhr=new XMLHttpRequest();
	xhr.upload.onload = function() {
		ident_load();
  		console.log('Upload completed successfully.');
	};
    xhr.onload=function(e) {
         if(this.readyState === 4) {
			 document.querySelector('html').innerHTML = e.target.responseText;
          }
    };
	var fd=new FormData();
	fd.append("uploaded_file",binary[0],filename);
	xhr.open("POST","index.php",true);
	xhr.send(fd);
	console.log("File is being uploaded...");
	return 0;
	}
	else
		{
			load_complete();
			type_err();
			console.log("ERR: Unsupported file type");
			return -1;
		}
}