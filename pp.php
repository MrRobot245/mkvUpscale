#!/usr/bin/env php
<?php
// Do some echoing, why? why not..
echo "Starting the Sonarr.php postprocessing script\n";
// for some reason, environment variables from the cli is hiding in $_SERVER, don't ask me, php is silly sometimes
$envVars = $_SERVER;
// All the env variables attached to variables
$my_file = 'ppLog.txt';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
$path = isset($envVars["radarr_moviefile_path"]) ? $envVars["radarr_moviefile_path"] : null;
$pieces = explode("/", $path);
$my_path=$pieces[6]."/".$pieces[7];
$newstring = substr($path, -3);
echo "Path: ".$my_path. PHP_EOL;
echo "python /root/newHand.py ".$path;
echo "Extension: ".$newstring. PHP_EOL;
fwrite($handle, $path."\n");
fwrite($handle, "Extension: ".$newstring."\n");
fwrite($handle, "MyPath: ".$my_path."\n");
// If the event isn't download, just die here
// Make sure the filepath is set
if ($path != null) {
    // make sure the file path is actually real
    if (file_exists($path)) {
        if ($newstring=='mkv' || $newstring=='mp4' || $newstring=='avi') {
            // Dump some stuff into the log
        file_put_contents("/root/ffmpeg.log", "Converting $pieces[7]"."\n", FILE_APPEND);
        }

        // Now execute the sickbeard mp4 converter so we can convert the file to an mp4 with the settings we've set
        exec('/usr/bin/python /root/newHand.py "'.$my_path.'"');
    }
}
