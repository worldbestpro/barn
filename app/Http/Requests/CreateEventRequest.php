<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateEventRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function sanitize()
    {
        if ($this->get('recurring')) {
            $input['recurring'] = 1;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['name'] = 'required';
        $rules['icon'] = 'required';
        $rules['date'] = 'required|date_format:m/d/Y';

        if ($this->get('type') != 'general')
            $rules['type_id'] = 'required';

        return $rules;
    }

    public function messages()
    {
        $messages['type_id.required'] = 'You must attach event to ' . $this->get('type');

        return $messages;
    }
}
