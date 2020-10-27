<?php
return [
    'DB_DSN' => 'pgsql:host=localhost;port=5432;dbname=yii-db',
    'DB_USERNAME' => 'postgres',
    'DB_PASSWORD' => 'secret',
    'DB_SCHEMECHACHE' => false,

    'APP_NAME' => 'APP-TEMPLATE',

    'THEME_PATH' => '@app/themes/adminlte',
    'THEME_URL' => '@web/themes/adminlte',

    #setting mailer
    'MAIL_HOST' => 'mail.trap.com',
    'MAIL_USERNAME' => 'user@trap.com',
    'MAIL_PASSWORD' => 'secret',
    'MAIL_PORT' => '587',
    'MAIL_ENCRYPTION' => null,

    'YII_DEBUG' => false,
    'YII_ENV' => 'prod',

]
?>