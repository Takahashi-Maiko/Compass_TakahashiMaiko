<?php

namespace App\Http\Controllers\Authenticated\Calendar\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\Admin\CalendarView;
use App\Calendars\Admin\CalendarSettingView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    // ↓↓スクール予約確認(2024/7/15)
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.admin.calendar', compact('calendar'));
    }

    // ↓↓スクール予約詳細画面にデータを送る記述が必要(2024/10/26)
    public function reserveDetail($date, $part){
        $reservePersons = ReserveSettings::with('users')->where('setting_reserve', $date)->where('setting_part', $part)->get();
        // dd($date,$part);
        // 'setting_reserve', $date=日にち
        // 'setting_part', $part=部

        // ユーザーIDと名前のリストを作成
        $users = [];
        foreach ($reservePersons as $reserve) {
            foreach ($reserve->users as $user) {
                $users[] = [
                    'id' => $user->id,
                    'over_name' => $user->over_name,
                    'under_name' => $user->under_name,
                ];
            }
        }
        return view('authenticated.calendar.admin.reserve_detail', compact('reservePersons', 'date', 'part', 'users'));
    }

    // ↓↓スクール枠登録(2024/7/15)
    // Reserve=予約
    public function reserveSettings(){
        $calendar = new CalendarSettingView(time());
        return view('authenticated.calendar.admin.reserve_setting', compact('calendar'));
    }

    public function updateSettings(Request $request){
        $reserveDays = $request->input('reserve_day');
        foreach($reserveDays as $day => $parts){
            foreach($parts as $part => $frame){
                ReserveSettings::updateOrCreate([
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                ],[
                    'setting_reserve' => $day,
                    'setting_part' => $part,
                    'limit_users' => $frame,
                ]);
            }
        }
        return redirect()->route('calendar.admin.setting', ['user_id' => Auth::id()]);
    }
}
