<?php

namespace App\Traits;

trait LangsTrait
{
    public function getFieldValueByLang($field, $code)
    {
        $value = $this->$field;

        if (empty($value)) {
            return null;
        }

        $json = json_decode($value, true);

        if ($json === null) {
            return $json;
        }

        if (isset($json[$code])) {
            return $json[$code];
        }
    }

    public function setFieldValueByLang($field, $code, $new_value) : bool
    {
        $value = $this->$field;

        if (empty($value)) {
            $json = [];
        } else {
            $json = json_decode($value, true);

            if ($json === null) {
                return false;
            }
            if (!is_array($json)) {
                return false;
            }
        }

        $json[$code] = $new_value;

        $this->$field = json_encode($json);
        return true;
    }
}
