#!/usr/bin/env php
<?php
error_log("Starting the postprocessing script");
$envVars = $_SERVER;
// All the env variables attached to variables

$path = isset($envVars["radarr_moviefile_path"]) ? $envVars["radarr_moviefile_path"] : null;
$pieces = explode("/", $path);
$pieceCount=count($pieces);
$my_path=$pieces[$pieceCount-2]."/".$pieces[$pieceCount-1];
$newstring = substr($path, -3);

$path=$envVars["radarr_moviefile_path"];

// $p1="/usr/local/bin/ffprobe -v error -of flat=s=_ -select_streams v:0 -show_entries stream=width,height ";
exec("/usr/local/bin/ffprobe -i \"".$path."\" -v quiet -print_format json -show_format -show_streams -hide_banner", $output,$res);
$data = implode("\n",$output);
$info = json_decode($data,true);

$width=$info['streams'][0]['width'];
$height=$info['streams'][0]['height'];
$bitrate=floor($info['format']['bit_rate']/1000);

error_log("Width: ".$width);
error_log("Height: ".$height);
error_log("Bitrate: ".$bitrate);

$FN=$my_path;
error_log("Filename: ".$FN);

$myFolder= explode("/", $my_path)[0];
$myFolder= '"'.$myFolder.'"';

error_log("Folder :".$myFolder);

if ($width == 1280 && $height == 720) {
    error_log("Exact 720");
    shell_exec("rm -r /media/Movies/".$myFolder);
    shell_exec("mkdir /media/Movies/".$myFolder);
	error_log("Copying ".$myFolder." now!");
    shell_exec("cp -r /media/Original/".'"'.$FN.'"'." /media/Movies/".$myFolder);
    error_log("Finished copy");
} elseif ($width == 1920 && $height == 1080) {
    error_log("Exact 1080");
    shell_exec("rm -r /media/Movies/".$myFolder);
    shell_exec("mkdir /media/Movies/".$myFolder);
	error_log("Copying ".$myFolder." now!");
    shell_exec("cp -r /media/Original/".'"'.$FN.'"'." /media/Movies/".$myFolder);
    error_log("Finished copy");
} elseif ($width <= 1280) {
    error_log("Trans 720");
    shell_exec("rm -r /media/Movies/".$myFolder);
    shell_exec("mkdir /media/Movies/".$myFolder);
    if($bitrate < 4500)
    $crf=23;
    else
    $crf=19;
    error_log("Transcoding ".$myFolder." now!");
    error_log("CRF: ".$crf);
    // error_log("/usr/media/bin/ffmpeg -i /media/Original/".'"'.$FN.'"'." -vf scale=1280:720,setdar=16:9 -c:v libx264 -preset medium -crf 20 -sn -c:a aac -strict experimental -b:a 256k /media/Movies/".'"'.$FN.'"'." 2> /dev/null");

    shell_exec("/usr/local/bin/ffmpeg -i /media/Original/".'"'.$FN.'"'." -vf scale=1280:720,setdar=16:9 -c:v libx264 -preset medium -crf ".$crf." -sn -c:a aac -strict experimental -b:a 256k /media/Movies/".'"'.$FN.'"'." 2> /dev/null");
    error_log("Finished transcode");
} elseif ($width >= 1281) {
    error_log("Trans 1080");
    shell_exec("rm -r /media/Movies/".$myFolder);
    shell_exec("mkdir /media/Movies/".$myFolder);
    if($bitrate < 5000)
    $crf=22;
    else
    $crf=18;
    error_log("Transcoding ".$myFolder." now!");
    error_log("CRF: ".$crf);


    // error_log("/usr/media/bin/ffmpeg -i /media/Original/".'"'.$FN.'"'." -vf scale=1920:1080,setdar=16:9 -c:v libx264 -preset medium -crf 19 -sn -c:a aac -strict experimental -b:a 256k /media/Movies/".'"'.$FN.'"'." 2> /dev/null");

    shell_exec("/usr/local/bin/ffmpeg -i /media/Original/".'"'.$FN.'"'." -vf scale=1920:1080,setdar=16:9 -c:v libx264 -preset medium -crf ".$crf." -sn -c:a aac -strict experimental -b:a 256k /media/Movies/".'"'.$FN.'"'." 2> /dev/null");
    error_log("Finished transcode");
} else {
    error_log("unknown res, copy only");
    shell_exec("rm -r /media/Movies/".$myFolder);
    shell_exec("mkdir /media/Movies/".$myFolder);
    shell_exec("cp -r /media/Original/".'"'.$FN.'"'." /media/Movies/".$myFolder);
    error_log("Finished copy");
}

?>
