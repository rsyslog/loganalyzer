<?php
/**
* StreamConfig has the capability to create a specific LogStream 
* object depending on a configured LogStream*Config object.
*/
abstract class LogStreamConfig {
	protected $_logStreamConfigObj = null;

	public abstract function LogStreamFactory($o);

}
?>