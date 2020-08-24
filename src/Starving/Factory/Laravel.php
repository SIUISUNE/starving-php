<?php


namespace Starving\Factory;


use Illuminate\Support\Facades\Validator;
use Starving\Exception\StarvingException;

class Laravel
{
    /**
     * 获取订单号
     *
     * @param $model
     * @param $field
     * @param string $prefix
     * @return string
     */
    public static function getOrderNumber($model, $field, $prefix = '')
    {
        do {
            $model = new $model;
            $res = $prefix . date('Ymd') . date('s') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $e = $model::where($field, $res)->first();
        } while (!empty($e));

        return $res;
    }

    /**
     * 地区id获取地区名称
     *
     * @param $model
     * @param $field
     * @param string $symbol
     * @param mixed ...$region
     * @return string
     */
    public static function provinceCityAreaToString($model, $field, $symbol = ' ', ...$region)
    {
        $string = '';
        $filter = ['市辖区'];
        foreach ($region as $item) {
            $regionModel = $model::where($field, $item)->first();
            $regionName = !empty($regionModel->region_name) ? $regionModel->region_name : '';
            if (!empty($regionName) && in_array($regionName, $filter)) {
                continue;
            }
            $string .= $regionName . $symbol;
        }

        return trim($string, $symbol);
    }

    /**
     * 获取域名
     *
     * @return string
     */
    public static function domainName()
    {
        return ($_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://' . request()->server('HTTP_HOST');
    }

    /**
     * @param $data
     * @param $model
     * @return array
     */
    public function getModelByColumnLists($data, $model)
    {
        $columns = self::getColumnListing($model);
        $res = array();
        foreach ($columns as $key) {
            if (isset($data[$key])) {
                $res[$key] = $data[$key];
            }
        }
        return $res;
    }


    /**
     * @param $model
     * @return array
     */
    public function getColumnListing($model)
    {
        if (!is_string($model)) {
            return [];
        }
        $model = new $model();
        $schemaBuilder = null;
        $connection = null;
        $table = $model->getTable();
        $connection = $model->getConnection();
        $schemaBuilder = $connection->getSchemaBuilder();

        return $schemaBuilder->getColumnListing($table);
    }

    /**
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @throws StarvingException
     */
    public function verifyData(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        /*
         例子：
         $this->verifyData($request->input(), [
            'username' => 'required|regex:/^\w+$/',
            'password' => 'required|min:5|max:16',
            'code' => 'required|size:4|captcha'
        ], [
            'username.required' => '用户名不能为空',
            'password.required' => '密码不能为空',
            'code.required' => '验证码不能为空',
            'code.size' => '验证码必须是4位',
        ]);
        */


        $validator = Validator::make($data, $rules, $messages, $customAttributes);
        if ($validator->fails()) {
            $e = config('laravel-starving-php.validator_exception');
            throw new $e($validator->messages()->first());
        }
    }
}