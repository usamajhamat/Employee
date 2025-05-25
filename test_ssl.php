<?php
$url = "https://cloud.tanaza.com/apis/v3.0/11F975B0CFD3F548776B8C9C738605D1/A9E3F295D529FABA248B0790DAA30D9F/status";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CAINFO, "C:\\wamp64\\bin\\php\\php8.2.26\\cacert.pem");
$response = curl_exec($ch);
if ($response === false) {
    echo "cURL Error: " . curl_error($ch);
} else {
    echo "Success: " . substr($response, 0, 500);
}
curl_close($ch);
?>