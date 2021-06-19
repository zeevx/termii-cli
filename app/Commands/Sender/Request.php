<?php

namespace App\Commands\Sender;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Request extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'sender-id:request';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Request a new sender ID.';

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

            //Ask Sender ID
            $sender_id = $this->ask('Enter Alphanumeric sender ID length should be between 3 and 11 characters (Example:CompanyName)');

            //Ask Usecase
            $use_case = $this->ask('Enter use case(i.e: A sample of the type of message sent.)');

            //Ask Company
            $company = $this->ask('Enter the name of the company with the sender ID.');

            //Print Key
            $this->info("API-KEY: " . $key);

            try{
                $request = Http::post("https://termii.com/api/sender-id/request", [
                    "api_key" => $key,
                    "sender_id" => $sender_id,
                    "usecase" => $use_case,
                    "company" => $company
                ]);
            }catch (Exception $e){
                $this->error('Connection Error');
                die();
            }

            $this->task("Requesting Sender-ID...", function () use ($request) {
                if ($request) {
                    return true;
                }
                return false;
            });

            $response = $request->getBody()->getContents();

            //Log Response
            Log::info("===SenderID Request Response===");
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
