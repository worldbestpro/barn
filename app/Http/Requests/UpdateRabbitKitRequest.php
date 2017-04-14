<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateRabbitKitRequest extends Request
{
    protected $kit;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('kits')->user->id != auth()->user()->id)
            return false;

        return true;
    }

    public function sanitize()
    {
        if ( !$this->has('return_count')) {
            $input['return_count'] = null;
            $this->merge($input);
        }
        if ( !$this->has('weight_changed')) {
            $input['weight_changed'] = null;
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
        if (count($this->get('weight')) > 3)
            $rules['weighFail'] = 'required';

//        if (count($this->route('kits')->weight) != count($this->get('weight')))
//            $rules['weightCheck'] = 'required';

//        $rules['weight.1'] = 'required_with:weight.0';
//        $rules['weight.2'] = 'required_with_all:weight.0,weight.1';

        return [];
    }

    public function messages()
    {
        return [
            'weightCheck.required'   => 'You can\'t weight kit before the litter weigh',
            'weight.1.required_with' => 'You can\'t weight kit before the litter weigh',
            'weight.2.required_with' => 'You can\'t weight kit before the litter weigh',
            'weighFail.required'     => 'Only 3 weighs available',
        ];
    }
}
