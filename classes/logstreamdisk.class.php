<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* LogStreamDisk provides access to the data on disk. In the most
	* cases this will be plain text files. If we need access to e.g.
	* zipped files, this will be handled by a separate driver.
	*
	* \version 1.0.1 2nd Version
	* \version 1.0.0 Init Version
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

class LogStreamDisk extends LogStream {
	private $_currentOffset = -1;
	private $_currentStartPos = -1;
	private $_fp = null;
	private $_bEOF = false;

	const _BUFFER_LENGHT = 4096;
	private $_buffer = false;
	private $_buffer_lenght = -1;
	private $_p_buffer = -1;

	// cache for backwards reading
	private $_cache_lines = null;
	private $_p_cache_lines = -1;

	// Constructor
	public function LogStreamDisk($streamConfigObj) {
		$this->_logStreamConfigObj = $streamConfigObj;
	}

	/**
	* Open the file with read access.
	*
	* @param streamConfigObj object in: It has to be a LogSteamDiskConfig object.
	* @param bStartAtEOF bool in: If set to true the file pointer is set at EOF.
	* @return integer Error stat
	*/
	public function Open($arrProperties, $bStartAtEOF = false) {
		if(!file_exists($this->_logStreamConfigObj->FileName)) {
			return ERROR_FILE_NOT_FOUND;
		}

		$this->_fp = fopen($this->_logStreamConfigObj->FileName, 'r');	
		if ($bStartAtEOF) {
			fseek($this->_fp, 0, SEEK_END);
		}
		$this->_currentOffset = ftell($this->_fp);
		$this->_currentStartPos = $this->_currentOffset;
		$this->_arrProperties = $arrProperties;

		// init read
		$this->ReadNextBlock();
		return SUCCESS;
	}

	/**
	* Close the file.
	*
	* @return integer Error state
	*/
	public function Close() {
		
		if (!fclose($this->_fp)) {
			return ERROR_FILE_CANT_CLOSE;
		}
		return SUCCESS;
	}

	public function ReadNextBlock() {
		//echo 'in ReadNextBlock<br />';
		$this->_buffer = fread($this->_fp, self::_BUFFER_LENGHT);
		$this->_buffer_lenght = strlen($this->_buffer);
		$this->_p_buffer = 0;

		if ($this->_buffer == false)
			return ERROR_FILE_BOF;

		return SUCCESS;
	}

	/**
	* Read the data from a specific uID which means in this
	* case from a given offset of the file.
	* 
	* @param uID integer in/out: unique id of the data row 
	* @param logLine string out: data row
	* @return integer Error state
	* @see ReadNext()
	*/
	public function Read($uID, &$logLine) {
		fseek($this->_fp, $uI);

		// with Read we can only read forwards.
		// so we have to remember the current read
		// direction
		$tmp = $this->_readDirection;
		$iRet = $this->ReadNext($uID, $logLine);
		$this->_readDirection = $tmp;

		return $iRet;
	}

	/**
	* Read the next line from the file depending on the current
	* read direction.
	*
	* Hint: If the current stream becomes unavailable an error
	* stated is retuned. A typical case is if a log rotation
	* changed the original data source.
	*
	* @param uID integer out: uID is the offset of data row
	* @param logLine string out: data row
	* @return integer Error state
	* @see ReadNext
	*/
	public function ReadNext(&$uID, &$logLine) {
		if ($this->_readDirection == EnumReadDirection::Forward) {
			return $this->ReadNextForwards($uID, $logLine);
		}

		return $this->ReadNextBackwards($uID, $logLine);
	}

