<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class WeighKitRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (count($this->route('kits')->weight) >= 3)
            return false;

        return true;
    }

    public function forbiddenResponse()
    {
        return response()->json(['error'=>'You cant weight kit more than 3 times'],403);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_weight' => 'required',
        ];
    }
}
