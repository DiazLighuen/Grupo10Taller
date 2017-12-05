<?php

class HistorialView extends TwigView{

    public function detalle_carrito($params){
        $template = $this->getTwig()->loadTemplate("detalle_carrito.html.twig");
        $template->display($params);
    }
    public function listar_historial($params){
        $template = $this->getTwig()->loadTemplate("historial.html.twig");
        $template->display($params);
    }
}