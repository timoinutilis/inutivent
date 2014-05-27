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
		}
	}
	request.open(method, action, true);
	request.send(formData);
}

function setFormsDisabled(disabled)
{
	var elements = document.getElementsByTagName("input");
	for (var i = 0; i < elements.length; i++)
	{
		elements[i].disabled = disabled;
	}
}