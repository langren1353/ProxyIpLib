<?php

return [

    //测速 限制最大时长
    'speed_limit' => env("SPEED_LIMIT_SECONDS", 4000) // 时间长了会导致各种验证非常的慢

];
