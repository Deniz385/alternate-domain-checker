<?php
header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); 
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests are supported.']);
    exit;
}

$domain = $_POST['domain'] ?? null;
if (!$domain || !is_string($domain) || !trim($domain)) {
    http_response_code(400); 
    echo json_encode(['status' => 'error', 'message' => 'The `domain` parameter is missing or invalid.']);
    exit;
}
$domain = trim($domain);

$url = "https://www.atakdomain.com/service/domain/check-availability";

$postData = json_encode([
    'domain'  => $domain,
    'request' => $domain,
    'valid'   => true
]);

// A hardcoded cookie string, likely captured from a browser session.
// Note: This is fragile and may expire or become invalid.
$cookie = implode('; ', [
    'CartOrder=0x0100000064187852c600ca8e0956010e1b8ffe4b6741c7e99eaa3b24c706a51781d91392',
    'SID=dadtvjvlaiis5aycfasv2ahx',
    '_gcl_gs=2.1.k1$i1753353318$u108670162',
    '_gcl_au=1.1.282490647.1753353323',
    '_ga=GA1.1.2128102375.1753353323',
    'twk_idm_key=yVmhU05JglPfjrgn1bJYG',
    'cto_bundle=TIN3Ml84MEFZZFI2UVlzMkc5T0RhZkxyYjFVVExtMEp2SURianpQZWZ0TWNVSVFEWDJZWWZTNkJacHlGeElXcGJnWk8lMkZORHczNmI5dGZPOXpFandnOUlSbDFmbUlGbFBMQVdLbXJCOVhXa3clMkJERHNUTzJsSGFFcE5pN2tLSiUyRjBJdVZMRVJCWklCU29zUHZMb1NqJTJGTHQwNkI1NkljMVRkcXJCVHBhTzVYQzFidnVLRSUzRA',
    '_gcl_aw=GCL.1753362573.EAIaIQobChMIrYSr_qXVjgMVm52DBx0BNBnBEAAYASAAEgLNw_D_BwE',
    '_ga_K1RSBYPQTB=GS2.1.s1753361682$o2$g1$t1753362573$j58$l0$h0'
]);

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $postData,
    CURLOPT_HTTPHEADER     => [
        'Accept: application/json, text/javascript, */*; q=0.01',
        'Accept-Language: tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7',
        'Content-Type: application/json',
        'Cookie: ' . $cookie,
        'Origin: https://www.atakdomain.com',
        'Referer: https://www.atakdomain.com/domain-sorgulama',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/5.0 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36'
    ],
    CURLOPT_TIMEOUT        => 20
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($response === false) {
    http_response_code(500); 
    echo json_encode(['status' => 'error', 'message' => 'cURL request failed: ' . $error]);
    exit;
}

if ($httpCode !== 200) {
    http_response_code(502); 
    echo json_encode(['status' => 'error', 'message' => 'Invalid HTTP code received from the API.', 'httpCode' => $httpCode, 'raw_response' => $response]);
    exit;
}

$data = json_decode($response, true);

if (isset($data['d']['AdditionalData'])) {
    
    $html = $data['d']['AdditionalData'];

    if (str_contains($html, "add('$domain', this)")) {
        echo json_encode([
            'status' => 'success',
            'data'   => ['status'  => 'available', 'message' => "$domain is available!"]
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'data'   => ['status'  => 'not-available', 'message' => "$domain is already taken."]
        ]);
    }
} else {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Expected data structure not received from the API.', 'raw' => $response]);
}
?>
