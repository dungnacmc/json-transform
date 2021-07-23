<?php


namespace App\Services;

/**
 * Class JsonService
 * @package App\Services
 */
class JsonService
{

    /**
     * Handle json transform request
     * @param string $data
     * @return array
     */
    public function handleJson(string $data): array
    {
        $decodeData = $this->isJson($data);
        if ($decodeData === false) {
            return $this->setMessage(false, 'Wrong JSON string');
        }

        $arrData = $this->isObject($decodeData);
        if ($arrData === false) {
            return $this->setMessage(false, 'Incorrect object');
        }

        // find last level
        $lastLevel = count($arrData) - 1;
        return $this->transform($lastLevel, $arrData);
    }


    /**
     * Travel through array data to collect children
     * @param int $level Current level
     * @param array $data
     * @return array
     */
    private function transform(int $level, array $data): array
    {
        if ($level > 0) {
            foreach ($data[$level] as $value) {
                foreach ($data[$level - 1] as $parentIndex => $parentValue)  {
                    if ($value->parent_id == $parentValue->id) {
                        array_push($data[$level - 1][$parentIndex]->children, $value);
                        break;
                    }
                }
            }
            unset($data[$level]);
            return $this->transform($level - 1, $data);
        }

        return $data[0];
    }

    /**
     * Validate data obj
     * @param mixed $data
     * @return array|false
     */
    public function isObject($data)
    {
        if (!is_object($data)) {
            return false;
        }

        return get_object_vars($data);
    }

    /**
     * Check string input is JSON
     * @param string $json
     * @return false|mixed
     */
    public function isJson(string $json)
    {
        $data = json_decode($json);
        if ($data === null) {
            return false;
        }

        return $data;
    }

    /**
     * Provide error message
     * @param bool $success
     * @param string $message
     * @return array
     */
    public function setMessage(bool $success, string $message): array
    {
        return ['success' => $success, 'message' => $message];
    }
}
