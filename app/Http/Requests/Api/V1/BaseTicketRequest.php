<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

abstract class BaseTicketRequest extends FormRequest
{
    public function mappedAttributes(){

        $mappedAttributes = [
            'data.attributes.title' => 'title',
            'data.attributes.description' => 'description',
            'data.attributes.status' => 'status',
            'data.attributes.createdAt' => 'created_at',
            'data.attributes.updatedAt' => 'updated_at',
            'data.relationships.auther.data.id' => 'user_id',
        ];

        $attributesToUpdate = [];
        foreach($mappedAttributes as $key => $attribute){
            if($this->has($key)){
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }

    #[Override]
    public function messages()
    {
        return [
            'data.attributes.status' => 'the status should be A(Active), C(Completed), H(idk), or X(Canceled)'
        ];
    }
}
