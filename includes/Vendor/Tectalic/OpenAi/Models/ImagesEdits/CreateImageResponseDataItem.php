<?php

/**
 * Copyright (c) 2022 Tectalic (https://tectalic.com)
 *
 * For copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * Please see the README.md file for usage instructions.
 */
declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\ImagesEdits;

use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\AbstractModel;
final class CreateImageResponseDataItem extends AbstractModel
{
    /** @var string */
    public $url;
    /** @var string */
    public $b64_json;
}
