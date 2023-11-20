<?php

namespace App\Console\Commands;

use App\Models\Photo;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class syncImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailup:sync-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get images from URL';

    const URL = "https://jsonplaceholder.typicode.com/";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Syncing images... [START]");
        // $this->info("Syncing images...");
        // $this->error("Syncing images...");
        // $this->line("Syncing images...");
        $client = new Client([
            "base_uri" => self::URL,
            "timeout" => 2.0,
            "http_errors" => false,
            "headers" => [
                "Accept" => "application/json"
            ]
        ]);
        $this->line("Fetching images");

        $response = $client->get("phdotos");

        if(in_array($response->getStatusCode(), [200, 201])){
            $photos = json_decode($response->getBody());

            $this->warn(count($photos). " photos found.");

            $this->warn("Processing photos...");

            $bar = $this->output->createProgressBar(count($photos));
            $bar->start();

            foreach ($photos as $photo){
                $model = Photo::where("external_id", $photo->id)->firstOrNew();

                $model->fillApi($photo);

                $model->save();

                $bar->advance();
            }
        
            $bar->finish();
            $this->newLine();
        }else{
            $this->error("Error {$response->getStatusCode()}: There was an error fetching the images");
        }
        $this->info("Syncing images... [DONE]");

    }
}
