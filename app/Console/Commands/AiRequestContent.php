<?php
/**
 * ============================================================================
 * Copyright (c) 2015-2023 贵州大师兄信息技术有限公司 All rights reserved.
 * siteַ: https://www.gzdsx.cn
 * ============================================================================
 * @author:     David Song<songdewei@163.com>
 * @version:    v1.0.0
 * ---------------------------------------------
 * Date: 2023/3/19
 * Time: 下午10:59
 */

namespace App\Console\Commands;

/**
 * Class AiRequestContent
 * @package App\Console\Commands
 *
 * @property string $type
 * @property string $quickly_id
 * @property string $prompt
 * @property string $token
 * @property string $access_token
 * @property string $device_id
 */
class AiRequestContent
{
    const TYPE_CHAT = 'chat';
    const TYPE_COMPLETION = 'completion';

    protected $contents = [];

    public function __construct($contents = [])
    {
        $this->contents = $contents;
    }

    public function toArray()
    {
        return $this->contents;
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->contents[$name] = $value;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->contents[$name] ?? null;
    }
}
