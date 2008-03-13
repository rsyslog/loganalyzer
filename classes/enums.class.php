<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Since php does not support enums we emulate it					*
	* using a abstract class.											*
	*																	*
	* All directives are explained within this file						*
	*********************************************************************
*/

// --- Avoid directly accessing this file! 
if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
	exit;
}
// --- 

/**
* ENUM of available ReadDirection.
*/
abstract class EnumReadDirection {
	const Forward = 1;
	const Backward = 2;
}

/**
*	Available modes of seek
*/
abstract class EnumSeek {
	const BOS = 1; // seek from begin stream 
	const EOS = 2; // seek from end of stream 
	const UID = 3; // seek from position uid (which MUST be a *valid* uid!)
}
?>