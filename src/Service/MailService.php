<?php


namespace App\Service;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
        $email = (new Email())
            ->from('moussabaka@openkanz.com') // Update with your email address
            ->to($to)
            ->subject($subject)
            ->html($body);

        $this->mailer->send($email);
    }

}