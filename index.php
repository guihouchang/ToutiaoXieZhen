<?php

function login($token, $platform = "ios")
    {
       /*
             * 请将以下值改为7659 开放平台提供的AppKey(ios)或者HI_GAMEKEY(android)值
             */
            $AppKey = 'a7aed67ffb5bc1c74f78f627b7cd9c32';
            $url = [
                'ios' => 'http://f_signin.bppstore.com/loginCheck.php',
                'android' => 'http://api.hisdk.7659.com/user/get',
                ];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url[$platform]);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true );
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
                'tokenKey' => $token,
                'sign' => md5($AppKey . $token),
                ]));
            $return = [];
            $return['data'] = curl_exec($curl);
            $return['code'] = curl_errno($curl);
            $return['msg'] = curl_error($curl);
            curl_close($curl);

            if ($return["code"] == 0)
            {
               return  json_decode($return['data'], true);
            }

            #return $return['code'] ? $return : json_decode($return['data'], true);
    }
    $token = '05a1626e4a584e49d0cf0f0e1b666052';
   var_dump(login($token, "ios"));