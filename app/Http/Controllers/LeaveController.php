<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Event;
use App\User;
use Calendar;
use App\Holiday;
use App\LeaveType;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function showCalendar()
    {
        $user = User::where('permission', '<>', 'boss')->where('permission', '<>', 'admin')->get();
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->format('Y-m-d');
        $leavetype = LeaveType::all();
        $holidays = Holiday::where('status', '=', 1)->orderBy('date','asc')->get();
        $cannotholidays = Holiday::where('status', '=', 2)->orderBy('date','asc')->get();
        if (!empty($_GET['emp'])) {
            $data = Event::select('events.*', 'users.name as uname', 'leave_type.name as tname')
                ->where('users.id', $_GET['emp'])
                ->join('users', 'users.emp_id', '=', 'events.uid')
                ->join('leave_type', 'leave_type.id', '=', 'events.reason')
                ->where('events.status', 1)
                ->orderby('events.reason', 'asc')
                ->get();
        } else {
            $data = Event::select('events.*', 'users.name as uname', 'leave_type.name as tname')
                ->join('users', 'users.emp_id', '=', 'events.uid')
                ->join('leave_type', 'leave_type.id', '=', 'events.reason')
                ->where('events.status', 1)
                ->orderby('events.reason', 'asc')
                ->get();
        }

        $holidays_m = Holiday::where('date', 'Like', "%$year".'-'."$month%")->where('status', '=', 1)->orderBy('date','asc')->get();
        $cannotholidays_m = Holiday::where('date', 'Like', "%$year".'-'."$month%")->where('status', '=', 2)->orderBy('date','asc')->get();

        $holidays_y = Holiday::where('status', '=', 1)->orderBy('date','desc')->limit(20)->get();
        $cannotholidays_y = Holiday::where('status', '=', 2)->orderBy('date','desc')->limit(20)->get();

        $news = DB::table('news')->where('ns_date', 'Like', "%$year".'-'."$month%")->orderBy('ns_date','asc')->get();
        $all_news = DB::table('news')->orderBy('ns_date','desc')->limit(20)->get();

        foreach ($holidays as $key => $holiday) {
            $events[] = Calendar::event(
                // $holiday->name . ' || ',
                "Valentine's Day", //event title
                true, //full day event?
                new \DateTime(Carbon::parse($holiday->date)->format('Y-m-d')), //start time (you can also use Carbon instead of DateTime)
                new \DateTime(Carbon::parse($holiday->date)->add(1, 'days')->format('Y-m-d')), //end time (you can also use Carbon instead of DateTime)
                'stringEventId', //optionally, you can specify an event ID
                [
                    'color' => '#d2f3ff',
                    'url' => 'pass here url and any route',
                    'rendering' => 'background'
                ]
            );
            $events[] = Calendar::event(
                $holiday->name,
                // "Valentine's Day", //event title
                true, //full day event?
                new \DateTime(Carbon::parse($holiday->date)->format('Y-m-d')), //start time (you can also use Carbon instead of DateTime)
                new \DateTime(Carbon::parse($holiday->date)->add(1, 'days')->format('Y-m-d')), //end time (you can also use Carbon instead of DateTime)
                null, //optionally, you can specify an event ID
                [
                    'color' => '#00a1ff',
                    // 'url' => 'pass here url and any route',
                    // 'rendering' => 'background'
                ]
            );
        }

        foreach ($cannotholidays as $key => $cannotholiday) {
            $events[] = Calendar::event(
                // $holiday->name . ' || ห้ามหยุด',
                "Valentine's Day", //event title
                true, //full day event?
                new \DateTime(Carbon::parse($cannotholiday->date)->format('Y-m-d')), //start time (you can also use Carbon instead of DateTime)
                new \DateTime(Carbon::parse($cannotholiday->date)->add(1, 'days')->format('Y-m-d')), //end time (you can also use Carbon instead of DateTime)
                'stringEventId', //optionally, you can specify an event ID
                [
                    'color' => '#ffdbdb',
                    'url' => 'pass here url and any route',
                    'rendering' => 'background'
                ]
            );
            $events[] = Calendar::event(
                $cannotholiday->name,
                // "Valentine's Day", //event title
                true, //full day event?
                new \DateTime(Carbon::parse($cannotholiday->date)->format('Y-m-d')), //start time (you can also use Carbon instead of DateTime)
                new \DateTime(Carbon::parse($cannotholiday->date)->add(1, 'days')->format('Y-m-d')), //end time (you can also use Carbon instead of DateTime)
                null, //optionally, you can specify an event ID
                [
                    'color' => 'red',
                    // 'url' => 'pass here url and any route',
                    // 'rendering' => 'background'
                ]
            );
        }
        
        

        if ($data->count()) {
            foreach ($data as $key => $value) {
                switch ($value->reason) {
                    case $value->reason == 1:
                        ($value->type == '0.5') ? $color = 'orange' : $color = 'red';
                        break;
                    case $value->reason == 2:
                        ($value->type == '0.5') ? $color = '#b32be5' : $color = 'green';
                        break;
                    case $value->reason == 3:
                        ($value->type == '0.5') ? $color = 'blue' : $color = 'blue';
                        break;
                    case $value->reason == 6:
                        ($value->type == '0.5') ? $color = 'black' : $color = 'black';
                        break;
                    case $value->reason == 7:
                        ($value->type == '0.5') ? $color = '#696969' : $color = '#696969';
                        break;
                    case $value->reason == 8:
                        ($value->type == '0.5') ? $color = 'orange' : $color = 'red';
                        break;
                    case $value->reason == 9:
                        ($value->type == '0.5') ? $color = 'pink' : $color = 'pink';
                        break;
                    default:
                        ($value->type == '0.5') ? $color = '#ed4bca' : $color = '#ed4bca';
                        break;
                }
                if ($value->status == 1) {
                    $events[] = Calendar::event(
                        ($value->type == '0.5') ? $value->uname . ' || ' .  ' ครึ่งวัน' . ' || ' . $value->title. $value->note : $value->uname . ' || ' . ' ทั้งวัน' . ' || ' . $value->title. $value->note,
                        true,
                        new \DateTime($value->start_date),
                        new \DateTime($value->end_date . ' +1 day'),
                        null,
                        // Add color and link on event
                        [
                            'color' => $color,
                            // 'url' => 'pass here url and any route',
                            // 'rendering' => 'background'
                        ]
                    );
                }
            }
            // print_r($events);
        }
        $calendar = Calendar::addEvents($events)->setOptions([ //set fullcalendar options
            'locale' => 'th',
            'editable' => false,
            'navLinks' => true,
            'selectable'  => true,
            'defaultView' => 'month',
        ]);
        return view('leave/calendar', compact('calendar', 'leavetype', 'startDate', 'endDate', 'holidays_m', 'cannotholidays_m', 'holidays_y', 'cannotholidays_y', 'user', 'news', 'all_news'));
    }

    public function addHoliday(Request $request)
    {
        try {
            DB::table('holidays')->insert([
                'date' => $request->get('start'),
                'name' => $request->get('holiday_name'),
                'status' => 1
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function editHoliday(Request $request)
    {
        try {
            DB::table('holidays')
            ->where('id', $request->get('id'))
            ->update([
                'date' => $request->get('start'),
                'name' => $request->get('holiday_name')
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function cancleHoliday(Request $request)
    {
        try {
            DB::table('holidays')->where('id', '=',$request->get('id'))->delete();
            alert()->success('ลบเรียบร้อย');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function addcannotHoliday(Request $request)
    {
        try {
            DB::table('holidays')->insert([
                'date' => $request->get('start'),
                'name' => $request->get('holiday_name'),
                'status' => 2
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function addNews(Request $request)
    {
        try {
            DB::table('news')->insert([
                'ns_date' => $request->get('ns_date'),
                'ns_text' => $request->get('ns_text')
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function editNews(Request $request)
    {
        try {
            DB::table('news')
            ->where('ns_id', $request->get('ns_id'))
            ->update([
                'ns_date' => $request->get('ns_date'),
                'ns_text' => $request->get('ns_text')
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function cancleNews(Request $request)
    {
        try {
            DB::table('news')->where('ns_id', '=',$request->get('ns_id'))->delete();
            alert()->success('ลบเรียบร้อย');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function adminaddCalendar(Request $request){
        if ($request->get('end') != null) {
            $endDate = $request->get('end');
        } else {
            $endDate = $request->get('start');
        }
        $status = 1;


        $emp_name = DB::table('users')->where('emp_id', '=', $request->get('emp_id'))->get();
        $name = $emp_name[0]->name;

        try {
            $diff = date_diff(date_create($request->get('start')),date_create($endDate));
            $e_count_date = $diff->format("%a")+1;

            Event::insert([
                'uid' => trim($request->get('emp_id')),
                'reason' => trim($request->get('reason')),
                'type' => trim($request->get('type')),
                'start_date' => trim($request->get('start')),
                'end_date' => $endDate,
                'count_date' => $e_count_date,
                'note' => $request->get('note')."[ลงลาด้วยแอดมิน]",
                'status' => $status,
            ]);

        // line notify
            switch ($request->get('reason')) {
                case '1':
                    $reason = 'ลากิจล่วงหน้า';
                    break;
                case '2':
                    $reason = 'ลาป่วย';
                    break;
                case '4':
                    $reason = 'ลาบวช';
                    break;
                case '5':
                    $reason = 'ลาคลอด';
                    break;
                case '6':
                    $reason = 'ลากรณีพิเศษ';
                    break;
                case '7':
                    $reason = 'ไปเรียน';
                    break;
                case '8':
                    $reason = 'ลากิจฉุกเฉิน';
                    break;
                case '9':
                    $reason = 'ทำงานนอกสถานที่';
                    break;
                default:
                    $reason = 'วันหยุดประจำสัปดาห์';
                    break;
            }
            if ($request->get('type') == 0.5) {
                $type = 'ครึ่งวัน' ;
            }
            else {
                $type = 'ทั้งวัน' ;
            }

            // line Bot
            $message = "\n".'ลงลาด้วยแอดมิน'."\n".'ชื่อพนักงาน : '.$name."\n".'ประเภทการลา : '.$reason;
            $message .= "\n".'วันที่ : '.Carbon::parse($request->get('start'))->format('d-m-Y').'['.$type.']';
            if ($request->get('start') != $endDate) {
                $message .= "\n".'ถึงวันที่ : '.Carbon::parse($endDate)->format('d-m-Y').'['.$type.']';
            }
            $message .= "\n".'เวลาบันทึก : '.Carbon::parse(Carbon::now())->format('d-m-Y H:i:s');
            $message .= "\n".'หมายเหตุ : '.$request->get('note')."\n";
            // sendlinemesg(1);
            header('Content-Type: text/html; charset=utf-8');
            notify_message($message, 1);
        // line notify

            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function addCalendar(Request $request)
    {        
        $year = Carbon::now()->format('Y');
        if ($request->get('end') != null) {
            $endDate = $request->get('end');
        } else {
            $endDate = $request->get('start');
        }

        $tmr_holiday_check = date("Y-m-d", strtotime("1 day",strtotime($request->get('start'))));
        $tmr_cn_holiday_check = DB::table('holidays')->selectRaw('count(*) as tmr')->where('date', '=', $tmr_holiday_check)->where('status', '=', 1)->get();

        $ytd_holiday_check = date("Y-m-d", strtotime("-1 day",strtotime($request->get('start'))));
        $ytd_cn_holiday_check = DB::table('holidays')->selectRaw('count(*) as ytd')->where('date', '=', $ytd_holiday_check)->where('status', '=', 1)->get();

        if ($request->get('reason') == 3) {
            if($ytd_cn_holiday_check[0]->ytd >= 1 || $tmr_cn_holiday_check[0]->tmr >= 1){
                $status = 0;
                $ps = '[ลงลาติดกับวันหยุด]';
            }
            elseif ($request->get('start') > Carbon::parse(Carbon::now()->add('day',7))->format('Y-m-d')) {
                $status = 0;
                $ps = '[ลงลาล่วงหน้าเกิน 7 วัน]';
            }
            else {
                $status = 1;
                $ps = '[หยุดประจำสัปดาห์]';
            }
        }
        elseif ($request->get('reason') == 7) {
            if ($request->get('start') > Carbon::parse(Carbon::now()->add('day',7))->format('Y-m-d')) {
                $status = 0;
                $ps = '[ลงลาล่วงหน้าเกิน 7 วัน]';
            }
            else {                
                $status = 1;
                $ps = '[เรียน]';
            }
        }
        else {
            $status = 0;
            $ps = '';
        }

        $countDepartment = Event::selectRaw('count(*) as num')
        ->join('employee', 'events.uid', '=', 'employee.id')
        ->where('events.reason', '<>', 7)        
        // ->where('events.start_date', '=', $request->get('start'))
        ->whereRaw('"'.$request->get('start').'" between start_date and end_date')
        ->where('employee.dept_id', '=', $request->get('dept_id'))
        ->get();

        $limit_event = DB::table('department')->where('dept_id', '=', $request->get('dept_id'))->get();

        $cannot_holiday = DB::table('holidays')->selectRaw('count(*) as cannot')->where('date', '=', $request->get('start'))->where('status', '=', 2)->get();

        $emp_limitErrand = DB::table('employee')->selectRaw('emp_limitErrand')->where('id', '=', $request->user()->emp_id)->get();

        $count_errand = DB::table('events')->selectRaw('sum(count_date) as c_errand')
        ->where('uid', '=', $request->user()->emp_id)
        ->whereRaw('(reason = 1 or reason = 8)')
        ->whereRaw('start_date like "'.$year.'%"')
        ->where('status', '=', 1)
        ->get();

        if ($limit_event[0]->limit_event == 0) {
            $limit = 100;
        }
        else {
            $limit = $limit_event[0]->limit_event;
        }

        $count_emp = DB::table('employee')->selectRaw('COUNT(*) as c_emp')->get();

        $count_date = DB::table('events')->selectRaw('COUNT(*) as c_date')
        // ->where('start_date', '=', $request->get('start'))        
        ->whereRaw('"'.$request->get('start').'" between start_date and end_date')
        ->where('reason', '=', 3)->get();

        try {
            // don't leave monday
            if (Carbon::parse($request->get('start'))->isoFormat('dddd') == 'Monday' && $request->get('reason') == 3) {
                alert()->error('ไม่สามารถลงหยุดประจำสัปดาห์วันจันทร์ได้');
                return redirect()->back();
            }
            //don't leave with department
            elseif ($countDepartment[0]->num >= $limit && $request->get('reason') == 3) {
                alert()->error('แผนกคุณหยุดเกินจำนวนที่กำหนดต่อวันแล้ว');
                return redirect()->back();
            }
            //don't leave back
            elseif ($request->get('start') < Carbon::parse(Carbon::now()) && $request->get('reason') != 2 && $request->get('reason') != 8 ) {
                alert()->error('ห้ามลงหยุดย้อนหลัง');
                return redirect()->back();
            }
            //leave before 3 day
            elseif ($request->get('start') <= Carbon::parse(Carbon::now()->add('day',1)) && $request->get('reason') == 1) {
                alert()->error('ลากิจต้องลงล่วงหน้า 1 วัน');
                return redirect()->back();
            }
            //check cannot leave
            elseif ($cannot_holiday[0]->cannot >= 1 && $request->get('reason') == 3) {
                alert()->error('วันนี้ห้ามหยุดประจำสัปดาห์');
                return redirect()->back();
            }
            //check leave = emp/3
            elseif ($count_date[0]->c_date >= ($count_emp[0]->c_emp/3) && $request->get('reason') == 3) {
                alert()->error('จำนวนคนหยุดต่อวันเกินกำหนดแล้ว');
                return redirect()->back();
            }
            else {
                $diff = date_diff(date_create($request->get('start')),date_create($endDate));
                $e_count_date = $diff->format("%a")+1;

                Event::insert([
                    'uid' => $request->user()->emp_id,
                    'reason' => trim($request->get('reason')),
                    'type' => trim($request->get('type')),
                    'start_date' => trim($request->get('start')),
                    'end_date' => $endDate,
                    'count_date' => $e_count_date,
                    'note' => $request->get('note'),
                    'status' => $status,
                ]);

            // line notify
            if ($status == 0) {
                $stat = '[รออนุมัติ]';
            }
            else {
                $stat = '';
            }
                switch ($request->get('reason')) {
                    case '1':
                        $reason = 'ลากิจ'.$stat ;
                        break;
                    case '2':
                        $reason = 'ลาป่วย'.$stat;
                        break;
                    case '4':
                        $reason = 'ลาบวช'.$stat;
                        break;
                    case '5':
                        $reason = 'ลาคลอด'.$stat;
                        break;
                    case '6':
                        $reason = 'ลากรณีพิเศษ'.$stat;
                        break;
                    case '7':
                        $reason = 'ไปเรียน'.$stat;
                        break;
                    case '8':
                        $reason = 'ลากิจฉุกเฉิน'.$stat;
                        break;
                    case '9':
                        $reason = 'ทำงานนอกสถานที่'.$stat;
                        break;
                    default:
                        $reason = 'วันหยุดประจำสัปดาห์'.$stat;
                        break;
                }
                if ($request->get('type') == 0.5) {
                    $type = 'ครึ่งวัน' ;
                }
                else {
                    $type = 'ทั้งวัน' ;
                }
                $message = "\n".'ชื่อพนักงาน : '.Auth::user()->name."\n".'ประเภทการลา : '.$reason;
                $message .= "\n".'วันที่ : '.Carbon::parse($request->get('start'))->format('d-m-Y').'['.$type.']';
                if ($request->get('start') != $endDate) {
                    $message .= "\n".'ถึงวันที่ : '.Carbon::parse($endDate)->format('d-m-Y').'['.$type.']';
                }
                $message .= "\n".'เวลาบันทึก : '.Carbon::parse(Carbon::now())->format('d-m-Y H:i:s');
                $message .= "\n".'หมายเหตุ : '.$request->get('note').$ps."\n";
                // sendlinemesg(1);

                //Line Bot
                if(intval($request->get('reason')) !== 3 ){
                    $setData = (object)[];
                    $setData->name = Auth::user()->name;
                    $setData->typeleave = $reason;
                    $setData->DateStart = Carbon::parse($request->get('start'))->format('d-m-Y').'['.$type.']';
                    if ($request->get('start') != $endDate) {
                        $setData->DateEnd = Carbon::parse($endDate)->format('d-m-Y').'['.$type.']';
                    }
                    $setData->note = $request->get('note');
                    seandNotitoLineHR($setData);
                }


            // line notify
            header('Content-Type: text/html; charset=utf-8');
            notify_message($message, 1);

                if(($request->get('reason') == 1 || $request->get('reason') == 8) && ($count_errand[0]->c_errand >= $emp_limitErrand[0]->emp_limitErrand)){
                    alert()->success('จำนวนวันลากิจคุณเกินกำหนด กรุณาติดต่อ HR');
                }
                else {
                    alert()->success('บันทึกข้อมูลสำเร็จ');
                }
                return redirect()->back();
            }
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function editCalendar(Request $request)
    {
        if ($request->get('end') != null) {
            $endDate = $request->get('end');
        } else {
            $endDate = $request->get('start');
        }

        $tmr_holiday_check = date("Y-m-d", strtotime("1 day",strtotime($request->get('start'))));
        $tmr_cn_holiday_check = DB::table('holidays')->selectRaw('count(*) as tmr')->where('date', '=', $tmr_holiday_check)->where('status', '=', 1)->get();

        $ytd_holiday_check = date("Y-m-d", strtotime("-1 day",strtotime($request->get('start'))));
        $ytd_cn_holiday_check = DB::table('holidays')->selectRaw('count(*) as ytd')->where('date', '=', $ytd_holiday_check)->where('status', '=', 1)->get();

        if ($request->get('reason') == 3) {
            if($ytd_cn_holiday_check[0]->ytd >= 1 || $tmr_cn_holiday_check[0]->tmr >= 1){
                $status = 0;
                $ps = '[ลงลาติดกับวันหยุด]';
            }
            elseif ($request->get('start') > Carbon::parse(Carbon::now()->add('day',15))->format('Y-m-d')) {
                $status = 0;
                $ps = '[ลงลาล่วงหน้าเกิน 15 วัน]';
            }
            else {
                $status = 1;
                $ps = '[หยุดประจำสัปดาห์]';
            }
        }
        elseif ($request->get('reason') == 7) {
            $status = 1;
            $ps = '[เรียน]';
        }
        else {
            $status = 0;
            $ps = '';
        }

        $countDepartment = Event::selectRaw('count(*) as num')
        ->join('employee', 'events.uid', '=', 'employee.id')
        ->where('events.reason', '<>', 7)        
        // ->where('events.start_date', '=', $request->get('start'))
        ->whereRaw('"'.$request->get('start').'" between start_date and end_date')
        ->where('employee.dept_id', '=', $request->get('dept_id'))
        ->get();

        $limit_event = DB::table('department')->where('dept_id', '=', $request->get('dept_id'))->get();

        $cannot_holiday = DB::table('holidays')->selectRaw('count(*) as cannot')->where('date', '=', $request->get('start'))->where('status', '=', 2)->get();

        $emp_limitErrand = DB::table('employee')->selectRaw('emp_limitErrand')->where('id', '=', $request->user()->emp_id)->get();

        $count_errand = DB::table('events')->selectRaw('count(*) as c_errand')->where('uid', '=', $request->user()->emp_id)->whereRaw('reason = 1 or reason = 8')->get();

        if ($limit_event[0]->limit_event == 0) {
            $limit = 100;
        }
        else {
            $limit = $limit_event[0]->limit_event;
        }

        $count_emp = DB::table('employee')->selectRaw('COUNT(*) as c_emp')->get();

        $count_date = DB::table('events')->selectRaw('COUNT(*) as c_date')
        // ->where('start_date', '=', $request->get('start'))        
        ->whereRaw('"'.$request->get('start').'" between start_date and end_date')
        ->where('reason', '=', 3)->get();

        try {
            // don't leave monday
            if (Carbon::parse($request->get('start'))->isoFormat('dddd') == 'Monday' && $request->get('reason') == 3) {
                alert()->error('ไม่สามารถลงหยุดประจำสัปดาห์วันจันทร์ได้');
                return redirect()->back();
            }
            //don't leave with department
            elseif ($countDepartment[0]->num >= $limit && $request->get('reason') == 3) {
                alert()->error('แผนกคุณหยุดเกินจำนวนที่กำหนดต่อวันแล้ว');
                return redirect()->back();
            }
            //don't leave back
            elseif ($request->get('start') < Carbon::parse(Carbon::now()) && $request->get('reason') != 2 && $request->get('reason') != 8 ) {
                alert()->error('ห้ามลงหยุดย้อนหลัง');
                return redirect()->back();
            }
            //leave before 3 day
            elseif ($request->get('start') <= Carbon::parse(Carbon::now()->add('day',1)) && $request->get('reason') == 1) {
                alert()->error('ลากิจต้องลงล่วงหน้า 1 วัน');
                return redirect()->back();
            }
            //check cannot leave
            elseif ($cannot_holiday[0]->cannot >= 1 && $request->get('reason') == 3) {
                alert()->error('วันนี้ห้ามหยุดประจำสัปดาห์');
                return redirect()->back();
            }
            //check leave = emp/3
            elseif ($count_date[0]->c_date >= ($count_emp[0]->c_emp/3) && $request->get('reason') == 3) {
                alert()->error('จำนวนคนหยุดต่อวันเกินกำหนดแล้ว');
                return redirect()->back();
            }
            else {
                $diff = date_diff(date_create($request->get('start')),date_create($endDate));
                $e_count_date = $diff->format("%a")+1;

                Event::where('id', '=', ($request->get('id')))
                ->update([
                    'reason' => trim($request->get('reason')),
                    'type' => trim($request->get('type')),
                    'start_date' => trim($request->get('start')),
                    'end_date' => $endDate,
                    'count_date' => $e_count_date,
                    'note' => $request->get('note'),
                    'status' => $status,
                ]);
                if ($request->get('type') == 0.5) {
                    $type = 'ครึ่งวัน' ;
                }
                else {
                    $type = 'ทั้งวัน' ;
                }
                if(intval($request->get('reason')) !== 3 ){
                    $setData = (object)[];
                    $setData->name = Auth::user()->name;
                    $setData->typeleave = $request->get('reason');
                    $setData->DateStart = Carbon::parse($request->get('start'))->format('d-m-Y').'['.$type.']';
                    if ($request->get('start') != $endDate) {
                        $setData->DateEnd = Carbon::parse($endDate)->format('d-m-Y').'['.$type.']';
                    }
                    $setData->note = $request->get('note');
                    seandNotitoLineHR($setData);
                }
                alert()->success('บันทึกข้อมูลสำเร็จ');
                return redirect()->back();
            }
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function editCalendaradmin(Request $request)
    {
        if ($request->get('end') != null) {
            $endDate = $request->get('end');
        } else {
            $endDate = $request->get('start');
        }
        $status = 1;

        try{
            $diff = date_diff(date_create($request->get('start')),date_create($endDate));
            $e_count_date = $diff->format("%a")+1;

            Event::where('id', '=', ($request->get('id')))
            ->update([
                'reason' => trim($request->get('reason')),
                'type' => trim($request->get('type')),
                'start_date' => trim($request->get('start')),
                'end_date' => $endDate,
                'count_date' => $e_count_date,
                'note' => $request->get('note')."[ลงลาด้วยแอดมิน]",
                'status' => $status,
            ]);
            if ($request->get('type') == 0.5) {
                $type = 'ครึ่งวัน' ;
            }
            else {
                $type = 'ทั้งวัน' ;
            }
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();

        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function cancleCalendar(Request $request)
    {
        try {
            Event::where('id', '=', $request->get('id'))->delete();
            alert()->success('ลบข้อมูลสำเร็จ');
            return redirect()->back();
            // }
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function leaveHistory(Request $request)
    {
        $fdateResult = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $request->user()->emp_id)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $request->user()->emp_id)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult = $fdateResult->dateDiff1 + $hdateResult->dateDiff1;

        $fdateResult = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $request->user()->emp_id)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $request->user()->emp_id)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult = $fdateResult->dateDiff1 + $hdateResult->dateDiff1;

        $fdateResult1 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $request->user()->emp_id)->whereRaw('(reason = 1 or reason = 8)')->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult1 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $request->user()->emp_id)->whereRaw('(reason = 1 or reason = 8)')->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult1 = $fdateResult1->dateDiff1 + $hdateResult1->dateDiff1;

        $fdateResult2 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $request->user()->emp_id)->where('reason', 2)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult2 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $request->user()->emp_id)->where('reason', 2)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult2 = $fdateResult2->dateDiff1 + $hdateResult2->dateDiff1;

        $fdateResult3 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $request->user()->emp_id)->where('reason', 3)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult3 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $request->user()->emp_id)->where('reason', 3)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult3 = $fdateResult3->dateDiff1 + $hdateResult3->dateDiff1;

        $fdateResult4 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $request->user()->emp_id)->where('reason', 4)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult4 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $request->user()->emp_id)->where('reason', 4)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult4 = $fdateResult4->dateDiff1 + $hdateResult4->dateDiff1;

        $fdateResult5 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $request->user()->emp_id)->where('reason', 5)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult5 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $request->user()->emp_id)->where('reason', 5)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult5 = $fdateResult5->dateDiff1 + $hdateResult5->dateDiff1;

        $fdateResult6 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $request->user()->emp_id)->where('reason', 6)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult6 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $request->user()->emp_id)->where('reason', 6)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult6 = $fdateResult6->dateDiff1 + $hdateResult6->dateDiff1;

        $leavetype = LeaveType::all();

        $emp = DB::table('employee')->select('emp_limitErrand')->where('id', '=', Auth::user()->emp_id)->limit(1)->get();

        $leaves = Event::select('events.*', 'users.name as uname', 'leave_type.name as tname')
            ->join('users', 'users.emp_id', '=', 'events.uid')
            ->join('leave_type', 'leave_type.id', '=', 'events.reason')->orderBy('start_date', 'DESC')
            ->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')
            ->where('uid',  $request->user()->emp_id)
            ->paginate(100);

        // // echo $L1->l1;
        return view('leave/history', compact('leaveResult', 'leaveResult1', 'leaveResult2', 'leaveResult3', 'leaveResult4', 'leaveResult5', 'leaveResult6', 'leaves', 'leavetype', 'emp'));
    }

    public function leaveRequest(Request $request)
    {
        $L = Event::where('status', 0)->get();

        $leaves = Event::select('events.*', 'users.name as uname', 'leave_type.name as tname')
            ->join('users', 'users.id', '=', 'events.uid')
            ->join('leave_type', 'leave_type.id', '=', 'events.reason')->orderBy('start_date', 'DESC')
            ->where('status', 0)
            ->paginate(100);

        // echo $L1->l1;
        return view('leave/history', compact('leaves'));
    }

    public function viewApprove(Request $request)
    {
        if ($request->user()->permission == 'programer' || $request->user()->permission == 'boss' || $request->user()->permission == 'admin') {
            $events = Event::select('events.*', 'events.created_at as send_date', 'leave_type.name as leave_type', 'users.name as name')
                ->join('users', 'users.emp_id', '=', 'events.uid')
                ->join('leave_type', 'leave_type.id', '=', 'events.reason')
                ->orderBy('status', 'ASC')->orderBy('created_at', 'DESC')
                ->paginate(10);
            return view('leave/approve', compact('events'));
        } else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }

    public function allowLeave(Request $request, $id)
    {
        if ($request->user()->permission == 'programer' || $request->user()->permission == 'boss' || $request->user()->permission == 'admin') {
            if ($_GET['status'] == 'allow') {
                $status = 1;
            } else {
                $status = 2;
            }
            $dateleave = null;
            $event = Event::find($id);
            $dataUser = DB::table('employee')->where('id', $event->uid)->first();
                if(!is_null($dataUser->lineUUID)){
                    $dateLeavestart = $event->start_date;
                    $dateLeavesend = $event->end_date;
                    $_dateleaves = [thai_date($dateLeavestart), thai_date($dateLeavesend)];
                    $dateleaveSet = null;
                    if($_dateleaves[0] === $_dateleaves[1]){
                        $dateleaveSet = $_dateleaves[0];
                    }
                    else{
                        $dateleaveSet = $_dateleaves[0].' ถึง '.$_dateleaves[1];
                    }
                    if($status === 2){
                        $dataSend = '{ "to": "'.$dataUser->lineUUID.'", "messages":[ { "type": "flex", "altText": "แจ้งเตือนการขอลา", "contents": { "type": "bubble", "direction": "ltr", "header": { "type": "box", "layout": "vertical", "contents": [ { "type": "text", "text": "แจ้งเตือนการขอลา", "margin": "none", "align": "center" } ] }, "body": { "type": "box", "layout": "vertical", "contents": [ { "type": "text", "text": "ไม่อนุมัติ", "margin": "md", "size": "3xl", "align": "center", "weight": "bold", "color": "#E80101", "wrap": true }, { "type": "separator", "margin": "xl" }, { "type": "text", "text": "ให้ลาในวันที่  '.$dateleaveSet.'", "margin": "lg", "align": "center", "weight": "bold", "wrap": true } ] } } } ] }';
                    }
                    else{
                        $dataSend = '{ "to": "'.$dataUser->lineUUID.'", "messages":[ { "type": "flex", "altText": "แจ้งเตือนการขอลา", "contents": { "type": "bubble", "direction": "ltr", "header": { "type": "box", "layout": "vertical", "contents": [ { "type": "text", "text": "แจ้งเตือนการขอลา", "margin": "none", "align": "center" } ] }, "body": { "type": "box", "layout": "vertical", "contents": [ { "type": "text", "text": "อนุมัติ", "margin": "md", "size": "3xl", "align": "center", "weight": "bold", "color": "#04A11E", "wrap": true }, { "type": "separator", "margin": "xl" }, { "type": "text", "text": "ให้ลาในวันที่  '.$dateleaveSet.'", "margin": "lg", "align": "center", "weight": "bold", "wrap": true } ] } } } ] }';
                    }
                    sendToline($dataSend);
                }
            try {
                $event->status = $status;
                $event->save();
                if ($_GET['status'] == 'allow') {
                    alert()->success('อนุมัติการลาเรียบร้อยแล้ว');
                } else {
                    alert()->success('ปฎิเสธการลาเรียบร้อย');
                }
                return redirect('admin/approve');
            } catch (\Throwable $th) {
                alert()->error('เกิดข้อผิดพลาดบางอย่าง กรุณาตรวจสอบ หรือติดต่อผู้พัฒนา');
                return redirect()->back();
            }
        } else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }

    public function flightShow(Request $request){
        return view('leave/airport');
    }

    public function flightAdd(Request $request){
        $check_event = DB::table('events')->selectRaw('count(*) as num')->whereRaw('start_date = "'.$request->get('bp_go_date').'" or end_date = "'.$request->get('bp_go_date').'" ')->where('uid', '=', Auth::user()->emp_id)->get();
        
        if (!empty($request->get('bp_go_con_start_airportName'))) {
            $bp_go_con_start_airportName = trim($request->get('bp_go_con_start_airportName'));
        }
        else{
            $bp_go_con_start_airportName = null;
        }        
        if (!empty($request->get('bp_go_con_end_airportName'))) {
            $bp_go_con_end_airportName = trim($request->get('bp_go_con_end_airportName'));
        }
        else{
            $bp_go_con_end_airportName = null;
        }
        if (!empty($request->get('bp_go_con_date'))) {
            $bp_go_con_date = trim($request->get('bp_go_con_date'));
        }
        else{
            $bp_go_con_date = null;
        }
        if (!empty($request->get('bp_go_con_time'))) {
            $bp_go_con_time = trim($request->get('bp_go_con_time'));
        }
        else{
            $bp_go_con_time = null;
        }

        if (!empty($request->get('bp_back_start_airportName'))) {
            $bp_back_start_airportName = trim($request->get('bp_back_start_airportName'));
        }
        else{
            $bp_back_start_airportName = null;
        }        
        if (!empty($request->get('bp_back_end_airportName'))) {
            $bp_back_end_airportName = trim($request->get('bp_back_end_airportName'));
        }
        else{
            $bp_back_end_airportName = null;
        }
        if (!empty($request->get('bp_back_date'))) {
            $bp_back_date = trim($request->get('bp_back_date'));
        }
        else{
            $bp_back_date = null;
        }
        if (!empty($request->get('bp_back_time'))) {
            $bp_back_time = trim($request->get('bp_back_time'));
        }
        else{
            $bp_back_time = null;
        }

        if (!empty($request->get('bp_back_con_start_airportName'))) {
            $bp_back_con_start_airportName = trim($request->get('bp_back_con_start_airportName'));
        }
        else{
            $bp_back_con_start_airportName = null;
        }        
        if (!empty($request->get('bp_back_con_end_airportName'))) {
            $bp_back_con_end_airportName = trim($request->get('bp_back_con_end_airportName'));
        }
        else{
            $bp_back_con_end_airportName = null;
        }
        if (!empty($request->get('bp_back_con_date'))) {
            $bp_back_con_date = trim($request->get('bp_back_con_date'));
        }
        else{
            $bp_back_con_date = null;
        }
        if (!empty($request->get('bp_back_con_time'))) {
            $bp_back_con_time = trim($request->get('bp_back_con_time'));
        }
        else{
            $bp_back_con_time = null;
        }

        try {    
            if ($check_event[0]->num >= 1) {                
                DB::table('book_plane_tickets')->insert([
                    'bp_emp_id' => Auth::user()->emp_id,
                    'bp_go_start_airportName' => trim($request->get('bp_go_start_airportName')),
                    'bp_go_end_airportName' => trim($request->get('bp_go_end_airportName')),
                    'bp_go_date' => trim($request->get('bp_go_date')),
                    'bp_go_time' => trim($request->get('bp_go_time')),

                    'bp_go_con_start_airportName' => $bp_go_con_start_airportName,
                    'bp_go_con_end_airportName' => $bp_go_con_end_airportName,
                    'bp_go_con_date' => $bp_go_con_date,
                    'bp_go_con_time' => $bp_go_con_time,

                    'bp_back_start_airportName' => $bp_back_start_airportName,
                    'bp_back_end_airportName' => $bp_back_end_airportName,
                    'bp_back_date' => $bp_back_date,
                    'bp_back_time' => $bp_back_time,

                    'bp_back_con_start_airportName' => $bp_back_con_start_airportName,
                    'bp_back_con_end_airportName' => $bp_back_con_end_airportName,
                    'bp_back_con_date' => $bp_back_con_date,
                    'bp_back_con_time' => $bp_back_con_time,
                ]);
                alert()->success('บันทึกข้อมูลสำเร็จ');
                return redirect()->back();   
            }
            else {
                alert()->error('คุณต้องลงหยุดก่อนจองตั๋วในวันนี้');
                return redirect()->back(); 
            }        
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function viewHistoryadmin(Request $request)
    {
        if ($request->user()->permission == 'programer' || $request->user()->permission == 'boss' || $request->user()->permission == 'admin') {
            
            if (!empty($request->get('emp_id'))) {
                if(!empty($request->get('date_rep'))){
                    $events = Event::select('events.*', 'events.created_at as send_date', 'leave_type.name as leave_type', 'users.name as name')
                    ->join('users', 'users.emp_id', '=', 'events.uid')
                    ->join('leave_type', 'leave_type.id', '=', 'events.reason')
                    ->where('events.status', '=', 1)
                    ->where('events.uid', '=', $request->get('emp_id'))
                    ->where('events.start_date', '=', $request->get('date_rep'))
                    ->orderBy('events.start_date', 'DESC')->orderBy('created_at', 'DESC')
                    ->paginate(10);
                }
                else{
                    $events = Event::select('events.*', 'events.created_at as send_date', 'leave_type.name as leave_type', 'users.name as name')
                    ->join('users', 'users.emp_id', '=', 'events.uid')
                    ->join('leave_type', 'leave_type.id', '=', 'events.reason')
                    ->where('events.status', '=', 1)
                    ->where('events.uid', '=', $request->get('emp_id'))
                    ->where('users.web_agent', '=', Auth::user()->web_agent)
                    ->orderBy('events.start_date', 'DESC')->orderBy('created_at', 'DESC')
                    ->paginate(10);
                }
            }
            elseif(!empty($request->get('date_rep'))){
                $events = Event::select('events.*', 'events.created_at as send_date', 'leave_type.name as leave_type', 'users.name as name')
                ->join('users', 'users.emp_id', '=', 'events.uid')
                ->join('leave_type', 'leave_type.id', '=', 'events.reason')
                ->where('events.status', '=', 1)
                ->where('events.start_date', '=', $request->get('date_rep'))
                ->where('users.web_agent', '=', Auth::user()->web_agent)
                ->orderBy('events.start_date', 'DESC')->orderBy('created_at', 'DESC')
                ->paginate(10);
            }
            else {
                $events = Event::select('events.*', 'events.created_at as send_date', 'leave_type.name as leave_type', 'users.name as name')
                ->join('users', 'users.emp_id', '=', 'events.uid')
                ->join('leave_type', 'leave_type.id', '=', 'events.reason')
                ->where('events.status', '=', 1)
                ->where('users.web_agent', '=', Auth::user()->web_agent)
                ->orderBy('events.start_date', 'DESC')->orderBy('created_at', 'DESC')
                ->paginate(10);
            }

            $employees = DB::table('employee')->selectRaw('employee.id,employee.nickname')
                        ->leftjoin('users', 'users.emp_id', '=', 'employee.id')
                        ->where('users.status', '<>', 0)
                        ->where('users.web_agent', '=', Auth::user()->web_agent)
                        ->orderby('employee.dept_id')->get();

            $eventtype = DB::table('leave_type')->get();
            return view('admin/history-leave', compact('events', 'eventtype', 'employees'));
        } else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }



}

function sendlinemesg($msg_id)
{
    $leave = DB::table('token_line')->where('t_deptid', '=', $msg_id)->get();
    $leave_token = $leave[0]->t_token;
    define('LINE_API',"https://notify-api.line.me/api/notify");
	define('LINE_TOKEN',$leave_token);
}

// function notify_message($message)
// {
//     $queryData = array('message' => $message);
//     $queryData = http_build_query($queryData,'','&');
//     $headerOptions = array(
//         'http'=>array(
//             'method'=>'POST',
//             'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
//                     ."Authorization: Bearer ".LINE_TOKEN."\r\n"
//                     ."Content-Length: ".strlen($queryData)."\r\n",
//             'content' => $queryData
//         )
//     );
//     $context = stream_context_create($headerOptions);
//     $result = file_get_contents(LINE_API,FALSE,$context);
//     $res = json_decode($result);
//     return $res;
// }
function notify_message($message, $msg_id)
{

    $curl = curl_init();
    $leave = DB::table('token_line')->where('t_deptid', '=', $msg_id)->get();
    $leave_token = $leave[0]->t_token;


    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://notify-api.line.me/api/notify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"message\"\r\n\r\n$message\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $leave_token",
            "Postman-Token: 9f199469-4a59-44c3-9bf5-1c71356bbe25",
            "cache-control: no-cache",
            "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }
}

function seandNotitoLineHR($data)
{
    $getHR = DB::table('user_linebot')->where('type_noti', '=', 'HR')->get();
    $getEmpoley = Employee::where('id', '=', Auth::user()->emp_id)->first();
    $lastleaveSet = 0;
    $lastleave = 0;
    $coutLeave = 0;
    $type = null;
    $stat = null;
    $reason = null;
    if(iconv_substr($data->typeleave, 0 , 5, 'UTF-8') === "ลากิจ"){
        $fdateResult1 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $getEmpoley->id)->whereRaw('(reason = 1 or reason = 8)')->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult1 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $getEmpoley->id)->whereRaw('(reason = 1 or reason = 8)')->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $coutLeave = $fdateResult1->dateDiff1 + $hdateResult1->dateDiff1;
        $coutLeave = 6 - $coutLeave;
    }
    elseif(iconv_substr($data->typeleave, 0 , 6, 'UTF-8') === "ลาป่วย"){
        $fdateResult2 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $getEmpoley->id)->where('reason', 2)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult2 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $getEmpoley->id)->where('reason', 2)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $coutLeave = $fdateResult2->dateDiff1 + $hdateResult2->dateDiff1;
        $coutLeave = 30 - $coutLeave;
    }
    $lastleaveSet = Event::where('uid', '=', $getEmpoley->id)->orderBy('start_date', 'DESC')->limit(15)->get();
    for ($j=0; $j < count($lastleaveSet) ; $j++) {
        if(strtotime($lastleaveSet[$j]->start_date) < strtotime(substr($data->DateStart, 0, 10))){
            if ($lastleaveSet[$j]->type == 0.5) {
                $type = 'ครึ่งวัน' ;
            }
            else {
                $type = 'ทั้งวัน' ;
            }
            $lastleave = date("Y-m-d",strtotime($lastleaveSet[$j]->start_date)).'['.$type.']';
            if($lastleaveSet[$j]->start_date !== $lastleaveSet[$j]->end_date){
                $lastleave .= " ถึง ".date("Y-m-d",strtotime($lastleaveSet[$j]->start_date)).'['.$type.']';
            }
            if ($lastleaveSet[$j]->status == 1) {
                $stat = '[อนุมัติ]';
            }
            elseif($lastleaveSet[$j]->status == 2){
                $stat = '[ไม่อนุมัติ]';
            }
            else {
                $stat = '[รออนุมัติ]';
            }
            switch ($lastleaveSet[$j]->reason) {
                case '1':
                    $reason = 'ลากิจ'.$stat ;
                    break;
                case '2':
                    $reason = 'ลาป่วย'.$stat;
                    break;
                case '4':
                    $reason = 'ลาบวช'.$stat;
                    break;
                case '5':
                    $reason = 'ลาคลอด'.$stat;
                    break;
                case '6':
                    $reason = 'ลากรณีพิเศษ'.$stat;
                    break;
                case '7':
                    $reason = 'ไปเรียน'.$stat;
                    break;
                case '8':
                    $reason = 'ลากิจฉุกเฉิน'.$stat;
                    break;
                case '9':
                    $reason = 'ทำงานนอกสถานที่'.$stat;
                    break;
                default:
                    $reason = 'วันหยุดประจำสัปดาห์'.$stat;
                    break;
            }
            $typeLeave = $reason;
            break;
        }
    }
    for ($i=0; $i < count($getHR) ; $i++) {
        if(!is_null($getHR[$i]->lineuuid)){
            if(!empty($data->DateEnd)){
                $meassage = '{ "to": "'.$getHR[$i]->lineuuid.'", "messages":[ { "type": "flex", "altText": "แจ้งเตือนการขอลา", "contents": { "type": "bubble", "header": { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "แจ้งเตือนการขอลา", "size": "xl", "align": "center", "weight": "bold", "color": "#000000" } ] }, "hero": { "type": "image", "url": "https://leave.digitalmerce.com/storage/profile/'.$getEmpoley->photo.'", "size": "full", "aspectRatio": "4:3", "aspectMode": "fit" }, "body": { "type": "box", "layout": "vertical", "spacing": "md", "contents": [ { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "ชื่อ" }, { "type": "text", "text": "'.$data->name.'" } ] }, { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "ประเภทการลา", "color": "#474747" }, { "type": "text", "text": "'.$data->typeleave.'", "weight": "bold", "color": "#000000" } ] }, { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "วันที่ลา", "color": "#474747" }, { "type": "text", "text": "'.$data->DateStart.' ถึง '.$data->DateEnd.'", "color": "#000000", "wrap": true } ] }, { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "เหตุผลในการลา", "color": "#474747" }, { "type": "text", "text": "'.$data->note.'", "color": "#000000", "wrap": true } ] }, { "type": "box", "layout": "vertical", "contents": [ { "type": "spacer" }, { "type": "separator", "color": "#CECECE" } ] }, { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "วันที่ลาล่าสุด", "color": "#434343" }, { "type": "text", "text": "'.$lastleave.'", "weight": "bold", "color": "#000000", "wrap": true } ] },{ "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "ประเภทการลา", "color": "#434343" }, { "type": "text", "text": "'.$typeLeave.'", "weight": "bold", "color": "#000000" } ] }, { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "วันลาคงเหลือ" }, { "type": "text", "text": "'.$coutLeave.'", "weight": "bold" } ] } ] }, "footer": { "type": "box", "layout": "horizontal", "contents": [ { "type": "button", "action": { "type": "postback", "label": "ไม่อนุมัติ", "text": "ไม่อนุมัติให้'.$data->name.'ลา", "data": "ไม่อนุมัติ,'.$data->DateStart.','.Auth::user()->emp_id.'" }, "color": "#FF0000", "margin": "md", "style": "primary" }, { "type": "button", "action": { "type": "postback", "label": "อนุมัติ", "text": "อนุมัติให้'.$data->name.'ลา", "data": "อนุมัติ,'.$data->DateStart.','.Auth::user()->emp_id.'" }, "color": "#2FFF00", "margin": "md", "style": "secondary" } ] } } } ] }' ;
            }
            else{
                $meassage = '{ "to": "'.$getHR[$i]->lineuuid.'", "messages":[ { "type": "flex", "altText": "แจ้งเตือนการขอลา", "contents": { "type": "bubble", "header": { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "แจ้งเตือนการขอลา", "size": "xl", "align": "center", "weight": "bold", "color": "#000000" } ] }, "hero": { "type": "image", "url": "https://leave.digitalmerce.com/storage/profile/'.$getEmpoley->photo.'", "size": "full", "aspectRatio": "4:3", "aspectMode": "fit" }, "body": { "type": "box", "layout": "vertical", "spacing": "md", "contents": [ { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "ชื่อ" }, { "type": "text", "text": "'.$data->name.'" } ] }, { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "ประเภทการลา", "color": "#474747" }, { "type": "text", "text": "'.$data->typeleave.'", "weight": "bold", "color": "#000000" } ] }, { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "วันที่ลา", "color": "#474747" }, { "type": "text", "text": "'.$data->DateStart.'", "color": "#000000", "wrap": true } ] }, { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "เหตุผลในการลา", "color": "#474747" }, { "type": "text", "text": "'.$data->note.'", "color": "#000000", "wrap": true } ] }, { "type": "box", "layout": "vertical", "contents": [ { "type": "spacer" }, { "type": "separator", "color": "#CECECE" } ] }, { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "วันที่ลาล่าสุด", "color": "#434343" }, { "type": "text", "text": "'.$lastleave.'", "weight": "bold", "color": "#000000", "wrap": true } ] },{ "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "ประเภทการลา", "color": "#434343" }, { "type": "text", "text": "'.$typeLeave.'", "weight": "bold", "color": "#000000" } ] }, { "type": "box", "layout": "horizontal", "contents": [ { "type": "text", "text": "วันลาคงเหลือ" }, { "type": "text", "text": "'.$coutLeave.'", "weight": "bold" } ] } ] }, "footer": { "type": "box", "layout": "horizontal", "contents": [ { "type": "button", "action": { "type": "postback", "label": "ไม่อนุมัติ", "text": "ไม่อนุมัติให้'.$data->name.'ลา", "data": "ไม่อนุมัติ,'.$data->DateStart.','.Auth::user()->emp_id.'" }, "color": "#FF0000", "margin": "md", "style": "primary" }, { "type": "button", "action": { "type": "postback", "label": "อนุมัติ", "text": "อนุมัติให้'.$data->name.'ลา", "data": "อนุมัติ,'.$data->DateStart.','.Auth::user()->emp_id.'" }, "color": "#2FFF00", "margin": "md", "style": "secondary" } ] } } } ] }' ;
            }
            sendToline($meassage);
        }
    }
}

function sendToline($dateleave)
{
    $dataline = DB::table('linebot')->first();
    $url = "https://api.line.me/v2/bot/message/push";
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $dateleave,
        CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$dataline->LB_TOKEN,
        "cache-control: no-cache",
        "content-type: application/json; charset=UTF-8",
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if($response !== '{}'){
        alert()->error('เกิดข้อผิดพลาดบางอย่าง กรุณาตรวจสอบ หรือติดต่อผู้พัฒนา');
        return redirect()->back();
    }
}

function thai_date($time){
    $thai_month_arr=array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม");
    $thai_date_return = date("j",strtotime($time));
    $thai_date_return .=" ".$thai_month_arr[date("n",strtotime($time))];
    $thai_date_return .= " ".substr((date("Y",strtotime($time))+543) , 2);
    return $thai_date_return;
}