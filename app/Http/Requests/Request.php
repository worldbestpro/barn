<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    protected function getValidatorInstance() {
        if (method_exists($this, 'sanitize')) {
            $this->sanitize();
        }
        return parent::getValidatorInstance();
    }
}
