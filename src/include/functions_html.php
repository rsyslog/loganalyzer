<?php
/*
 * Context-aware output helpers for values that may originate in configuration,
 * database rows, or HTTP requests.
 */

if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
}

function HtmlEscapeText($value)
{
	return htmlspecialchars(is_scalar($value) ? (string)$value : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function HtmlEscapeAttribute($value)
{
	return htmlspecialchars(is_scalar($value) ? (string)$value : '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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
