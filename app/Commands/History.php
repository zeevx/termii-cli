<?php

namespace App\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class History extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'history';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Reports for messages sent across the sms, voice & whatsapp channels.';

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

            $request = Http::get("https://termii.com/api/sms/inbox?api_key=$key");

            $this->task("Fetching Histories...", function () use ($request) {
                if ($request) {
                    return true;
                }
                return false;
            });

            $response = $request->getBody()->getContents();

            //Log Response
            Log::info("===History Response===");
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
