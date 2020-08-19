<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait LangsTrait
{
    protected $changed;

    public function __construct()
    {
        $this->changed = [];
    }

    public function getFieldValueByLang($field, $code)
    {
        $table = $this->getTable();
        $table_lang = $table.'_'.$code;

        if (!Schema::hasTable($table_lang)) {
            return null;
        }

        $row = DB::table($table_lang)->find($this->id);
        if ($row === null) {
            return $row;
        }
        $value = $row->$field;
        return $value;
    }

    public function setFieldValueByLang($field, $code, $new_value)
    {
        $this->changed[] = [
            'field' => $field,
            'code' => $code,
            'new_value' => $new_value,
        ];
    }

    public function save(array $options = [])
    {
        parent::save($options);

        foreach ($this->changed as $changed) {
            $field = $changed['field'];
            $code = $changed['code'];
            $new_value = $changed['new_value'];

            $table = $this->getTable();
            if (!Schema::hasTable($table)) {
                return false;
            }

            $table_lang = $table.'_'.$code;
            if (!Schema::hasTable($table_lang)) {
                Schema::create($table_lang, function (Blueprint $table) {
                    $table->unsignedBigInteger('id')->unique();
                    $table->string('title')->nullable();
                    $table->string('content')->nullable();
                });
            }

            $row = DB::table($table_lang)->find($this->id);
            if (!$row) {
                $row = [
                    'id' => $this->id,
                ];
            }
            $row[$field] = $new_value;
            DB::table($table_lang)->updateOrInsert($row);
        }
        return true;
    }
}
