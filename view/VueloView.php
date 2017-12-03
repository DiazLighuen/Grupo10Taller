<?php

class VueloView extends TwigView{

    public function buscar_vuelo($params){
      $template = $this->getTwig()->loadTemplate("buscar_vuelo.html.twig");
      $template->display($params);
    }
}
