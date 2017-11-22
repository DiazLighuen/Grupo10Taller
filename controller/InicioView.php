<?php

class InicioView extends TwigView
{

    public function inicio($hospitalName)
    {

        echo self::getTwig()->render('inicio.html.twig', array('hospitalName' => $hospitalName));

    }


    /*
    public function show($hospitalData)
    {

        echo self::getTwig()->render('home.html.twig', array(
            'hospitalData' => $hospitalData,
            'hospitalName' => $hospitalData->getName()));

    }
    */
}
