<?php

namespace fw_Gunicorn\kernel\templates;

use fw_Gunicorn\kernel\classes\abstracts\aController;
use fw_Gunicorn\kernel\engine\dataBase\ConexionDataBase;
use fw_Gunicorn\kernel\engine\middleware\Request;

class TemplateList extends aController {
    private $limit = 0;
    private $page_current = 1;
    private $obj_controller;
    public function __construct(Request $request){
        $this->request = $request;

        /* detecta si existe la variable page por el metodo GET */
        if(empty($this->request->_get('page'))){
            $this->page_current = 1;
        }else{
            $this->page_current = $this->request->_get('page');
        }
    }
    private function createQuery(Array $paramets){
        $query = '';

        $models = 'SELECT '.$paramets['fields'];
        $fields = ' FROM '.$paramets['models'];

        if(isset($paramets['filter']))
            $filter = ' WHERE '.$paramets['filter'];
        else
            $filter = '';

        $query = $models . $fields . $filter;

        try{
            $rs = $this->obj_controller->DB()->getArray($query);
            if(empty($rs))
                return;
            $this->execPaginator($rs);
        }catch (\PDOException $e){
            die($e->getMessage());
        }
    }
    public function setConf(Array $paramets_query, aController $obj, $limit = 10){
        $this->obj_controller = $obj;
        if($limit != 10)
            $this->limit = $limit;

        $this->createQuery($paramets_query);
    }
    private function execPaginator(Array $rs){
        print_r($rs);
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
    public function render($template, $context = null){
        $context_local = $this->getPagination();
        print_r($context_local);
        $this->render($template);
    }
}