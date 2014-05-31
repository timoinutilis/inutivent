/*
JS Functions
*/

function sendForm(form, onComplete, onError)
{
	sendFormData(form.method, form.action, new FormData(form), onComplete, onError);
}

function sendFormData(method, action, formData, onComplete, onError)
{
	var request = new XMLHttpRequest();
	request.onreadystatechange = function()
	{
		if (request.readyState == 4)
		{
			if (request.status == 200)
			{
				try
				{
					var data = JSON.parse(request.responseText);
					if (data.error != null)
					{
						onError(data.error);
					}
					else
					{
						onComplete(data);
					}
				}
				catch (error)
				{
					onError("Invalid response format");
				}
			}
			else
			{
				onError("Error " + request.status);
			}
			unblockScreen();
		}
	}
	request.open(method, action, true);
	request.send(formData);
	blockScreen();
}

function blockScreen()
{
	var blocker = document.createElement("div");
	blocker.id = "blocker"
	blocker.innerHTML = '<div style="position:fixed; top:50%; left:50%;"><img src="images/loading.gif" style="margin: -40px 0 0 -40px;"></div>';
	blocker.style.width = "100%";
	blocker.style.height = "100%";
	blocker.style.top = "0";
	blocker.style.left = "0";
	blocker.style.position = "fixed";
	blocker.style.backgroundColor = "rgba(255, 255, 255, 0.5)";

	document.body.appendChild(blocker);
  }

function unblockScreen()
{
	var blocker = document.getElementById("blocker");
	document.body.removeChild(blocker);
}

function onClickMail(event)
{
	var h = event.target.href;
	var l = String.fromCharCode(64);
	if (h.indexOf(l) == -1)
	{
		l += "inutilis";
		l += String.fromCharCode(46);
		l += "com";
		event.target.href = h + l;
	}
}
