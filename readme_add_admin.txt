
php bin/console account:create admin@a.a admin 1

php bin/console doctrine:query:sql "UPDATE accounts SET roles = '[\"ROLE_USER\", \"ROLE_ADMIN\"]' WHERE email = 'admin@a.a'"
