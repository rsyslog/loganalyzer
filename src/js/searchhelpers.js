/* 
Helper Javascript Constants
*/
const DATEMODE_ALL = 1, DATEMODE_RANGE = 2, DATEMODE_LASTX = 3;
const DATE_LASTX_HOUR = 1, DATE_LASTX_12HOURS = 2, DATE_LASTX_24HOURS = 3, DATE_LASTX_7DAYS = 4,DATE_LASTX_31DAYS = 5;

/*
Helper Javascript functions
*/

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

		toggleformelement('filter_daterange_from_year', false);
		toggleformelement('filter_daterange_from_month', false);
		toggleformelement('filter_daterange_from_day', false);
		toggleformelement('filter_daterange_to_year', false);
		toggleformelement('filter_daterange_to_month', false);
		toggleformelement('filter_daterange_to_day', false);
		toggleformelement('filter_daterange_from_hour', false);
		toggleformelement('filter_daterange_from_minute', false);
		toggleformelement('filter_daterange_from_second', false);
		toggleformelement('filter_daterange_to_hour', false);
		toggleformelement('filter_daterange_to_minute', false);
		toggleformelement('filter_daterange_to_second', false);

		toggleformelement('filter_daterange_last_x', false);
	}
	else if (myform.elements['filter_datemode'].value == DATEMODE_RANGE)
	{
		togglevisibility('HiddenDateFromOptions');
		hidevisibility('HiddenDateLastXOptions');

		toggleformelement('filter_daterange_from_year', true);
		toggleformelement('filter_daterange_from_month', true);
		toggleformelement('filter_daterange_from_day', true);
		toggleformelement('filter_daterange_to_year', true);
		toggleformelement('filter_daterange_to_month', true);
		toggleformelement('filter_daterange_to_day', true);
		toggleformelement('filter_daterange_from_hour', true);
		toggleformelement('filter_daterange_from_minute', true);
		toggleformelement('filter_daterange_from_second', true);
		toggleformelement('filter_daterange_to_hour', true);
		toggleformelement('filter_daterange_to_minute', true);
		toggleformelement('filter_daterange_to_second', true);

		toggleformelement('filter_daterange_last_x', false);
	}
	else if (myform.elements['filter_datemode'].value == DATEMODE_LASTX)
	{
		togglevisibility('HiddenDateLastXOptions');
		hidevisibility('HiddenDateFromOptions');

		toggleformelement('filter_daterange_from_year', false);
		toggleformelement('filter_daterange_from_month', false);
		toggleformelement('filter_daterange_from_day', false);
		toggleformelement('filter_daterange_to_year', false);
		toggleformelement('filter_daterange_to_month', false);
		toggleformelement('filter_daterange_to_day', false);
		toggleformelement('filter_daterange_from_hour', false);
		toggleformelement('filter_daterange_from_minute', false);
		toggleformelement('filter_daterange_from_second', false);
		toggleformelement('filter_daterange_to_hour', false);
		toggleformelement('filter_daterange_to_minute', false);
		toggleformelement('filter_daterange_to_second', false);

		toggleformelement('filter_daterange_last_x', true);
	}
}

/*
*	Helper function to add a date filter into the search field
*/
function CalculateSearchPreview(szSearchFormName, szPreviewArea)
{
	var mySearchform = document.getElementById(szSearchFormName);
	var myPreviewArea = document.getElementById(szPreviewArea);
	var szOutString = "", szTmpString = "", nCount = 0;
	if (mySearchform.elements['filter_datemode'].value == DATEMODE_RANGE)
	{
		szOutString += "datefrom:"	+ mySearchform.elements['filter_daterange_from_year'].value + "-" 
									+ mySearchform.elements['filter_daterange_from_month'].value + "-"
									+ mySearchform.elements['filter_daterange_from_day'].value + "T"
									+ mySearchform.elements['filter_daterange_from_hour'].value + ":"
									+ mySearchform.elements['filter_daterange_from_minute'].value + ":"
									+ mySearchform.elements['filter_daterange_from_second'].value + " ";
		szOutString += "dateto:"	+ mySearchform.elements['filter_daterange_to_year'].value + "-" 
									+ mySearchform.elements['filter_daterange_to_month'].value + "-"
									+ mySearchform.elements['filter_daterange_to_day'].value + "T"
									+ mySearchform.elements['filter_daterange_to_hour'].value + ":"
									+ mySearchform.elements['filter_daterange_to_minute'].value + ":"
									+ mySearchform.elements['filter_daterange_to_second'].value + " ";
	}
	else if (mySearchform.elements['filter_datemode'].value == DATEMODE_LASTX)
	{
		szOutString += "datelastx:" + mySearchform.elements['filter_daterange_last_x'].value + " ";
	}

	// --- Syslog Facility
	szTmpString = "";
	nCount = 0;
	for (var i = 0; i < mySearchform.elements['filter_facility[]'].length; i++)
	{
		if (mySearchform.elements['filter_facility[]'].options[i].selected == true)
		{
			if ( szTmpString.length > 0)
			{
				szTmpString += ",";
			}
			szTmpString += mySearchform.elements['filter_facility[]'].options[i].value;
			nCount++;
		}
	}
	if ( nCount < 18 )
	{	
		// Only if not all selected!
		szOutString += "facility:" + szTmpString + " ";
	}
	// --- 

	// --- Syslog Severity
	szTmpString = "";
	nCount = 0;
	for (var i = 0; i < mySearchform.elements['filter_severity[]'].length; i++)
	{
		if (mySearchform.elements['filter_severity[]'].options[i].selected == true)
		{
			if ( szTmpString.length > 0)
			{
				szTmpString += ",";
			}
			szTmpString += mySearchform.elements['filter_severity[]'].options[i].value;
			nCount++;
		}
	}
	if ( nCount < 8 )
	{	
		// Only if not all selected!
		szOutString += "severity:" + szTmpString + " ";
	}
	// --- 

	// --- SyslogTag
	if (mySearchform.elements['filter_syslogtag'].value.length > 0 )
	{
		szOutString += "syslogtag:" + mySearchform.elements['filter_syslogtag'].value + " ";
	}
	// --- 

	// --- Source
	if (mySearchform.elements['filter_source'].value.length > 0 )
	{
		szOutString += "source:" + mySearchform.elements['filter_source'].value + " ";
	}
	// --- 

	// --- Message | Just append as it is 
	szOutString += mySearchform.elements['filter_message'].value; 
	// --- 

	// Set preview area
	myPreviewArea.innerHTML = szOutString;
}