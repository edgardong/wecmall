<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-22
 * Time: 13:36
 */

return [
  'app_id' => '2019052965370751', // 沙箱app_id
  'rsa_private_key' => 'MIIEogIBAAKCAQEAvpiUgZ4casf2aY7WUS7JhVBQTNrYyIm+rOsD2ssQqZId9lalGaWtP+3LPgHqDHrZnTMOdasNymeiKFaXa/YkbKgcTJc165MxztqMzf3oLC5C4cQOHD2Q6cV4Oy4qW4rGCbTvHS+7xErsCmb1DIFrsFpqZigforyofF/9qIxbp+qwA/YSHwOSc0LNPN1mDAAUe3CcMGoxxkZl14vKG9aYpWahqT1kgU8gjgBahlbavvPDqV9Lgmmc0lTtekqBOkmwLVHWFoOrxHGiLrNhgsXm2MBeV+LDIE1/3PsJ9hdZ/i+81318ieXyeY/ek/Md5X1Q34H5Zio//vx+qoEyrreAvQIDAQABAoIBAARn7I7ug8OoKbcY+TVZOoldaO3N5VMFdCX1LNqi9RimaxDlwd1M6itfKOKfErUFJgCvTHdF5AS4zpPWJJv3TbJBVJ4kpLd0aKNahATDCNn0m9EyQg0/ExmEWhosIAiLtbZFu31WPCX2gWxgIuwOFdo2zNzdspWFfPObGHcL/5ZrGT4Ex0XiBzdoanjtsUhwZdgj4Mv1Tr/KFt0iFPTLP8skhzOM9I13jTLBALx73gz8aFwe4HQq9nJjV0in37YrzGHK2eir85YmWDu46MveMMsiTCa0CST0y4rUS6/G6li+xnybXue/z2H/OeQqEyFZYyEM7onu6WdzG83LpCu2R4ECgYEA3r8dmKQCb0v22YsJd48/aFdljb+1KySrabOOTxWTJ4309pGDdvvEBcBGhOxcud//oXzx1lT3+xfjM277ffrAZmUnW1mKBJv9M6BbEI+pw0jI4UxJlTwjcY6WBiLZOSyucb9MtQaQJZ7gtUWm0z9gculQsN5+hlCFKGhizj2Ur2ECgYEA2wy95VhQM6O5zqFow7bEAQuWpFm1ac3nlw4IR60ti2LA3EBsWZhzgSbpZy1kUxTy5Ghf1676kXJ+9l6WbYbtZea2GtcFcA3v46VNPrze24X7U6VV88O7H2+13iGL1jAciz26PXBJ8xNbiWNBw+cU2CR7XVZA3/prS/eGUaA0Wt0CgYBrcUxZYGM8RCwh6wr5/MLdFasKoLpGwT1dkxrF0uZUYgkTFkWPkwmOrJxXaZugnQe1YYtVk066c19IY/QzZEyCF7DQQ3RrnMKZsHO8nU+JvwYGhsHHlmaSU/DRrOWSypaqj1f0yeGmA+joB1zc8OkZBKnDZs8pApX4U8G1MccSgQKBgEuT9eEvu1xV3UIiBtegM7h4mx1Rpno/BOzldo3kfpZdGFXmFHxFUXT6TAXBb6AK1lNgf9EXGnUcoBm5QQXBTB+gnqxcDBBBfsd4vVIsMGhlrfWKUkGtAt8x1/owcOMR7odMYPnZyGoJ/8dDa+l/zX1V+QqXPAMLkAHCAaIgOHAxAoGAOu5rr70JaQDKQldHaS+Vw9Y7E3dJyO74UMhw+js1mQJLxdCY+AD0BSw8e2dryj4pmnibv8bAH5ZL4irMpwGQ3+yRgDNhXNJv2XCElWj9BN6wk0/G2W8TsUNdvyfiDzMwsRPUZMrsalEJ6zPuU9blpsrWs24HjG8vOrCj7vEw8oc=',
  'rsa_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvpiUgZ4casf2aY7WUS7JhVBQTNrYyIm+rOsD2ssQqZId9lalGaWtP+3LPgHqDHrZnTMOdasNymeiKFaXa/YkbKgcTJc165MxztqMzf3oLC5C4cQOHD2Q6cV4Oy4qW4rGCbTvHS+7xErsCmb1DIFrsFpqZigforyofF/9qIxbp+qwA/YSHwOSc0LNPN1mDAAUe3CcMGoxxkZl14vKG9aYpWahqT1kgU8gjgBahlbavvPDqV9Lgmmc0lTtekqBOkmwLVHWFoOrxHGiLrNhgsXm2MBeV+LDIE1/3PsJ9hdZ/i+81318ieXyeY/ek/Md5X1Q34H5Zio//vx+qoEyrreAvQIDAQAB',
  'app_secret' => '29a7809bc04fac72d8af77033eb8fafd',
  'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code',
  // 微信获取access_token的url地址
  'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?" .
    "grant_type=client_credential&appid=%s&secret=%s",
	'notify_url'=>'http://store.free.idcfengye.com/wecstore/public/index.php/api/v1/alipay/notify'
];