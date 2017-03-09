<?php

namespace fw_Gunicorn\kernel\templates;

use fw_Gunicorn\kernel\engine\middleware\Request;

class TemplateList{

    public function __construct($model, Request $request){
        $this->model = $model;

    }
    public function setFilter(){

    }
    public function setConf($field, $template, $limit = 10){

    }
    private function getPagination(){
        return [
            'count_page' => $this->pages,
            'previous_page' => $this->previous,
            'current_page' => $this->current,
            'next_page' => $this->next,
            'range_page' => $this->range,
            'object_list' => $this->rs
        ];
    }
}