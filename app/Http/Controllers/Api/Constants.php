<?php

namespace App\Http\Controllers\Api;


class Constants
{

    public static $PAGE_LIMIT = 10;

    //just use a really large number to paginate 'All' items, better approach is to use if/else to omit paginate() call altogether.
    public static $PAGINATE_ALL = 10000000;
}
