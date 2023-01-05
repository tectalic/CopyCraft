<?php

namespace OM4\CopyCraft\Vendor;

// Register chunk filter if not found
if (!\array_key_exists('chunk', \stream_get_filters())) {
    \stream_filter_register('chunk', 'OM4\\CopyCraft\\Vendor\\Http\\Message\\Encoding\\Filter\\Chunk');
}
