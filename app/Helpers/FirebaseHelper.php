<?php

namespace App\Helpers;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\ServiceAccount;
use Exception;

class FirebaseHelper
{
    protected static $messaging;

    public static function initializeFirebase($serviceAccountPath)
    {
        // Initialize the Firebase Messaging service
        self::$messaging = (new Factory)
            ->withServiceAccount($serviceAccountPath)
            ->createMessaging();
    }

    public static function sendPushNotification($fcm_token, $title, $message, $id = "", $trigger_type = "home", $trigger_id = "", $job_id = "", $source = "")
    {
        try {
            // Construct message payload using Kreait's CloudMessage
            $messagePayload = CloudMessage::fromArray([
                'topic' => "Global",
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                ],
                'data' => [
                    'id' => (string)$id,
                    'title' => $title,
                    'description' => $message,
                    'trigger_type' => $trigger_type,
                    'trigger_id' => (string)$trigger_id,
                    'job_id' => (string)$job_id,
                    'source' => $source,
                    'is_read' => (string)0,
                ],
            ]);

            // Send message
            $response = self::$messaging->send($messagePayload);
            
            // Optionally, log or return the response
            return $response;

        } catch (Exception $ex) {
            // Handle exceptions
            throw $ex;
        }
    }
}
