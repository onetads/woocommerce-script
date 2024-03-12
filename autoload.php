<?php
$GLOBALS['RAS']['PATH'] = plugin_dir_path(__FILE__);
$GLOBALS['RAS']['PLUGIN_FILENAME'] = basename(__FILE__);

require_once $GLOBALS['RAS']['PATH'] . 'src/Utilities/RasSettings.php';
require_once $GLOBALS['RAS']['PATH'] . 'src/Hooks/CheckVersionHook.php';
require_once $GLOBALS['RAS']['PATH'] . 'src/Hooks/ProductHook.php';
require_once $GLOBALS['RAS']['PATH'] . 'src/Utilities/ProductUtil.php';