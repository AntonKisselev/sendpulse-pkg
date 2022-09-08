<?php

namespace Sendpulse;

class ApiClient extends \Sendpulse\RestApi\ApiClient
{
    /**
     * валидирует email
     * @param string $email
     * @return bool
     * @throws \Exception
     */
    function validate(string $email):bool
    {
        $data = array(
            'email' => $email,
        );

        $requestResult = $this->sendRequest('verifier-service/send-single-to-verify', 'POST', $data);
        $requestResult = $this->sendRequest('verifier-service/get-single-result', 'GET', $data);
        if ($requestResult->data->result == 1 && $requestResult->data->data
            && $requestResult->data->data->checks && $requestResult->data->data->checks->status == 1){
            return true;
        }elseif ($requestResult->data->result == 1 && $requestResult->data->data
            && $requestResult->data->data->checks && $requestResult->data->data->checks->status != 1){
            return false;
        }
        throw new \Exception('error getting data from sendpulse');
    }
}