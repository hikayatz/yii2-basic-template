<?php

return [
    'class' => 'yii\db\Connection',

    'dsn' => env('DB_DSN'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => 'utf8',

    'schemaCache' => 'cache',
    'enableSchemaCache' => env('DB_SCHEMECHACHE'),
    'schemaCacheDuration' => 360, // Duration of schema cache.
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];