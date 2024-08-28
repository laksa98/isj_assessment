<?php 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Provider;
use Illuminate\Support\Facades\Http;
use App\Helpers\Constants;

class checkHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-health';

    /**
     * The console command description.
     * 
     * This command checks the health status of a provider by sending a GET request to the provider's health check URL.
     * Based on the response, the provider's status is updated to active or inactive in the database.
     *
     * @var string
     */
    protected $description = 'Check the health status of a provider and update its status in the database.';

    /**
     * Execute the console command.
     * 
     * This method handles the main logic of the command. It sends an HTTP GET request to the specified URL.
     * If the request is successful, it updates the provider's status to active.
     * If the request fails, it updates the provider's status to inactive.
     * The status is then saved back to the database.
     *
     * @return int
     */
    public function handle()
    {
        // Retrieve the provider with ID 1 from the database
        $provider = Provider::find(1);

        // Get the health check URL from the environment variable or use a default value
        $url = env('PRAGMATIC_PLAY_HEALTH_CHECK', 'http://localhost/ISJ-tect-assessment/public/api/testHealthCheck');

        // Send an HTTP GET request to the health check URL
        $response = Http::get($url);

        // Check if the response is successful (HTTP status code 200-299)
        if ($response->successful())
        {
            // Set the provider's status to active
            $provider->status = Constants::PROVIDER_STATUS_ACTIVE;
        }
        else
        {
            // Set the provider's status to inactive
            $provider->status = Constants::PROVIDER_STATUS_INACTIVE;
        }

        // Save the updated provider status to the database
        $provider->save();

        // Return 0 to indicate that the command executed successfully
        return 0;
    }
}
?>