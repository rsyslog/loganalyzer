<?php
/*
	*********************************************************************
	* LogAnalyzer - http://loganalyzer.adiscon.com
	* -----------------------------------------------------------------
	* StreamConfig has the capability to create a specific LogStream
	* object depending on a configured LogStream*Config object.
	*
	* All directives are explained within this file
	*
	* Copyright (C) 2008-2010 Adiscon GmbH.
	*
	* This file is part of LogAnalyzer.
	*
	* LogAnalyzer is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	*
	* LogAnalyzer is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with LogAnalyzer. If not, see <http://www.gnu.org/licenses/>.
	*
	* A copy of the GPL can be found in the file "COPYING" in this
	* distribution.
	*********************************************************************
*/

// --- Avoid directly accessing this file! 
if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
	exit;
}
// --- 

abstract class LogStreamConfig {
	// Public needed properties
	public $_pageCount = 50;	// Paging Count number!

	// protected properties
	protected $_logStreamConfigObj = null;
	protected $_logStreamId = -1;
	protected $_logStreamName = '';
	protected $_defaultFacility = '';
	protected $_defaultSeverity = '';
	
	// helpers properties for message parser list!
	protected $_msgParserList = null;		// Contains a string list of configure msg parsers
	protected $_msgParserObjList = null;	// Contains an object reference list to the msg parsers
	protected $_MsgNormalize = 0;			// If set to one, the msg will be reconstructed if successfully parsed before
	public $_defaultfilter = "";			// Default filter for this source, will be added to all further filters. 
	public $_SkipUnparseable = 0;			// If set to one, all unparseable message will be ignored! This of course only applies if a msg parser is used

	// Constructor prototype 
	public abstract function LogStreamFactory($o);
	
	/*
	* Initialize Msg Parsers!
	*/
	public function InitMsgParsers()
	{
		// Init parsers if available and not initialized already!
		if ( $this->_msgParserList != null && $this->_msgParserObjList == null ) 
		{
			// Loop through parsers
			foreach( $this->_msgParserList as $szParser )
			{
				// Set Classname
				$szClassName = "MsgParser_" . $szParser;

				// Create OBjectRef!
				$NewParser = new $szClassName();					// Create new instance
				$NewParser->_MsgNormalize = $this->_MsgNormalize;	// Copy property!
				$this->_msgParserObjList[] = $NewParser;			// Append NewParser to Parser array
			}
		}
	}

	/*
	* Helper function to init Parserlist
	*/
	public function SetSkipUnparseable( $nNewVal )
	{
		if ( $nNewVal == 0 ) 
			$this->_SkipUnparseable = 0;
		else
			$this->_SkipUnparseable = 1;
	}

	/*
	* Helper function to init Parserlist
	*/
	public function SetMsgNormalize( $nNewVal )
	{
		if ( $nNewVal == 0 ) 
			$this->_MsgNormalize = 0;
		else
			$this->_MsgNormalize = 1;
	}

	/*
	* Helper function to set defautl filters 
	*/
	public function SetDefaultfilter( $szNewVal )
	{
		$this->_defaultfilter = $szNewVal;
	}

	/*
	* Helper function to init Parserlist
	*/
	public function SetMsgParserList( $szParsers )
	{
		global $gl_root_path;

		// Check if we have at least something to check 
		if ( $szParsers == null || strlen($szParsers) <= 0 )
			return;

		// Set list of Parsers!
		if ( strpos($szParsers, ",") ) 
			$aParsers = explode( ",", $szParsers );
		else
			$aParsers[0] = $szParsers;

		// Loop through parsers
		foreach( $aParsers as $szParser )
		{
			// Remove whitespaces
			$szParser = trim($szParser);

			// Check if parser file include exists
			$szIncludeFile = $gl_root_path . 'classes/msgparsers/msgparser.' . $szParser . '.class.php';
			if ( file_exists($szIncludeFile) )
			{
				// Try to include
				if ( @include_once($szIncludeFile) )
					$this->_msgParserList[] = $szParser;
				else
					OutputDebugMessage("Error, MsgParser '" . $szParser . "' could not be included. ", DEBUG_ERROR);

			}
		}

//		print_r ( $this->_msgParserList );
	}

	public function ProcessMsgParsers($szMsg, &$arrArguments)
	{
		// Abort msgparsers if we have less then 5 seconds of processing time!
		global $content, $gl_starttime;
		$scriptruntime = intval(microtime_float() - $gl_starttime);
		if ( $scriptruntime > ($content['MaxExecutionTime']-5) )
			return ERROR_MSG_SCANABORTED;

		// Process if set!
		if ( $this->_msgParserObjList != null )
		{
			foreach( $this->_msgParserObjList as $myMsgParser )
			{
				// Perform Parsing, and return if was successfull or the message needs to be skipped!
				// Otherwise the next Parser will be called. 
				$ret = $myMsgParser->ParseMsg($szMsg, $arrArguments);
				if ( $ret == SUCCESS || $ret == ERROR_MSG_SKIPMESSAGE )
					return $ret;
					
				// Extra check, if user wants to, we SKIP the message!
				if ( $this->_SkipUnparseable == 1 && $ret == ERROR_MSG_NOMATCH )
					return ERROR_MSG_SKIPMESSAGE;
			}
		}

		// reached this means all work is done!
		return SUCCESS;
	}

}
?>