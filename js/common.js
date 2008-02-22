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


function togglevisibility(ElementName)
{
	var toggle = document. getElementById(ElementName);
	if (toggle.style.visibility == "visible")
	{
		toggle.style.visibility = "hidden";
		toggle.style.display = "none";
	}
	else
	{
		toggle.style.visibility = "visible";
		toggle.style.display = "inline";
	}
}
