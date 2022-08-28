<?php
    namespace App\Classes\Services;

    use Symfony\Component\Mailer\Mailer;
    use Symfony\Component\Mailer\Transport;
    use Symfony\Component\Mime\Email;

    class Mail {
        public static function send($emailSender, $emailRecipient, $subject, $message) {
            $transport = Transport::fromDsn('smtp://user:pass@smtp.example.com:port');
            $mailer = new Mailer($transport);

            $email = (new Email())
                ->from($emailSender)
                ->to($emailRecipient)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject($subject)
                //->text('Sending emails is fun again!')
                ->html($message);

            return $mailer->send($email);
        }
    }
