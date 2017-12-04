<?php

$config = array();

// Подключение скриптов шаблона
$config['head']['template']['js'] = array(
	'___path.skin.assets.web___/js/jquery.touchSwipe/jquery.touchSwipe.js',
	'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.js',
	'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/locale/bootstrap-table-ru-RU.js',
	'___path.skin.assets.web___/js/gmap.js',
	'___path.skin.assets.web___/js/document_fields.js',
	'___path.skin.assets.web___/js/offcanvas.js',
	'___path.skin.assets.web___/js/notification.js',
	'___path.skin.assets.web___/js/init.js'
);

// Подключение стилей шаблона
$config['head']['template']['css'] = array(
	"___path.skin.web___/libs/ionicons/css/ionicons.css",
	"___path.skin.assets.web___/css/style.css",
	"___path.skin.assets.web___/css/navbar.css",
	"___path.skin.assets.web___/css/response.css",
	"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.css",
);

$config['components'] = Config::Get('components');
$config['components'][] = 'bootstrap';

return $config;