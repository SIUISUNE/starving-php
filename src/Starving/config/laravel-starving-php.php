<?php

return [
    'builder' => [
        //默认分页大小，每页多少条数据
        'page_size' => 30,
        //列表结果参数名称
        'list_name' => 'list',
        //页码参数名称
        'page_name' => 'page',
        //每页多少数据参数名称参数名称
        'page_size_name' => 'page_size',
        //不保留的分页字段
        'unset' => [
            'data',
            'first_page_url',
            'last_page_url',
            'next_page_url',
            'path',
            'prev_page_url',
            'to',
            'per_page'
        ],
    ],

    /*
    | convert.request 转换所有请求参数统一为下划线，false：不转换
    | convert.response 转换所有响应参数未驼峰，false：不转换，需添加中间件
    |
    */
    
    'convert' => [
        'request' => false,
        'response' => false,
    ],

    'validator_exception' => \Starving\Exception\StarvingException::class,
];