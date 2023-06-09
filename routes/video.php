<?php
/**
 * ============================================================================
 * Copyright (c) 2015-2021 贵州大师兄信息技术有限公司 All rights reserved.
 * siteַ: https://www.gzdsx.cn
 * ============================================================================
 * @author:     David Song<songdewei@163.com>
 * @version:    v1.0.0
 * ---------------------------------------------
 * Date: 2021/1/8
 * Time: 19:42
 */

Route::group(['namespace' => 'Video', 'prefix' => 'video'], function () {
    Route::get('/', 'IndexController@index');
    Route::get('{vid}.html', 'IndexController@detail');
});
