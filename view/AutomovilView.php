<?php

class AutomovilView extends TwigView{

    public function buscar_automovil($params){
      $template = $this->getTwig()->loadTemplate("buscar_automovil.html.twig");
      $template->display($params);
    }
}
