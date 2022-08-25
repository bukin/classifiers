<?php

namespace InetStudio\ClassifiersPackage\Groups\Presentation\Http\Requests\Back\Tables\Index;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\DataTransferObject\DataTransferObject;

class GetTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [];
    }

    public function rules(): array
    {
        return [];
    }

    public function getDataObject(): ?DataTransferObject
    {
        return null;
    }
}
