<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use App\Jobs\SendMessage;
use App\Model\Settings\ControllerDevice;

class SyncTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $current = Carbon::now('+7');
      $year = $current -> year;
      $month = $current -> month;
      $day = $current -> day;
      $hour = $current -> hour;
      $minute = $current -> minute;
      $second = $current -> second;
      $time = $month.';'.$day.';'.$year.';'.$hour.';'.$minute.';'.$second;
			$timezone = "GMT0";
      $length = strlen($time) + 11;
      $length2 = strlen($timezone) + 11;
      if ($length<10){
        $length = "000".$length;
      } else if ($length < 100) {
        $length = "00".$length;
      } else if ($length < 1000) {
        $length = "0".$length;
      } else {
        //nothing need to be done;
      }
      if ($length2<10){
        $length2 = "000".$length2;
      } else if ($length2 < 100) {
        $length2 = "00".$length2;
      } else if ($length2 < 1000) {
        $length2 = "0".$length2;
      } else {
        //nothing need to be done;
      }
      $controllers = ControllerDevice::all();
      foreach($controllers as $controller){
        dispatch(new SendMessage('0088;'.$length2.';'.$timezone.';%'.$controller->ip));
        dispatch(new SendMessage('0018;'.$length.';'.$time.';%'.$controller->ip));
      }
      return $next($request);
    }
}
