<?php


class HomeView extends TwigView
{

    public function show($hospitalData)
    {

        echo self::getTwig()->render('home.html.twig', array(
            'hospitalData' => $hospitalData,
            'hospitalName' => $hospitalData->getName()));

    }

}