	private function ReadNextForwards(&$uID, &$logLine) {
		if ($this->bEOF) {
			return ERROR_FILE_EOF;
		}

		if (($this->_p_buffer == $this->_buffer_lenght) && ($this->ReadNextBlock() != SUCCESS)) {
				return ERROR_UNDEFINED;
		}

		$line = '';
		do {

			$pos = -1;
			if (($pos = strpos($this->_buffer, "\n", $this->_p_buffer)) !== false) {
				$logLine = $line . substr($this->_buffer, $this->_p_buffer, $pos - $this->_p_buffer);
				$this->_currentOffset = $pos - $this->_p_buffer + 1;
				$this->_p_buffer = $pos + 1;
				$uID = $this->_currentStartPos;
				$this->_currentStartPos = $this->_currentOffset;
				return SUCCESS;
			}
			
			$line .= substr($this->_buffer, $this->_p_buffer, $this->_buffer_lenght - $this->_p_buffer);
			$this->_currentOffset += $this->_buffer_lenght - $this->_p_buffer;
		} while ($this->ReadNextBlock() == SUCCESS);

		return ERROR_UNDEFINED;
	}

/*
	private function ReadNextForwards(&$uID, &$logLine) {
		
		if (feof($this->_fp)) {
			return ERROR_FILE_EOF;
		}

		$uID = ftell($this->_fp);

		$logLine = fgets($this->_fp);
		if ($logLine === false) {
			// ToDo: error occurs, or EOF
			return 1;
		}
		
		$this->_currentOffset = $this->_currentOffset + sizeof($logLine);
		
		return SUCCESS;	
	}
*/

	private function ReadNextBackwards(&$uID, &$logLine) {
		if ($this->_p_cache_lines < 0) {
			if (($iRet = $this->InitCacheLines()) > 0) { // error or BOF?
				return $iRet;
			}
		}

		// at this stage we can read from cache
		$uID = $this->_cache_lines[$this->_p_cache_lines][0];
		$logLine = $this->_cache_lines[$this->_p_cache_lines][1];
		$this->_p_cache_lines--;

		return SUCCESS;
	}

	private function ClearCacheLines() {
			unset($this->_cache_lines);
			$this->_p_cache_lines = -1;
	}

	private function InitCacheLines() {
		$orig_offset = ftell($this->_fp);
		if ($this->_readDirection == EnumReadDirection::Backward) {
			// if we have already used the cache take the last positon
			// as offset and then clear the cache
			if (isset($this->_cache_lines[0][0])) {
				$orig_offset = $this->_cache_lines[0][0];
				$this->ClearCacheLines();

				// check if it is the first line so we have BOF
				if ($orig_offset == 0) {
					return ERROR_FILE_BOF;
				}
			}
		}

		$offset = $orig_offset - 4096;
		if ($offset < 0) {
			$offset = 0;
		} 

		fseek($this->_fp, $offset);
		
		if ($offset != 0) {
			// we do not know if we are on the beginning of a line
			// therefore we simply skip the first line
			fgets($this->_fp);
		}

		while (($offset = ftell($this->_fp)) < $orig_offset) {
			$this->_p_cache_lines++;
			$this->_cache_lines[$this->_p_cache_lines][0] = $offset;
			$this->_cache_lines[$this->_p_cache_lines][1] = fgets($this->_fp);
			if ($this->_cache_lines[$this->_p_cache_line][1] === false) {
				// probably EOF or an error
				unset($this->_cache_lines[$this->_p_cache_line]);
				$this->_p_cache_lines--;
				break;
			}		
		}
		return SUCCESS;
	}

	/**
	* Set the filter for the current stream.
	* 
	* @param filter object in: filter object
	* @return integer Error state
	*/
	public function SetFilter($filter) {
		return SUCCESS;	
	}

	/**
	* Set the direction the stream should read data.
	*
	* 
	*
	* @param enumReadDirectionfilter EnumReadDirection in: The new direction.
	* @return integer Error state
	*/
	public function SetReadDirection($enumReadDirection) {
		
		// only if the read direction change we have do do anything
		if ($this->_readDirection == $enumReadDirection)
			return SUCCESS;

		$this->_readDirection = $enumReadDirection;
		return SUCCESS;
	}
}

?>