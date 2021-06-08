<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use LaravelZero\Framework\Commands\Command;

class CheckBalance extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'balance';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Check your balance on Termii';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        //Check if Key is set
        $this->task("Check if API Key is set...", function () {
            if (Cache::has('key')){
                return true;
            }
            return false;
        });

        //Get Stored Api key
        $key = Cache::get('key', 'Not Set');

        //Print Key
        $this->info("API-KEY: ".$key);

        $request = Http::get("https://termii.com/api/get-balance?api_key=$key");
        $response = $request->getBody()->getContents();

        //Print Response
        $this->info("Termii Response: $response");
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
