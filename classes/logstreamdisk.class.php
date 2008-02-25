<?php

/**
* LogStreamDisk provides access to the data on disk. In the most
* cases this will be plain text files. If we need access to e.g.
* zipped files, this will be handled by a separate driver.
*
* \version 1.0.1 2nd Version
* \version 1.0.0 Init Version
*
*/
class LogStreamDisk extends LogStream {
	private $_currentOffset = -1;
	private $_fp = null;

	// Constructor
	public function LogStreamDisk($streamConfigObj) {
		$this->_logStreamConfigObj = $streamConfigObj;
	}

	/**
	* Open the file with read access.
	*
	* @param streamConfigObj object in: It has to be a LogSteamDiskConfig object.
	* @return integer Error stat
	*/
	public function Open($arrProperties) {
		if(!file_exists($this->_logStreamConfigObj->FileName)) {
			return ERROR_FILE_NOT_FOUND;
		}
		
		$this->_fp = fopen($this->_logStreamConfigObj->FileName, 'r');
		$this->_currentOffset = 0;
		$this->_arrProperties = $arrProperties;
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

		$uID = $this->_currentOffset;

		if (feof($this->_fp)) {
			return ERROR_FILE_EOF;
		}

		$logLine = fgets($this->_fp);
		if ($logLine === false) {
			// ToDo: error occurs, or EOF
			return 1;
		}
		
		$this->_currentOffset = $this->_currentOffset + sizeof($logLine);
		
		return 0;
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
		$this->_currentOffset = $uID;
		fseek($fp, $this->_currentOffset);

		// with Read we can only read forwards.
		// so we have to remember the current read
		// direction
		$tmp = $this->_readDirection;
		$iRet = $this->ReadNext($uID, $logLine);
		$this->_readDirection = $tmp;

		return $iRet;
	}

	/**
	* Set the filter for the current stream.
	* 
	* @param filter object in: filter object
	* @return integer Error state
	*/
	public function SetFilter($filter) {
		return 0;	
	}

	/**
	* Set the direction the stream should read data.
	*
	* @param enumReadDirectionfilter EnumReadDirection in: The new direction.
	* @return integer Error state
	*/
	public function SetReadDirection($enumReadDirection) {
		return 0;
	}
}

?>