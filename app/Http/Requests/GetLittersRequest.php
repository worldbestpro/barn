<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Cache;

class GetLittersRequest extends Request
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
        $input['archived'] = $this->has('archived') ? (int)$this->get('archived') : null;
        $cachedOrderKey = 'user-' . \Auth::user()->id .'-litter-sort';

        if($this->has('order')){
            Cache::forever($cachedOrderKey,  $this->get('order'));
        }
        $cachedOrder = Cache::get($cachedOrderKey, function(){
            return 'id';
        });
        $orderParameters = explode('|', $cachedOrder);

        $input['order'] = array_shift($orderParameters);
        $input['orderDirection'] = !empty($orderParameters)? array_shift($orderParameters) : 'asc';
        
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
