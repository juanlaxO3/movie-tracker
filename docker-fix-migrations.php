<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pending = [
    'sessions' => '2026_07_10_020601_create_sessions_table',
];

foreach ($pending as $table => $migration) {
    if (Schema::hasTable($table) && !DB::table('migrations')->where('migration', $migration)->exists()) {
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch' => DB::table('migrations')->max('batch') + 1,
        ]);
        echo "Marked $migration as already run.\n";
    }
}
