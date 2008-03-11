<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* LogStream provides access to the log data. Be sure to always		*
	* use LogStream if you want to access a text file or database.		*
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

abstract class LogStream {
	protected $_readDirection = EnumReadDirection::Forward;
	protected $_filter = null;
	protected $_current_uId = -1;
	protected $_logStreamConfigObj = null;
	protected $_arrProperties = null;

	/**
	* Open the stream for read access.
	*
	* @param arrProperties string in: properties of interest. There can be no guarantee the logstream can actually deliver them.
	* @return integer Error stat
	*/
	public abstract function Open($arrProperties);

	/**
	* Close the current stream.
	*
	* @return integer Error stat
	*/
	public abstract function Close();

	/**
	* Read the next data from the current stream. If it reads
	* forwards or backwards depends on the current read direction.
	*
	* Example for reading forward:
	* Is the current uID == 4, readDirection set to forwards
	* ReadNext will provide uID 5 or EOS if no more data exist.
	*
	* Exampe for reading backward:
	* Is the current uID == 4, readDirection set to backwards
	* ReadNext will provide uID 3.
	*
	* Hint: If the current stream becomes unavailable an error
	* stated is retuned. A typical case is if a log rotation
	* changed the original data source.
	*
	* @param uID integer out: unique id of the data row 
	* @param logLine string out: data row
	* @return integer Error state
	*/
	public abstract function ReadNext(&$uID, &$logLine);

	/**
	* Read the data from a specific uID.
	* 
	* @param uID integer in: unique id of the data row 
	* @param logLine string out: data row
	* @return integer Error state
	* @see ReadNext()
	*/
	public abstract function Read($uID, &$logLine);

	/**
	* Set the filter for the current stream.
	* 
	* @param filter object in: filter object
	* @return integer Error state
	*/
	public abstract function SetFilter($filter);

	/**
	* Set the direction the stream should read data.
	*
	* @param enumReadDirectionfilter EnumReadDirection in: The new direction.
	* @return integer Error state
	*/
	public abstract function SetReadDirection($enumReadDirection);
}

?>