<?php
$plugin_dir_name = basename(dirname(__FILE__));
$plugin_file_name = $plugin_dir_name .'.php';
$rename_to_filename = '__' . $plugin_file_name;

rename($plugin_file_name,$rename_to_filename);

echo 'Plugin file: ' . $plugin_file_name . '<br/>';
echo 'Renamed to : ' . $rename_to_filename .'<br/>';

?>



