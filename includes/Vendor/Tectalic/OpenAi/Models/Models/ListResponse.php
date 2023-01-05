<?php

/**
 * Copyright (c) 2022 Tectalic (https://tectalic.com)
 *
 * For copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * Please see the README.md file for usage instructions.
 */
declare (strict_types=1);
namespace OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\Models;

use OM4\CopyCraft\Vendor\Tectalic\OpenAi\Models\AbstractModel;
final class ListResponse extends AbstractModel
{
    /**
     * List of required property names.
     *
     * These properties must all be set when this Model is instantiated.
     */
    protected const REQUIRED = ['object', 'data'];
    /** @var string */
    public $object;
    /** @var \Tectalic\OpenAi\Models\Models\ListResponseDataItem[] */
    public $data;
}
