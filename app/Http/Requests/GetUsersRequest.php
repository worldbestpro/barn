<?php

namespace App\Http\Requests;


class GetUsersRequest extends Request
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
        if ( !$this->has('paginated')) {
            $input['paginated'] = false;
        }
        else {
            $input['paginated'] = (int)(bool)$this->get('paginated');
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
