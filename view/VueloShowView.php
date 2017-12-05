<?php

class VueloShowView extends TwigView{

    public function vuelo_show($params){
      $template = $this->getTwig()->loadTemplate("vuelo_show.html.twig");
      $template->display($params);
    }
}
