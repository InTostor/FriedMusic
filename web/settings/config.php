<?php
// Root of webserver
$root = $_SERVER['DOCUMENT_ROOT'];

// Database connection
$dbHost = "192.168.0.186:9889";
$dbUser = "Admin";
$dbPass = "ffq6KLHYY583MdEahTYe";
$dbName = "friedmusic";

// Paths
$musicPath = "$root/Music/";
$userData = "$root/userdata/";

// Constants
$maxHistoryFileLines = 500;
$allowedFileExtensions = ["fpl","fbl"];
$MAXUPLOADSIZE = 5242880; // 2^16 * mean filename size * 2 bytes unicode character size
$MAXALLOWEDFILES = 64;
$cookieTime = 157680000; // 5 years