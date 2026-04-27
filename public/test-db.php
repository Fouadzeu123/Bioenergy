<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Default DB Connection: " . config('database.default') . "\n";
echo "MySQL Database: " . config('database.connections.mysql.database') . "\n";
echo "SQLite Database: " . config('database.connections.sqlite.database') . "\n";
