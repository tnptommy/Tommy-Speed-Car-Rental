<?php
$userDataFile = 'data/user_data.json';
if (file_exists($userDataFile)) {
    file_put_contents($userDataFile, json_encode([]));
}
http_response_code(200);
?>