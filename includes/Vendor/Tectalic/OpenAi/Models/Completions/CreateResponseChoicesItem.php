<?php

/**
 * Copyright (c) 2022 Tectalic (https://tectalic.com)
 *
 * For copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * Please see the README.md file for usage instructions.
 */
declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\Completions;

use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\AbstractModel;
final class CreateResponseChoicesItem extends AbstractModel
{
    /** @var string */
    public $text;
    /** @var int */
    public $index;
    /** @var \Tectalic\OpenAi\Models\Completions\CreateResponseChoicesItemLogprobs|null */
    public $logprobs;
    /** @var string */
    public $finish_reason;
}
