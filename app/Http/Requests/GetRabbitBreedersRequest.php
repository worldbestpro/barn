<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Cache;
use Auth;

class GetRabbitBreedersRequest extends Request
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
        $input['sex']      = $this->has('sex') ? $this->get('sex') : null;
        $input['archived'] = $this->has('archived') ? (int)$this->get('archived') : null;

        $cachedOrderKey = 'user-' . Auth::user()->id .'-breeder-sort';

        if($this->has('order')){
            Cache::forever($cachedOrderKey,  $this->get('order'));
        }
        $cachedOrder = Cache::get($cachedOrderKey, function(){
            return 'id';
        });
        $input['order'] = $cachedOrder;
        if ($this->has('sex') && $this->get('sex') != 'buck') {
            $input['sex'] = 'doe';
        }

        if ($this->has('archived') && $this->get('archived') != 0) {
            $input['archived'] = 1;
        }
        $this->merge($input);
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
