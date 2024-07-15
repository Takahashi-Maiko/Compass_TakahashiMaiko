<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class RegisterFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()  //authorizeは利用の許可判断の為のメソッド。
    {
        return true;  //trueに変更しなければ表示されない。(最初は自動的にfalseになっている。)
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    //  ↓↓生年月日の結合(2024/7/14)
    public function getValidatorInstance()
    {
        $year = $this->input('old_year');
        $month = $this->input('old_month');
        $day = $this->input('old_day');
        $birth_day = $year . '-' . $month . '-' . $day;
            $this->merge([   //データを追加する(現在のリクエストインスタンスに)
                'birth_day' => $birth_day,
            ]);

        return parent::getValidatorInstance();   //この記述がないとバリデーションにつながらない
    }


    public function rules()  //バリデーション条件の定義
    {
        return [
            //記述方法→→['検証する値'=>'検証ルール1 | 検証ルール2',] もしくは['検証する値'=>['検証ルール1','検証ルール2'],]
            //required(入力必須)
            //string(文字列であるかどうか)
            'over_name' => 'required|string|min:1|max:10',
            'under_name' => 'required|string|min:1|max:10',
            'over_name_kana' => 'required|string|min:1|max:30|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',   //regexでカタカナ表記
            'under_name_kana' => 'required|string|min:1|max:30|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',   //regexでカタカナ表記
            'mail_address' => 'required|min:5|max:100|unique:users,mail_address|email',   //登録済みのものは無効にする
            'sex' => 'required',
            'birth_day' => 'required|after:1999-12-31|date',
            'role' => 'required',
            'password' => 'required|regex:/^[a-zA-Z0-9]+$/|min:8|max:30|confirmed:password',
            'password_confirmation' => 'required|regex:/^[0-9a-zA-Z]+$/|min:8|max:30',    //regex:/^[a-zA-Z0-9]+$/=正規表現の「半角英数字のみ(空文字OK)」
        ];
    }
    // ↓↓バリデーションメッセージ'(2024/7/7)改修課題様に変える！
    public function messages()
    {
        return [
            'over_name.required' => '姓は入力必須です。',
            'over_name.string' => '姓は文字列で入力して下さい。',
            'over_name.min' => '姓は1文字以上で入力してください。',
            'over_name.max' => '姓は10文字以下で入力してください。',

            'under_name.required' => '名は入力必須です。',
            'under_name.string' => '名は文字列で入力して下さい。',
            'under_name.min' => '名は1文字以上で入力してください。',
            'under_name.max' => '名は10文字以下で入力してください。',

            'over_name_kana.required' => 'セイは入力必須です。',
            'over_name_kana.string' => 'セイは文字列で入力して下さい。',
            'over_name_kana.min' => 'セイは1文字以上で入力してください。',
            'over_name_kana.max' => 'セイは30文字以下で入力してください。',
            'over_name_kana.regex' => 'セイはカタカナで入力してください。',

            'under_name_kana.required' => 'メイは入力必須です。',
            'under_name_kana.string' => 'メイは文字列で入力して下さい。',
            'under_name_kana.min' => 'メイは1文字以上で入力してください。',
            'under_name_kana.max' => 'メイは30文字以下で入力してください。',
            'under_name_kana.regex' => 'メイはカタカナで入力してください。',

            'mail_address.required' => 'メールアドレスは入力必須です。',
            'mail_address.min' => 'メールアドレスは5文字以上で入力してください。',
            'mail_address.max' => 'メールアドレスは100文字以下で入力してください。',
            'mail_address.unique' => '登録済みのメールアドレスは使用不可です。',
            'mail_address.email' => 'メールアドレスの形式で入力してください。',

            'sex.required' => '性別は入力必須です。',

            'birth_day.required' => '生年月日は入力必須です。',
            'birth_day.date' => '正しい日付で入力して下さい。',
            'birth_day.after' => '生年月日は2000年1月1日から本日までの日付で入力して下さい。',

            // 'old_year.required' => '年は入力必須です。',
            // 'old_year.after_or_equal' => '年は2000年以上で指定してください。',

            // 'old_month.required' => '月は入力必須です。',
            // 'old_month.date' => '正しい日付を入力して下さい。',

            // 'old_day.required' => '日は入力必須です。',
            // 'old_day.date' => '正しい日付を入力して下さい。',

            'role.required' => '権限は入力必須です。',

            'password.required' => 'パスワードは入力必須です。',
            'password.regex' => 'パスワードは英数字のみで入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは30文字以下で入力してください。',
            'password.confirmed' => 'パスワードが一致していません。',

            'password_confirmation.required' => 'パスワード確認は入力必須です。',
            'password_confirmation.regex' => 'パスワード確認は英数字のみで入力してください。',
            'password_confirmation.min' => 'パスワード確認は8文字以上で入力してください。',
            'password_confirmation.max' => 'パスワード確認は20文字以下で入力してください。'
        ];
    }
}
