<?php

/**
 * Make the API call
 *
 * @param string $url
 * @param array  $data
 * @param string $expected_code
 * @param string $method
 * @param array  $extra_options
 *
 * @return mixed
 */
function makeApiCall($url, $data, $expected_code, $method = null, $extra_options = [])
{
    // e-satisfaction API base url
    $baseUrl = 'https://api.e-satisfaction.com/v3.2';

    $auth = 'YOUR_TOKEN';
    $domain = 'YOUR_WORKING_DOMAIN';
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => sprintf('%s/%s', $baseUrl, $url),
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => ['esat-auth: ' . $auth, 'esat-domain: ' . $domain],
    ]);
    if ($method) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    } else {
        curl_setopt($ch, CURLOPT_POST, 1);
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    foreach ($extra_options as $option => $value) {
        curl_setopt($ch, $option, $value);
    }
    $res = curl_exec($ch);
    if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == $expected_code) {
        return $res;
    }

    return false;
}

/**
 * Create Queue Item
 *
 * No method parameter means POST
 */
makeApiCall('/q/questionnaire/QUESTIONNAIRE_ID/pipeline/PIPELINE_ID/queue/item', [
    'responder_channel_identifier' => 'test@esat.com',
    'locale' => null,
    'send_time' => '2018-01-01T00:00:00+03:00',
    'metadata' => [
        'questionnaire' => [
            'transaction_id' => '12345',
            'transaction_date' => '14/08/2019',
            'store_id' => 'STR123',
        ],
        'responder' => [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'phone_number' => '6912345678',
        ],
    ],
], '201');
