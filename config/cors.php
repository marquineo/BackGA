<?php

return [

    'paths' => ['users/*','progresos/*','rutinas/*'], // Evita usar '*', Laravel no lo recomienda

    'allowed_methods' => ['*'],

    'allowed_origins' => ['https://www.gymbroanalytics.xyz'], // SIN slash al final

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];

