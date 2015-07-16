<?php
namespace Api\Bundle\Mailer;

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

    // ...
}
