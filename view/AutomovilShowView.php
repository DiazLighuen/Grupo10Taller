<?php

class AutomovilShowView extends TwigView{

    public function automovil_show($params){
      $template = $this->getTwig()->loadTemplate("automovil_show.html.twig");
      $template->display($params);
    }
}
