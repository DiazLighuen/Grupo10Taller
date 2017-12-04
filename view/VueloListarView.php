<?php

class VueloListarView extends TwigView{

    public function listar_vuelos($params){
        $template = $this->getTwig()->loadTemplate("listar_vuelos.html.twig");
        $template->display($params);
    }
}