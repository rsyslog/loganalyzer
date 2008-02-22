<?php
/*
	*********************************************************************
	* Copyright by Adiscon GmbH | 2008!									*
	* -> www.phplogcon.org <-											*
	*																	*
	* Use this script at your own risk!									*
	* -----------------------------------------------------------------	*
	*																	*
	*	Template Class 1.02												*
	*																	*
	*	Release Date: 26.08.2001										*
	*	Author: Philipp von Criegern (philipp@criegern.de)				*
	*																	*
	*	This is Open Source Software. Published 'as is' without			*
	*	any warranty.													*
	*	Feel free to use or edit it. Any comments are welcome!			*
	*																	*
	*	Modify Date: 2006-01-20											*
	*	by Andre Lorbach												*
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

	class Template {

		var $path    = '';
		var $filename  = '';
		var $extension  = '';
		var $template,  $vars,  $page;

		function Template ($fname = '') {
			if ($fname)
				$this->filename  =  $fname;
		}

		function set_path ($path) {
			$this->path  =  $path;
		}

		function set_extension ($ext) {
			$this->extension  =  $ext;
		}

		function set_templatefile ($fname) {
			$this->filename  =  $fname;
		}

		function set_template ($template) {
			$this->template  =  $template;
		}

		function set_values ($vars) {
			$this->vars  =  $vars;
		}

		function add_value ($name,  $value) {
		        $this->vars[$name]  =  $value;
		}

		function add_array ($name,  $values) {
			if (is_array($values))
				$this->vars[$name][]  =  $values;
		}

		function add_list ($name,  $values) {
			if (is_array($values))
				foreach ($values as $value)
					$this->vars[$name][]  =  array($name => $value);
		}

		function parser ($vars = '', $filename = '')
		{
// BEGIN DELTA MOD
			global $CFG;
			// For ShowPageRenderStats
			if ( $CFG['ShowPageRenderStats'] == 1 )
				FinishPageRenderStats( $vars );
// END DELTA MOD

			if ($filename)
				$this->filename  =  $filename;
			if ($vars)
				$this->vars  =  $vars;
			if (!isset($this->template)) {
				$fname  =  $this->path . $this->filename . $this->extension;
				$this->template  =  load_file($fname);
			}
			$this->page  =  template_parser( $this->template,  $this->vars,  $this->path,  $this->extension );
			
		}

		function result () {
			return $this->page;
		}

		function output () {
			echo $this->page;
		}

		function create_file ($fname) {
			if ($datafile  =  @fopen($fname,  'w')) {
				fputs($datafile,  $this->page);
				fclose($datafile);
				return true;
			} else {
				return false;
			}
		}

	}

	function load_file($fname)
	{
		if (@is_file($fname))
			return join('',  file($fname));
		else
		{
// BEGIN DELTA MOD
			DieWithErrorMsg( "Could not find the template <B>".$fname."</B>");
// END DELTA MOD
		}
	}

	function template_parser($template,  $values,  $path = '',  $ext = '')
	{
		while (preg_match("<!-- INCLUDE ([^\>]+) -->",  $template,  $matches))
			$template  =  str_replace( "<!-- INCLUDE ".$matches[1]." -->",  load_file( $path . $matches[1] . $ext),  $template );
		        
		$template  =  template_parser_sub($template,  $values);
		$template  =  str_replace("\t",  " ",  $template);
		$template  =  preg_replace("/ +/",  " ",  $template);
		return $template;
	}

	function template_parser_sub($template,  $values)
	{
		if (is_array($values))
		{
			foreach ($values as $k => $v)
			{
				if (is_array($v)) 
				{
					$len  =  strlen($k);
					$lp  =  strpos($template,  "<!-- BEGIN $k -->");
					if (is_int($lp)) 
					{
						if ($rp  =  strpos($template,  "<!-- END $k -->"))
						{
							$page  =  substr($template,  0,  $lp);
							$iter  =  substr($template,  $lp + 15 + $len,  $rp - $lp - $len - 15);
							$rowcnt  =  0;
							$zaehler =  1; 
							foreach ($v as $subval)
							{
								$subval['COUNTER']  =  $rowcnt%2;
								$subval['ODDROW']   =  $rowcnt%2;
								$subval['ROWCNT']   =  $rowcnt++;
								$subval['ZAEHLER']   = $zaehler++;
								$page  .=  template_parser_sub($iter,  $subval);
							}
							$template  =  $page  .  substr($template,  $rp + 13 + $len);
						}
					}
				} 
				else 
				{
					$template  =  str_replace('{'.$k.'}',  "$v",  $template);
				}
			}
		}
		
		
		if (preg_match_all("<!-- BEGIN ([a-zA-Z0-9_]+) -->",  $template,  $matches))
		{
			foreach ($matches[1] as $block) 
			{
				if (isset($values[$block])) 
				{
					$template  =  str_replace("<!-- BEGIN $block -->",  "",  $template);
					$template  =  str_replace("<!-- END $block -->",  "",  $template);
				} 
				else if ($blockend  =  strpos($template,  "<!-- END $block -->")) {
					$blockbeg  =  strpos($template,  "<!-- BEGIN $block -->");
					$template  =  substr($template,  0,  $blockbeg) . substr($template,  $blockend + 13 + strlen($block));
				}
			}
		}
//		else
		
		if (preg_match_all( '<!-- IF ([a-zA-Z0-9_]+)(!?)="([^"]*)" -->',  $template,  $matches,  PREG_SET_ORDER) )
		{
//			echo $matches[0][0];
//			exit;
			
			foreach ($matches as $block) {
				$blockname  =  $block[1];
				$not    =  $block[2];
				$blockvalue  =  $block[3];
				if ((@$values[$blockname] == $blockvalue  &&  !$not)  ||  (@$values[$blockname] != $blockvalue  &&  $not))
				{
					$template  =  str_replace( "<!-- IF $blockname$not=\"$blockvalue\" -->",  "",  $template );
					$template  =  str_replace( "<!-- ENDIF $blockname$not=\"$blockvalue\" -->",  "",  $template );
				}
				else if ($blockend  =  strpos( $template,  "<!-- ENDIF $blockname$not=\"$blockvalue\" -->"))
				{
					$blockbeg  =  strpos($template,  "<!-- IF $blockname$not=\"$blockvalue\" -->");
					$template  =  substr($template,  0,  $blockbeg)  .  substr($template,  $blockend + 18 + strlen($blockname) + strlen($blockvalue) + strlen($not));
				}
			}
		}
		
		
		
		return $template;
	}
?>