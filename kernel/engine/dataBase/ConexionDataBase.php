<?php
/**
 *   14/02/2014
 *   autor: Angel Bejarano
 *   Driver para conexiones de base de datos mysql y postgres
 */
namespace fw_Gunicorn\kernel\engine\dataBase;

 use PDO;

class ConexionDataBase extends PDO {
    public function __construct(){
        $params = unserialize(DATABASE);

        $driver = $params['ENGINE'];

        if($driver == 'pgsql' || $driver == 'mysql'){
            $db = $params['NAME'];
            $host = $params['HOST'];
            $port = $params['PORT'];
            $user = $params['USER'];
            $pass = $params['PASSWORD'];

            $dsn = "$driver:dbname=$db;host=$host;port=$port";
            if($driver == 'pgsql'){
                $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_PERSISTENT => true
                            );
            }else{
                $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                PDO::ATTR_PERSISTENT => true,
                                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                            );
            }
        }elseif ($driver == 'sqlite'){
            $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => false
            );
            $db = $params['ROUTE_DB'];
            $dsn = "sqlite:$db";
            $user = null;
            $pass = null;
        }
        try{
            parent::__construct($dsn, $user, $pass, $options);
            restore_exception_handler();

            if(!empty($params['SHEMA'])){
                $this->setSchema($params['SHEMA'], $driver);
            }
            $this->driver = $driver;
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }
    private function setSchema($schema, $driver){
        switch ($driver) {
            case 'mysql':
                //$stmt->query('use '.$schema);
                break;
            case 'pgsql':
                $stmt = parent::prepare('set search_path to '.$schema);
                $stmt->execute();
                break;
            default:
                # code...
                break;
        }
    }
    public function getValue($sql, $data = ""){
        //try{
        $stmt =parent::prepare($sql);

        if(empty($data)){

            $stmt->execute();
        }else{
            $stmt->execute($data);
        }

        $rs = $stmt->fetchAll(PDO::FETCH_BOTH);
        $stmt->closeCursor();
        if(count($rs) > 0){
            return $rs[0][0];
        }else{
            return '';
        }
    }
    public function getArray($sql, $data = ""){
        //try{
        $stmt =parent::prepare($sql);

        if(empty($data)){
            $stmt->execute();
        }else{
            $stmt->execute($data);
        }
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        if(count($data) > 0){
            return $data;
        }else{
            return null;
        }

        //}catch (PDOException $e) {
        //echo $e->getMessage();
        //}
    }
    public function qqUpdate($table,$data){
        $table_data = $this->pairsColumnWithData($data, $table);
        $update = "update $table set ";
        $where = " where ";
        $i = 1;
        $arrayValue = "";
        $field  = "";
        if(!isset($table_data["PK"])){
            throw new Exception("No se puede actualizar el registro por que no se halla definida la clave primaria");
            return false;
        }else{
            $where .= $table_data["PK"][0]. " = ?";
        }

        foreach ($table_data as $key => $val) {

            if($key  != "PK"){
                if(count($table_data) -1 > $i ){

                    $field .= $val[0]." = ?,";
                    $arrayValue .= $val[2]."***";
                }else{
                    $field .= $val[0]." = ? ";
                    $arrayValue .= $val[2];
                }
                $i++;
            }



        }
        $arrayValue .= "***".$table_data["PK"][2];
        $sql = $update.$field.$where;

        return $this->exec($sql,explode("***",$arrayValue));
    }
    public function qqInsert($table, $data){
        $table_data = $this->pairsColumnWithData($data, $table);
        $insert = "insert into $table ";
        $field = "(";
        $value = "values(";
        $arrayValue= "";
        $i = 1;
        $pkfield = '';
        foreach ($table_data as $key => $val) {
            if($key == 'PK'){
                $pkfield = $val[0];
            }
            if(count($table_data) > $i){
                if($i == 1){
                    $value .= "?";
                }else{
                    $value .= ",?";
                }
                $field .= $val[0].",";
                $arrayValue .= $val[2]."***";
            }else{
                if(count($table_data)>1){
                    $field .= $val[0].")";
                    $value .= ",?)";
                    $arrayValue .= $val[2];
                }else{
                    $field .= $val[0].")";
                    $value .= "?)";
                    $arrayValue .= $val[2];
                }

            }
            $i++;

        }
        $sql = $insert.$field." ".$value;

        return $this->exec($sql,explode("***",$arrayValue),"qqInsert", $table, $pkfield);

    }
    public function exec($sql, $data="", $action = "otro", $table = "", $fieldPk = ""){
        $stmt =parent::prepare($sql);
        if(empty($data)){
            return $stmt->execute();
        }else{
            $stmt->execute($data);

            if($action == "qqInsert"){

                if(!empty($fieldPk)){
                    $stmt->closeCursor();
                    $sql = "select max(".$fieldPk.") from $table";
                    return $this->getValue($sql);
                }else{
                    $stmt->closeCursor();
                    $rowCount = $stmt->rowCount();
                    return $rowCount;
                }



            }else{
                $stmt->closeCursor();
                $rowCount = $stmt->rowCount();
                return $rowCount;
            }
        }
    }
    private function getFields($table){
        /* Obtener Conjunto de Campos de la Tabla */
        $COL = array();

        switch ($this->driver) {
            case 'pgsql':
                /*$sql = "select a.column_name as field,data_type as type, constraint_name as key
                        from information_schema.columns a left JOIN information_schema.key_column_usage b on a.COLUMN_NAME = b.column_name
                        where a.table_name = '".$table."'";*/
                $sql = "select a.column_name as Field,data_type as Type, constraint_name  as Key
						from information_schema.columns a LEFT JOIN information_schema.key_column_usage b on a.table_name = b.table_name and a.column_name = b.column_name
						where a.table_name = '".$table."'";
                break;
            case 'mysql':
                $sql = "SHOW COLUMNS FROM " . $table;
                break;
            case 'sqlite':
                $sql = 'PRAGMA table_info('.$table.')';
                break;
            default:
                # code...
                break;
        }

        $rsCol = parent::query($sql);

        $rsCol = $this->getArray($rsCol->queryString);



        foreach ($rsCol as $i => $row) {
            if($this->driver == 'pgsql'){
                $string = $row['key'];
                if(preg_match("/pkey/", $string) || preg_match("/pk/", $string) || preg_match("/PRI/", $string) ){
                    $COL = array_merge($COL, array("PK" => array($row['field'],$row['type'])));
                }else{
                    $COL = array_merge($COL, array("Field_".$i => array($row['field'],$row['type'])));
                }
            }elseif($this->driver == 'mysql'){

            }elseif($this->driver == 'sqlite'){
                if(preg_match('/1/', $row['pk']))
                    $COL = array_merge($COL, array("PK" => array($row['name'],$row['type'])));
                else
                    $COL = array_merge($COL, array("Field_".$i => array($row['name'],$row['type'])));
            }

        }
        return $COL;

    }
    private function pairsColumnWithData($data,$table){

        if(!$table) {
            throw new Exception("No se puede verificar los campos por que tabla no esta configurada");
            return false;
        }
        if(!is_array($data)) {
            throw new Exception("La variable data debe ser una matriz asociativa");
            return false;
        }
        $structTable = $this->getFields($table);

        $arrayAssocc = array();

        /* checar si los campos pasados en el array $data existen en la estructura de la tabla y si existen asociar a esos campos su valor correspondiente */
        foreach ($structTable as $i => $val) {
            if($i == "PK"){
                $this->fieldPK = $val[0];
            }
            foreach ($data as $j => $value) {
                if($val[0] == $j){
                    $arrayAssocc = array_merge($arrayAssocc, array($i => array($val[0],$val[1],$value)));
                }
            }
        }
        return ($arrayAssocc);
    }
    /** manejo de transacciones **/
    public function setBeginTrans(){
        parent::beginTransaction();
    }
    public function setCommit($commit){
        if($commit){
            parent::commit();
        }else{
            parent::rollBack();
        }
    }
}