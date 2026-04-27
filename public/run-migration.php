<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\StringInput('migrate --force'),
    new Symfony\Component\Console\Output\BufferedOutput()
);

echo "Migration Status: " . $status . "\n";
echo "Output: \n";
// How to get output from BufferedOutput?
// I'll just use Artisan facade
use Illuminate\Support\Facades\Artisan;
Artisan::call('migrate', ['--force' => true]);
echo Artisan::output();
