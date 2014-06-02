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
	blocker.style.zIndex = "100";
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

function linkTextURLs(elements)
{
	for (var i = 0; i < elements.length; i++)
	{
		var element = elements[i];
		var originalText = element.innerHTML;
		var linkedText = autolink(originalText);
		if (linkedText != originalText)
		{
			element.innerHTML = linkedText;
		}
	}
}

function autolink(str, attributes)
{
	attributes = attributes || {};
	var attrs = "";
	for (name in attributes)
	{
		attrs += " "+ name +'="'+ attributes[name] +'"';
	}
	
	var reg = new RegExp("(\\s?)((http|https|ftp)://[^\\s<]+[^\\s<\.)])", "gim");
	str = str.toString().replace(reg, function(match, p1, p2, offset, string) {
		var safeLink = "link.php?url=" + encodeURIComponent(decodeHTML(p2));
		var content = p2;
		if (endsWith(p2, ".jpg") || endsWith(p2, ".jpeg") || endsWith(p2, ".png"))
		{
			content = '<img src="' + safeLink + '">';
		}
		return p1 + '<a href="' + safeLink + '"'+ attrs +' target="_blank">' + content + '</a>';
	});
	
	return str;
}

function decodeHTML(input)
{
  var e = document.createElement('div');
  e.innerHTML = input;
  return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}

function endsWith(str, suffix)
{
	return str.indexOf(suffix, str.length - suffix.length) !== -1;
}
