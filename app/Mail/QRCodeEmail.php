<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QRCodeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $qrCodeUrl;
    public $loginUrl;

    /**
     * Create a new message instance.
     *
     * @param string $qrCodeUrl
     * @param string $loginUrl
     */
    public function __construct($qrCodeUrl, $loginUrl)
    {
        $this->qrCodeUrl = $qrCodeUrl;
        $this->loginUrl = $loginUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.qr_code')
                    ->with([
                        'qrCodeUrl' => $this->qrCodeUrl,
                        'loginUrl' => $this->loginUrl,
                    ]);
    }
}
