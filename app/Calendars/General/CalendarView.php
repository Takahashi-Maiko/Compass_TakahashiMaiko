<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  // ↓↓カレンダーを形成している記述
  function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';

      $days = $week->getDays();
      foreach($days as $day){
        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");

        if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
          $html[] = '<td class="past-day">';
        }else{
          $html[] = '<td class="calendar-td '.$day->getClassName().'">';
        }
        $html[] = $day->render();

        // ↓↓予約の機能
        if(in_array($day->everyDay(), $day->authReserveDay())){   //ログインしているユーザーが予約していたら
          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
          if($reservePart == 1){
            $reservePart = "リモ1部";
          }else if($reservePart == 2){
            $reservePart = "リモ2部";
          }else if($reservePart == 3){
            $reservePart = "リモ3部";
          }
          if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){   //過去の場合かつ予約している場合
            $html[] = '<p class="">受付終了</p>';   //過去日グレーアウト
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px"></p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }else{   //未来の場合かつ予約している場合
            $html[] = '<button type="submit" class="btn btn-danger delete-modal-open p-0 w-75" name="delete_date" style="font-size:12px" reserveDate='.$day->everyDay().' reserveParts='.$reservePart.' value="'. $day->authReserveDate($day->everyDay())->first()->setting_reserve .'">'. $reservePart .'</button>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
            $html[] = '<input type="hidden" day='.$day->everyDay().' value="" form="reserveParts">';
            // モーダル（js）に送る記述が必要(2024/9/24)
            // reserveDate = "<button type="submit">"
            // reservePart = "<button type="submit">"

          }
        }else{   //ログインしているユーザーが予約していない場合
          if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){   //過去の場合かつ予約していない場合
          $html[] = '<p class="">受付終了</p>';   //過去日グレーアウト
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }else{   //未来の場合かつ予約していない場合
            $html[] = $day->selectPart($day->everyDay());
          }
        }
        $html[] = $day->getDate();
        $html[] = '</p>';

          //予約キャンセル機能の実装をしたい(2024/9/7)

      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    return implode('', $html);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
