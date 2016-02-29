<?php
namespace AppBundle\Mailer;

use Symfony\Component\Templating\EngineInterface;

class Manager
{
    protected $mailer;

    protected $templating;

    public function __construct(
        \Swift_Mailer $mailer,
        EngineInterface $templating
    ) {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendValidateEmail($u) {
          $message = \Swift_Message::newInstance()
        ->setSubject('Hello Email')
        ->setFrom('no-reply@pebidi.com')
        ->setTo($u->getEmail())
        ->setBody(
            $this->templating->render(
                'ApiBundle:Emails:validateEmail.html.twig',
                array('name' => $u->getId())
            ),
            'text/html'
        )
        ;
        $this->mailer->send($message);
    }
}
