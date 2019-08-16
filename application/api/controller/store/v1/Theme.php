<?php

namespace app\api\controller\store\v1;


use app\api\validate\IDCollection;

use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;

class Theme
{
  /**
   * 获取精选主题
   * @url /theme?ids=id1,id2,id3...
   * @param $ids
   * @throws
   * @return mixed
   */
  public function getSimpleList($ids = '')
  {
    (new IDCollection())->goCheck();

    $ids = explode(',', $ids);

    $result = ThemeModel::with('headImg,topicImg')
      ->select($ids);

    if ($result->isEmpty()) {
      throw new ThemeException();
    }

    return $result;
  }

  /**
   * 获取专题详情
   * @url /theme/:id
   * @params $id
   * @throws
   * @return mixed
   */
  public function getComplexOne($id)
  {
    (new IDMustBePositiveInt())->goCheck();
    $result = ThemeModel::getThemeWithProducts($id);
    if (!$result) {
      throw new ThemeException();
    }
    return $result;
  }
}
