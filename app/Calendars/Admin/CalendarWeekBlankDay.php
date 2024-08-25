<?php
namespace App\Calendars\Admin;

// ↓↓カレンダーの前の月、次の月の余白を出力するためのCalendarWeekBlankDayクラス(2024/8/25)
class CalendarWeekBlankDay extends CalendarWeekDay{

  function getClassName(){
    return "day-blank";   //グレーアウトしている部分
  }

  function render(){
    return '';
  }

  function everyDay(){
    return '';
  }

  function dayPartCounts($ymd = null){
    return '';
  }

  function dayNumberAdjustment(){
    return '';
  }
}
