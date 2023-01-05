<?php

namespace OM4\CopyCraft\Vendor\Spatie\DataTransferObject;

abstract class FlexibleDataTransferObject extends DataTransferObject
{
    /** @var bool */
    protected $ignoreMissing = \true;
}
