<?php


namespace App\Services;

use App\Services\Helpers\Integer;

/**
 * Class JsonService
 * @package App\Services
 */
class JsonService
{

    const ID = 'id';
    const TITLE = 'title';
    const LEVEL = 'level';
    const CHILDREN = 'children';
    const PARENT_ID = 'parent_id';


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

        $validateData = $this->validateData($decodeData);
        if (isset($validateData['message'])) {
            return $validateData;
        }

        // find last level
        $lastLevel = count($validateData) - 1;
        return $this->transform($lastLevel,$validateData);
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
            foreach ($data[$level] as $childVal) {
                if (isset($data[$level - 1][$childVal[self::PARENT_ID]])) {
                    array_push($data[$level - 1][$childVal[self::PARENT_ID]][self::CHILDREN], $childVal);
                }
            }
            unset($data[$level]);
            return $this->transform($level - 1, $data);
        }

        $result = [];
        foreach ($data[0] as $value) {
            array_push($result, $value);
        }

        return $result;
    }

    /**
     * Check string input is JSON
     * @param string $json
     * @return false|mixed
     */
    public function isJson(string $json)
    {
        $data = json_decode($json, true);
        if ($data === null) {
            return false;
        }
        return $data;
    }

    /**
     * Validate object properties
     * @param array $arrData
     * @return array|bool
     */
    public function validateObj(array $arrData)
    {

        $properties = [self::ID, self::TITLE, self::LEVEL, self::CHILDREN, self::PARENT_ID];

        foreach ($properties as $property) {
            if (!array_key_exists($property, $arrData)) {
                return $this->setMessage(false, 'Missing property: ' . $property);
            }
            $validateProperty = $this->validateProperty($property, $arrData);
            if ($validateProperty !== true) {
                return $validateProperty;
            }

        }
        return true;
    }

    /**
     * Validate JSON decoded data and rearrange elements inside
     * @param array $data
     * @return array|bool
     */
    public function validateData(array $data)
    {
        // loop through each level in array data
        foreach($data as $index => $level) {
            $newLevelArr = [];
            // loop through each element in level to validate
            foreach ($level as $arr) {
                $validObj = $this->validateObj($arr);
                if ($validObj !== true) {
                    return $validObj;
                }
                // assign id of object as index
                $newLevelArr[$arr[self::ID]] = $arr;
            }
            $data[$index] = $newLevelArr;
        }

        return $data;
    }

    /**
     * Validate value of object property
     * @param $property
     * @param $arrData
     * @return array|bool
     */
    public function validateProperty($property, $arrData)
    {
        switch ($property) {
            case self::ID:
                if (!Integer::isPositive($arrData[$property])) {
                    return $this->setMessage(false, 'Invalid ID value');
                }
                break;

            case self::TITLE:
                if (strlen($arrData[$property])  == 0) {
                    return $this->setMessage(false, 'Empty title');
                }
                break;

            case self::LEVEL:
                if (!Integer::isNotNegative($arrData[$property])) {
                    return $this->setMessage(false, 'Invalid level value');
                }
                break;

            case self::CHILDREN:
                if (!is_array($arrData[$property])) {
                    return $this->setMessage(false, 'Invalid children value');
                }
                break;

            case self::PARENT_ID:
                break;

            default:
                return $this->setMessage(false, 'Invalid property');
        }

        return true;
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
