/* 
Helper Javascript Constants
*/

/*
Helper Javascript functions
*/

function CheckAlphaPNGImage(ImageName, ImageTrans)
{
	var agt=navigator.userAgent.toLowerCase();
	var is_ie = ((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1));

	if (is_ie)
		document.images[ImageName].src = ImageTrans;
}

function NewWindow(Location, WindowName,X_width,Y_height,Option) {
	var windowReference;
	var Addressbar = "location=NO";		//Default
	var OptAddressBar = "AddressBar";	//Default für Adressbar
	if (Option == OptAddressBar) {		//Falls AdressBar gewünscht wird
		Addressbar = "location=YES";
		}
	windowReference = window.open(Location,WindowName, 
	'toolbar=no,' + Addressbar + ',directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=' + X_width + 
	',height=' + Y_height);
	if (!windowReference.opener)
		windowReference.opener = self;

}

/*
*	Helper function to show and hide a div area
*/
function togglevisibility(ElementNameToggle, ElementNameButton)
{
	var toggle = document.getElementById(ElementNameToggle);

	// Button is optional
	if (ElementNameButton != null)
	{
		var button = document.getElementById(ElementNameButton);
	}
	else
		var button = null;

	if (toggle.style.visibility == "visible")
	{
		if (button != null)
		{
			button.className = "topmenu2 ExpansionPlus";
		}

		toggle.style.visibility = "hidden";
		toggle.style.display = "none";
	}
	else
	{
		if (button != null)
		{
			button.className = "topmenu2 ExpansionMinus";
		}

		toggle.style.visibility = "visible";
		toggle.style.display = "inline";
	}
}

/*
*	Helper function to hide a div area
*/
function showvisibility(ElementNameToggle, ElementNameButton)
{
	var toggle = document.getElementById(ElementNameToggle);

	// Button is optional
	if (ElementNameButton != null)
	{
		var button = document.getElementById(ElementNameButton);
	}
	else
		var button = null;

	if (button != null)
	{
		button.className = "topmenu2 ExpansionMinus";
	}

	toggle.style.visibility = "visible";
	toggle.style.display = "inline";
}

/*
*	Helper function to hide a div area
*/
function hidevisibility(ElementNameToggle, ElementNameButton)
{
	var toggle = document.getElementById(ElementNameToggle);

	// Button is optional
	if (ElementNameButton != null)
	{
		var button = document.getElementById(ElementNameButton);
	}
	else
		var button = null;

	if (button != null)
	{
		button.className = "topmenu2 ExpansionPlus";
	}

	toggle.style.visibility = "hidden";
	toggle.style.display = "none";
}

function ResetFormValues(formName)
{
	var myform = document.getElementById(formName);
	var i = 0;
	var iCount = myform.elements.length;

	// Loop through text fields
	for(i = 0; i < iCount; i++)
	{
		if (myform.elements[i].type == "text" )
		{
			// Reset textfield
			myform.elements[i].value = "";
		}
	}
}

function SubmitForm(formName)
{
	var myform = document.getElementById(formName);
	if (myform != null)
	{
		myform.submit();
	}
}

/*
*	Helper function to show and hide areas of the filterview
*/
function toggleFormareaVisibility(FormFieldName, FirstHiddenArea, SecondHiddenArea )
{
	var myfield = document.getElementById(FormFieldName);
	if (myfield.value == 1)
	{
		togglevisibility(FirstHiddenArea);
		hidevisibility(SecondHiddenArea);
	}
	else if (myfield.value == 2)
	{
		hidevisibility(FirstHiddenArea);
		togglevisibility(SecondHiddenArea);
	}
}

// helper array to keep track of the timeouts!
var runningTimeouts = new Array();
var defaultMenuTimeout = 3000;
/*
* Toggle display type from NONE to BLOCK
*/ 
function ToggleDisplayTypeById(ObjID)
{
	var obj = document.getElementById(ObjID);
	if (obj != null)
	{
		if (obj.style.display == '' || obj.style.display == 'none')
		{
			obj.style.display='block';
			
			// Set Timeout to make sure the menu disappears
			ToggleDisplaySetTimeout(ObjID);
		}
		else
		{
			obj.style.display='none';
			
			// Abort Timeout if set!
			ToggleDisplayClearTimeout(ObjID);
		}
	}
}

function ToggleDisplaySetTimeout(ObjID)
{
	// Set Timeout 
	var szTimeOut = "ToggleDisplayOffTypeById('" + ObjID + "')";
	runningTimeouts[ObjID] = window.setTimeout(szTimeOut, defaultMenuTimeout);
}

function ToggleDisplayClearTimeout(ObjID)
{
	// Abort Timeout if set!
	if ( runningTimeouts[ObjID] != null )
	{
		window.clearTimeout(runningTimeouts[ObjID]);
	}
}

function ToggleDisplayEnhanceTimeOut(ObjID)
{
	// First clear timeout
	ToggleDisplayClearTimeout(ObjID);

	// Set new  timeout
	ToggleDisplaySetTimeout(ObjID);
}

/*
* Make Style sheet display OFF in any case
*/ 
function ToggleDisplayOffTypeById(ObjID)
{
	var obj = document.getElementById(ObjID);
	if (obj != null)
	{
		obj.style.display='none';
	}
}

/*
* Debug Helper function to read possible properties of an object 
*/ 
function DebugShowElementsById(ObjName)
{
	var obj = document.getElementById(ObjName);
	for (var key in obj) {
		document.write(obj[key]);
	}
}

/* 
*	Detail popup handling functions
*/
var myPopupHovering = false;
function HoveringPopup(event, parentObj)
{
	// This will allow the detail window to be relocated
	myPopupHovering = true;
}

function FinishHoveringPopup(event, parentObj)
{
	// This will avoid moving the detail window when it is open
	myPopupHovering = false;
}

function initPopupWindow(parentObj)
{
	// Change CSS Class
	parentObj.className='syslogdetails_popup';
}

function FinishPopupWindow(parentObj)
{
	// Change CSS Class
	parentObj.className='syslogdetails';
}

function disableEventPropagation(myEvent)
{
	/* This workaround is specially for our beloved Internet Explorer */
	if ( window.event)
	{
		window.event.cancelBubble = true; 
	}
}

function movePopupWindow(myEvent, ObjName, PopupContentWidth, parentObj)
{
	var obj = document.getElementById(ObjName);
	var middle = PopupContentWidth / 2;
//	alert ( parentObj.className ) ;
	if (myPopupHovering == false)
	{
		obj.style.left = (myEvent.clientX - middle) + 'px';
	}
}

function GoToPopupTarget(myTarget, parentObj)
{
	if (!myPopupHovering)
	{
		// Change document location
		document.location=myTarget;
	}
	else /* Close Popup */
	{
		FinishPopupWindow(parentObj);
	}
}
