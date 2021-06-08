<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Cache;
use LaravelZero\Framework\Commands\Command;

class StoreKey extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'key';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Store your api-key gotten from your Termii Dashboard.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        //Get Key
        $key = $this->ask('Input your api key(gotten from Termii dashboard)');

        //Flush Cache
        $this->task("Flushing system..", function () {
            Cache::flush();
            return true;
        });

        //Set Key in DB
        $this->task("Storing/Updating API Key...", function () use ($key) {
            Cache::forever('key', $key);
            return true;
        });

        //Check if Key is set
        $this->task("Checking if API Key is set...", function () {
            if (Cache::has('key')){
                return true;
            }
            return false;
        });

        //Return Success Message
        $this->info("API Key stored/updated successfully. \nAPI-KEY: $key");
    }

    /**
     * Define the command's schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
