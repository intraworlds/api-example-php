
/*
* Function for getting input from the form, sending request to form_handle.php,
* setting loading circle and setting design of response area
*/
function getResponse() {


	var urlV = document.getElementById('url').value;
	var usernameV = document.getElementById('username').value;
	var passwordV = document.getElementById('password').value;
	var methodV = document.getElementById('method').value;
	var payloadV = document.getElementById('payload').value;
	var responseArea = document.getElementById('response_area');

	textAreaAdjust(responseArea);


	responseArea.style.display = "none";

	document.getElementById('loader').innerHTML = '<img src="public/loading_circle.gif" id="loading_circle"/>';

	var params = { 
		url: urlV, 
		username : usernameV,
		password : passwordV,
		method : methodV,
		payload : payloadV
	};


	$.post("ajax.php", params, function(response, status){
		responseArea.style.display = "inline-block";
		responseArea.innerHTML = "";


		var obj_response = jQuery.parseJSON(response);
		var response_time = obj_response['time'];
		var code = obj_response['response']['response_code'];
		response_without_time = JSON.stringify(obj_response['response']);


		if(code == 200){
			$("#response_area").css("background-color", "#ccff99");
		}else{
			$("#response_area").css("background-color", "#ffb3b3");

		}
		$('#response_area').jsonView(response);
		responseArea.prepend("Response took " + response_time + " seconds.\n");
		textAreaAdjust(responseArea);
		document.getElementById('loader').innerHTML = '';

	});
}
/*
* Ensures that there is exactly one "https://" in url input.
*/
function handleHttpsInUrl(url){
	var url = $("#url").val();
	
	while(url.match("^https://")){
		url = url.replace('https://','');
	}
	$("#url").val("https://" + url);

}
/*
* Adjust response area based on its volume of text.
*/
function textAreaAdjust(o) {
	o.style.height = "1px";
	o.style.height = (o.scrollHeight)+"px";
}
/*
* Checks if string is a valid JSON.
* Returns true if string is in valid JSON format.
* Returns false if string is not in valid JSON format.
*/
function IsJsonString(str) {
	if(str==""){
		return true;
	}
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
}
/*
* Function for disabling/enabling payload based on what method is user chooses.
*/
function disablePayload(){
	var method = document.getElementById('method');
	var payload = document.getElementById('payload');
	if (method.selectedIndex == 1 || method.selectedIndex == 0) {
		payload.value = null;
		payload.disabled = true;
	}else{
		payload.disabled = false;

	}
}
/*
* Function that checks if all required input is provided.
* If all required input is provided, the request button is enabled.
* If not all required input is provided, the request button is disabled.
*/
function buttonAbility(){
	disablePayload();
	var url = document.getElementById('url').value;
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;
	var method = document.getElementById('method');
	var button = document.getElementById('button');
	var payload = document.getElementById('payload').value;


	if (url && username && password && method.selectedIndex != 0 && IsJsonString(payload)) {
		button.disabled = false;
	}else{
		button.disabled = true;
	}

}

