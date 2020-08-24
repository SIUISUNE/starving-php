<?php

namespace Starving\Laravel;


use Illuminate\Http\Request;
use Starving\Factory\PHP;

class FrameService
{
    public $data = null;

    /**
     * @param bool $not_convert
     * @return \Illuminate\Http\JsonResponse
     */
    public function response($not_convert = false)
    {
        $data = $this->data;

        if (!empty($data) && !is_string($data)) {
            $data = json_decode(json_encode($data), true);
            if (!empty($data)) {
                if (!$not_convert) {
                    if (config('laravel-starving-php.convert.response')) {
                        $data = PHP::convertHump($data);
                    }
                }
            }
        }

        $response = [
            'code' => 200,
            'msg' => '成功',
            'data' => $data,
            'time' => time()
        ];

        return response()->json($response);

    }

    /**
     * @param Request $request
     * @return Request
     */
    public function requestTransform(Request $request)
    {
        if (config('laravel-starving-php.convert.request')) {
            $input = $request->input();
            if (empty($input)) return $request;

            foreach ($input as $k => $v) {
                $request->offsetUnset($k);
            }

            $data = PHP::convertLine($input);
            $request->merge($data);
        }

        return $request;
    }

    /**
     * @param callback $option
     * @return mixed
     */
    public function push($option)
    {
        $this->data = call_user_func([new $option[0](), $option[1]]);
    }
}