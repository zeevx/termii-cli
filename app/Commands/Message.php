<?php

namespace App\Commands;

use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Message extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'message';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Send text messages to customer(s) across different messaging channels.';

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

            //Ask To
            $to = $this->ask("Enter the destination phone number. \nPhone number must be in the international format (Example: 23490126727).");

            //Ask From
            $from = $this->ask("Enter a sender ID for sms which can be Alphanumeric or Device name for Whatsapp. ");

            //Ask SMS
            $sms = $this->ask("Enter text of a message that would be sent to the destination phone number");

            //Ask Type
            $type = "plain";

            //Ask Channel
            $channel = $this->ask("Select the route through which the message is sent. \nEnter 0 for Generic \nEnter 1 for DND \nEnter 2 for Whatsapp");

            if ($channel != 0 || $channel != 1 || $channel != 2){
                $this->error('Incorrect response, try again..');
                die();
            }

            $media = "";
            $media_url = "";
            $media_caption = "";

            if ($channel == 2){

                //Ask Media
                $media = $this->ask("Is this a media message? (When using the media parameter, the sms parameter will not be used) \nEnter 0 for Yes \nEnter 1 for No");

                if ($media != 0 || $media != 1){
                    $this->error('Incorrect response, try again..');
                    die();
                }

                if ($media == 0){
                    //Ask Media URL
                    $media_url = $this->ask("Enter the url to the file resource.");

                    //Ask Caption
                    $media_caption = $this->ask("Enter the caption that should be added to the image.");
                }
            }



            //Print Key
            $this->info("API-KEY: " . $key);

            if ($media == 0 && $channel == 2){
                $channel = "whatsapp";

                $data = [
                    "api_key" => $key,
                    "to" => $to,
                    "from" => $from,
                    "type" => $type,
                    "channel" => $channel,
                    "media" => json_encode([
                        "media.url" => $media_url,
                        "media.caption" => $media_caption
                    ])
                    ];
            }

            if ($channel == 0){
                $channel = "generic";
            }
            elseif($channel == 1){
                $channel = "dnd";
            }
            else{
                $channel = "whatsapp";
            }

            $data = [
                "api_key" => $key,
                "to" => $to,
                "from" => $from,
                "sms" => $sms,
                "type" => $type,
                "channel" => $channel
            ];

            try{
                $request = Http::post("https://termii.com/api/sms/send", $data);
            }catch (Exception $e){
                $this->error('Connection Error');
                die();
            }

            $this->task("Sending message...", function () use ($request) {
                if ($request) {
                    return true;
                }
                return false;
            });

            $response = $request->getBody()->getContents();

            //Log Response
            Log::info("===Message Response===");
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
