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
    protected $fillable = ['title', 'description', 'file', 'file_screenshot','file_type','topic'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notifications')->withTimestamps();
    }

    public static function sendPushNotification($topic, $title, $message, $id = "", $trigger_type = "home", $trigger_id = "", $job_id = "", $source = "")
    {
        try {
            $serviceAccountPath = storage_path('app/public/sami-pharma-37634-firebase-adminsdk-6e13q-acb3f98c19.json');
            FirebaseHelper::initializeFirebase($serviceAccountPath);
            $response = FirebaseHelper::sendPushNotification(
                $topic,
                $title,
                $message,
                $id,
                $trigger_type,
                $trigger_id
            );
            // Handle response if needed
            $success = isset($response['name']) ? 1 : 0;
            $failure = $success ? 0 : 1;

        } catch (Exception $ex) {
            // Handle exceptions
            throw $ex;
        }
    }
}
