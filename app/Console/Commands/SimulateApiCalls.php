<?php

namespace App\Console\Commands;

use Curl;
use Config;
use Illuminate\Console\Command;

class SimulateApiCalls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info("Starting to send fake traffic!");

        $map = [
            "registration-failed" => [
                "name field incorrect" => 100,
                "email field incorrect" => 100,
                "password too short" => 300,
                "password too weak" => 300,
                "password confirmation is incorrect" => 500,
                "captcha is incorrect" => 2000
            ],
            "login-failed" => [
                "email field incorrect" => 100,
                "password field incorrect" => 200,
                "user doesn't exist" => 1000,
                "captcha is incorrect" => 2000
            ],
            "contact-us-failed" => [
                "email field incorrect" => 100, 
                "message field incorrect" => 100, 
                "captcha is incorrect" => 2000
            ],
            "error-500-api" => [
                "Internal server error" => 10000,
                "Permissions error" => 5000,
                "Timeout" => 100000
            ],
            "error-404-api" => [
                "Page not found" => 15000,
                "Model not found" => 30000,
                "Route not found for given url" => 100
            ]
        ];

        $curl = new Curl\Curl();
        
        while(true) {

            $channel = array_rand($map);
            $title = array_rand($map[$channel]);
            $ms = $map[$channel][$title];
            
            $curl->post(Config::get('app.url').'/api/logs/new', array(
                "channel" => $channel,
                "title" => $title,
                "ms" => $ms
            ));

            $this->info($channel." - ".$title." - ".$ms);

        }


    }
}
