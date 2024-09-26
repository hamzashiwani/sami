<?php

namespace App\Http\Controllers\Api;


use App\Events\JobEvent;
use App\Mail\DefaultMail;
use App\Models\MailTemplate;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as GoogleNotification;

/**
 *
 */
class BaseController extends Controller
{
    protected $ips, $user, $token, $resultArray, $postData = [];
    protected $flag = true;
    protected $statusCode = 200;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $identifier
     * @param $to
     * @param $params
     * @param null $to_name
     * @param null $reply_to
     * @param null $cc
     * @param null $bcc
     * @param null $attachment
     * @param null $attachment_name
     * @param null $subject
     * @return bool|string
     *
     * This Function is for sending email with Model and custom Blade Design
     */


    function __sendNotification($device_id, $title, $body, $screen = NULL, $job_data = NULL)
    {
        try {
            $factory = (new Factory())->withServiceAccount(resource_path('firebase.json'));
            $messaging = $factory->createMessaging();
//            $messaging = app('firebase.messaging');
            $message = CloudMessage::withTarget('token', $device_id)
                ->withNotification(GoogleNotification::create($title, $body))
                ->withData([
                    'job' => $job_data,
                    'screen' => $screen
                ]);
            $messaging->send($message);
        } catch (\Exception $e) {
            var_dump($e->getMessage());die;
            return response()->json(['status' => 'error', 'msg' => 'Unable to send notification, customer Device Id Was Not Found']);
        }
    }

    public function notification($user_id, $care_id, $job_id, $send_to, $type, $title, $description)
    {
        Notification::create([
            'user_id' => $user_id,
            'driver_id' => $care_id,
            'job_id' => $job_id,
            'send_to' => $send_to,
            'type' => $type,
            'title' => $title,
            'description' => $description
        ]);
    }

    /**
     * @param null $customer_id
     * @param null $care_giver_id
     * @param null $jobData
     */
    public function jobPusher($customer_id = null, $care_giver_id = null, $jobData = null)
    {
        $data['customer'] = User::find($customer_id);
        $data['care_giver'] = User::find($care_giver_id);
        broadcast(new JobEvent($data, $jobData))->toOthers();
    }

