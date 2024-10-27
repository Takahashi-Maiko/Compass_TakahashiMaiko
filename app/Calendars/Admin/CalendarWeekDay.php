<?php
namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

// ↓↓カレンダーの日を出力するためのCalendarWeekDayクラス(2024/8/25)
class CalendarWeekDay{
  protected $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  function getClassName(){
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function render(){
    return '<p class="day">' . $this->carbon->format("j") . '日</p>';
  }

  function everyDay(){
    return $this->carbon->format("Y-m-d");
  }

  function dayPartCounts($ymd){
    $html = [];
    $one_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '1')->first();
    $two_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '2')->first();
    $three_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '3')->first();

    // ↓↓予約確認画面の予約人数を表示させたい(2024/10/25)
    // ReserveSettingsモデルにusersリレーションが正しく定義されいれば変数を定義して各部の予約人数を表示できるようになります。
    $html[] = '<div class="text-left">';
    // 1部の表示
    if($one_part){
      $userCount = $one_part->users->count(); // ユーザー数を取得
      // $html[] = '<p class="day_part m-0 pt-1"><a href="/calendar/{date}/{part}">1部' . $userCount .'</a></p>';   //hrefで詳細画面のリンクを付ける ここ修正
      $html[] = '<p class="day_part m-0 pt-1"><a href="/calendar/' . $this->carbon->format('Y-m-d') . '/1">1部 ' . $userCount . '</a></p>';
    }
    // 2部の表示
    if($two_part){
      $userCount = $two_part->users->count();
      // $html[] = '<p class="day_part m-0 pt-1"><a href="/calendar/{date}/{part}">2部'. $userCount .'</a></p>';
      $html[] = '<p class="day_part m-0 pt-1"><a href="/calendar/' . $this->carbon->format('Y-m-d') . '/2">2部 ' . $userCount . '</a></p>';
    }
    // 3部の表示
    if($three_part){
       $userCount = $three_part->users->count();
      // $html[] = '<p class="day_part m-0 pt-1"><a href="/calendar/{date}/{part}">3部'. $userCount .'</a></p>';
      $html[] = '<p class="day_part m-0 pt-1"><a href="/calendar/' . $this->carbon->format('Y-m-d') . '/3">3部 ' . $userCount . '</a></p>';
    }
    // リンクの部分で$this->carbon->format('Y-m-d')を使って日付を取得し、部数（1、2、3）も動的に指定する。
    // この形式で、URLが/calendar/2024-10-25/1のように生成される。
    $html[] = '</div>';

    return implode("", $html);
  }


  function onePartFrame($day){
    $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first();
    if($one_part_frame){
      $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first()->limit_users;
    }else{
      $one_part_frame = "20";
    }
    return $one_part_frame;
  }
  function twoPartFrame($day){
    $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first();
    if($two_part_frame){
      $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first()->limit_users;
    }else{
      $two_part_frame = "20";
    }
    return $two_part_frame;
  }
  function threePartFrame($day){
    $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first();
    if($three_part_frame){
      $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first()->limit_users;
    }else{
      $three_part_frame = "20";
    }
    return $three_part_frame;
  }

  //
  function dayNumberAdjustment(){
    $html = [];
    $html[] = '<div class="adjust-area">';
    $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="1" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="2" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="3" type="text" form="reserveSetting"></p>';
    $html[] = '</div>';
    return implode('', $html);
  }
}
