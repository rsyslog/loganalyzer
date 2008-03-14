/* 
Helper Javascript Constants
*/
const DATEMODE_ALL = 1, DATEMODE_RANGE = 2, DATEMODE_LASTX = 3;
const DATE_LASTX_HOUR = 1, DATE_LASTX_12HOURS = 2, DATE_LASTX_24HOURS = 3, DATE_LASTX_7DAYS = 4,DATE_LASTX_31DAYS = 5;

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

/*
*	Helper function to show and hide areas of the filterview
*/
function toggleDatefiltervisibility(FormName)
{
	var myform = document.getElementById(FormName);
	if (myform.elements['filter_datemode'].value == DATEMODE_ALL)
	{
		hidevisibility('HiddenDateFromOptions');
		hidevisibility('HiddenDateLastXOptions');
	}
	else if (myform.elements['filter_datemode'].value == DATEMODE_RANGE)
	{
		togglevisibility('HiddenDateFromOptions');
		hidevisibility('HiddenDateLastXOptions');
	}
	else if (myform.elements['filter_datemode'].value == DATEMODE_LASTX)
	{
		togglevisibility('HiddenDateLastXOptions');
		hidevisibility('HiddenDateFromOptions');
	}

}

/*
*	Helper function to add a date filter into the search field
*/
function addDatefilterToSearch(DateName, SearchFormName)
{
	var myDateform = document.getElementById(DateName);
	var mySearchform = document.getElementById(SearchFormName);
	if (myDateform.elements['filter_datemode'].value == DATEMODE_RANGE)
	{
		mySearchform.elements['filter'].value += "date:from:"	+ myDateform.elements['filter_daterange_from_year'].value + "-" 
																+ myDateform.elements['filter_daterange_from_month'].value + "-"
																+ myDateform.elements['filter_daterange_from_day'].value + ":to:"
																+ myDateform.elements['filter_daterange_to_year'].value + "-" 
																+ myDateform.elements['filter_daterange_to_month'].value + "-"
																+ myDateform.elements['filter_daterange_to_day'].value + " ";
	}
	else if (myDateform.elements['filter_datemode'].value == DATEMODE_LASTX)
	{
		mySearchform.elements['filter'].value += "date:lastx:"	+ myDateform.elements['filter_daterange_last_x'].value + " ";
	}
}