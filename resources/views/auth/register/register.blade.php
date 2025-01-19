<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AtlasBulletinBoard</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300&family=Oswald:wght@200&display=swap" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<!-- ↓↓class="all_content"を追加して背景色を変える(style.cssの10行目) 2024/11/17 -->
<body class="all_content">



  <form action="{{ route('registerPost') }}" method="POST" class="register_form">
    <!-- <div class="w-100 vh-75 d-flex" style="align-items:center; justify-content:center;"> -->
    <div class="w-100 d-flex" style="align-items:center; justify-content:center;">
      <!-- <div class="w-25 vh-75 border p-3 register-box"> -->
      <div class="border p-3 register-box">
        <!-- <div class="register_form"> -->
          <div class="d-flex mt-3" style="justify-content:space-between">
            <div class="" style="width:140px">
              @if ($errors->has('over_name'))
               <li>{{$errors->first('over_name')}}</li>
              @endif
              <label class="d-block m-0" style="font-size:13px">姓</label>
              <div class="border-bottom border-primary" style="width:140px;">
                <input type="text" style="width:140px;" class="border-0 over_name" name="over_name">
              </div>
            </div>

            <div class="" style="width:140px">
              @if ($errors->has('under_name'))
               <li>{{$errors->first('under_name')}}</li>
              @endif
              <label class=" d-block m-0" style="font-size:13px">名</label>
              <div class="border-bottom border-primary" style="width:140px;">
                <input type="text" style="width:140px;" class="border-0 under_name" name="under_name">
              </div>
            </div>
          </div>

          <div class="d-flex mt-3" style="justify-content:space-between">
            <div class="" style="width:140px">
              @if ($errors->has('over_name_kana'))
               <li>{{$errors->first('over_name_kana')}}</li>
              @endif
              <label class="d-block m-0" style="font-size:13px">セイ</label>
              <div class="border-bottom border-primary" style="width:140px;">
                <input type="text" style="width:140px;" class="border-0 over_name_kana" name="over_name_kana">
              </div>
            </div>

            <div class="" style="width:140px">
              @if ($errors->has('over_name'))
               <li>{{$errors->first('over_name')}}</li>
              @endif
              <label class="d-block m-0" style="font-size:13px">メイ</label>
              <div class="border-bottom border-primary" style="width:140px;">
                <input type="text" style="width:140px;" class="border-0 under_name_kana" name="under_name_kana">
              </div>
            </div>
          </div>

          <div class="mt-3">
            @if ($errors->has('mail_address'))
             <li>{{$errors->first('mail_address')}}</li>
            @endif
            <label class="m-0 d-block" style="font-size:13px">メールアドレス</label>
            <div class="border-bottom border-primary">
              <input type="mail" class="w-100 border-0 mail_address" name="mail_address">
            </div>
          </div>
        <!-- </div> -->

        <!-- ↓↓mt-3=margin topが1rem  px-5=paddingが2rem(xは左右。yだと上下を調節できる。)2024/1/13 -->
          <div class="mt-3 register-item px-5">
            @if ($errors->has('sex'))
            <li>{{$errors->first('sex')}}</li>
            @endif
            <!-- <input type="radio" name="sex" class="sex" value="1"> -->
            <label style="font-size:13px"><input type="radio" name="sex" class="sex" value="1">男性</label>
            <!-- <input type="radio" name="sex" class="sex" value="2"> -->
            <label style="font-size:13px"><input type="radio" name="sex" class="sex" value="2">女性</label>
            <!-- <input type="radio" name="sex" class="sex" value="3"> -->
            <label style="font-size:13px"><input type="radio" name="sex" class="sex" value="3">その他</label>
          </div>

          <div class="mt-3 register-item">
            @if ($errors->has('birth_day'))
            <li>{{$errors->first('birth_day')}}</li>
            @endif
            <label class="d-block m-0 aa w-100" style="font-size:13px">生年月日</label>
            <select class="old_year border-bottom border-primary" name="old_year">
              <option value="none">-----</option>
              <option value="1985">1985</option>
              <option value="1986">1986</option>
              <option value="1987">1987</option>
              <option value="1988">1988</option>
              <option value="1989">1989</option>
              <option value="1990">1990</option>
              <option value="1991">1991</option>
              <option value="1992">1992</option>
              <option value="1993">1993</option>
              <option value="1994">1994</option>
              <option value="1995">1995</option>
              <option value="1996">1996</option>
            <option value="1997">1997</option>
            <option value="1998">1998</option>
            <option value="1999">1999</option>
            <option value="2000">2000</option>
            <option value="2001">2001</option>
            <option value="2002">2002</option>
            <option value="2003">2003</option>
            <option value="2004">2004</option>
            <option value="2005">2005</option>
            <option value="2006">2006</option>
            <option value="2007">2007</option>
            <option value="2008">2008</option>
            <option value="2009">2009</option>
            <option value="2010">2010</option>
          </select>
          <label style="font-size:13px">年</label>
          <select class="old_month border-bottom border-primary" name="old_month">
            <option value="none">-----</option>
            <option value="01">1</option>
            <option value="02">2</option>
            <option value="03">3</option>
            <option value="04">4</option>
            <option value="05">5</option>
            <option value="06">6</option>
            <option value="07">7</option>
            <option value="08">8</option>
            <option value="09">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
          </select>
          <label style="font-size:13px">月</label>
          <select class="old_day border-bottom border-primary" name="old_day">
            <option value="none">-----</option>
            <option value="01">1</option>
            <option value="02">2</option>
            <option value="03">3</option>
            <option value="04">4</option>
            <option value="05">5</option>
            <option value="06">6</option>
            <option value="07">7</option>
            <option value="08">8</option>
            <option value="09">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">19</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
            <option value="31">31</option>
          </select>
          <label style="font-size:13px">日</label>
        </div>

        <div class="mt-3 register-item">
          @if ($errors->has('role'))
          <li>{{$errors->first('role')}}</li>
          @endif
          <label class="d-block m-0 w-100" style="font-size:13px">役職</label>
          <input type="radio" name="role" class="admin_role role" value="1">
          <label style="font-size:13px">教師(国語)</label>
          <input type="radio" name="role" class="admin_role role" value="2">
          <label style="font-size:13px">教師(数学)</label>
          <input type="radio" name="role" class="admin_role role" value="3">
          <label style="font-size:13px">教師(英語)</label>
          <input type="radio" name="role" class="other_role role" value="4">
          <label style="font-size:13px" class="other_role">生徒</label>
        </div>

        <div class="select_teacher d-none">
          <label class="d-block m-0" style="font-size:13px">選択科目</label>
          @foreach($subjects as $subject)
          <div class="">
            <input type="checkbox" name="subject[]" value="{{ $subject->id }}">
            <label>{{ $subject->subject }}</label>
          </div>
          @endforeach
        </div>

        <div class="mt-3">
          @if ($errors->has('password'))
          <li>{{$errors->first('password')}}</li>
          @endif
          <label class="d-block m-0" style="font-size:13px">パスワード</label>
          <div class="border-bottom border-primary">
            <input type="password" class="border-0 w-100 password" name="password">
          </div>
        </div>

        <div class="mt-3">
          @if ($errors->has('password_confirmation'))
          <li>{{$errors->first('password_confirmation')}}</li>
          @endif
          <label class="d-block m-0" style="font-size:13px">確認用パスワード</label>
          <div class="border-bottom border-primary">
            <input type="password" class="border-0 w-100 password_confirmation" name="password_confirmation">
          </div>
        </div>

        <div class="mt-5 text-right">
          <input type="submit" class="btn btn-primary register_btn" disabled value="新規登録" onclick="return confirm('登録してよろしいですか？')">
        </div>


        <div class="text-center">
          <a href="{{ route('loginView') }}">ログインはこちら</a>
        </div>
      </div>
      {{ csrf_field() }}
    </div>
  </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="{{ asset('js/register.js') }}" rel="stylesheet"></script>
</body>
</html>
