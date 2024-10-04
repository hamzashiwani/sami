<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Exception;

class FirebaseHelper
{
    public static function getAccessToken($serviceAccountPath)
    {
        // Decode the service account JSON file
        $key = json_decode(file_get_contents($serviceAccountPath), true);

        $postdata = [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => self::getToken($key['private_key'], $key['client_email'])
        ];

        // Initialize cURL for token request
        $ch = curl_init('https://www.googleapis.com/oauth2/v4/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);

        // Debugging: Log the raw response
        error_log('API Response: ' . $response);

        $token = json_decode($response, true);

        // Check if the response contains an error
        if (isset($token['error'])) {
            error_log('API Error: ' . print_r($token, true));
            throw new Exception('API Error: ' . $token['error_description']);
        }

        // Check if the access token is present
        if (isset($token['access_token'])) {
            return $token['access_token'];
        } else {
            error_log('Decoded Response: ' . print_r($token, true));
            throw new Exception('Access token not found in the API response.');
        }
    }

    public static function getToken($privateKey, $clientEmail)
    {
        // Token generation payload
        $now = time();
        $payload = [
            'iat' => $now,
            'exp' => $now + 3600, // Expiration time (1 hour)
            'iss' => $clientEmail, // Issuer claim (service account email)
            'aud' => 'https://www.googleapis.com/oauth2/v4/token', // Audience claim
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging' // Scope for FCM
        ];

        // Return encoded JWT token using RS256 algorithm
        return JWT::encode($payload, $privateKey, 'RS256');
    }

    public static function sendMessage($accessToken, $projectId, $message)
    {
        $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        // Initialize cURL for sending FCM message
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['message' => $message]));

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }
        curl_close($ch);

        // Return the decoded response from FCM
        return json_decode($response, true);
    }
}