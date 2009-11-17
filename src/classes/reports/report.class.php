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

// Include LogStream facility
include_once($gl_root_path . 'classes/logstream.class.php');



abstract class Report {
	// Common Properties
	public $_reportVersion = 1;								// Internally Version of the ReportEngine
	public $_reportID = "report.syslog.base.class";			// ID for the report, needs to be unique! - Format report.Category.ReportID.class
	public $_reportFileBasicName = "report.syslog.base";	// Basic Filename for reportfiles
	public $_reportTitle = "Base Report Class";				// Display name for the report
	public $_reportDescription = "This is the base class for all reports";
	public $_reportHelpArticle = "http://";
	public $_reportNeedsInit = false;				// True means that this report needs additional init stuff
	public $_reportInitialized = false;				// True means report is installed

	// SavedReport Configuration Properties
	protected $_customTitle = "";
	protected $_customComment = "";
	protected $_filterString = "";
	protected $_customFilters = ""; 
	protected $_outputFormat = REPORT_OUTPUT_HTML;	// Default HTML Output
	protected $_outputTarget = "";
	protected $_scheduleSettings = "";
	
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
	* This function inits data for the report
	*/
	public abstract function InitReport();


	/**
	* This function removes data for the report
	*/
	public abstract function RemoveReport();


	/**
	* verifyDataSource, verifies if data is accessable and 
	* contains what we need
	*
	* @param arrProperties array in: Properties wish list.
	* @return integer Error stat
	*/
	public function verifyDataSource()
	{
		global $content; 

		if ( $this->_streamCfgObj == null ) 
		{
			if ( isset($content['Sources'][$this->_mySourceID]['ObjRef']) )
			{
				// Obtain and get the Config Object
				$this->_streamCfgObj = $content['Sources'][$this->_mySourceID]['ObjRef'];

				// Fix Filename manually for FILE LOGSTREAM!
				if ( $content['Sources'][$this->_mySourceID]['SourceType'] == SOURCE_DISK ) 
					$this->_streamCfgObj->FileName = CheckAndPrependRootPath(DB_StripSlahes($content['Sources'][$this->_mySourceID]['DiskFile']));
			}
			else
				return ERROR_SOURCENOTFOUND;
		}

		if ( $this->_streamObj == null ) 
		{
			// Create LogStream Object 
			$this->_streamObj = $this->_streamCfgObj->LogStreamFactory($this->_streamCfgObj);
		}

		// Check datasource and return result
		$res = $this->_streamObj->Verify();
		return $res;
	}


	/**
	* This function checks if we have a valid template for the selected output
	* Will return error code on failure!
	*/
	public function validateOutputTemplate( $szOutputID )
	{
		global $content, $gl_root_path;
		$szDirectory = $gl_root_path . 'classes/reports/'; 
		$szIncludeFile = $szDirectory . $this->_reportFileBasicName . "." . $szOutputID; 

		if ( file_exists($szIncludeFile) )
		{
			// Success!
			return SUCCESS;
		}
		else
		{
			// Template file not found!
			return ERROR_FILE_NOT_FOUND;
		}
	}


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
	public function SetOutputFormat($newOutputType)
	{
		// Set new Outputtype
		$this->_outputFormat = $newOutputType; 

		// Set Filebasename
		$this->_baseFileName = $this->_reportFileBasicName . ".template." . $this->_outputFormat;
	}

	/*
	* Helper function to set the OutputTarget
	*/
	public function SetOutputTarget($newOutputTarget)
	{
		// Set new OutputTarget
		$this->_outputTarget = $newOutputTarget; 
	}

	/*
	* Helper function to set the Scheduled Settings
	*/
	public function SetScheduleSettings($newScheduleSettings)
	{
		// Set new ScheduleSettings
		$this->_scheduleSettings = $newScheduleSettings; 
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
	public function SetCustomFilters($newAdvancedOptions)
	{
		// Set new Outputtype
		$this->_customFilters = $newAdvancedOptions; 
	}

	/*
	* Helper function to set the FilterString 
	*/
	public function SetCustomTitle($newCustomTitle)
	{
		// Set new Custom Title
		$this->_customTitle = $newCustomTitle; 
	}

	/*
	* Helper function to set the FilterString 
	*/
	public function SetCustomComment($newCustomComment)
	{
		// Set new Custom Comment
		$this->_customComment = $newCustomComment; 
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
	* Helper function to return the BaseFileName
	*/
	public function GetBaseFileName()
	{
		// return Filebasename
		return $this->_baseFileName; 
	}

	/*
	* Helper function to return the CustomTitle
	*/
	public function GetCustomTitle()
	{
		// return Filebasename
		return $this->_customTitle; 
	}

	/*
	* Helper function to return the CustomComment
	*/
	public function GetCustomComment()
	{
		// return Filebasename
		return $this->_customComment; 
	}

	/*
	* Helper function to return the ReportVersion
	*/
	public function GetReportVersion()
	{
		// return Filebasename
		return $this->_reportVersion; 
	}

	/*
	* Helper function to trigger initialisation 
	*/
	public function RunBasicInits()
	{
		$this->SetOutputType( REPORT_OUTPUT_HTML ); 
	}

	/*
	*	Helper function to set settings from savedreport!
	*/
	public function InitFromSavedReport( $mySavedReport )
	{
		global $content; 

		// Copy settings from saved report!
		$this->SetSourceID( $mySavedReport["sourceid"] );
		$this->SetCustomTitle( $mySavedReport["customTitle"] );
		$this->SetCustomComment( $mySavedReport["customComment"] );
		$this->SetFilterString( $mySavedReport["filterString"] );
		$this->SetCustomFilters( $mySavedReport["customFilters"] );
		$this->SetOutputFormat( $mySavedReport["outputFormat"] );
		$this->SetOutputTarget( $mySavedReport["outputTarget"] );
		$this->SetScheduleSettings(	$mySavedReport["scheduleSettings"] );
	}

	
}
?>