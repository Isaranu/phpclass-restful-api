<?php 
    include('../user_class.php');
    $data = $getArray['data'];
    if($getArray['mode'] === 'register'){
        #สมัครสมาชิกใหม่
        if($data){
            
            $status = true;

            $key_for_check = array(
                'user_email', 
                'user_name', 
                'user_password', 
                'user_citizen',
                //'user_citizen_img', 
                'user_birthday',
                'user_postcode',
                'user_province',
                'user_district',
                'user_subdistrict',
                'user_address',
                'user_tel'
            );

            for($i = 0; $i < count($key_for_check); $i++){
                if(!$data[$key_for_check[$i]]){
                    $status = false;
                    continue;
                }
            }

           $check_email = manageUser::regisCheckEmail($data['user_email']);
           $check_user = manageUser::regisCheckUser($data['user_name']);
           if($check_email){
               Tools::error_json_api('R100003', 'อีเมล์นี้มีผู้ใช้งานแล้ว !');
           }else if($check_user){
               Tools::error_json_api('R100004', 'ชื่อผู้ใช้นี้ถูกใช้งานแล้ว !');
           }else{
                if($status){
                    $data['user_regis_date'] = $today;
                    $d = date("dm");
                    $data['user_token'] = 'U'.$d.'00000'.md5($data['user_name']);
                    $data['user_no'] = 'U'.$d.Tools::randomNumber(10);
                    $data['user_password'] = md5(base64_encode($data['user_password']));
                    manageUser::insertUser($data);
                }else{
                    Tools::error_json_api('R100002', 'โปรดระบุข้อมูลให้ครบ !');
                }
           }
           
        }else{
            Tools::error_json_api('R100001', 'โปรดระบุ data ของข้อมูลสมาชิก');
        }
    } else if($getArray['mode'] === 'search'){
        #ค้นหาสมาชิก
        if($data){
            $status = false;
            if($data['searchBy'] === 'user_email'){
                $status = true;
            }else if($data['searchBy'] === 'user_name'){
                $status = true;
            }
            if($data['searchBy'] === ''){
                Tools::error_json_api('S100002', 'โปรดกำหนดค่า SearchBy !');
                $status = false;
            }else
            if($data['searchValue'] === ''){
                Tools::error_json_api('S100001', 'โปรดกำหนดค่า SearchValue !');
                $status = false;
            }

            if($status){
                manageUser::searchUser($data['searchBy'], $data['searchValue']);
            }
        }else{
            Tools::error_json_api('S100000', 'โปรดกำหนดค่า data !');
        }
    } else if($getArray['mode'] === 'update'){
        #อัพเดทข้อมูลสมาชิก
        if($data){
            $status = true;
            $key_for_update = array(
                'user_email', 
                'user_name', 
                'user_password', 
                'user_citizen_img', 
                'user_birthday',
                'user_postcode',
                'user_province',
                'user_district',
                'user_subdistrict',
                'user_address',
                'user_tel',
                'user_status'
            );

            $update_data = array();
            for($i = 0; $i < count($key_for_update); $i++){
                if($data[$key_for_update[$i]]){
                    $update_data[$key_for_update[$i]] = htmlspecialchars($data[$key_for_update[$i]]);
                }
            }

            if(!empty($update_data)){
                $update_data['user_no'] = $data['user_no'];
                $update_data['user_token'] = $data['user_token'];
                if($update_data['user_no'] && $update_data['user_token']){
                    manageUser::updateUser($data);
                }else{
                    Tools::error_json_api('UD100002', 'โปรดกำหนดค่า data เพื่ออ้างอิง user !');
                }
                
            }else{
                Tools::error_json_api('UD100001', 'โปรดกำหนดค่า data ตาม format !');
            }

        }else{
            Tools::error_json_api('UD100000', 'โปรดกำหนดค่า data !');
        }
    } else if($getArray['mode'] === 'login'){
        if($data){
            $status = false;
            $login_mode = 'user_email';
            if($data['user_email']){
                if($data['user_password']){
                    $login_mode = 'user_email';
                    $status = true;
                }else{
                    Tools::error_json_api('L100003', 'โปรดระบุรหัสผ่าน !');
                }
            }else if($data['user_name']){
                if($data['user_password']){
                    $login_mode = 'user_name';
                    $status = true;
                }else{
                    Tools::error_json_api('L100003', 'โปรดระบุรหัสผ่าน !');
                }
            }else{
                Tools::error_json_api('L100002', 'ค่าที่กำหนดไม่ถูกต้อง !');
            }
            if($status){
                $password = md5(base64_encode($data['user_password']));
                
                manageUser::loginhUser($login_mode, $data[$login_mode], $password);
            }
        }else{
            Tools::error_json_api('L100000', 'โปรดกำหนดค่า data !');
        }
    }else{
        Tools::error_json_api('N100000', 'โปรดกำหนด mode !');
    }
?>