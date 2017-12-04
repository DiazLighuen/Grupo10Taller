<?php

class CarritoView extends TwigView{

    public function listar_carrito($params){
      $template = $this->getTwig()->loadTemplate("listar_carrito.html.twig");
      $template->display($params);
    }
}
