<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FirebaseHelper;
use Exception;

class Notification extends Model
{
    use HasFactory;

    protected $guarded;

    protected $table = 'notifications';
    protected $fillable = ['sender_id', 'receiver_id', 'title', 'message','trigger_id','trigger_type','device_type','success','failure' ,'image','is_read','job_id','source'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notifications')->withTimestamps();
    }

    public static function sendPushNotification($fcm_token, $title, $message, $id = "", $trigger_type = "home", $trigger_id = "", $job_id = "", $source = "")
    {
        try {
            $projectId = 'sami-pharma-37634';
            $serviceAccountPath = public_path('sami-pharma-37634-firebase-adminsdk-6e13q-13b83dc6a9.json');

            $accessToken = FirebaseHelper::getAccessToken($serviceAccountPath);

            $messagePayload = [
                'topic' => "global",
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
            ];

            $response = FirebaseHelper::sendMessage($accessToken, $projectId, $messagePayload);
            // Handle response if needed
            $success = isset($response['name']) ? 1 : 0;
            $failure = $success ? 0 : 1;

        } catch (Exception $ex) {
            // Handle exceptions
            throw $ex;
        }
    }
}
