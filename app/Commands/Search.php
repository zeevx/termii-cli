<?php

namespace App\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Search extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'search';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Verify phone numbers and automatically detect their status';

    /**
     *
     */
    public function go()
    {
        //Check if Key is set
        $this->task("Check if API Key is set...", function () {
            if (Storage::exists('TERMII/env.php')){
                return true;
            }
            return false;
        });

        if (Storage::exists('TERMII/env.php')) {

            //Get Stored Api key
            $key = Storage::get('TERMII/env.php') ?? "No-Key";

            //Print Key
            $this->info("API-KEY: " . $key);

            $phone = $this->ask('Enter the phone number you want to search(format: 234903875967)');


            $request = Http::get("https://termii.com/api/check/dnd?api_key=$key&phone_number=$phone");

            $this->task("Searching...", function () use ($request) {
                if ($request) {
                    return true;
                }
                return false;
            });

            $response = $request->getBody()->getContents();

            //Log Response
            Log::info("===Search Response===");
            Log::info($response);

            //Print Response
            $this->info("Termii Response: $response");
        }
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->go();
    }

}
