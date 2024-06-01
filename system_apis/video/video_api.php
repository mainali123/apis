<?php

include "../../globals.php";

$file = explode("/", gettext($_FILES['fileToUpload']['type']))[0];
if ($file != 'video') {
    echo "The file must be a video file";
    return;
}
$target_file = $original_videoFile . basename($_FILES["fileToUpload"]["name"]);

if (!file_exists($original_videoFile)) {
    mkdir($original_videoFile, 0777, true);
}

// Move the uploaded file to the target directory
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
} else {
    echo "Sorry, there was an error uploading your file.";
}

// Processing the video
$file_name = $_FILES["fileToUpload"]["name"];

process_video($original_videoFile, "4M", "28", "fast", "1280x720", $processed_videoFile);

video_info($original_videoFile);

function process_video($filepath, $bitrate, $crf, $preset, $resolution, $output_path) {
    global $file_name;
    $videoPath = $filepath . $file_name;
    $saveVideoPath = $output_path . $file_name;
    $command = "C:\\ffmpeg-2024-05-29-full_build\\bin\\ffmpeg -i \"$videoPath\" -c:v \"libx264\" -b:v $bitrate -crf $crf -preset \"$preset\" -vf scale=$resolution \"$saveVideoPath\" 2>&1";

    exec($command, $output, $return_var);

    if ($return_var !== 0) {
        return array("success" => false, "message" => implode("\n", $output));
    } else {
//        echo "Video processed successfully";
        return array("success" => true, "message"=> "Video processed successfully");
    }
}

function video_info($videoPath) {
    global $file_name;
    $videoPath = $videoPath . $file_name;

    $command = "ffprobe -v quiet -select_streams v:0 -show_entries stream=codec_name,width,height,bit_rate -of default=noprint_wrappers=1 \"$videoPath\" 2>&1";
    $command_metadata = "ffprobe -v quiet -print_format json -show_format -show_streams -show_chapters -show_programs -show_data -show_error \"$videoPath\" 2>&1";

    exec($command, $output, $return_var);
    exec($command_metadata, $output_metaData, $return_var_metaData);

    if ($return_var !== 0) {
        echo "<br>" . implode("\n", $output);
        die();
    } else {
        $width = explode('=', $output[1])[1];
        $height = explode('=', $output[2])[1];
        $bitrate = explode('=', $output[3])[1];
    }
    return array("width" => $width, "height" => $height, "bitrate" => $bitrate, "meta-data" => $output_metaData);
}