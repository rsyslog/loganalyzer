<?php

function WriteHead($title)
{

?>

<!-- Head creation -->
<html>
	<head>
		<title><?php echo $title; ?></title>
		<META NAME="robots" CONTENT="INDEX,FOLLOW">
		<META http-equiv="pragma" CONTENT="no-cache">
		<link rel="stylesheet" href="../layout/phplogcon.css" type="text/css">
	</head>
	<body>
		<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0" CLASS="EventTable">
			<tr>
				<td width="220" align="left"><img src="../images/phplogcon.gif" border="0"></td>
				<td align="left">
					<h1>phpLogCon monitoring</h1>
				</td>
			</tr>
		</table>
		<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0" CLASS="EventTable">
			<tr>
				<td align="center"><font size="3"><b>.: phpLogCon Installation :.</b></font></td>
			</tr>
		</table>
		<br><br>

<?php

}

function WriteFoot()
{

?>
	<!-- Footer creation -->
		<center>
		<table>
			<tr>
				<td>
					<br /><br />
					<small><i><a href="http://www.phplogcon.org/" target="phplogcon">phpLogCon</a>,
					Copyright &copy; 2003 - 2005 <a href="http://www.adiscon.com" target="Adiscon">Adiscon GmbH</a>. Part of the <a href="http://www.monitorware.com/">MonitorWare line of Products</a>.</i></small>
				</td>
			</tr>
		</table>
		</center>
	</body>
</html>

<?php

}

function GetSQLQueries($strFilePath)
{
	// Get SQL queries from file
	$arQueryFile = file("../scripts/" . $strFilePath);

	for($i = 0, $j = 0; $i < count($arQueryFile); $i++)
	{
		if( !strstr($arQueryFile[$i], "#") && $arQueryFile != "")
		{
			if( isset($arQueries[$j]) )
				$arQueries[$j] .= $arQueryFile[$i];
			else
				$arQueries[$j] = $arQueryFile[$i];
			if( strstr($arQueryFile[$i], ";") )
				$j++;
		}
	}

	return $arQueries;
}

function GetUTCtime($iTime)
{ 
	if ( $iTime == 0 ) $iTime = time();
		$ar = localtime ( $iTime );

	$ar[5] += 1900; $ar[4]++;
	$iTztime = gmmktime ( $ar[2], $ar[1], $ar[0],
	$ar[4], $ar[3], $ar[5], $ar[8] );
	return ( $iTime - ($iTztime - $iTime) );
}
