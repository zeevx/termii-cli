<?php

namespace App\Commands\Sender;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Fetch extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'sender-id:fetch';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Retrieve the status of all registered sender ID.';

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

            try{
                $request = Http::get("https://termii.com/api/sender-id?api_key=$key");
            }catch (Exception $e){
                $this->error('Connection Error');
                die();
            }


            $this->task("Fetching Sender-IDs...", function () use ($request) {
                if ($request) {
                    return true;
                }
                return false;
            });

            $response = $request->getBody()->getContents();

            //Log Response
//            Log::info("===SenderIDs Response===");
//            Log::info($response);

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
