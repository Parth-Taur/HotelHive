<?php
require __DIR__ . '/vendor/autoload.php';  // Correct path to autoload

use Cloudinary\Configuration\Configuration;

Configuration::instance([
    'cloud' => [
        'cloud_name' => 'dtylhmacy',
        'api_key'    => '617434839931378',
        'api_secret' => '9SB-NDoZPIxSCcoo7q4W-q96RQo',
    ],
    'url' => [
        'secure' => true  // Ensure HTTPS URLs
    ]
]);
?>
