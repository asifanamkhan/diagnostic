function handleFiles(image) {
	
	if (document.getElementById("showImage")) {
		document.getElementById("showImage").style.display = 'none';
	}
	
	document.getElementById("uploadImage").style.display = 'block';
	var file = image[0];
	if (file.type.startsWith('image/')) { 
		var reader = new FileReader();
		reader.onload = function(event) {
			$('#upload')
			.attr('src', event.target.result)
		};
		reader.readAsDataURL(file);
	}
}