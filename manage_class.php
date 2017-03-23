<?php
    include('api_list.php');
    include('database_config.php');
    $today = date("Y-m-d H:i:s");
    $getArray = array();
    $data = json_decode(file_get_contents('php://input'), true);
    if(!empty($data)){
        $getArray = $data;
    }

    if(!empty($_POST)){
        $getArray = $_POST;
    }
    $data = $getArray['data'];
    
    class jsonManage{
        public function converJson($data){
            $result = json_encode(json_decode($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);   
            return $result;
        }
    }

    class manageRequest extends jsonManage{
        protected function sendRequest($mode, $url, $data){
            $result = false;
            if($mode == 'POST'){
                $new_data = http_build_query($data);
                $curl = curl_init();
                curl_setopt($curl,CURLOPT_URL, $url);
                curl_setopt($curl,CURLOPT_POST, sizeof($new_data));
                curl_setopt($curl,CURLOPT_POSTFIELDS, $new_data);
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
                $result = curl_exec($curl);
                
                $result = parent::converJson($result);         
                
                curl_close($curl);
            }
            return $result;
        }
    }

    class connectSql{
        protected function queryData($sql){
            global $dblink;
            //echo $sql;
            return $qr = mysqli_query($dblink,$sql);
        }
    }

    class manageSql extends connectSql{
        
        public function insert($table, $data=array()){
            $str_insert_field = "";
            $str_insert_value = "";
            foreach($data as $key => $value){
                $str_insert_field .= $key.",";
                $str_insert_value .= '"'.$value.'",';
            }
            $sql = "INSERT INTO ".$table." (".substr($str_insert_field,0,strlen($str_insert_field)-1).") VALUES (".substr($str_insert_value,0,strlen($str_insert_value)-1).")";
            if(parent::queryData($sql)){
                return true;
            }else{
                return false;
            }
        }

        public function update($table, $data=array(), $update_id_field , $update_id_value){
            if ($table == "" || count($data)==0 || $update_id_field=="" || $update_id_value=="") {return false;}
            $str_update_merge_value 	= "";
            foreach($data as $key => $value){$str_update_merge_value .= $key."='".$value."', ";}
            $sql ="UPDATE ".$table." SET ".substr($str_update_merge_value,0,strlen($str_update_merge_value)-2)." WHERE ".$update_id_field." = '".$update_id_value."'";
            if(parent::queryData($sql)){return true;
            }else{return false;}
        }

        public function delete($table, $delete_id_field , $delete_id_value) {
            global $dblink;
            if($table=="" || $delete_id_field=="" || $delete_id_value==""){return false;}
            $sql = "DELETE FROM ".$table." WHERE ".$delete_id_field." = '".$delete_id_value."'";
            if (parent::queryData($sql)) {
                mysqli_query($dblink,"OPTIMIZE TABLE ".$table);
                return true;
            } else {
                return false;
            }
        }

        public function select($table,$select,$where,$order,$limit){
            $where_start="";
            if($where!=""){
                $where_start=" and ";
            }
            if($select==""){
                $select	=	" * ";
            }
            if($limit!=""){
                $limit	=	" LIMIT ".$limit;
            }else{
                $limit = "";
            }
            $sql="SELECT ".$select." FROM `".$table."` WHERE 1 ".$where_start." ".$where." ".$order." ".$limit;
            $Result = array();
            $query = parent::queryData($sql);
            // print_r($query);
            if ($query)
            {
                while($dataResult = mysqli_fetch_assoc($query)){
                    $Result[] = $dataResult;
                }
                
                return $Result;
            }else{
                
                return false;
            }
        }
    }
    
    # Tools class
    class Tools {
        function randomNumber($length){
            $chars = '0123456789';
            $genNumber = '';
            for ( $i = 0; $i < $length; $i++ ){
                $genNumber .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            }
            return $genNumber;
        } 
        # Print error by json
        function error_json_api($code, $status = ''){
            $error = array();
            
            switch($code){
                case '100':
                    $status =  "CAN'T LOADING PAGE";
                    break;
                case '101':
                    $status =  "NOT FOUND";
                    break;
                case '404':
                    $status =  "PAGE ERROR";
                    break;
            };

            $error['success'] = 0;
            $error['responce']['name'] = 'error';
            $error['responce']['code'] = $code;
            $error['responce']['status'] = $status;
            self::converToJson($error);
        }

        # Convert array to json
        function converToJson($arr){
            $data = json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            print_r($data);
        }
    }

    

?>