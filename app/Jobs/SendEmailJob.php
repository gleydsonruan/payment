<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const FROM_EMAIL = 'noreply@payment.com';

    protected $to;
    protected $subject;
    protected $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $subject, $body)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendEmail();
    }

    /**
     * Send email
     * 
     * @return bool
     */
    public function sendEmail() : bool
    {
        $email = [
            'from' => self::FROM_EMAIL,
            'to' => $this->to,
            'subject' => $this->subject,
            'body' => $this->body,
        ];

        try {
            $response = Http::retry(3, 100)
                ->post($this->getUrl(), $email);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Get mail service url
     * 
     * @return string
     */
    protected function getUrl() : string
    {
        return env(
            'MAIL_SERVICE_URL',
            'http://o4d9z.mocklab.io/notify'
        );
    }
}
