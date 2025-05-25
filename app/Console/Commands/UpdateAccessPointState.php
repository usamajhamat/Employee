<?php

namespace App\Console\Commands;

use App\Services\AccessPointStateService;
use Illuminate\Console\Command;

class UpdateAccessPointState extends Command
{
    protected $signature = 'access-points:update-state';
    protected $description = 'Fetch and update access point and client states from Tanaza API';

    public function handle()
    {
        $this->info('Updating access point states...');
        
        try {
            $service = new AccessPointStateService();
            $service->updateState();
            $this->info('Access point states updated successfully.');
        } catch (\Exception $e) {
            $this->error('Error updating access point states: ' . $e->getMessage());
        }
    }
}