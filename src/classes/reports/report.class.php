<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* The Report Class is the base class for all reports				*
	*																	*
	* All directives are explained within this file						*
	*
	* Copyright (C) 2008 Adiscon GmbH.
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

// --- Basic Includes
require_once($gl_root_path . 'classes/enums.class.php');
require_once($gl_root_path . 'include/constants_errors.php');
require_once($gl_root_path . 'include/constants_logstream.php');
// --- 


abstract class Report {
	// Common Properties
	public $_reportVersion = 1;						// Internally Version of the ReportEngine
	public $_reportID = "report.syslog.base.class";	// ID for the report, needs to be unique!
	public $_reportTitle = "Base Report Class";		// Display name for the report
	public $_reportDescription = "This is the base class for all reports";
	public $_reportHelpArticle = "http://";

	// Configuration Properties
	protected $_filterString = "";
	protected $_advancedOptionsXml = ""; 
	protected $_outputType = REPORT_OUTPUT_HTML;	// Default HTML Output
	protected $_mySourceID = ""; 
	protected $_arrProperties = null;				// List of properties we need for the main logstream query!

	// License properties
	protected $_licenseName = "";
	protected $_licenseKey = "";

	// Helper Objects 
	protected $_reportcontent = null; 
	protected $_baseFileName = "";
	public $_streamCfgObj = null; 
	public $_streamObj = null; 

	// Begin Abstract Function definitions!


	/**
	* This function process the data
	* Will return -1 on failure!
	*/
	public abstract function startDataProcessing();


	/**
	* This function checks if the license is valid 
	* Will return -1 on failure!
	*/
	public abstract function validateLicense();


	/**
	* This functions check if the data source is valid 
	* Will return -1 on failure!
	*/
	public abstract function verifyDataSource();

	/**
	*	Helper function using the template parser to create the report
	*/
	public function CreateReportFromData()
	{
		// Create new template parser
		$page = new Template();
		$page -> set_path ( $gl_root_path . "classes/reports/" );

		// Run Parser
		$page -> parser($this->_reportcontent, $this->_baseFileName);
		
		// Return result!
		return $page -> result(); 
	}

	/*
	* Helper function to set the OutputType 
	*/
	public function SetOutputType($newOutputType)
	{
		// Set new Outputtype
		$this->_outputType = $newOutputType; 

		// Set Filebasename
		$this->_baseFileName = $this->_reportID . ".template." . $this->_outputType;
	}

	/*
	* Helper function to set the FilterString 
	*/
	public function SetFilterString($newFilterString)
	{
		// Set new Outputtype
		$this->_filterString = $newFilterString; 
	}

	/*
	* Helper function to set the FilterString 
	*/
	public function SetAdvancedOptions($newAdvancedOptions)
	{
		// Set new Outputtype
		$this->_advancedOptionsXml = $newAdvancedOptions; 
	}

	/*
	* Helper function to set the FilterString 
	*/
	public function SetSourceID($newSourceID)
	{
		global $content; 
		
		// check if valid!
		if ( isset($content['Sources'][$newSourceID]) ) 
			$this->_mySourceID = $newSourceID; 
		else
		{
			OutputDebugMessage("SetSourceID failed, ID '" . $newSourceID . "' is not a valid Logstream Source", DEBUG_ERROR);
			return; 
		}
	}


	/*
	* Helper function to trigger initialisation 
	*/
	public function RunBasicInits()
	{
		$this->SetOutputType( REPORT_OUTPUT_HTML ); 
	}

	
}
?>