<?php
session_start();

function config() {
	$config = array();
	$config['db']['host'] = 'localhost';
	$config['db']['username'] = 'root';
	$config['db']['password'] = '';
	$config['db']['name'] = 'govoutside';
	$config['base_url'] = '/govoutside/';
	return $config;
}
