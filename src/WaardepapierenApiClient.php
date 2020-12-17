<?php

namespace Conduction\Waardepapieren;

use GuzzleHttp\Client;
use http\Url;
use Throwable;

class WaardepapierenApiClient {
    /**
     * Endpoint of the API
     */
    const API_ENDPOINT = 'https://id-vault.com';

    /**
     * HTTP Methods
     */
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';

    private $client;

    private $headers;

    public function __construct()
    {

        $this->headers = [
            'Accept'        => 'application/ld+json',
            'Content-Type'  => 'application/json',
        ];

        $this->client = new Client([
            'headers'  => $this->headers,
            'base_uri' => self::API_ENDPOINT,
            'timeout'  => 20.0,
        ]);

    }

    /**
     * This function sends mail from id-vault to provided receiver
     *
     * @param string $applicationId id of your id-vault application.
     * @param string $body html body of the mail.
     * @param string $subject subject of the mail.
     * @param string $receiver receiver of the mail.
     * @param string $sender sender of the mail.
     *
     * @return array|false returns response from id-vault or false if wrong information provided for the call
     */
    public function sendMail(string $applicationId, string $body, string $subject, string $receiver, string $sender)
    {
        try {

            $body = [
                'applicationId' => $applicationId,
                'body'          => $body,
                'subject'       => $subject,
                'receiver'      => $receiver,
                'sender'        => $sender,
            ];

            $response = $this->client->request(self::HTTP_POST, '/api/mails', [
                'json'         => $body,
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

        } catch (Throwable $e) {
            return false;
        }

        return $response;
    }

    /**
     * This function retrieve's user information from id-vault.
     *
     * @param string $applicationId id of your id-vault application.
     * @param string $secret secret of your id-vault application.
     * @param string $code the code received by id-vault oauth endpoint.
     * @param string $state (optional) A random string used by your application to identify a unique session
     *
     * @return array|false returns response from id-vault or false
     */
    public function authenticateUser(string $code, string $applicationId, string $secret, string $state = null)
    {
        try {

            $body = [
                'clientId'          => $applicationId,
                'clientSecret'      => $secret,
                'code'              => $code,
                'grantType'         => 'authorization_code',
                'state'             => $state
            ];

            $response = $this->client->request(self::HTTP_POST, '/api/access_tokens', [
                'json'         => $body,
            ]);

        } catch (Throwable $e) {
            return false;
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * this function requests additional scopes from user that they must authorize.
     *
     * @param array $scopes scopes you wish to request from the user.
     * @param string $accessToken accessToken received from id-vault.
     *
     * @return array|Throwable returns response from id-vault
     */
    public function getScopes(array $scopes, string $accessToken)
    {
        try {

            $json = base64_decode(explode('.', $accessToken)[1]);
            $json = json_decode($json, true);

            $body = [
                'scopes'            => $scopes,
                'authorization'     => $json['jti'],

            ];

            $response = $this->client->request(self::HTTP_POST, '/api/getScopes', [
                'json'         => $body,
            ]);

        } catch (Throwable $e) {
            return $e;
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * This function add a dossier to an id-vault user.
     *
     * @param array $scopes scopes the dossier is blocking (scopes must be authorized by the user).
     * @param string $accessToken accessToken received from id-vault.
     * @param string $name name of the dossier.
     * @param string $goal the goal of the Dossier.
     * @param string $expiryDate Expiry date of the Dossier (example: "27-10-2020 12:00:00").
     * @param string $sso valid URL with which the user can view this Dossier.
     * @param string $description (optional) description of the dossier.
     * @param bool $legal (default = false) whether or not this Dossier is on legal basis.
     *
     * @return array|string response from id-vault if dossier created was successful, error message otherwise.
     */
    public function createDossier(array $scopes, string $accessToken, string $name, string $goal, string $expiryDate, string $sso, string $description = '', bool $legal = false)
    {
        if (!filter_var($sso, FILTER_VALIDATE_URL)) {
            throw new \ErrorException('Url invalid', 500);
        }

        $json = base64_decode(explode('.', $accessToken)[1]);
        $json = json_decode($json, true);

        try {

            $headers = $this->headers;
            $headers['authentication'] = $json['jti'];

            $body = [
                'scopes'            => $scopes,
                'name'              => $name,
                'goal'              => $goal,
                'expiryDate'        => $expiryDate,
                'sso'               => $sso,
                'description'       => $description,
                'legal'             => $legal,
            ];

            $response = $this->client->request(self::HTTP_POST, '/api/dossiers', [
                'json'         => $body,
                'headers'      => $headers,
            ]);

        } catch (Throwable $e) {
            return false;
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
