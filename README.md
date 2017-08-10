# mkvUpscale


Headless FFmpeg script to convert strip black bars from not true 720p or 1080p rips for plex.

Ex. A 1280x540 video will be upscaled to 1280x720 to remove black bars from plex

**Does stretch video, not crop

Also included is a script for Radarr to get the full file path and trigger the script (radarrHook.php)

The conversion can also be run with `python convert.py path/to/file.mkv` but it expects the Radarr current naming convention.
