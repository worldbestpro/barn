<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateLitterRequest extends Request
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
        if ( !$this->has('breedplan')) {
            $input['breedplan'] = null;
            $this->merge($input);
        }
        if ( !$this->has('bred')) {
            $input['bred'] = null;
            $this->merge($input);
        }
        if ( !$this->has('father_id')) {
            $input['father_id'] = null;
            $this->merge($input);
        }
        if ( !$this->has('mother_id')) {
            $input['mother_id'] = null;
            $this->merge($input);
        }
        if ( !$this->has('animal_type')) {
            $input['animal_type'] = 'rabbit';
            $this->merge($input);
        }
        if ( !$this->has('notes')) {
            $input['notes'] = null;
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
        if(!$this->get('breedplan')){
            $rules['bred']        = 'required|date_format:m/d/Y';
            $rules['father_id']        = 'required';
            $rules['mother_id']        = 'required';
        }

        $rules['given_id']    = 'required';
        $rules['born']        = 'required|date_format:m/d/Y';
        $rules['kits_amount'] = 'required|integer';

        return $rules;
    }
}