    /**
     * @param $identifier
     * @param $to
     * @param $params
     * @param null $to_name
     * @param null $reply_to
     * @param null $cc
     * @param null $bcc
     * @param null $attachment
     * @param null $attachment_name
     * @param null $subject
     * @return bool|string
     */
    public function __sendMail($identifier, $to, $params, $to_name = NULL, $reply_to = NULL,
                               $cc = NULL, $bcc = NULL, $attachment = NULL, $attachment_name = NULL, $subject = NULL)
    {
        $template = MailTemplate::where('identifier', $identifier)->first();
        $mail_body = $template->body;
        $mail_wildcards = explode(',', $template->wildcards);
        $mail_wildcard_values = [];
        foreach ($mail_wildcards as $value) {
            $value = str_replace(['[', ']'], '', $value);
            $mail_wildcard_values[] = $params[$value];
        }
        $mail_body = str_replace($mail_wildcards, $mail_wildcard_values, $mail_body);
        try {
            Mail::to($to)->send(new DefaultMail($mail_body, $template->subject));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

    /**
     * @param $request
     * @param $folder
     * @return array
     */
    public function uploadImage($image, $folder)
    {
        if ($image != '') {
            $file = $image;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename = strtotime(date('y-m-d H:i:s')) . '.' . $extension;
            $dataa = $file->move($folder, $filename);
            $extra['size'] = (!empty($dataa->getSize())) ? $dataa->getSize() : "";
            $data = $filename;
            sleep(1);
            return array('status' => true, 'message' => 'Image Uploaded', 'data' => $data, 'extra' => $extra);
        }
    }

    /**
     * @param string $message
     * @return mixed
     * 200: OK. The standard success code and default option.
     */
    public function respondSuccess($message = 'Success!')
    {
        return $this->setStatusCode(200)->respondWithError([], true, $message);
    }

    /**
     * @param array $errors
     * @param bool $status
     * @param string $message
     * @return mixed
     */
    public function respondWithError($errors = [], $status = false, $message)
    {
        return $this->respond([], $errors, $status, $message);
    }

    /**
     * @param $data
     * @param array $headers
     * @return mixed
     */
    public function respond($data = [], $errors = [], $status, $message, $headers = [])
    {
        return response()->json([
            'statusCode' => $this->getStatusCode(),
            'response' => [
                'data' => $data
            ],
            'message' => $message,
            'status' => $status,
            'errors' => $errors
        ],
            $this->getStatusCode(), $headers);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return \App\Http\Controllers\Api\BaseController
     */
    public function setStatusCode($statusCode)
    {

        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param array $errors
     * @param bool $status
     * @param string $message
     * @return mixed
     * 201: Object created. Useful for the store actions.
     */
    public function respondObjectCreated($errors = [], $status = false, $message = 'Object Created!')
    {
        return $this->setStatusCode(201)->respondWithError($errors, $status, $message);
    }

    /**
     * @param array $errors
     * @param bool $status
     * @param string $message
     * @return mixed
     * 204: No content. When an action was executed successfully, but there is no content to return.
     */
    public function respondNoContent($errors = [], $status = false, $message = 'No Content!')
    {
        return $this->setStatusCode(204)->respondWithError($errors, $status, $message);
    }

    /**
     * @param array $errors
     * @param bool $status
     * @param string $message
     * @return mixed
     * 206: Partial content. Useful when you have to return a paginated list of resources.
     */
    public function respondPartialContent($errors = [], $status = false, $message = 'Partial Content!')
    {
        return $this->setStatusCode(206)->respondWithError($errors, $status, $message);
    }

    /**
     * @param array $errors
     * @param bool $status
     * @param string $message
     * @return mixed
     * 400: Bad request. The standard option for requests that fail to pass validation.
     */
    public function respondBadRequest($errors = [], $status = false, $message = 'Bad Request!')
    {
        return $this->setStatusCode(400)->respondWithError($errors, $status, $message);
    }

    /**
     * @param array $errors
     * @param bool $status
     * @param string $message
     * @return mixed
     * 401: Unauthorized. The user needs to be authenticated.
     */
    public function respondUnauthorized($errors = [], $status = false, $message = 'Unauthorized!')
    {
        return $this->setStatusCode(401)->respondWithError($errors, $status, $message);
    }

    /**
     * @param array $errors
     * @param bool $status
     * @param string $message
     * @return mixed
     * 403: Forbidden. The user is authenticated, but does not have the permissions to perform an action.
     */
    public function respondForbidden($errors = [], $status = false, $message = 'Forbidden!')
    {
        return $this->setStatusCode(403)->respondWithError($errors, $status, $message);
    }

    /**
     * @param array $errors
     * @param bool $status
     * @param string $message
     * @return mixed
     * 404: Not found. This will be returned automatically by Laravel when the resource is not found.
     */
    public function respondNotFound($errors = [], $status = false, $message = 'Records Not Found!', $data = [])
    {
        return response()->json([
            'statusCode' => 204,
            'response' => [
                'data' => $data
            ],
            'message' => $message,
            'status' => $status,
            'errors' => $errors
        ],
            $this->getStatusCode());

    }

    public function respondWithErro404($errors = [], $status = false, $message = 'Records Not Found!', $data = [])
    {
        return response()->json([
            'statusCode' => 404,
            'response' => [
                'data' => $data
            ],
            'message' => $message,
            'status' => $status,
            'errors' => $errors
        ],
            404);

    }

    /**
     * @param array $errors
     * @param bool $status
     * @param string $message
     * @return mixed
     * 405: Method Not Allowed. The request method is known by the server but is not supported by the target resource.
     */
    public function respondMethodNotAllowed($errors = [], $status = false, $message = 'Method Not Allowed!')
    {
        return $this->setStatusCode(405)->respondWithError($errors, $status, $message);
    }

    /**
     * @param array $errors
     * @param bool $status
     * @param string $message
     * @return mixed
     * 500: Internal server error. Ideally you're not going to be explicitly returning this, but if something unexpected breaks, this is what your user is going to receive.
     */
    public function respondInternalError($errors = [], $status = false, $message = 'Internal Error!')
    {
        return $this->setStatusCode(500)->respondWithError($errors, $status, $message);
    }

    /**
     * @param string $message
     * @return mixed
     * 503: Service unavailable. Pretty self explanatory, but also another code that is not going to be returned explicitly by the application.
     */
    public function respondServiceUnavailable($message = 'Service Unavailable!')
    {

        return $this->setStatusCode(503)->respondWithError($message);
    }

    /**
     * @param $userId
     * @param $methodNameCreatedFor
     * @return mixed
     */
    protected function createUserToken($user, $methodNameCreatedFor)
    {
        /** @var TYPE_NAME $user . created user */
        $this->user = $user;
        /** @var TYPE_NAME $methodNameCreatedFor */
        $this->token = $this->user->createToken($methodNameCreatedFor)->plainTextToken;
        /** @var TYPE_NAME $this */
        return $this->token;
    }

    /**
     * @param array $request
     * @param array $validationRules
     * @return mixed
     */
    protected function responseValidation(array $request, array $validationRules)
    {
        /** @var TYPE_NAME $request */
        /** @var TYPE_NAME $validationRules */
        return Validator::make($request, $validationRules);
    }

    /**
     * @param array $errors
     * @return mixed
     */
    protected function validationErrors(array $errors)
    {
        /** @var TYPE_NAME $errors */
        foreach ($errors as $error) {
            /** @var TYPE_NAME $this */
            $this->resultArray[] = $error;
        }
        return $this->resultArray;
    }

    protected function skippedElementArray(array $skippedArray, array $postData)
    {
        foreach ($postData as $key => $value) {
            if (!in_array($key, $skippedArray)) {
                $this->postData[$key] = $value;
            }
        }

        return $this->postData;
    }

    /**
     * @param array $rules
     * @param string $request
     * @return array|int[]
     */
    protected function validateInput($request = [], $rules = [])
    {
        $validator = Validator::make($request, $rules);
        if ($validator->fails()) {
            return [
                'message' => $validator->messages(),
                'error' => 1
            ];
        } else {
            return ['error' => 0];
        }
    }
}
