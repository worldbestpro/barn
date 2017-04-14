<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class GetEventsRequest extends Request
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
        if (!$this->has('for')) {
            $input['for'] = null;
            $this->merge($input);
        }
        if (!$this->has('sinceToday') || $this->get('sinceToday') != 'true') {
            $input['sinceToday'] = false;
            $this->merge($input);
        } else {
            $input['sinceToday'] = (bool)$this->get('sinceToday');
            $this->merge($input);
        }
        if (!$this->has('expired') || $this->get('expired') != 'true') {
            $input['expired'] = false;
            $this->merge($input);
        } else {
            $input['expired'] = (bool)$this->get('expired');
            $this->merge($input);
        }

        if (!$this->has('perPage')) {
            $input['perPage'] = 3;
            $this->merge($input);
        }
        if (!$this->has('skipBreeders')) {
            $input['skipBreeders'] = 0;
            $this->merge($input);
        }
        if (!$this->has('skipLitters')) {
            $input['skipLitters'] = 0;
            $this->merge($input);
        }
        if (!$this->has('type')) {
            $input['type'] = null;
            $this->merge($input);
        }
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
