<?php


class LoginView extends TwigView
{

    public function show($hospitalName)
    {

        echo self::getTwig()->render('login.html.twig', array('hospitalName' => $hospitalName));

    }
}
