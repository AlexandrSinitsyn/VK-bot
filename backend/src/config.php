<?php

define('BOT_TOKEN', getenv('VK_BOT_BOT_TOKEN'));
define('API_CONFIRMATION_TOKEN', getenv('VK_BOT_API_CONFIRMATION_TOKEN'));
define('GROUP_SECRET', getenv('VK_BOT_GROUP_SECRET'));
define('GROUP_ID', getenv('VK_BOT_GROUP_ID'));

define('DB_USER', getenv('POSTGRES_USER'));
define('DB_PASSWORD', getenv('POSTGRES_PASSWORD'));
define('DB_NAME', getenv('POSTGRES_DB'));

const CACHE_PORT = 11211;
