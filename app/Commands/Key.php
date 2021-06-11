<?php

namespace App\Commands;

use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Key extends Command
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

    public function go(){
        //Get Key
        $key = $this->ask('Input your api key(gotten from Termii dashboard)');

        //Flush Cache
        $this->task("Flushing system..", function () {
            if (Storage::exists('TERMII/env.php')){
                Storage::delete('TERMII/env.php');
            }
            return true;
        });

        //Set Key in DB
        $this->task("Storing/Updating API Key...", function () use ($key) {
            Storage::put('TERMII/env.php', $key);
            return true;
        });

        //Check if Key is set
        $this->task("Checking if API Key is set...", function () {
            if (Storage::exists('TERMII/env.php')){
                return true;
            }
            return false;
        });

        //Return Success Message
        $this->info("API Key stored/updated successfully. \nAPI-KEY: $key");
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
