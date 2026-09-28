<?php
/*
 * Context-aware output helpers for values that may originate in configuration,
 * database rows, or HTTP requests.
 */

if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
}

function HtmlOutputCharset($charset = null)
{
	global $content;
	if ( $charset === null )
		$charset = isset($content['HeaderDefaultEncoding']) ? $content['HeaderDefaultEncoding'] : null;

	if ( is_scalar($charset) )
	{
		$normalizedCharset = strtolower(trim((string)$charset));
		if ( $normalizedCharset == 'utf-8' )
			return 'UTF-8';
		if ( $normalizedCharset == 'iso-8859-1' )
			return 'ISO-8859-1';
	}

	return defined('ENC_ISO_8859_1') ? ENC_ISO_8859_1 : 'ISO-8859-1';
}

function HtmlEscapeText($value)
{
	return htmlspecialchars(is_scalar($value) ? (string)$value : '', ENT_QUOTES | ENT_SUBSTITUTE, HtmlOutputCharset());
}

function HtmlEscapeErrorDetails($value)
{
	$text = is_scalar($value) ? (string)$value : '';
	$text = preg_replace('/<br\s*\/?>/i', "\n", $text);
	return nl2br(HtmlEscapeText($text), false);
}

function HtmlEscapeAttribute($value)
{
	return htmlspecialchars(is_scalar($value) ? (string)$value : '', ENT_QUOTES | ENT_SUBSTITUTE, HtmlOutputCharset());
}

function HtmlSafeUrlAttributeFromQuery($path, $parameters)
{
	if ( !is_scalar($path) || !is_array($parameters) )
		return '';

	$safeParameters = array();
	foreach ($parameters as $name => $value)
	{
		if ( is_scalar($name) && is_scalar($value) )
			$safeParameters[(string)$name] = (string)$value;
	}

	$query = http_build_query($safeParameters, '', '&', PHP_QUERY_RFC3986);
	$url = (string)$path;
	if ( $query !== '' )
		$url .= '?' . $query;

	return HtmlEscapeAttribute($url);
}

function HtmlSafePostVariables($variables)
{
	if ( !is_array($variables) )
		return array();

	$safeVariables = array();
	foreach ($variables as $name => $value)
	{
		if ( is_scalar($name) && is_scalar($value) )
			$safeVariables[] = array( 'varname' => HtmlEscapeAttribute($name), 'varvalue' => HtmlEscapeAttribute($value) );
	}

	return $safeVariables;
}

function SecureRedirect($szRedir)
{
	if ( !is_scalar($szRedir) )
		return 'index.php';

	$szRedir = (string)$szRedir;
	if ( $szRedir === '' || trim($szRedir) !== $szRedir || preg_match('/[\x00-\x1F\x7F]/', $szRedir) || strpos($szRedir, chr(92)) !== false )
		return 'index.php';

	$parsed = parse_url($szRedir);
	if ( $parsed === false || isset($parsed['scheme']) || isset($parsed['host']) || isset($parsed['user']) || isset($parsed['pass']) || isset($parsed['port']) )
		return 'index.php';
	if ( substr($szRedir, 0, 2) === '//' )
		return 'index.php';

	$path = isset($parsed['path']) ? (string)$parsed['path'] : '';
	$decodedPath = rawurldecode($path);
	if ( $path === '' || substr($decodedPath, 0, 2) === '//' || (substr($decodedPath, 0, 1) === '/' && substr($path, 0, 1) !== '/') || strpos($decodedPath, chr(92)) !== false || preg_match('#(^|/)\.\.?(/|$)#', $decodedPath) )
		return 'index.php';

	return $szRedir;
}

function SafeImageUrl($value, $fallback)
{
	$value = is_scalar($value) ? trim((string)$value) : '';
	if ($value === '' || preg_match('/[\x00-\x1F\x7F]/', $value))
		return $fallback;

	if (strpos($value, '//') === 0)
		return $fallback;

	$parsed = parse_url($value);
	if ($parsed === false)
		return $fallback;

	if (isset($parsed['scheme']) && !in_array(strtolower($parsed['scheme']), array('http', 'https'), true))
		return $fallback;

	return $value;
}
