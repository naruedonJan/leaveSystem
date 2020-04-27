<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use App\Employee;
use App\User;
use App\Event;
use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PersonalController extends Controller
{
    public function viewEmp(Request $request)
    {
        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {

            // $department = Department::select('department.name', 'department.dept_id')
            // ->whereNotIn('id', [1, 2])->get();

            $countEmp = Employee::select(DB::raw('count(users.id) as user'), 'department.dept_id', 'department.name as dep_name')
            ->join('users', 'users.emp_id', 'employee.id')
            ->join('department', 'department.dept_id', 'users.department')
            ->where('status', '=', 1)
            ->whereNotIn('department.dept_id', [1, 2])->groupBy('department.dept_id','dep_name')->get();

            $employee = Employee::select('users.*', 'users.id as uid', 'employee.*', 'employee.id as eid', 'department.name as dep', 'department.dept_id as dep_id')
                ->where('users.status', 1)
                ->join('users', 'users.emp_id', 'employee.id')
                ->join('department', 'department.dept_id', 'users.department')
                ->orderBy('users.department')->orderBy('piority')
                ->paginate(50);
            return view('admin/employee', compact('employee', 'countEmp'));
        } else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }

    public function viewDepartment(Request $request)
    {
        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {
            $departments = DB::table('department')->get();
            return view('admin/department', compact('departments'));
        } else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }

    public function addDepartment(Request $request)
    {
        try {
            DB::table('department')->insert([
                'name' => $request->get('name'),
                'limit_event' => $request->get('limit_event')
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function editDepartment(Request $request)
    {
        try {
            DB::table('department')
            ->where('dept_id', $request->get('id'))
            ->update([
                'name' => $request->get('name'),
                'limit_event' => $request->get('limit_event')
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function deleteDepartment(Request $request)
    {
        try {
            DB::table('department')->where('dept_id', '=',$request->get('id'))->delete();
            alert()->success('ลบเรียบร้อย');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function viewPosition(Request $request)
    {
        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {
            # code...
            return view('admin/position');
        } else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }

    public function getProfile(Request $request)
    {
        if (!empty($_GET['emp_id'])) {
            $emp_id = $_GET['emp_id'];
        } else {
            $emp_id = Auth::user()->emp_id;
        }
        $employee = Employee::selectRaw('employee.*,district.*,amphur.*,province.*,department.name,.department.limit_event')->where('employee.id', $emp_id)
            ->join('department', 'department.dept_id', 'employee.dept_id')
            ->join('district', 'district.DISTRICT_ID', 'employee.district')
            ->join('amphur', 'amphur.AMPHUR_ID', 'employee.tambon')
            ->join('province', 'province.PROVINCE_ID', 'employee.province')
            ->first();

        $events = DB::table('events')->leftjoin('leave_type', 'leave_type.id', '=', 'events.reason')->where('events.status', '=', '1')->where('events.uid', '=', $emp_id)->where('start_date', '>=', Carbon::parse(Carbon::now()))->orderby('start_date')->limit(8)->get();
        // $employee = Employee::where('id',$emp_id)
        // ->join('amphur', 'amphur.AMPHUR_ID', 'employee.district')->get();

        $fdateResult = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $emp_id)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $emp_id)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult = $fdateResult->dateDiff1 + $hdateResult->dateDiff1;

        $fdateResult = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $emp_id)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $emp_id)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult = $fdateResult->dateDiff1 + $hdateResult->dateDiff1;

        $fdateResult1 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $emp_id)->whereRaw('(reason = 1 or reason = 8)')->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult1 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $emp_id)->whereRaw('(reason = 1 or reason = 8)')->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult1 = $fdateResult1->dateDiff1 + $hdateResult1->dateDiff1;

        $fdateResult2 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $emp_id)->where('reason', 2)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult2 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $emp_id)->where('reason', 2)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult2 = $fdateResult2->dateDiff1 + $hdateResult2->dateDiff1;

        $fdateResult3 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $emp_id)->where('reason', 3)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult3 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $emp_id)->where('reason', 3)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult3 = $fdateResult3->dateDiff1 + $hdateResult3->dateDiff1;

        $fdateResult4 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $emp_id)->where('reason', 4)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult4 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $emp_id)->where('reason', 4)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult4 = $fdateResult4->dateDiff1 + $hdateResult4->dateDiff1;

        $fdateResult5 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $emp_id)->where('reason', 5)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult5 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $emp_id)->where('reason', 5)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult5 = $fdateResult5->dateDiff1 + $hdateResult5->dateDiff1;

        $fdateResult6 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $emp_id)->where('reason', 6)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult6 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $emp_id)->where('reason', 6)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult6 = $fdateResult6->dateDiff1 + $hdateResult6->dateDiff1;

        $dataline = DB::table('linebot')->first();
        $linkLine = 'https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id='.$dataline->LO_CLIENT_ID.'&redirect_uri='.urlencode(($dataline->LO_CALLBACK_URI).'chatbot/callbackdata').'&bot_prompt=aggressive&scope=profile';
        return view('personal/profile', compact('leaveResult', 'leaveResult1', 'leaveResult2', 'leaveResult3', 'leaveResult4', 'leaveResult5', 'leaveResult6', 'employee', 'linkLine', 'events'));
    }

    public function editProfile(Request $request)
    {
        if (!empty($_POST['id'])) {
            if($request->file('profile-img') == NULL) {
                $image = $_POST['old-profile-img'];
            }
            else{
                $original_filename = $request->file('profile-img')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = 'storage/profile/';
                $image = time() . '.' . $file_ext;
                $request->file('profile-img')->move($destination_path, $image);
            }
            if($request->file('idcard-img') == NULL) {
                $image_id = $_POST['old-idcard-img'];
            }
            else{
                $original_filename = $request->file('idcard-img')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = 'storage/id_card/';
                $image_id = time() . '.' . $file_ext;
                $request->file('idcard-img')->move($destination_path, $image_id);
            }
            try {
                DB::table('employee')->where('id', '=', ($request->get('id')))
                ->update([
                    'title_th' => trim($request->get('title_th')),
                    'name_th' => trim($request->get('name_th')),
                    'surname_th' => trim($request->get('surname_th')),
                    'title_en' => trim($request->get('title_en')),
                    'name_en' => trim($request->get('name_en')),
                    'surname_en' => trim($request->get('surname_en')),
                    'nickname' => trim($request->get('nickname')),
                    'birthdate' => trim($request->get('birthdate')),
                    'dept_id' => trim($request->get('dept_id')),
                    'position' => trim($request->get('position')),
                    'gender' => trim($request->get('gender')),
                    'address' => trim($request->get('address')),
                    'district' => trim($request->get('district')),
                    'tambon' => trim($request->get('amphur')),
                    'province' => trim($request->get('province')),
                    'zipcode' => trim($request->get('zipcode')),
                    'mobile' => trim($request->get('mobile')),
                    'nationality' => trim($request->get('nationality')),
                    'religion' => trim($request->get('religion')),
                    'marital_status' => trim($request->get('marital_status')),
                    'card_id' => trim($request->get('card_id')),
                    'email' => trim($request->get('email')),
                    'soldier' => trim($request->get('soldier')),
                    'photo' => trim($image),
                    'emp_idCard' => trim($image_id),
                    'salary' => 0,
                    'emp_type' => trim($request->get('emp_type')),
                    'bank_no' => trim($request->get('bank_no')),
                    'emp_start_date' => trim($request->get('emp_start_date')),
                    'emp_limitErrand' => trim($request->get('emp_limitErrand')),
                ]);
                DB::table('users')->where('emp_id', '=', ($request->get('id')))
                ->update([
                    'department' => trim($request->get('dept_id'))
                ]);
                alert()->success('บันทึกข้อมูลสำเร็จ');
                return redirect()->back();
            } catch (QueryException $e) {
                alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
                return redirect()->back();
            }
        }

        if (!empty($_GET['emp_id'])) {
            $emp_id = $_GET['emp_id'];
        } else {
            $emp_id = Auth::user()->emp_id;
        }
        $employee = Employee::where('employee.id', $emp_id)
            ->join('department', 'department.dept_id', 'employee.dept_id')
            ->join('district', 'district.DISTRICT_ID', 'employee.district')
            ->join('amphur', 'amphur.AMPHUR_ID', 'employee.tambon')
            ->join('province', 'province.PROVINCE_ID', 'employee.province')
            ->first();

        $departments = DB::table('department')->where('dept_id', '<>', '1')->where('dept_id', '<>', '2')->get();
        $provinces = DB::table('province')->get();
        $amphurs = DB::table('amphur')->get();
        $districts = DB::table('district')->get();

        return view('personal/edit-profile', compact('employee', 'departments', 'provinces', 'amphurs', 'districts'));
    }

    public function changePassword()
    {
        return view('personal/change-password');
    }

    public function viewNewEmp(Request $request)
    {
        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {
            $employee = User::select('users.*', 'users.id as uid', 'employee.*')->where('users.status', 0)->join('employee', 'employee.id', 'users.emp_id')->paginate(50);
            return view('admin/new-emp', compact('employee'));
        } else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }

    public function acceptNewEmp(Request $request, $id)
    {
        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {
            $employee = User::find($id);
            if ($_GET['status'] == 'allow') {
                $status = 1;
            } else {
                $status = 2;
            }
            try {
                if ($_GET['status'] == 'allow') {
                    $employee->status = $status;
                    $employee->save();
                    alert()->success('ยืนยันตัวตนพนักงานเรียบร้อย สามารถใช้ระบบได้แล้ว');
                    return redirect()->back();
                } else {
                    $employee->status = $status;
                    $employee->save();
                    alert()->success('พนักงานออกแล้วไม่สามารถเข้าใช้ระบบได้อีก');
                    return redirect()->back();
                }
            } catch (\Throwable $th) {
                alert()->error('เกิดข้อผิดพลาดบางอย่าง กรุณาตรวจสอบ หรือติดต่อผู้พัฒนา');
                return redirect()->back();
            }
        } else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }
}
