<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ArchiveRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ( !$this->has('archived'))
            return false;

        return true;
    }

    public function sanitize()
    {
        $input = [];
        if ($this->get('archived') != 1) {
            $input['archived'] = 0;
        }
        $this->merge($input);
    }

    public function forbiddenResponse()
    {
        return response()->json(['error' => 'No param'], 403);
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
