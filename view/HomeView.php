<?php

class HomeView extends TwigView {
    
    public function show() {
        echo self::getTwig()->render('prueba.twig.html');   
    }  
}
