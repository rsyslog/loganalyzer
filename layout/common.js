function NewWindow(WindowName,X_width,Y_height,Option)
{
	var Addressbar = "location=NO";	//Default
	var OptAddressBar = "AddressBar"; //Default adress bar
	if (Option == OptAddressBar)
	{		
		Addressbar = "location=YES";
	}
	window.open('',WindowName, 
	'toolbar=no,' + Addressbar + ',directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=' + X_width + 
	',height=' + Y_height);
}

function GotoSite()
{
	var szUrl = "";
	szUrl = document.BookmarkConfiguration.favorites.options.selectedIndex;
	window.open(szUrl,"","");
}