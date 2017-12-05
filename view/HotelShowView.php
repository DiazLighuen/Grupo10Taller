<?php

class HotelShowView extends TwigView{

    public function hotel_show($params){
      $template = $this->getTwig()->loadTemplate("hotel_show.html.twig");
      $template->display($params);
    }
}
