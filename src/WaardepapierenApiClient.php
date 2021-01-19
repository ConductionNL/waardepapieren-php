<?php

namespace Conduction\Waardepapieren;

use GuzzleHttp\Client;
use http\Url;
use Throwable;
use Jose\Component\Signature\Algorithm\RS512;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\JWK;

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
     * This function verifies if the provided public key is the signature needed for the JWS token
     *
     * @param JWK $key The public jwk key we use to validate the JWS token
     * @param string $token The JWS Token we want to validate
     * @return bool True if valid or false if the public key does not match the token's signature
     */
    public function verifyJWSToken(JWK $key, string $token) {
        $algorithmManager = new AlgorithmManager([
            new RS512(),
        ]);

        $jwsVerifier = new JWSVerifier($algorithmManager);

        $serializerManager = new JWSSerializerManager([new CompactSerializer()]);

        $jws = $serializerManager->unserialize($token);

        return $jwsVerifier->verifyWithKey($jws, $key, 0);
    }

    /**
     * This function checks if the data stored in an array is the same as the data stored in the JWS Token
     *
     * @param string $token The JWS token we compare to the data array
     * @param array $data array we want to compare to the JWS token
     * @return bool True of the JWS token and data array match or false of there is a difference between the two
     */
    public function checkTokenData(string $token, array $data) {
        $json = base64_decode(explode('.', $token)[1]);
        $json = json_decode($json, true)['data'];

        $difference = array_diff(array_map('serialize', $json), array_map('serialize', $data));

        if (empty($difference)) {
            return true;
        } else {
            return false;
        }
    }

}
