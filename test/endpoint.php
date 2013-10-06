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
			'title' => 'Parks',
			'color' => '#3ba672'
		),
		array(
			'slug' => 'trails',
			'title' => 'Trails',
			'color' => '#a67a3d'
		)
	),
	'locations' => array(
		array(
			'title' => 'Barbara Bennett Park',
			'lat' => 39.523335,
			'lng' => -119.818062,
			'desc' => '400 Island Ave, Reno, NV 89501',
			'category' => 'parks'
		),
		array(
			'title' => 'Bicentennial Park',
			'lat' => 39.524273,
			'lng' => -119.818642,
			'desc' => '10 Ralston St, Reno, NV 89503',
			'category' => 'parks'
		),
		array(
			'title' => 'Billinghurst Park & Fields',
			'lat' => 39.522270,
			'lng' => -119.894142,
			'desc' => '6605 Chesterfield Ln, Reno, NV 89523',
			'category' => 'trails'
		)
	)
);

header('Content-type: application/json');
echo json_encode($output);