<?php

class AutomovilListarView extends TwigView{

    public function listar_automovil($params){
        $template = $this->getTwig()->loadTemplate("listar_automoviles.html.twig");
        $template->display($params);
    }
}