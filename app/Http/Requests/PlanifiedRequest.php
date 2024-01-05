<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanifiedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'distribution_id' => 'required|integer',
            'execution_date' => 'required',
            'driver_id' => 'required|integer',
            'vehicle_id' => 'required|integer',
            /*'client_id' => 'nullable|string',
            'id_type_ot' => 'nullable|string',
            'volume' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'qty' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
            'axe_distribution' => 'nullable|string',*/
        ];
    }
}
