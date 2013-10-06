<?php

$output = array(
	'init' => array(
		'zoom' => 8,
		'center' => array(
			'lat' => 39.5272,
			'lng' => -119.8219
		)
	),
	'categories' => array(
		array(
			'slug' => 'parks',
			'title' => 'Parks'
		),
		array(
			'slug' => 'trails',
			'title' => 'Trails'
		)
	),
	'locations' => array(
		array(
			'title' => 'Barbara Bennett Park',
			'lat' => 39.523335,
			'lng' => -119.818062,
			'desc' => '<b>Barbara Bennett Park</b><br />400 Island Ave, Reno, NV 89501',
			'category' => 'parks'
		),
		array(
			'title' => 'Bicentennial Park',
			'lat' => 39.524273,
			'lng' => -119.818642,
			'desc' => '<b>Bicentennial Park</b><br />10 Ralston St, Reno, NV 89503',
			'category' => 'parks'
		),
		array(
			'title' => 'Billinghurst Park & Fields',
			'lat' => 39.522270,
			'lng' => -119.894142,
			'desc' => '<b>Billinghurst Park & Fields</b><br />6605 Chesterfield Ln, Reno, NV 89523',
			'category' => 'trails'
		)
	)
);

header('Content-type: application/json');
echo json_encode($output);