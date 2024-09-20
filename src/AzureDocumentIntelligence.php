<?php

namespace BlueHex\LaravelAzureDI;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;

class AzureDocumentIntelligence
{
    protected $client;
    protected $endpoint;
    protected $apiKey;

    public function __construct()
    {
        $this->endpoint = rtrim(config('azure-di.endpoint'), '/');
        $this->apiKey = config('azure-di.api_key');

        $this->client = new Client([
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $this->apiKey,
            ],
        ]);
    }

    public function make()
    {
        return $this;
    }

    /**
     * @throws GuzzleException
     */
    public function analyzeDocument($filePath)
    {
        // Prepare the request URL
        $url = $this->endpoint . '/formrecognizer/documentModels/prebuilt-document:analyze?api-version=2023-02-28-preview';

//        dd($filePath);

        // Read the file content
        $fileContent = file_get_contents($filePath);

        // Send the request to Azure DI
        $response = $this->client->post($url, [
            'body' => $fileContent,
            'headers' => [
                'Content-Type' => 'application/pdf', // Adjust based on file type
            ],
        ]);

        if ($response->getStatusCode() !== 202) {
            throw new \Exception('Failed to submit document for analysis');
        }

        $operationLocation = $response->getHeader('Operation-Location')[0];

//        dd($operationLocation);

        if (!$operationLocation) {
            throw new \Exception('Operation-Location header not found in response');
        }

        // Poll for results
        $maxRetries = 60; // Adjust based on your needs
        $retryInterval = 5; // Seconds between retries

        for ($i = 0; $i < $maxRetries; $i++) {
            $pollResponse = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $this->apiKey,
            ])->get($operationLocation);

            $result = $pollResponse->json();

            if ($result['status'] === 'succeeded') {
                return collect($result['analyzeResult']);
            } elseif ($result['status'] === 'failed') {
                throw new \Exception('Document analysis failed: ' . json_encode($result['errors']));
            }

            // If still running, wait before next poll
            sleep($retryInterval);
        }

        throw new \Exception('Document analysis timed out');

        // return the response as laravel collection
        return collect(json_decode($response, true));
    }
}
