<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;   //CalendarViewクラス(カレンダーを出力するためのクラス。重要!!! 2024/8/25)
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }
    // ↑↑time()を使用し、現在時刻を渡して今月のカレンダーを用意する。
    //  return view～でViewに作成したCalendarViewオブジェクトを渡す。

    // Reserve=予約
    public function reserve(Request $request){
        // dd($request);
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;   //予約枠の取得
            $getDate = $request->getData;   //カレンダーの日付の取得
            $reserveDays = array_filter(array_combine($getDate, $getPart));   //array_combine()で予約枠と日付を結合し予約設定を更新する。
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');   //decrementは予約枠を減らす
                $reserve_settings->users()->attach(Auth::id());   //attachでユーザーの関連付けをする
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    // ↓↓予約キャンセル機能(2024/9/15)
    public function delete(Request $request){
        // dd($request);
        // beginTransaction(トランザクション)=データの一貫性と完全性を保護するために使用する。
        // 一貫性の確保: トランザクション内の操作は、すべてが成功した場合のみコミットされ、一部の操作が失敗した場合はロールバックされる。これにより、データベースの状態が一貫していることが保証される。
        // エラー時のデータ復元: トランザクション内でエラーが発生した場合、自動的にロールバックされる。これにより、エラーが発生しても、データベースは以前の状態に戻る。
        // 同時アクセスの問題の回避: 複数のユーザーが同時にデータベースにアクセスする場合、トランザクションを使用することで、競合状態（複数の処理が同時にデータを変更しようとする状況）を防ぐことができる。
        DB::beginTransaction();
        try{   //トランザクション内で行う操作の記述
            $getPart = $request->getPart;   //予約枠の取得
            $getDate = $request->getData;   //カレンダーの日付の取得
            // dd($request->getPart, $request->getDate);

            // ↓↓配列かどうかを確認する 2024/10/6
            if (!is_array($getPart) || !is_array($getDate) || count($getPart) !== count($getDate)) {
            return redirect()->back()->with('error', '無効なデータが提供されました。');
            }


            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->increment('limit_users');   //decrement→increment変更。incrementは予約枠を増やす。2024/10/5
                $reserve_settings->users()->detach(Auth::id());   //detach()でユーザーの関連付けを解除する。2024/10/6
            }
            DB::commit();
        }catch(\Exception $e){   //トランザクションの途中でエラーが発生した場合の処理
            DB::rollback();   //変更をすべて無効にする
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
