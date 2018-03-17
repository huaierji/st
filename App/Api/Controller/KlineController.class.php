<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/31
 * Time: 18:28
 */

namespace Api\Controller;
class KlineController extends ApiController
{

    public function one()
    {
        $key = "jfdksajfkl;dsajfkdjsaklfdajffdsafdsfdsfdsfdsklfdsafdsafdsafdsdsajlkfdsa";
        $token = array(
            'uid' => 1050,
            'username' => 'baby',
        );

        $jwt = JWT::encode($token, $key);
        echo $jwt;
    }


    public function two()
    {
        $key = "jfdksajfkl;dsajfkdjsaklfdajffdsafdsfdsfdsfdsklfdsafdsafdsafdsdsajlkfdsa";
        $str = isset($_GET['str']) ? $_GET['str'] : '';
        if ($str == '') {
            exit('empty');
        }

        $decoded = JWT::decode($jwt, $key, array('HS256'));
        if (!is_object($decode)) {
            echo "error";
        } else {
            $arr = json_decode(json_encode($decoded), true);
            dump($arr);
            $uid = $arr['uid'];

        }
    }
}