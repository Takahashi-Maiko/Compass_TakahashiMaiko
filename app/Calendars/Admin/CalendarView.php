<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Users\User;

// CalendarViewクラスはカレンダーを出力するためのクラス(2024/8/25)
// これはLaravelの機能で作るものではない自作のクラスの為、コマンドではなく手動で自作する。
class CalendarView{
  private $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  // ↓↓タイトル
  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  // ↓↓カレンダーを出力する
  // tr= 「Table Row」 「表の行」という意味。trタグは「表の行」の要素に値する。
  // th= 「Table Header」 「表の見出し」という意味。thタグは「表の見出し」の要素に値する。
  // td= 「Table Data」 「表のデータ」という意味。tdタグは「表のデータ」の要素に値する。
  public function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border">土</th>';
    $html[] = '<th class="border">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    $weeks = $this->getWeeks();

    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();
      foreach($days as $day){
        $startDay = $this->carbon->format("Y-m-01");
        $toDay = $this->carbon->format("Y-m-d");
        if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
          $html[] = '<td class="past-day border">';   //past-dayが過去日のグレーアウトにかかわっている
        }else{
          $html[] = '<td class="border '.$day->getClassName().'">';
        }
        $html[] = $day->render();
        $html[] = $day->dayPartCounts($day->everyDay());
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';

    return implode("", $html);
  }

  // ↓↓週の情報を取得するためのgetWeeks()関数
  // getWeeks()関数は週カレンダーを一月分用意した配列$weeksを返却するのが目的
  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();   //初日
    $lastDay = $this->carbon->copy()->lastOfMonth();   //月末まで
    // ↑↑71,72行目は月の開始日と末尾を取得する処理。Carbonを使うと、便利なメソッドを使って日付の操作を行うことができる。
    //   copy()を間に挟むことで日付操作をしても影響が出ないようにしている。
    $week = new CalendarWeek($firstDay->copy());   //1週目
    $weeks[] = $week;                              //1週目
    // ↑↑75,76行目で一週目、1日を指定してCalendarWeekを作成している。
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();   //作業用の日
    // ↑↑78行目で作業用の日を作成する。翌週の月曜日が欲しいので、+7日した後、週の開始日に移動する記述。その後、月末までループしながら一週毎にCalendarWeekを作成していく。
    while($tmpDay->lte($lastDay)){     //月末までループさせる
      $week = new CalendarWeek($tmpDay, count($weeks));   //週カレンダーViewを作成する
      $weeks[] = $week;     //週カレンダーViewを作成する
      $tmpDay->addDay(7);   //次の週+7日する
      // ↑↑80~83行目で一周毎に+7日することで$tmpDayを翌週に移動している。
      // 81,82行目でCalendarWeekを作成する際に、第2引数でcount($weeks)を指定している。
      // これは何週目かを週カレンダーオブジェクトに伝えるために設置している。
      // 一回目のループは既に1週目を追加するので1、次のループでは2と順に増えていく。
    }
    return $weeks;
  }
}
