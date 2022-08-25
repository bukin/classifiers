<?php

namespace InetStudio\ClassifiersPackage\Entries\Presentation\JsonApi\V1;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class EntryRequest extends ResourceRequest
{
    public function messages(): array
    {
        return [
            'value.required' => 'Поле «Значение» обязательно для заполнения',
            'value.max' => 'Поле «Значение» не должно превышать 255 символов',
            'alias.max' => 'Поле «Алиас» не должно превышать 255 символов',
            'alias.unique' => 'Такое значение поля «Алиас» уже существует',
            'groups.required' => 'Поле «Группы» обязательно для заполнения',
        ];
    }

    public function rules(): array
    {
        $unique = Rule::unique('classifiers_package_entries');

        if ($entry = $this->model()) {
            $unique->ignore($entry);
        }

        return [
            'id' => ['required', JsonApiRule::clientId()],
            'value' => ['required', 'max:255', 'string'],
            'alias' => ['nullable', 'max:255', 'string', $unique],
            'groups' => ['required', JsonApiRule::toMany()],
        ];
    }
}
