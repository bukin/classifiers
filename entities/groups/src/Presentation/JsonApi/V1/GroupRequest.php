<?php

namespace InetStudio\ClassifiersPackage\Groups\Presentation\JsonApi\V1;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class GroupRequest extends ResourceRequest
{
    public function messages(): array
    {
        return [
            'name.required' => 'Поле «Название» обязательно для заполнения',
            'name.max' => 'Поле «Название» не должно превышать 255 символов',
            'alias.required' => 'Поле «Алиас» обязательно для заполнения',
            'alias.max' => 'Поле «Алиас» не должно превышать 255 символов',
            'alias.unique' => 'Такое значение поля «Алиас» уже существует',
        ];
    }

    public function rules(): array
    {
        $unique = Rule::unique('classifiers_package_groups');

        if ($group = $this->model()) {
            $unique->ignore($group);
        }

        return [
            'id' => ['required', JsonApiRule::clientId()],
            'name' => ['required', 'max:255', 'string'],
            'alias' => ['required', 'max:255', 'string', $unique],
            'description' => ['nullable', 'string'],
            'entries' => ['nullable', JsonApiRule::toMany()],
        ];
    }
}
