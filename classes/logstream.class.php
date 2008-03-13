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
	* @param arrProperitesOut array out: list with properties
	* @return integer Error state
	*/
	public abstract function ReadNext(&$uID, &$arrProperitesOut);

	/**
	* Read the data from a specific uID.
	* 
	* @param uID integer in: unique id of the data row 
	* @param arrProperitesOut array out: list with properties
	* @return integer Error state
	* @see ReadNext()
	*/
	public abstract function Read($uID, &$arrProperitesOut);

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
	
	/**
	* Sseek - a strange seek which has a skip capability
	* 
	* This method was introduced to enable the upper layer to jump to a specific 
	* position within the stream and/or skip some records. Probably this method is used by
	* a pager or to navigate from an overview page to a detailed page.
	*
	* mm: We had some discussion about the name of the this method. Initially we named
	* it Seek. While implementing I got pain in the stomach forced me to start a discussion about
	* the name and the functionality. The outcome is here - a strange seek method. Please do not
	* confuse it with a seek method, it is no seek, it is a strange seek. rger suggested to name
	* it diddledaddle, but I still feel uncomfortable with that name. Probably my imagination is
	* too poor associating any functionality of this method with such a name. So strange seek
	* is much better. It reminds me that is no seek, but a strange seek which does not work like
	* a typical seek like fseek in php but in some way similar. Here is how it works:
	*
	* If you Sseek to EOS for example and then call a NextRead you do not get a EOS return status. 
	* Instead you will obtain the last record in the stream. The similarity of Sseek with a seek
	* is when you use Sseek to jump to BOS. After calling a ReadNext will give you the first record
	* in the stream. Here are some samples:
	*
	*
	* Sample: 
	* To read the last record of a stream, do a 
	* seek(uid_out, EOS, 0) 
	* ReadNext 
	*
	* For the first record, similarly: 
	* seek(uid_out, BOS, 0) 
	* ReadNext 
	* 
	* To skip the next, say, 49 records from the current position, you first need to know the 
	* current uid. You may have obtained it by a previous ReadNext call. Then, do 
	* seek(uidCURR, UID, 50) 
	* ReadNext
	* 
	* @param uID integer in/out: is a unique ID from where to start, ignored in all modes except UID. 
	* On return, uID contains the uID of the record seeked to. It is undefined if an error occured. 
	* If no error ocucrred, the next call to ReadNext() will read the record whom's uID has been returned.
	* @param mode EnumSeek in: how the seek should be performed
	* @param numrecs integer in: number of records to seek from this position. Use 0 to seek to the
	* actual position, a positive value to seek the the record numrecs records forward or a negative
	* value to seek to a position numrecs backward
	* @return integer Error state
	*/
	public abstract function Sseek(&$uID, $mode, $numrecs);
}


?>