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