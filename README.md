# mkvUpscale


Headless FFmpeg script to transcode video and strip black bars from not true 720p or 1080p rips for [Plex](https://www.plex.tv "Plex").

Ex. A 1280x540 video will be upscaled to 1280x720 to remove black bars from plex

**Does stretch video, not crop

Raddarr ENV paths are used to get the path for the video, script could be changed to be run without easily

ffprobe determines the width/height and bitrate of the video

Based on the screen size and bitrate, it will transcode the video to h.264/ac3 mkv

Uses realitive file paths as everything is dockerized

For radarr image with php included refer to my [docker container](https://github.com/MrRobot245/docker-radarr).
