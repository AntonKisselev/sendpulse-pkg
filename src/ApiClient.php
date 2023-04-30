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
        $tries  = 10;

        while ($tries > 0) {
            $tries--;
            $requestResult = $this->sendRequest('verifier-service/get-single-result', 'GET', $data);
            if (!$requestResult->data->result){
                $this->sendRequest('verifier-service/send-single-to-verify', 'POST', $data);
                $tries++;
                continue;
            }

            ///if already checked and email valid
            if ($requestResult->data->result == 1 && $requestResult->data->data
                && $requestResult->data->data->checks && $requestResult->data->data->checks->status == 1) {
                return true;
            ///if not ckecked
            } elseif ($requestResult->data->result == 1 && $requestResult->data->data
                && $requestResult->data->data->checks && $requestResult->data->data->checks->status == 0) {
                sleep(1);
                continue;
            /// already checked and email not valid
            }else{
                return false;
            }
        }
        throw new \Exception('error getting data from sendpulse');
    }
}