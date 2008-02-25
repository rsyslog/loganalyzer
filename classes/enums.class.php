<?php

/*
* Since php does not support enums we emulate it
* using a abstract class. 
*/

/**
* ENUM of available ReadDirection.
*/
abstract class EnumReadDirection {
	const Forward = 1;
	const Backward = 2;
}

?>