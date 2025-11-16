<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing export functionality...');

        try {
            $companies = \App\Models\Company::all();
            $this->info('Found ' . $companies->count() . ' companies');

            if ($companies->count() > 0) {
                $export = new \App\Exports\CompaniesExport();
                $this->info('Export class instantiated successfully');

                $collection = $export->collection();
                $this->info('Collection retrieved: ' . $collection->count() . ' items');

                $headings = $export->headings();
                $this->info('Headings: ' . implode(', ', $headings));

                $this->info('Export test completed successfully!');
            } else {
                $this->warn('No companies found in database');
            }
        } catch (\Exception $e) {
            $this->error('Export test failed: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
        }
    }
}
