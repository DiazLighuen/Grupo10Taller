<?php

class HotelListarView extends TwigView{

    public function listar_hotel($params){
        $template = $this->getTwig()->loadTemplate("listar_hoteles.html.twig");
        $template->display($params);
    }
}