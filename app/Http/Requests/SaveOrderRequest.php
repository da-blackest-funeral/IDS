<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveOrderRequest extends FormRequest
{
    protected array $rulesForCategories = [
        'glazed_windows' => [
            'ids' => ['14', '15', '16'],
            'rules' => [
                "height" => ["required", "numeric"],
                "width" => ["required", "numeric"],
                "count" => ["required", "numeric"],
                "glass-width-1" => ["required", "numeric", "min:0", "not_in:0"],
                "cameras-width-1" => ["required", "numeric", "min:0", "not_in:0"],
                "glass-width-2" => ["required", "numeric", "min:0", "not_in:0"],
                "installation" => ["required"],
                "coefficient" => ["required"],
                "add-mount-tools" => ["required"],
                "takeaway" => ["required"],
                "fast" => ["required"],
            ],
            'messages' => [
                "height.required" => 'Заполните поле "высота".',
                "height.numeric" => 'Высота должна быть числом.',
                "width.required" => 'Заполните поле "ширина".',
                "width.numeric" => 'Ширина должна быть числом.',
                "count.required" => 'Заполните поле "количество".',
                "count.numeric" => 'Количество должно быть числом.',
                "glass-width-1.*" => 'Заполните поле "ширина" у "1-е стекло".',
                "glass-width-2.*" => 'Заполните поле "ширина" у "2-е стекло".',
                "cameras-width-1.*" => 'Заполните поле "ширина" у "1-я камера".',
            ],
        ],
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return $this->getCategoryAttributes('rules');
    }

    public function messages() {
        return $this->getCategoryAttributes('messages');
    }

    protected function getCategoryAttributes(string $name): array {
        foreach ($this->rulesForCategories as $category) {
            if (in_array($this->input('categories'), $category['ids'])) {
                return $category[$name];
            }
        }

        return [];
    }
}
