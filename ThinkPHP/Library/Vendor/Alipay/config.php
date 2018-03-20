<?php
$config = array (
		//应用ID,您的APPID。
        'app_id' => "2017112100082452",
        //商户私钥
        'merchant_private_key' => "MIIEpQIBAAKCAQEA136QHFiCmLVAJOxLJGz2Gsd691tdc8PI2ce5JU7riMu/IBBFlsD4xhW7OL7h4Q0c7nHH0vTcXjIE01d8I8du+qN28Bm0JkHOK7Pl3bMKCeHV1RV6pFy+nGZlglf+DRSp8xPPNhiF9fldcEJtkZ3ztGxFx6o1FYcltU0FzxwAm0do7XB+lcYOzXspLFTe50ljN18JO3RU67bugcb8Rr3s268PZoQVeKOs+qrMZb6sMUOVXVKOXg6bIYwZvW8aURZbnbGYuJU+xf05VhR6p8a+VWkzTc07SKgQOGxHk4PU4SpdfHKMLAwZJtrrZYnpV747MF3tRRlSF2kHqgmd0hO0dQIDAQABAoIBAQDFhX3EbaCvJpu3/FrX9YQTgbsFldpv7QpiDD55owAjsFXspt2SVCjzMCIe3mAer61QJjy58bU4JfLkYPEpvnjMBh9T7suAsZqv1kKhVqWh7z3YBcsXcudIZlcvBUaZaNJqO0MYW5wWnU53Qnw6GCS0wPWpESt9IxZ+Oy5S62Dm8fvu4tdqPun3RkEECrH0AmRDgH4L/Edr/fCq42eiSuJm/cjT6iyJX3v3LGFZ0vNKkyGDAqiZUp1ZjV6euFItr3dVmr00dVcB+NBf8GjhLqBuRRw6LcMxO44SwbwjT2nexLvuafZJF93GrtDSgb6KFolzd8QWKjWYGvQtj+kWlkuhAoGBAO31nFzRFwhFpw7Q2WUiSK/bFFHpMbfODY8E6ZqhUqy6+ZW6dKIlYcEzB+glmIi5b0onZBZlxWRma+rUQtu9SAjDnwTESsx1/7E//THf/DV2VC/POWxUWf/4GHj3Eu5i+j6GegbuTO7bhws9RSfLktN9qQ62NqMVcMLEBU2FlCxNAoGBAOfU8aag2KbzOHM+uvpnVcUegO0GNuNlvKZXnRWGvdeRwZVU0hXfvwCjPqxLall/vK8cbeJjD2BdxqrHX8fbZgMEv4c6bAn3HFZfCcAKGZBYxCSY9gzP0n8T5phK0Nduezde6wD+h3dieoA1sLYXz1JmAUCZ8oib74gW3hJDtpzJAoGBAOmjfPoZBc9GbEd8weatqcaYiTP/jaRVuTRSc3cyrDUShBoB76CAqaGDFGFYAJNF1sJtOLVRCWWRtqXj8R1FlKOeRtTsUjC8Luh+1oAQ1tY7L1+dzFjT3WYY1xZ2KO1M2z1m1gtWEM15V/euGed/1tK2j7X1LGIlnAhvZHGAFUMFAoGANDHSMiS2gmfyBqhR6nyHZ/jlJ/glNW87WOwS1rzeUwFi5x8QqZIVa6xVOO9Fw16p+XbaGoUY+iZCy0JWAyYXQoi4Ilb56ghzndKJ3G2pscD6cA7sHphPmQK/APyfJlGedintmOy1TSCj2ee8oFKd/7wtfgDrBOyusiIL32rVW1kCgYEAxBsmouxg1LF6Zc08AT7u9+E3Flcfiwccrvm4UUMAvJcpUYelz/jRsvhbnXk+jWLOBJA3nrxyqtwKO/Jqeauw07fmW95mqy+YgPtT4wcqhCngKPJY0+6qU5vcAz7j76plKESAFp5DU+D6LLJYIQzZaW641/wX/XgaB5m0PX6Ri2I=",

        //异步通知地址
        'notify_url' => "http://www.xiaoyunju.com/Home/alipay/notify_alipay",

        //同步跳转  扫码支付后同步跳转到支付宝页面
        'return_url' => "http://www.xiaoyunju.com/Home/alipay/return_alipay",

        //支付宝网关
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
        
        //数据类型
        'format' => 'json',

        //编码格式
        'charset' => "UTF-8",

        //签名方式
        'sign_type'=>"RSA2",

        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
       'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAi+DBXhH243NuqAwPDhuWPKtNIWXXDmS/+BnqIKBVTRGoaiTgpCQjjuIaMKAk4Lo9W9vCENHP/+xx1PaArcyKsdeby6DfxTaoI054h3GWul1eOWaWEl8nP+QkctntCpm6kjxZjYGUutAkpEaQcblKu6wAy7UDtv2NPSLRCMmCP/AUeORwtncKVqEuDprPcjYZoCBrtKsYyQWBTzTO70dhmWoqpVVuyXNfy0T9siRy6V4tx7uRHMMb6dBXfaptL/+aAH1KX/Yt9gYYRB7jkRMtGeK5VZ4n7UlUWoAocrfalr46iADoKvXomfOFxOtGf5reN415O6/Y4WJcP2eqptKJ5wIDAQAB"
);