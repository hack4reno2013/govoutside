<?php
session_start();

function config() {
	$config = array();
	$config['db']['host'] = 'localhost';
	$config['db']['username'] = 'govdbadmin';
	$config['db']['password'] = 'G0vD4t401*';
	$config['db']['name'] = 'govdb';
	$config['base_url'] = '/go/';
	return $config;
}
 