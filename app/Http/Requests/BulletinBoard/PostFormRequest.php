<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
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
    public function rules()   //バリデーションルールの変更(2024/7/19)
    {
        return [
            'post_title' => 'min:4|string|max:100',    //maxを50→100へ変更
            'post_body' => 'min:10|string|max:1000',   //maxを500→1000へ変更
        ];
    }

    public function messages(){   //バリデーションメッセージの変更(2024/7/19)
        return [
            'post_title.min' => 'タイトルは4文字以上入力してください。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',
            'post_body.min' => '内容は10文字以上入力してください。',
            'post_body.max' => '最大文字数は1000文字です。',
        ];
    }
}
