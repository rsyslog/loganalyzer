<?php
/*
 * Chart ordering is intentionally represented by a finite semantic key.
 * SQL expressions are generated only from trusted field mappings.
 */

if ( !defined('IN_PHPLOGCON') )
{
	die('Hacking attempt');
}

function NormalizeChartOrderKey($value)
{
	$value = is_scalar($value) ? strtolower(trim((string)$value)) : '';

	switch ($value)
	{
		case 'count_asc':
		case 'totalcount asc':
			return 'count_asc';
		case 'field_asc':
		case 'field asc':
			return 'field_asc';
		case 'field_desc':
		case 'field desc':
			return 'field_desc';
		case 'count_desc':
		case 'totalcount desc':
		default:
			return 'count_desc';
	}
}

function GetChartOrderOptions($selected = 'count_desc')
{
	$selected = NormalizeChartOrderKey($selected);
	return array(
		array('value' => 'count_desc', 'DisplayName' => 'Count (descending)', 'selected' => $selected === 'count_desc' ? 'selected' : ''),
		array('value' => 'count_asc', 'DisplayName' => 'Count (ascending)', 'selected' => $selected === 'count_asc' ? 'selected' : ''),
		array('value' => 'field_asc', 'DisplayName' => 'Chart field (ascending)', 'selected' => $selected === 'field_asc' ? 'selected' : ''),
		array('value' => 'field_desc', 'DisplayName' => 'Chart field (descending)', 'selected' => $selected === 'field_desc' ? 'selected' : ''),
	);
}

function ChartOrderByExpression($orderKey, $selectFieldName)
{
	switch (NormalizeChartOrderKey($orderKey))
	{
		case 'count_asc':
			return 'totalcount ASC';
		case 'field_asc':
			return $selectFieldName . ' ASC';
		case 'field_desc':
			return $selectFieldName . ' DESC';
		case 'count_desc':
		default:
			return 'totalcount DESC';
	}
}
