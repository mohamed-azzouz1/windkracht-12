<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearAllCache extends Command
{
    protected $signature = 'cache:clear-all';
    protected $description = 'Clear all Laravel cache (config, route, view, etc.)';

    public function handle()
    {
        $this->info('Clearing Laravel Cache...');
        
        Artisan::call('config:clear');
        $this->info('✓ Configuration cache cleared!');
        
        Artisan::call('route:clear');
        $this->info('✓ Route cache cleared!');
        
        Artisan::call('view:clear');
        $this->info('✓ View cache cleared!');
        
        Artisan::call('cache:clear');
        $this->info('✓ Application cache cleared!');
        
        Artisan::call('optimize:clear');
        $this->info('✓ Optimizer cache cleared!');
        
        $this->info('All caches have been cleared successfully!');
        
        return 0;
    }
}
