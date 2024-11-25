<?php

namespace App\Services;

use Aws\Credentials\Credentials;
use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;
use Illuminate\Support\Facades\Log;

class NotificationService
{

    private static ?NotificationService $notificationServiceInstance = null;

    private ?SqsClient $client = null;

    public static function getNotificationService(): ?NotificationService
    {
        if(self::$notificationServiceInstance == null) {
            self::$notificationServiceInstance = new self();
        }

        return self::$notificationServiceInstance;
    }

    public function triggerSqsEmail($sender_email, $email_body): bool
    {
        try {

            if(empty($sender_email) || empty($email_body)) {
                Log::error('NotificationService::sendEmailToSqs');
                Log::error('Sender email or the email body is invalid');
                return false;
            }

            $this->_sqsConnector();

            $this->client->sendMessage([
                'QueueUrl' => env('AWS_EMAIL_SQS_QUEUE'),
                'MessageBody' => json_encode([
                    'to_email' => $sender_email,
                    'message' => $email_body
                ])
            ]);

            return true;

        } catch (AwsException $e) {
            Log::error('NotificationService::sendEmailToSqs');
            Log::error($e);
            return false;
        }

    }

    private function _sqsConnector(): void
    {
        $credentials = new Credentials(env('AWS_ACCESS_KEY_ID'), env('AWS_SECRET_ACCESS_KEY'));

        $this->client = new SqsClient([
            'region' => 'us-east-1',
            'version' => '2012-11-05',
            'credentials' => $credentials
        ]);
    }

}
