<?php

class HotelView extends TwigView{

    public function buscar_hotel($params){
      $template = $this->getTwig()->loadTemplate("buscar_hotel.html.twig");
      $template->display($params);
    }
}
