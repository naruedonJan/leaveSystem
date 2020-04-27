<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Employee;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $news = DB::table('news')->orderby('ns_date', 'desc')->get();

        $nowdate = Carbon::now()->format('m-d');
        $birthdays = Employee::where('birthdate', 'LIKE', '%'.$nowdate.'%')->get();

        $count_bd = 0;
        foreach ($birthdays as $birthday) {
            $count_bd++;
        }
        return view('home', compact('birthdays', 'count_bd', 'news'));
    }
    public function setting(){
        return view('admin/setting');
    }

    public function report(Request $request){
        $year = $request->get('year');
        $leaveHistory = $request->get('leaveHistory');
        $leaveEmp = $request->get('leaveEmp');

        if (empty($year)) {
            $year = Carbon::parse(Carbon::now())->format('Y');
        } else {
            $year = $year;
        }


        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {

            $countEmp = Employee::select(DB::raw('count(users.id) as user'), 'department.dept_id', 'department.name as dep_name')
            ->join('users', 'users.emp_id', 'employee.id')
            ->join('department', 'department.dept_id', 'users.department')
            ->whereNotIn('department.dept_id', [1, 2])->groupBy('department.dept_id','dep_name')->get();

            $employees = Employee::selectRaw('employee.*,department.name,department.dept_id as deptid')
                        ->join('department', 'employee.dept_id', '=', 'department.dept_id')
                        ->join('users', 'users.emp_id', 'employee.id')
                        ->where('users.status', '<>', 2)
                        ->orderby('dept_id', 'asc')->orderby('employee.id', 'asc')->get();

            // ลากิจ
            $errand_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numErrand'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->whereRaw('(reason = 1 or reason = 8)')
                            ->where('type', '=', '1.0')
                            ->where('status', '=', '1')
                            ->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')
                            ->orderby('dept_id','asc')->orderby('uid','asc')
                            ->get();
            $herrand_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numErrand'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->whereRaw('(reason = 1 or reason = 8)')
                            ->where('type', '=', '0.5')
                            ->where('status', '=', '1')
                            ->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')
                            ->orderby('dept_id','asc')->orderby('uid','asc')
                            ->get();

            // ลาป่วย
            $sick_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numSick'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 2)->where('type', '=', '1.0')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();
            $hsick_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numSick'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 2)->where('type', '=', '0.5')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();

            // หยุดประจำสัปดาห์
            $week_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numWeek'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 3)->where('type', '=', '1.0')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();
            $hweek_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numWeek'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 3)->where('type', '=', '0.5')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();

            // ลาบวช
            $ordained_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numOrdained'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 4)->where('type', '=', '1.0')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();
            $hordained_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numOrdained'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 4)->where('type', '=', '0.5')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();

            // ลาคลอด
            $maternity_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numMaternity'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 5)->where('type', '=', '1.0')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();
            $hmaternity_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numMaternity'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 5)->where('type', '=', '0.5')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();

            // ลากรณีพิเศษ
            $special_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numSpecial'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 6)
                            ->where('type', '=', '1.0')
                            ->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')
                            ->orderby('dept_id','asc')->orderby('uid','asc')
                            ->get();
            $hspecial_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numSpecial'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 6)->where('type', '=', '0.5')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();

            // ลาไปเรียน
            $learn_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numLearn'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 7)->where('type', '=', '1.0')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();
            $hlearn_leaves = DB::table('events')->select(DB::raw('uid,sum(count_date) as count_date,count(*) as numLearn'))
                            ->join('employee', 'events.uid', '=', 'employee.id')
                            ->where('reason', '=', 7)->where('type', '=', '0.5')->where('events.start_date', 'LIKE', '%'.$year.'%')
                            ->groupBy('events.uid')->orderby('dept_id','asc')->orderby('uid','asc')->get();

            if (!empty($request->get('emp_id'))) {
                if (!empty($request->get('date_rep'))) {
                    $leaves = DB::table('events')->selectRaw('*,events.created_at as ev_created_at')
                    ->join('employee', 'employee.id', '=', 'events.uid')
                    ->join('leave_type', 'events.reason', '=', 'leave_type.id')
                    ->where('events.uid', '=', $request->get('emp_id'))
                    ->where('events.start_date', '=', $request->get('date_rep'))
                    ->orderby('employee.id', 'asc')->get();
                }
                else {
                    $leaves = DB::table('events')->selectRaw('*,events.created_at as ev_created_at')
                    ->join('employee', 'employee.id', '=', 'events.uid')
                    ->join('leave_type', 'events.reason', '=', 'leave_type.id')
                    ->where('events.uid', '=', $request->get('emp_id'))
                    ->where('events.start_date', 'LIKE', '%'.Carbon::parse(Carbon::now())->format('Y').'%')
                    ->orderby('events.start_date', 'desc')->orderby('employee.id', 'asc')->get();
                }
            }
            elseif (!empty($request->get('date_rep'))) {
                    $leaves = DB::table('events')->selectRaw('*,events.created_at as ev_created_at')
                    ->join('employee', 'employee.id', '=', 'events.uid')
                    ->join('leave_type', 'events.reason', '=', 'leave_type.id')
                    ->where('events.start_date', '=', $request->get('date_rep'))
                    ->orderby('employee.id', 'asc')->get();
            }
            else {
                $leaves = DB::table('events')->selectRaw('*,events.created_at as ev_created_at')
                ->join('employee', 'employee.id', '=', 'events.uid')
                ->join('leave_type', 'events.reason', '=', 'leave_type.id')
                ->where('events.start_date', 'LIKE', '%'.Carbon::parse(Carbon::now())->format('Y').'%')
                ->orderby('events.start_date', 'desc')->orderby('employee.id', 'asc')->get();
            }

            return view('admin/report', compact('employees', 'countEmp', 'leaves', 'errand_leaves', 'herrand_leaves', 'sick_leaves', 'hsick_leaves', 'week_leaves', 'hweek_leaves', 'ordained_leaves', 'hordained_leaves', 'maternity_leaves', 'hmaternity_leaves', 'special_leaves', 'hspecial_leaves', 'learn_leaves', 'hlearn_leaves', 'leaveHistory', 'leaveEmp', 'year'));
        } else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }

    }

    public function settingLine(Request $request){
        $lines = DB::table('token_line')
                ->join('notify_to', 'notify_to.nt_id', '=', 'token_line.t_deptid')
                ->get();
        $notifys_to = DB::table('notify_to')->get();


        if ($request->get('add_token') != '') {
            $repeat_token = DB::table('token_line')->selectRaw('count(*) as t_deptid')->where('t_deptid', '=', $request->get('nt_id'))->get();
            if ($repeat_token[0]->t_deptid == 0) {
                DB::table('token_line')->insert([
                    't_token' => trim($request->get('add_token')),
                    't_deptid' => $request->get('nt_id')
                ]);
                alert()->success('บันทึกข้อมูลสำเร็จ');
                return redirect()->back();
            }
            else {
                alert()->error('มีโทเคนสำหรับฟังก์ชันนี้แล้ว');
                return redirect()->back();
            }
        }

        if ($request->get('edit_token') != '') {
            DB::table('token_line')->where('t_id', '=', ($request->get('t_id')))
            ->update([
                't_token' => trim($request->get('edit_token')),
                't_deptid' => $request->get('nt_id')
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        }

        if ($request->get('delete_token') != '') {
            DB::table('token_line')->where('t_id', '=', $request->get('delete_token'))->delete();
            alert()->success('ลบข้อมูลสำเร็จ');
            return redirect()->back();
        }

        return view('admin/line', compact('lines', 'notifys_to'));
    }

    public function functionLine(Request $request){
        $notifys_to = DB::table('notify_to')->get();

        if ($request->get('addnt_name') != '') {
            DB::table('notify_to')->insert([
                'nt_name' => trim($request->get('addnt_name'))
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        }

        if ($request->get('edit_id') != '') {
            DB::table('notify_to')->where('nt_id', '=', ($request->get('edit_id')))
            ->update([
                'nt_name' => trim($request->get('nt_name'))
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        }

        if ($request->get('delete_id') != '') {
            DB::table('notify_to')->where('nt_id', '=', $request->get('delete_id'))->delete();
            alert()->success('ลบข้อมูลสำเร็จ');
            return redirect()->back();
        }

        return view('admin/function-line', compact('notifys_to'));
    }

    public function loandataline()
    {
        $dataline = DB::table('linebot')->first();
        $typeLink = DB::table('user_linebot')->where('uuid', '=', Auth::user()->id)->first();
        $setline = null;
        if(empty($typeLink->lineuuid)){
            $setline = 'login';
        }
        else{
            $setline = "OK";
        }
        return view('admin/settingline', compact('dataline', 'setline'));
    }

    public function savedataline(Request $request)
    {
        if ($request->CLIENT_ID != '') {
            try{
                $dataline = DB::table('linebot')->where('id', 1)->update([
                    'LB_TOKEN' => trim($request->TOKEN),
                    'LO_CLIENT_ID' => trim($request->CLIENT_ID),
                    'LO_CLIENT_SECRET' => trim($request->CLIENT_SECRET),
                    'LO_CALLBACK_URI' => trim($request->CALLBACK_URI)
                ]);
                alert()->success('บันทึกสำเร็จ');
                return redirect()->back();
            } catch(\Throwable $th){
                alert()->error('เกิดข้อผิดพลาดบางอย่าง กรุณาตรวจสอบ หรือติดต่อผู้พัฒนา');
                return redirect()->back();
            };
        }
    }

    public function addRules(Request $request)
    {
        if (!empty($request->get('r_rules'))) {
            if (empty($request->get('r_id')) && $request->get('r_id') == 0) {
                try{
                    DB::table('rules')->insert([                        
                        'r_rules' => $request->get('r_rules'),
                        'r_webagent' => Auth::user()->web_agent
                    ]);
                    alert()->success('บันทึกข้อมูลสำเร็จ');
                    return redirect()->back();
                } catch(\Throwable $th){
                    alert()->error('เกิดข้อผิดพลาดบางอย่าง กรุณาตรวจสอบ หรือติดต่อผู้พัฒนา');
                    return redirect()->back();
                };
            }
            else {
                try{
                    DB::table('rules')->where('r_id', '=', ($request->get('r_id')))
                    ->update([
                        'r_rules' => $request->get('r_rules'),
                    ]);
                    alert()->success('บันทึกข้อมูลสำเร็จ');
                    return redirect()->back();
                } catch(\Throwable $th){
                    alert()->error('เกิดข้อผิดพลาดบางอย่าง กรุณาตรวจสอบ หรือติดต่อผู้พัฒนา');
                    return redirect()->back();
                };
            }
        }
    }
}
