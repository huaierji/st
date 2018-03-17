<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/31
 * Time: 14:54
 */

namespace Api\Controller;

use Think\Controller;
use Api\JWT\JWT;
Vendor('Api.JWT.JWT');
class ApiController extends Controller
{

    //空操作
    public function _empty()
    {
        header("HTTP/1.0 404 Not Found");
        $static = array("404" => "非法请求");
        $this->ajaxReturn($static);

    }

    //获取token
   /* public function getToken($phone)
    {


        $key = "jfdksajfkldsajfkdjsaklfdajffdsafdsfdsfdsfdsklfdsafdsafdsafdsdsajlkfdsa";
        $token = array(
            'uid' => 1050,
            'username' => 'baby',
        );

        $jwt = JWT::encode($key, $token);

        print_r($jwt) ;
    }*/

}