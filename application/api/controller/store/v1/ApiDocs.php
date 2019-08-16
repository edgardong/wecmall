<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-04-13
 * Time: 13:45
 */

namespace app\api\controller\store\v1;

require(APP_PATH . "../vendor/autoload.php");

use think\Controller;

class ApiDocs extends Controller
{
  public function getApiDocs()
  {


//    $basePath = '/Users/donghao/website/php/wecstore/';
    $basePath = APP_PATH.'../';

    $path = $basePath . 'application/api/controller/v1'; //你想要哪个文件夹下面的注释生成对应的API文档

    $swagger = \Swagger\scan($path);
    $swagger_json_path = $basePath . '/public/apidoc/swagger.json';
    $res = file_put_contents($swagger_json_path, $swagger);
//    return $res;
    if ($res == true) {
      $this->redirect('http://c.cin/apidoc/index.html');
    }
  }
}