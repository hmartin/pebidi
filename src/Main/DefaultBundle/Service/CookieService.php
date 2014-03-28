<?php

namespace Main\DefaultBundle\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;


class CookieService
{
    protected $request;
    protected $session;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->session = $this->request->getSession();
    }

    public function setCookie($name, $value)
    {
        $response = new Response();
        $response->headers->setCookie(new Cookie($name, $value));
        $response->send();
    }

    public function has($name)
    {
        if ($this->request->cookies->has($name)){
            return true;
        }
        return false;
    }

    public function get($name)
    {
        if ($this->has($name)){
            return $this->request->cookies->get($name);
        }
        return false;
    }

    public function remove($name)
    {
        $response = new Response();
        $response->headers->clearCookie($name);
        $response->send();
    }
}