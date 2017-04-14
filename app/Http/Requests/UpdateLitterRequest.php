<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateLitterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (count($this->route('litters')->weighs) > 0 && $this->route('litters')->kits_amount != $this->get('kits_amount'))
            return false;

        return true;
    }

    public function forbiddenResponse()
    {
        return response()->json(['error'=>['kits_amount'=>'You can change kits amount only before first weigh']]);
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
