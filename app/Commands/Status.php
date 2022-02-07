<?php

namespace App\Commands;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Status extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'status';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Detect if a number is fake or has ported to a new network';

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

            $phone = $this->ask('Enter the phone number(format: 234903875967)');

            $code = $this->ask('Enter the country code(format: "NG" - for Nigeria)');

            try{
                $request = Http::get("https://api.ng.termii.com/api/insight/number/query?api_key=$key&phone_number=$phone&country_code=$code");
            }catch (Exception $e){
                $this->error('Connection Error');
                die();
            }

            $this->task("Checking status...", function () use ($request) {
                if ($request) {
                    return true;
                }
                return false;
            });

            $response = $request->getBody()->getContents();

            //Log Response
//            Log::info("===Status Response===");
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
