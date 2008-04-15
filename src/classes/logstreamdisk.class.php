<?php
/*
	*********************************************************************
	* -> www.phplogcon.org <-											*
	* -----------------------------------------------------------------	*
	* Some constants													*
	*																	*
	* LogStreamDisk provides access to the data on disk. In the most
	* cases this will be plain text files. If we need access to e.g.
	* zipped files, this will be handled by a separate driver.
	*
	* \version 2.0.1 2nd Version
	* \version 2.0.0 Init Version
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

// --- Required Includes!
require_once($gl_root_path . 'include/constants_errors.php');
// --- 

class LogStreamDisk extends LogStream {
	private $_currentOffset = -1;
	private $_currentStartPos = -1;
	private $_fp = null;
	private $_bEOS = false;

	const _BUFFER_length = 8192;
	private $_buffer = false;
	private $_buffer_length = 0;
	private $_p_buffer = -1;

	// Constructor
	public function LogStreamDisk($streamConfigObj) {
		$this->_logStreamConfigObj = $streamConfigObj;
	}

	/**
	* Open the file with read access.
	*
	* @param arrProperties array in: Properties wish list.
	* @return integer Error stat
	*/
	public function Open($arrProperties) {
		if(!file_exists($this->_logStreamConfigObj->FileName)) {
			return ERROR_FILE_NOT_FOUND;
		}

		$this->_fp = fopen($this->_logStreamConfigObj->FileName, 'r');	

		$this->_currentOffset = ftell($this->_fp);
		$this->_currentStartPos = $this->_currentOffset;
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

	private function ReadNextBlock() {
		$this->_bEOS = false;
		$bCheckForLastLf = false;

		if ($this->_readDirection == EnumReadDirection::Backward) {	
			// in this case we have to adjust a few settings
			$this->_p_buffer = self::_BUFFER_length ; // set the point to the right index

			// first of all, check if this is the first read
			if ($this->_buffer == false) {
				// this means that we have to read from the end
				fseek($this->_fp, 0, SEEK_END);
				$this->_currentOffset = ftell($this->_fp);
				$this->_p_buffer -= 1; // eat EOF
				$bCheckForLastLf = true;
			}

			$orig_offset = ftell($this->_fp) - $this->_buffer_length;

			if ($orig_offset <= 0) {
				// apparently we are at BOF so nothing to read
				return ERROR_EOS;
			}

			// jumb to the new position
			$orig_offset -= self::_BUFFER_length;
			if ($orig_offset <= 0) {
				// ok, we have to adjust the buffer pointer
				$this->_p_buffer += $orig_offset; // note orig_offset is negative, see if
				$orig_offset = 0;
			}
			fseek($this->_fp, $orig_offset);

		} else {
			$this->_p_buffer = 0;
		}

		$this->_buffer = fread($this->_fp, self::_BUFFER_length);
		$this->_buffer_length = strlen($this->_buffer);
		
		if ($bCheckForLastLf && $this->_buffer[$this->_p_buffer] == "\n") {
			// skip it (can only occur if you read backwards)
			$this->_p_buffer--;
			$this->_currentOffset--;
		}
		
		if ($this->_buffer == false)
			return ERROR_FILE_EOF;

		return SUCCESS;
	}

	/**
	* Read the data from a specific uID which means in this
	* case from a given offset of the file.
	* 
	* @param uID integer in/out: unique id of the data row 
	* @param arrProperitesOut array out: array filled with properties
	* @return integer Error state
	* @see ReadNext()
	*/
	public function Read($uID, &$arrProperitesOut) {
		$this->Sseek($uID, EnumSeek::UID, 0);
		$tmp = $this->_readDirection;
		$this->_readDirection = EnumReadDirection::Forward;
		$ret = $this->ReadNext($uID, $arrProperitesOut);
		if ($tmp == EnumReadDirection::Backward) {

			$this->_p_buffer -= 2; 
			$this->_currentStartPos = $this->_currentOffset -= 1;

			$this->_readDirection = $tmp;
			// we have to skip one line that we are back on the right position
			$this->ReadNext($dummy1, $dummy2);
		}
				
		return $ret;
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
	* @param arrProperitesOut array out: properties
	* @return integer Error state
	* @see ReadNext
	*/
	public function ReadNext(&$uID, &$arrProperitesOut)
	{
		do
		{
			// Read next entry first!
			if ($this->_readDirection == EnumReadDirection::Forward)
				$ret = $this->ReadNextForwards($uID, $arrProperitesOut);
			else
				$ret = $this->ReadNextBackwards($uID, $arrProperitesOut);

		// Line Parser Hook here
		$this->_logStreamConfigObj->_lineParser->ParseLine($arrProperitesOut[SYSLOG_MESSAGE], $arrProperitesOut);

		// Loop until the filter applies, or another error occurs. 
		} while ( $this->ApplyFilters($ret, $arrProperitesOut) != SUCCESS && $ret == SUCCESS );

		// reached here means return result!
		return $ret;
	}

	private function ReadNextForwards(&$uID, &$arrProperitesOut) {
		if ($this->_bEOS) {
			return ERROR_EOS;
		}

		if ($this->_p_buffer == -1) {
			// init read
			$this->ReadNextBlock();
		}

		if (($this->_p_buffer == $this->_buffer_length || $this->_p_buffer == -1) && ($this->ReadNextBlock() != SUCCESS)) {
			return ERROR_UNDEFINED;
		}
		
		// Init variables dynamically
		$line = '';
		foreach ( $this->_arrProperties as $property ) 
			$arrProperitesOut[$property] = '';

		do {
			$pos = -1;
			if (($pos = strpos($this->_buffer, "\n", $this->_p_buffer)) !== false) {
				$uID = $this->_currentStartPos;
				$logLine = $line . substr($this->_buffer, $this->_p_buffer, $pos - $this->_p_buffer);
				$arrProperitesOut[SYSLOG_MESSAGE] = $logLine;

				// the buffer pointer currently points to the linefeed 
				// so we have to increment the pointer to eat it
				$this->_currentOffset += $pos - $this->_p_buffer + 1;
				$this->_p_buffer = $pos + 1;
				$this->_currentStartPos = $this->_currentOffset;
				return SUCCESS;
			}
			
			$line .= substr($this->_buffer, $this->_p_buffer, $this->_buffer_length - $this->_p_buffer);
			$this->_currentOffset += $this->_buffer_length - $this->_p_buffer;
		} while ($this->ReadNextBlock() == SUCCESS);

		if ($line != '') {
			$uID = $this->_currentStartPos;
			$arrProperitesOut[SYSLOG_MESSAGE] = $line;

			$this->_currentStartPos = $this->_currentOffset;
			return SUCCESS;
		}
		return ERROR_UNDEFINED;
	}

	private function ReadNextBackwards(&$uID, &$arrProperitesOut) {
		if ($this->_bEOS) {
			return ERROR_EOS;
		}

		if ($this->_p_buffer < 0) {
			// a negative buffer means that the we have to adjust
			// the offset
			$this->_currentOffset++;
			if ($this->ReadNextBlock() != SUCCESS) {
				return ERROR_UNDEFINED;
			}
		}
		
		// Init variables dynamically
		$line = '';
		foreach ( $this->_arrProperties as $property ) 
			$arrProperitesOut[$property] = '';

		do {
			$pos = -1;
			$neg_offset = ($this->_buffer_length - $this->_p_buffer) * -1;
			if (($pos = strrpos($this->_buffer, "\n", $neg_offset)) !== false) {
				// note that we are at the position of the linefeed, 
				// this is recognize in the next few calculation
				$uID = $this->_currentOffset -= $this->_p_buffer - $pos;
				$arrProperitesOut[SYSLOG_MESSAGE] = substr($this->_buffer, $pos + 1, $this->_p_buffer - $pos) . $line;

				$this->_currentOffset--; // eat the lf
				$this->_p_buffer = $pos - 1;

				return SUCCESS;
			}

			$line = substr($this->_buffer, 0, $this->_p_buffer) . $line;
			$this->_currentOffset -= $this->_p_buffer; 

		} while ($this->ReadNextBlock() == SUCCESS);

		if ($line != '') {
			// this case should only happend if we are on BOF
			$this->_bEOS = true;

			$uID = 0;
			$arrProperitesOut[SYSLOG_MESSAGE] = $line;
			
			return SUCCESS;
		}
		return ERROR_EOS;
	}

	/**
	* Implementation of Seek
	*/
	public function Sseek(&$uID, $mode, $numrecs) {
		// in any case we reset the buffer
		$this->ResetBuffer();

		$ret = -1;

		switch ($mode) { 
			case EnumSeek::BOS:
				$ret = fseek($this->_fp, 0);
				$this->_currentOffset = $this->_currentStartPos = 0;
				break;
			case EnumSeek::EOS: 
				// a simple ReadNextBackup will do all the work
				// for us, because we have reset the buffer
				// remember the current readDirection

				$tmp = $this->_readDirection;
				$this->_readDirection = EnumReadDirection::Backward;
				$ret = $this->ReadNextBackwards($uID, $dummy2);
				if ($tmp == EnumReadDirection::Forward) {
					// in this case we have to correct the buffer,
					// because we have read backwards even the current
					// readDirection is forwards
					$this->_p_buffer += 2; 
					$this->_currentStartPos = $this->_currentOffset;
				}
				$this->_readDirection = $tmp;
				break;
			case EnumSeek::UID:
				$ret = fseek($this->_fp, $uID);
				$this->_currentOffset = $this->_currentStartPos = $uID;
				break;
		}

		if ($ret != SUCCESS)
			return ERROR_UNDEFINED;

		return $this->Skip($uID, $numrecs);
	}

	/**
	*	
	* @param numrecs integer in: If positiv, skip 
	* @return uid integer Error state
	*/
	private function Skip($uID, $numrecs) {
		if ($numrecs == 0)
			return SUCCESS;
	
		if ($numrecs > 0) {
			/* due to performance reason we use php's fgets instead of ReadNext method
			while (!feof($this->_fp)) {
        fgets($this->_fp);
        $numrecs--;
				if ($numrecs == 0) {
					break;
				}
				$this->_currentOffset = ftell($this->_fp);
			}
			*/
			while ($this->ReadNextForwards($dummy1, $dummy2) == SUCCESS) {
        fgets($this->_fp);
        $numrecs--;
				if ($numrecs == 0) {
					break;
				}
				$this->_currentOffset = ftell($this->_fp);
			}
		} else {
			while ($this->ReadNextBackwards($dummy1, $dummy2) == SUCCESS) {
				$numrecs++;
				if ($numrecs == 0) {
					break;
				}
			}
		}
		
		// where we are?
		$uID = $this->_currentOffset;
		
		if ($numrecs != 0) {
			// obviously there were not enough records to skip
			return ERROR_NOMORERECORDS;
		}
		return SUCCESS;
	}


	/**
	* Set the filter for the current stream.
	* 
	* @param filter object in: filter object
	* @return integer Error state
	public function SetFilter($filter) {
		return SUCCESS;	
	}
	*/

	/**
	* GetMessageCount will always return -1 which means
	* that the message count is not available. We refuse
	* the request of the message count due to a count would
	* require to read the whole file which would be a big 
	* pain if the file is e.g. 1 gb.
	*/
	public function GetMessageCount() {
		return -1;
	}

	/*
	* GetSortOrderProperties is not implemented yet. So it always
	* return null.
	*/
	public function GetSortOrderProperties() {
		return null;
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

	private function ResetBuffer() {
		$this->_bEOS = false;
		$this->_buffer = false;
		$this->_buffer_length = 0;
		$this->_p_buffer = -1;
	}
}

?>
