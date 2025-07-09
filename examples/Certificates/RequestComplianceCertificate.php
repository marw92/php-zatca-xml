<?php

require __DIR__.'/../../vendor/autoload.php';

use GuzzleHttp\Client;
use Sevaske\ZatcaApi\Api;
use Sevaske\ZatcaApi\Exceptions\ZatcaException;

$api = new Api('sandbox', new Client);
$certificatePath = __DIR__.'/output/certificate.csr';
$csr = file_get_contents($certificatePath);

try {
    $response = $api->complianceCertificate($csr, '123123');
    $credentials = [
        'requestId' => $response->requestId(),
        'certificate' => $response->certificate(),
        'secret' => $response->secret(),
    ];

    print_r($credentials);

    // sava file output/ZATCA_certificate_data.json
    $outputFile = __DIR__.'/output/ZATCA_certificate_data.json';
    file_put_contents($outputFile, json_encode($credentials, JSON_PRETTY_PRINT));

    echo "\nCertificate data saved to {$outputFile}\n";
} catch (ZatcaException $e) {
    echo 'API Error: '.$e->getMessage()."\n";
    print_r($e->context());
} catch (\Exception $e) {
    echo 'Error: '.$e->getMessage();
}
