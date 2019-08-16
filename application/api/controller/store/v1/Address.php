<?php
/**
 * Created by PhpStorm.
 * User: donghao
 * Date: 2019-02-25
 * Time: 16:30
 */

namespace app\api\controller\store\v1;

use app\api\controller\BaseController;
use app\api\model\TbMember;
use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;
use app\lib\enum\UserTypeEnum;
use app\lib\exception\SuccessMessage;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use think\Log;


/**
 * @SWG\Info(
 *     version="1.0.0",
 *     title="雅文破冰 Api 文档",
 *     description="雅文破冰后台 Api 文档"
 * )
 */
class Address extends BaseController
{
	protected $beforeActionList = [
		'checkPrimaryScope' => ['only' => 'saveAddress,getAddress']
	];

	/**
	 * @SWG\Post(
	 *     path="/api/v1/address",
	 *     tags={"用户地址"},
	 *     summary="保存用户地址",
	 *     description="保存用户的地址",
	 *     @SWG\Response(response="200", description="An example resource")
	 * )
	 */

	/**
	 * 保存地址
	 * @return \think\response\Json
	 * @throws TokenException
	 * @throws UserException
	 * @throws \app\lib\exception\ParameterException
	 * @throws \think\Exception
	 */
	public function saveAddress()
	{
		$validate = new AddressNew();
		$validate->goCheck();
		// 获取当前用户的ID
		$uid = TokenService::getCurrentUId();
		$type = TokenService::getCurrentTokenVar('type');
		Log::record($type);

		// 根据ID获取用户信息
		if ($type == UserTypeEnum::User) {
			$user = UserModel::get($uid);
		} else if ($type == UserTypeEnum::Member) {
			$user = TbMember::get($uid);
		}

		if (!$user) {
			throw  new UserException();
		}
		// 获取参数
		$dataArray = $validate->getDataByRule(input('post.'));

		// 获取用户的地址
		$userAddress = $user->address;

		// 根据用户是否有地址，判断保存还是新增
		if (!$userAddress) {
			$user->address()->save($dataArray);
		} else {
			$user->address->save($dataArray);
		}

		return json(new SuccessMessage(), 200);
	}


	/**
	 * @SWG\Get(
	 *     path="/api/v1/address",
	 *     summary="获取用户地址",
	 *     tags={"用户地址"},
	 *     @SWG\Tag(
	 *       name="Address",
	 *       description="用户地址操作",
	 *     ),
	 *     @SWG\Parameter(
	 *       name="uid",
	 *       in="query",
	 *       description="用户Id",
	 *       type="integer",
	 *       required=true
	 *     ),
	 *   @SWG\Parameter(
	 *       name="token",
	 *       in="header",
	 *       description="用户令牌",
	 *       type="string",
	 *       required=true
	 *     ),
	 *     @SWG\Response(response="200", description="An example resource")
	 * )
	 */

	/**
	 * 返回用户地址
	 * @return array|\PDOStatement|string|\think\Model|null
	 * @throws TokenException
	 * @throws UserException
	 * @throws \think\Exception
	 * @throws \think\db\exception\DataNotFoundException
	 * @throws \think\db\exception\ModelNotFoundException
	 * @throws \think\exception\DbException
	 */
	public function getAddress()
	{
		$uid = TokenService::getCurrentUId();
		$userAddress = UserAddress::where('user_id', '=', $uid)
			->find();
//    if (!$userAddress) {
//      throw new UserException([
//        'msg' => '用户地址不存在',
//        'errorCode' => 60001
//      ]);
//    }

		return [
			'data' => $userAddress,
			'errorCode' => 0
		];
	}
}