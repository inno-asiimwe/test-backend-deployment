<?php 

namespace App;

define("ROOT_DIR", __DIR__ .'/');

use Google_Client;
use Google_Service_Sheets;

class SheetsService 
{

    /**
     * Fetch data from google spreadsheet
     * @codeCoverageIgnore
     * @return array data form spreadsheet in array format
     */
    public static function getSheetData(){
        // Get the API client and construct the service object.
        $client = SheetsService::getClient();
        $service = new Google_Service_Sheets($client);

        $spreadsheetId = env('DEV_PULSE_SHEET_ID');
        $range = env('DEV_PULSE_RANGE');
    
        $response =  $service->spreadsheets_values->get($spreadsheetId, $range);

        return $response->getValues();
   }

    /**
     * Get the API client and
     * Returns an authorized API client.
     * @codeCoverageIgnore
     * @return Client the authorized client object
     */
    public static function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('watchTower Server');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        if ($credentials_file = SheetsService::checkServiceAccountCredentialsFile()) {
            $client->setAuthConfig($credentials_file);
          } elseif (getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
            $client->useApplicationDefaultCredentials();
          } else {
            echo missingServiceAccountDetailsWarning();
            return;
          }
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = ROOT_DIR . 'token.json';
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            printf("No authorization credentials exist for this spreadsheet\n");
            return;
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }
    

    /**
     * Checks if the authorization file is present
     * @codeCoverageIgnore
     * @return string the path to the authorization file or false
     */
    public static function checkServiceAccountCredentialsFile(){
        return ROOT_DIR . 'credentials.json' ?? 0;
    }
}
