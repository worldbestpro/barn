<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class WeightLitterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        if ((int)$this->get('kits_weighed') != $this->route('litters')->kitsCount())
//            return false;

        return true;
    }

    public function forbiddenResponse()
    {
        return response()->json(['error'=>'Not all kits were weighed'], 422);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
