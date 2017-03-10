<?php

namespace fw_Gunicorn\kernel\templates;

use fw_Gunicorn\kernel\classes\abstracts\aController;
use fw_Gunicorn\kernel\engine\dataBase\ConexionDataBase;
use fw_Gunicorn\kernel\engine\middleware\Request;

class TemplateList {
    private $count_rows = 10;
    private $page_current = 1;
    private $obj_controller;

    private $pages = null;
    private $rows = 0;
    private $previous = null;
    private $current = null;
    private $next = null;
    private $rs = null;

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

        if(isset($paramets['filter']))
            $filter = ' WHERE '.$paramets['filter'];
        else
            $filter = '';

        /* determinar la cantidad de registro */
        $sql = "SELECT count(*) 
                FROM ".$paramets['models'].$filter;

        $this->rows = $this->obj_controller->DB()->getValue($sql);
        $this->pages = ceil($this->rows  / $this->count_rows);
        $desde = ($this->page_current - 1) * $this->count_rows;
        $limit = " LIMIT ".$this->count_rows." offset ".$desde." ";


        $models = 'SELECT '.$paramets['fields'];
        $fields = ' FROM '.$paramets['models'];
        $query = $models . $fields . $filter . $limit;

        try{
            $rs = $this->obj_controller->DB()->getArray($query);
            if(empty($rs))
                return;
            $this->rs = $rs;
        }catch (\PDOException $e){
            die($e->getMessage());
        }
    }
    public function setConf(Array $paramets_query, aController $obj, $count_rows = 10){
        $this->obj_controller = $obj;
        if($count_rows != 10)
            $this->count_rows = $count_rows;

        $this->createQuery($paramets_query);
    }
    /*private function execPaginator(Array $rs){
        echo $this->page_current;
    }*/
    private function getPagination(){
        if($this->pages == 1){
            $this->next = 0;
            $this->previous = 0;
        }else if($this->pages < $this->page_current){

            $this->next = $this->page_current + 1;
            if($this->next > $this->pages)
                $this->next = $this->pages;

            if($this->page_current == 1)
                $this->previous = 0;
        }else{
            $this->previous = $this->page_current - 1;
            $this->next = $this->page_current + 1;
            if($this->next > $this->pages)
                $this->next = 0;
        }
        $data = [

        ];
        return [
            'count_page' => $this->pages,
            'previous_page' => $this->previous,
            'current_page' => $this->page_current,
            'next_page' => $this->next,
            'object_list' => $this->rs,
            'rows' => $this->rows
        ];
    }
    public function render($template, $context = null){

        $context_local = $this->getPagination();
        if(!empty($context)){
            $context_local = array_merge($context_local,$context) ;
        }
        $this->obj_controller->render($template, $context_local);
    }
}