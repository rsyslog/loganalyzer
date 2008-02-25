<?php
/**
* StreamConfig has the capability to create a specific LogStream 
* object depending on a configured LogStream*Config object.
*/
class LogStreamConfigDisk extends LogStreamConfig {
	public $FileName = '';

	public function LogStreamFactory($o) {
		return new LogStreamDisk($o);
	}

}
?>