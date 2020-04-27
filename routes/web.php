<?php

/* 
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Carbon\Carbon;
use App\Http\Controllers\PersonalController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Event;
use RealRashid\SweetAlert\Facades\Alert;
use App\Employee;
use App\User;
use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/test', function (Request $request) {

    // $domain = substr (request()->getHost(), 7); // $domain is now 'www.example.com'
    $domain = request()->getHost(); // $domain is now 'www.example.com'
    $c_domain = 0;
    for ($i=0; $i < strlen($domain); $i++) { 
        if ($domain[$i] == '.') {
            break;
        }
        else {
            $c_domain++;
        }
    }
    $subdomain = substr (request()->getHost(), 0, $c_domain);

    echo $domain . '</br>';
    echo $subdomain . '</br>';
});

Route::get('/registerd', function () {

    $departments = DB::table('department')->where('dept_id', '<>', '1')->where('dept_id', '<>', '2')->get();
    $provinces = DB::table('province')->get();
    $amphurs = DB::table('amphur')->get();
    $districts = DB::table('district')->get();

    return view('register', compact('departments', 'provinces', 'amphurs', 'districts'));
});

Route::post('/register-emp', function(Request $request)
{
    if (!empty($_POST['email'])) {
        
            $original_filename = $request->file('profile-img')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = 'storage/profile/';
            $image = time() . '.' . $file_ext;
            $request->file('profile-img')->move($destination_path, $image);
            
            $original_filename = $request->file('idcard-img')->getClientOriginalName();
            $original_filename_arr = explode('.', $original_filename);
            $file_ext = end($original_filename_arr);
            $destination_path = 'storage/id_card/';
            $image_id = time() . '.' . $file_ext;
            $request->file('idcard-img')->move($destination_path, $image_id);
        try {
            DB::table('employee')->insert([
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
                // 'emp_limitErrand' => trim($request->get('emp_limitErrand')),
            ]);

            $id = DB::table('employee')->select('id')->orderby('id', 'desc')->limit(1)->get();

            $password = trim($request->get('password'));
            $password = bcrypt($password);

            DB::table('users')->insert([
                'emp_id' => $id[0]->id,
                'name' => trim($request->get('nickname')),
                'email' => trim($request->get('email')),
                'password' => trim($password),
                'permission' => 'employee',
                'department' => trim($request->get('dept_id')),
                'piority' => 2,
                'status' => 0,
                'web_agent' => 1,
            ]);
            alert()->success('บันทึกข้อมูลสำเร็จ');            
            return redirect('/login');
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }
});



Route::get('/flight', 'LeaveController@flightShow');
Route::post('/flightAdd', 'LeaveController@flightAdd');

Route::get('api/leaveallow/{data}', function ($data){
    $dataReturn = null;
    $dateleave = null;
    $datadecode = decode($data, strrev('pw0m[3094pmu').'w;eogjwpoi3u023'.strrev('y190psdkbm;l'));
    $dataPost = explode(',' , $datadecode);
    $event = Event::where("uid", intval($dataPost[2]))->where("start_date" ,date("Y-m-d",strtotime(substr($dataPost[1], 0 , 10))))->first();
    if($event->status === 0){
        if ($dataPost[0] === 'อนุมัติ') {
            $status = 1;
            $dataReturn = "อนุมัติการลาเรียบร้อยแล้ว";
        } else {
            $status = 2;
            $dataReturn = "ปฎิเสธการลาเรียบร้อยแล้ว";
        }
        try {
            $event->status = $status;
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
            $event->save();
        } catch (\Throwable $th) {
            $dataReturn = "บันทึกไม่สำเร็จ";
        }
    }
    else{
        $dataReturn = "ท่านอนุมัติการลาแล้ว";
    }
    
    return response()->json($dataReturn);
});

Route::get('api/leavecout/{id}', function ($id)
{
    $dataReturn = [];
    $datadecode = decode($id, strrev('Gwe923porewjfw6457869e7do').'[-kuvw30[]');
    $user = DB::table('employee')->where('lineUUID', '=', $datadecode)->first();
    if(!is_null($user)){
        $fdateResult = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $user->id)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $user->id)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult = $fdateResult->dateDiff1 + $hdateResult->dateDiff1;

        $fdateResult1 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $user->id)->whereRaw('(reason = 1 or reason = 8)')->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult1 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $user->id)->whereRaw('(reason = 1 or reason = 8)')->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult1 = $fdateResult1->dateDiff1 + $hdateResult1->dateDiff1;

        $fdateResult2 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $user->id)->where('reason', 2)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult2 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $user->id)->where('reason', 2)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult2 = $fdateResult2->dateDiff1 + $hdateResult2->dateDiff1;

        $fdateResult3 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $user->id)->where('reason', 3)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult3 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $user->id)->where('reason', 3)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult3 = $fdateResult3->dateDiff1 + $hdateResult3->dateDiff1;

        $fdateResult4 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $user->id)->where('reason', 4)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult4 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $user->id)->where('reason', 4)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult4 = $fdateResult4->dateDiff1 + $hdateResult4->dateDiff1;

        $fdateResult5 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $user->id)->where('reason', 5)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult5 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $user->id)->where('reason', 5)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult5 = $fdateResult5->dateDiff1 + $hdateResult5->dateDiff1;

        $fdateResult6 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`)) as dateDiff1'))->where('uid', $user->id)->where('reason', 6)->where('status', 1)->where('type', '1.0')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $hdateResult6 = Event::select(DB::raw('Sum(DATEDIFF(DATE_ADD(`end_date`, INTERVAL 1 DAY), `start_date`))/2 as dateDiff1'))->where('uid', $user->id)->where('reason', 6)->where('status', 1)->where('type', '0.5')->where('start_date', 'LIKE', Carbon::now()->format('Y') . '%')->first();
        $leaveResult6 = $fdateResult6->dateDiff1 + $hdateResult6->dateDiff1;
        $dataSet = [$leaveResult, $leaveResult1, $leaveResult2, $leaveResult3, $leaveResult4, $leaveResult5, $leaveResult6];
        $dataencode = encode(implode(",", $dataSet), strrev('-qv3y80,2[409yuewfsa').'9t9ug[0');
        $dataReturn['status'] = 'Success';
        $dataReturn['data'] = $dataencode;
    }
    else{
        $dataReturn['status'] = 'Unauthorized';
    }
    return response()->json($dataReturn);
});
Route::get('notifyDaily', function(){

$daily_leaves = DB::table('events')->selectRaw('events.*,employee.nickname')
->join('employee', 'employee.id', '=', 'events.uid')
// ->where('start_date', '=', Carbon::parse(Carbon::now())->format('Y-m-d'))
->whereRaw('"'.Carbon::parse(Carbon::now())->format('Y-m-d').'" between start_date and end_date')
->where('events.status', '<>', 2)
->get();

// line notify
    $message = "\n". 'แจ้งวันหยุด: ' .'วันที่ : '.Carbon::parse(Carbon::now())->format('d-m-Y');
    $i = 1;
    foreach ($daily_leaves as $key => $daily_leave) {
        switch ($daily_leave->reason) {
            case '1':
            $reason = 'ลากิจ';
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
        if ($daily_leave->type == 0.5) {
            $type = 'ครึ่งวัน' ;
        }
        else {
            $type = 'ทั้งวัน' ;
        }

        $message .= "\n".$i.'. : '.$daily_leave->nickname . ' - ' . $reason . '[' . $type . ']';
        $i++;
    }
    $leave = DB::table('token_line')->where('t_deptid', '=', 8)->get();
    $leave_token = $leave[0]->t_token;

    $curl = curl_init();



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

    // define('LINE_API',"https://notify-api.line.me/api/notify");
    // define('LINE_TOKEN',$leave_token);

    // header('Content-Type: text/html; charset=utf-8');

    // $queryData = array('message' => $message);
    // $queryData = http_build_query($queryData,'','&');
    // $headerOptions = array(
    //     'http'=>array(
    //         'method'=>'POST',
    //         'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
    //                 ."Authorization: Bearer ".LINE_TOKEN."\r\n"
    //                 ."Content-Length: ".strlen($queryData)."\r\n",
    //         'content' => $queryData
    //     )
    // );
    // $context = stream_context_create($headerOptions);
    // $result = file_get_contents(LINE_API,FALSE,$context);
    // $res = json_decode($result);
    // echo '<script>alert("การส่งข้อความสำเร็จ")</script>';
    // // line notify
});

Auth::routes();

// Route::get('/', function () {
//     return view('welcome');
// });

    Route::get('/', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'],function(){
    Route::get('profile',  'PersonalController@getProfile');
});



Route::group(['middleware' => 'auth', 'prefix' => 'leave'], function () {
    Route::get('/calendar', 'LeaveController@showCalendar');
    Route::post('/add-calender', 'LeaveController@addCalendar');
    Route::post('/edit-calender', 'LeaveController@editCalendar');
    Route::post('/cancle-calender', 'LeaveController@cancleCalendar');
    Route::get('/history', 'LeaveController@leaveHistory');
    Route::get('profile',  'PersonalController@getProfile');
    Route::get('edit-profile',  'PersonalController@editProfile');
    Route::post('/edit-profile', 'PersonalController@editProfile');
    Route::get('/flight', 'LeaveController@flightShow');
    Route::post('/flightAdd', 'LeaveController@flightAdd');
});

Route::group(['middlewate' => 'auth', 'prefix' => 'admin'], function () {
    Route::get('/home', 'HomeController@index')->name('admin/home');
    Route::get('approve', 'LeaveController@viewApprove');
    Route::get('approve/{id}', 'LeaveController@allowLeave');
    Route::get('calendar', 'LeaveController@showCalendar');
    Route::get('employee', 'PersonalController@viewEmp');
    Route::get('position', 'PersonalController@viewPosition');
    Route::get('new-emp', 'PersonalController@viewNewEmp');
    Route::get('accept/{id}', 'PersonalController@acceptNewEmp');
    Route::get('profile',  'PersonalController@getProfile');
    Route::get('setting',  'HomeController@setting');
    Route::get('adminmanage',  'UserController@adminmanage');
    Route::post('/addadmin',  'UserController@addadmin');
    Route::post('/editadmin',  'UserController@editadmin');
    Route::post('/editadminpassword',  'UserController@editadminpassword');
    Route::post('/deleteadmin',  'UserController@deleteadmin');

    Route::get('report',  'HomeController@report');
    Route::post('/report', 'HomeController@report');

    Route::get('line',  'HomeController@settingLine');
    Route::post('/setting-line', 'HomeController@settingLine');

    Route::get('function-line',  'HomeController@functionLine');
    Route::post('/function-line', 'HomeController@functionLine');

    Route::get('linebotsetting',  'HomeController@loandataline');
    Route::post('/savedataline', 'HomeController@savedataline');

    Route::get('department', 'PersonalController@viewDepartment');
    Route::post('/add-department', 'PersonalController@addDepartment');
    Route::post('/edit-department', 'PersonalController@editDepartment');
    Route::post('/delete-department', 'PersonalController@deleteDepartment');

    Route::get('/leave/all-holiday',  'LeaveController@allHoliday');
    Route::post('/leave/add-holiday', 'LeaveController@addHoliday');
    Route::post('/leave/edit-holiday', 'LeaveController@editHoliday');
    Route::post('/leave/cancle-holiday', 'LeaveController@cancleHoliday');

    Route::post('/leave/add-cannot-holiday', 'LeaveController@addcannotHoliday');

    Route::post('/leave/add-news', 'LeaveController@addNews');
    Route::post('/leave/edit-news', 'LeaveController@editNews');
    Route::post('/leave/cancle-news', 'LeaveController@cancleNews');

    Route::post('/leave/admin-add-calender', 'LeaveController@adminaddCalendar');
    
    Route::get('/history-leave', 'LeaveController@viewHistoryadmin');
    Route::post('/history-leave', 'LeaveController@viewHistoryadmin');
    Route::post('/edit-calender', 'LeaveController@editCalendaradmin');
    Route::post('/cancle-calender', 'LeaveController@cancleCalendar');
    
    Route::post('/add-rules', 'HomeController@addRules');
});
    Route::post('change-password', 'UserController@changePassword');
    Route::get('changepassword', 'UserController@password');

Route::group(['middleware' => 'auth', 'prefix' => 'job'], function () {
    Route::get('/', 'JobController@index');
    Route::get('/views/football', 'JobController@viewFootball');

    Route::get('/token', 'JobController@token');
    Route::post('/token/add', 'JobController@addtoken');

    // content
    Route::get('/content', 'JobController@viewContent');
    Route::get('/content/add', 'JobController@addContent');
    // Route::get('/content/add', function () {
    //     return view('job//views/content_add');
    // });
    Route::get('/content/addweb', function () {
        return view('job/content/content_addweb');
    });
    Route::post('/content/addweb', 'JobController@addWeb');
    Route::post('/content/addjob', 'JobController@contentAddjob');
    // content

    // social
    Route::get('/social', 'JobController@viewSocial');
    Route::get('/social/add', 'JobController@addSocial');
    // Route::get('/social/add', function () {
    //     return view('job//views/social_add');
    // });
    Route::get('/social/addweb', function () {
        return view('job/social/social_addweb');
    });
    Route::post('/social/addweb', 'JobController@addPage');
    Route::post('/social/addjob', 'JobController@socialAddjob');
    // social

    // graphic
    Route::get('/graphic', 'JobController@viewGraphic');
    Route::get('/graphic/add', function () {
        return view('job/graphic/graphic_add');
    });
    Route::post('/graphic/addjob', 'JobController@graphicAddjob');
    // graphic

    // backlink
    Route::get('/backlink', 'JobController@viewBacklink');
    Route::get('/backlink/add', 'JobController@addBacklink');
    // Route::get('/backlink/add', function () {
    //     return view('job//views/backlink_add');
    // });
    Route::get('/backlink/addweb', function () {
        return view('job/backlink/backlink_addweb');
    });
    Route::post('/backlink/addweb', 'JobController@addWeb');
    Route::post('/backlink/addjob', 'JobController@backlinkAddjob');
    // backlink

    // programmer
    Route::get('/programmer', 'JobController@viewProgrammer');
    Route::get('/programmer/add', function () {
        return view('job/programmer/programmer_add');
    });
    Route::post('/programmer/addjob', 'JobController@programmerAddjob');
    // programmer

    // pagecost
    Route::get('/pagecost', 'JobController@showPromote');
    Route::get('/pagecost/add', function () {
        return view('job/pagecost/add');
    });
    Route::post('/pagecost/addcost', 'JobController@addCost');
    // pagecost

    // program
    Route::get('/program', 'JobController@viewProgram');
    Route::get('/program/add', function () {
        return view('job/program/program_add');
    });
    Route::post('/program/addprogram', 'JobController@addProgram');
    // program

    //line
    Route::get('/line/new', 'JobController@newLine');
    Route::get('/line/line', 'JobController@viewLine');
    Route::get('/line/check', 'JobController@jobLine');
    Route::get('/line/checked', 'JobController@checkLine');
    Route::get('/line/report', 'JobController@reportLine');
    Route::post('/line/postLine', 'JobController@postLine');
    //line

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Chatbot
Route::group(['middleware' => 'auth', 'prefix' => 'chatbot'], function () {
    Route::get('callbackdata', 'ChatbotController@callbackLine');
    Route::get('disconnecttline', 'ChatbotController@disconnecttline');
    Route::get('getjson', 'LeaveController@testSend');
    Route::post('/apibot/v1/chatbot', 'ChatbotController@chatbot');

});

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
    }

function encode($string,$key) {
    $j = null;
    $hash = null;
    $key = md5(hash('sha512',base64_encode($key)));
    $strLen = strlen($string);
    $keyLen = strlen($key);
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($string,$i,1));
        if ($j === $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
    }
    return $hash;
}

function decode($string,$key) {
    $j = null;
    $hash = null;
    $key = md5(hash('sha512',base64_encode($key)));
    $strLen = strlen($string);
    $keyLen = strlen($key);
    for ($i = 0; $i < $strLen; $i+=2) {
        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    return $hash;
}

function thai_date($time){
    $thai_month_arr=array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม");
    $thai_date_return = date("j",strtotime($time));
    $thai_date_return .=" ".$thai_month_arr[date("n",strtotime($time))];
    $thai_date_return .= " ".substr((date("Y",strtotime($time))+543) , 2);
    return $thai_date_return;
}