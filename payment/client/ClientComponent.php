<?php

namespace app\payment\client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use sizeg\jwt\Jwt;
use Yii;
use yii\base\Component;
use yii\helpers\Json;

/**
 * Payment client component
 */
class ClientComponent extends Component
{
    /**
     * @var string payment api url
     */
    public $url = 'http://localhost/payment/accept';
    /**
     * @var string algorithm for token sign
     */
    public $alg;
    
    /**
     * Send payments array
     * @param array $payments
     * @return bool
     */
    public function sendPayments(array $payments)
    {
        $content = $this->buildToken($payments);
        return $this->send($content);
    }
    
    /**
     * Token builder
     * @param array $payments
     * @return string from \Lcobucci\JWT\Token
     */
    protected function buildToken(array $payments)
    {
        /* @var $jwt Jwt */
        $jwt = Yii::$app->jwt;
        $signer = $jwt->getSigner($this->alg);
        $key = $jwt->getKey();
        return $jwt->getBuilder()
            ->withClaim('payments', $payments)
            ->getToken($signer, $key); // Retrieves the generated token
    }
    
    /**
     * Send request to server
     * @param string $content
     * @return boolean
     */
    protected function send(string $content)
    {
        $request = new Request('POST', $this->url, [], $content);
        $response = (new Client())->send($request);
        if ($response->getStatusCode() === 200) {
            $data = Json::decode($response->getBody()->getContents());
            if (isset($data['status']) && $data['status'] === 'success') {
                return true;
            }
        }
        return false;
    }
}
