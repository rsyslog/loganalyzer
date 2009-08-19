<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* Monilog Report is a basic report for EventLog and Syslog data
	*
	* \version 1.0.0 Init Version
	*																	*
	* All directives are explained within this file						*
	*
	* Copyright (C) 2008-2009 Adiscon GmbH.
	*
	* This file is part of phpLogCon.
	*
	* PhpLogCon is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* (at your option) any later version.
	*
	* PhpLogCon is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	* GNU General Public License for more details.
	*
	* You should have received a copy of the GNU General Public License
	* along with phpLogCon. If not, see <http://www.gnu.org/licenses/>.
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

// --- Required Includes!
require_once($gl_root_path . 'include/constants_errors.php');
// --- 

class Report_monilog extends Report {
	// Common Properties
	public $_reportVersion = 1;								// Internally Version of the ReportEngine
	public $_reportID = "report.eventlog.monilog.class";	// ID for the report, needs to be unique!
	public $_reportTitle = "EventLog Summary Report";		// Display name for the report
	public $_reportDescription = "This is a EventLog Summary Report based on Monilog"


/*	private $_currentOffset = -1;
	private $_currentStartPos = -1;
	private $_fp = null;
	private $_bEOS = false;

	const _BUFFER_length = 8192;
	private $_buffer = false;
	private $_buffer_length = 0;
	private $_p_buffer = -1;
*/

	// Constructor
	public function Report_monilog() {
//		$this->_logStreamConfigObj = $streamConfigObj;
		

		// Fill fields we need for this report
		$this->_arrProperties[] = SYSLOG_UID;
		$this->_arrProperties[] = SYSLOG_DATE;
		$this->_arrProperties[] = SYSLOG_HOST;
		$this->_arrProperties[] = SYSLOG_MESSAGETYPE;
		$this->_arrProperties[] = SYSLOG_FACILITY;
		$this->_arrProperties[] = SYSLOG_SEVERITY;
		$this->_arrProperties[] = SYSLOG_EVENT_ID;
		$this->_arrProperties[] = SYSLOG_EVENT_LOGTYPE;
		$this->_arrProperties[] = SYSLOG_EVENT_SOURCE;
		$this->_arrProperties[] = SYSLOG_EVENT_CATEGORY;
		$this->_arrProperties[] = SYSLOG_EVENT_USER;
		$this->_arrProperties[] = SYSLOG_MESSAGE;

	}

	/**
	* startDataProcessing, analysing data
	*
	* @param arrProperties array in: Properties wish list.
	* @return integer Error stat
	*/
	public function startDataProcessing()
	{
		// Verify Datasource first!
		if ( $this->verifyDataSource() == SUCCESS ) 
		{
			$res = $stream->Open( $this->_arrProperties, true );
			if ( $res == SUCCESS )
			{
				// report logic

			}

		}
		
		// Return success!
		return SUCCESS;
	}


	/**
	* verifyDataSource, verifies if data is accessable and 
	* contains what we need
	*
	* @param arrProperties array in: Properties wish list.
	* @return integer Error stat
	*/
	public function verifyDataSource()
	{
		if ( $this->_streamCfgObj == null ) 
		{
			// Obtain and get the Config Object
			$this->_streamCfgObj = $content['Sources'][$this->_mySourceID]['ObjRef'];
		}

		if ( $this->_streamObj == null ) 
		{
			// Create LogStream Object 
			$this->_streamObj = $this->_streamCfgObj ->LogStreamFactory($this->_streamCfgObj);
		}

		// Success!
		return SUCCESS;
	}
	


	/**
	* validateLicense, check license code
	*
	*/
	public function validateLicense()
	{
		// This is a free report!
		return SUCCESS;
	}


	// Private functions...
/*
	private function ResetBuffer() {
		$this->_bEOS = false;
		$this->_buffer = false;
		$this->_buffer_length = 0;
		$this->_p_buffer = -1;
	}
*/


}

?>