## INSTALATION

<!-- // todo : setup app -->

-  git clone https://github.com/hikayatz/yii2-basic-template.git
-  cd yii2-basic-template
-  composer install
-  copy .env.example.php .env
-  setup your config
-  yii migrate --migrationPath=@yii/rbac/migrations
-  yii migrate

## RUN APP

-  php -S localhost:8000
-  run app test => localhost:8000/index-test.php
