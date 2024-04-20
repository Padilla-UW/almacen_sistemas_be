<?php

namespace ApiSistemas\Libs;

class Controller
{
    public $data;

    public function __construct()
    {
        $this->data = json_decode(file_get_contents('php://input'), true);
    }
    public function response(array $data, int $code = 200)
    {
        http_response_code($code);
        echo json_encode($data);
        exit;
    }

    public function exists(array $parameters)
    {

        $missingParameters = $this->checkMissingParameters($parameters);
        $emptyParameters = $this->checkEmptyParaneters($parameters);

        if (!empty($missingParameters)) {
            $this->response(['error' => "Missing parameters: $missingParameters"]);
        }

        if (!empty($emptyParameters)) {
            $this->response(["error" => "Parameters is empty: $emptyParameters"]);
        }

        return true;
    }

    private function checkMissingParameters(array $parameters)
    {
        $missing = array_diff($parameters, array_keys($this->data));
        return implode(',', $missing);
    }

    public function checkEmptyParaneters(array $parameters)
    {
        $emptyParams = [];
        foreach ($parameters as  $param) {
            if (empty($param)) {
                $emptyParams[] = $param;
            }
        }

        return implode(',', $emptyParams);
        if (!empty($aux)) {
            $string = "";
            $this->response(["message" => "Parameters is empty: $string"]);
        }
    }
}
