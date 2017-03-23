<?php
    include('manage_class.php');
    
    class manageUser extends manageSql{

        function loginhUser($mode, $user, $pass){
            $where = $mode.' = "'.$user.'" AND user_password ="'.$pass.'"';
           
            $data = parent::select('user',' * ',$where,'','');
            if($data){
                self::userResponse($data[0]);
            }else{
                Tools::error_json_api('L100004', 'เข้าสู่ระบบไม่สำเร็จ !');
            }
        }

        function regisCheckEmail($email){
            $status = parent::select('user',' user_email ','user_email = "'.$email.'"','','');
            return $status ? true : false;
        }

        function regisCheckUser($user_name){
            $status = parent::select('user',' user_name ','user_name = "'.$user_name.'"','','');
            return $status ? true : false;
        }

        function searchUser($searchBy, $searchValue){
            $data = parent::select('user',' * ',$searchBy.' = "'.$searchValue.'"','','');
            if($data){
                self::userResponse($data[0]);
            }else{
                Tools::error_json_api('UR1001', 'ไม่พบข้อมูลสมาชิก !');
            }
        }

        function updateUser($data){
            $status = parent::select('user',' * ','user_no = "'.$data['user_no'].'" AND user_token = "'.$data['user_token'].'"','','');
            if($status){
                $status = parent::update('user', $data, 'user_no' , $data['user_no']);
                if($status){
                    $newData = parent::select('user',' * ','user_no = "'.$data['user_no'].'" AND user_token = "'.$data['user_token'].'"','','');
                    self::userResponse($newData[0]);
                }else{
                    Tools::error_json_api('UD2001', 'ไม่สามารถอัพเดทข้อมูลได้ !');
                }
                
            }else{
                Tools::error_json_api('UD2000', 'ไม่พบข้อมูลสมาชิก !');
            }
        }

        function insertUser($data){
            $status = self::insert('user', $data);
            if($status){
                self::userResponse($data);
            }else{
                Tools::error_json_api('UR1000', 'ไม่สามารถสมัครสมาชิกได้ !');
            }
        }

        # Response template
        function userResponse($data){
            $arr = array();
            $arr['success'] = 1;
            $arr['response']['user_regis_date'] = $data['user_regis_date'];
            $arr['response']['user_token'] = $data['user_token'];
            $arr['response']['user_no'] = $data['user_no'];
            $arr['response']['user_name'] = $data['user_name'];
            $arr['response']['user_email'] = $data['user_email'];
            $arr['response']['user_birthday'] = $data['user_birthday'];
            $arr['response']['user_citizen'] = $data['user_citizen'];
            $arr['response']['user_tel'] = $data['user_tel'];
            $arr['response']['user_addr']['user_postcode'] = $data['user_postcode'];
            $arr['response']['user_addr']['user_province'] = $data['user_province'];
            $arr['response']['user_addr']['user_district'] = $data['user_district'];
            $arr['response']['user_addr']['user_subdistrict'] = $data['user_subdistrict'];
            $arr['response']['user_addr']['user_address'] = $data['user_address'];
            Tools::converToJson($arr);
        }
    }
?>