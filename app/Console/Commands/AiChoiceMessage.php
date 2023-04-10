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
 * Time: 下午11:09
 */

namespace App\Console\Commands;

/**
 * Class AiChoiceMessage
 * @package App\Console\Commands
 *
 * @property string $text
 * @property int $index
 * @property string $logprobs
 * @property string $finish_reason
 */
class AiChoiceMessage
{
    protected $message;

    public function __construct($message = [])
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->message);
    }

    public function __set($name, $value)
    {
        // TODO: Implement __set() method.
        $this->message[$name] = $value;
    }

    public function __get($name)
    {
        // TODO: Implement __get() method.
        return $this->message[$name] ?? null;
    }
}
