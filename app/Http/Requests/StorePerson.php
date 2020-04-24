<?php

namespace App\Http\Requests;

use App\Helpers\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePerson extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'cpf' => [
                'required_without:person_id',
                'cpf',
                Rule::unique('persons')->ignore($this->person_id)
            ],
            'birthday' => 'required_without:person_id|date_format:d/m/Y|before:today',
            'email' => [
                'required_without:person_id',
                'email',
                Rule::unique('company_users')->ignore($this->person_id)
            ],
            'phone' => [
                'required_without:person_id',
                Rule::unique('persons')->ignore($this->person_id)
            ]
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'cpf' => Helper::removePunctuation($this->cpf),
            'phone' => Helper::removePunctuation($this->phone)
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'unique' => 'O :attribute fornecido já está sendo utilizado.',
            'before' => 'O campo :attribute deve ser uma data anterior a hoje.',
            'birthday.date_format' => 'A Data de Nascimento é inválida'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'email' => 'e-mail',
            'cpf' => 'CPF',
            'phone' => 'Telefone'
        ];
    }
}
