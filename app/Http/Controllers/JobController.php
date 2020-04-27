<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Employee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Calendar;
use App\Line;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');

        $emps = DB::table('employee')->selectRaw('id,nickname')->get();

        $backlink_details = DB::table('backlink_detail')->selectRaw('UID,count(*) as c_job')
            ->where('job_date', 'Like', "%$year" . '-' . "$month%")
            ->groupby('backlink_detail.UID')
            ->orderBy('c_job', 'desc')
            ->get();

        $content_details = DB::table('content_detail')->selectRaw('UID,count(*) as c_job')
            ->where('job_date', 'Like', "%$year" . '-' . "$month%")
            ->groupby('content_detail.UID')
            ->get();

        $graphic_details = DB::table('graphic_detail')->selectRaw('UID,count(*) as c_job')
            ->where('job_date', 'Like', "%$year" . '-' . "$month%")
            ->groupby('graphic_detail.UID')
            ->get();

        $programmer_details = DB::table('programmer_detail')->selectRaw('UID,count(*) as c_job')
            ->where('job_date', 'Like', "%$year" . '-' . "$month%")
            ->groupby('programmer_detail.UID')
            ->get();

        $social_details = DB::table('social_detail')->selectRaw('UID,count(*) as c_job')
            ->where('job_date', 'Like', "%$year" . '-' . "$month%")
            ->groupby('social_detail.UID')
            ->get();

        $page_costs = DB::table('page_cost')->selectRaw('emp_id,count(*) as c_job')
            ->where('created_at', 'Like', "%$year" . '-' . "$month%")
            ->groupby('page_cost.emp_id')
            ->get();

        return view('job/home', compact('emps', 'backlink_details', 'content_details', 'graphic_details', 'programmer_details', 'social_details', 'page_costs'));
    }

    public function addWeb(Request $request)
    {
        $data = $request->input();

        try {
            DB::table('web')->insert([
                'Weblink' => $data['link_web']
            ]);
            alert()->success('บันทึกข้อมูลแล้ว');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function addPage(Request $request)
    {
        $data = $request->input();

        try {
            DB::table('social_page')->insert([
                'sm_name' => $data['page_name']
            ]);
            alert()->success('บันทึกข้อมูลแล้ว');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function token(Request $request)
    {
        $Token = DB::table('token_line')
            ->where('t_deptid', '=', $_GET['dept_id'])
            ->get();
        $Dept = $_GET['dept_id'];
        return view('job/token', compact('Token', 'Dept'));
    }
    public function addtoken(Request $request)
    {
        $data = $request->input();
        $Token = DB::table('token_line')
            ->where('t_deptid', '=', $data['t_deptid'])
            ->get();
        $count = 0;
        foreach ($Token as $key => $token) {
            $count++;
        }
        if ($count == 0) {
            try {
                DB::table('token_line')->insert([
                    't_token' => $data['t_token'],
                    't_deptid' => $data['t_deptid']
                ]);
                alert()->success('บันทึกข้อมูลแล้ว');
                return redirect()->back();
            } catch (QueryException $e) {
                alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
                return redirect()->back();
            }
        } else {
            try {
                DB::table('token_line')
                    ->where('t_id', '=', $data['t_id'])
                    ->update([
                        't_token' => $data['t_token']
                    ]);
                alert()->success('บันทึกข้อมูลแล้ว');
                return redirect()->back();
            } catch (QueryException $e) {
                alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
                return redirect()->back();
            }
        }
    }

    // backlink
    public function viewBacklink(Request $request)
    {
        $Emp = DB::table('backlink_detail')->join('employee', 'employee.id', '=', 'backlink_detail.UID')->groupby('UID')->get();

        $Web = DB::table('web')
            ->get();

        if (!empty($_GET['del_id'])) {
            DB::table('backlink_detail')
                ->where('WID', '=', $_GET['del_id'])
                ->delete();
            alert()->success('ลบเรียบร้อย');
        }

        //search
        if (!empty($_GET['web_id']) && !empty($_GET['emp_id']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $Job = DB::table('backlink_detail')
                ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                ->leftjoin('web', 'backlink_detail.BLID', '=', 'web.BLID')
                ->where('backlink_detail.BLID', '=', $_GET['web_id'])
                ->where('backlink_detail.UID', '=', $_GET['emp_id'])
                ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
        } elseif (!empty($_GET['web_id'])) {
            if (!empty($_GET['emp_id'])) {
                if (!empty($_GET['start_date'])) {
                    $Job = DB::table('backlink_detail')
                        ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'backlink_detail.BLID', '=', 'web.BLID')
                        ->where('backlink_detail.BLID', '=', $_GET['web_id'])
                        ->where('backlink_detail.UID', '=', $_GET['emp_id'])
                        ->where('job_date', '=', $_GET['start_date'])
                        ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
                } else {
                    $Job = DB::table('backlink_detail')
                        ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'backlink_detail.BLID', '=', 'web.BLID')
                        ->where('backlink_detail.BLID', '=', $_GET['web_id'])
                        ->where('backlink_detail.UID', '=', $_GET['emp_id'])
                        ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
                }
            } elseif (!empty($_GET['start_date'])) {
                if (!empty($_GET['end_date'])) {
                    $Job = DB::table('backlink_detail')
                        ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'backlink_detail.BLID', '=', 'web.BLID')
                        ->where('backlink_detail.BLID', '=', $_GET['web_id'])
                        ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                        ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
                } else {
                    $Job = DB::table('backlink_detail')
                        ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'backlink_detail.BLID', '=', 'web.BLID')
                        ->where('backlink_detail.BLID', '=', $_GET['web_id'])
                        ->where('job_date', '=', $_GET['start_date'])
                        ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
                }
            } else {
                $Job = DB::table('backlink_detail')
                    ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                    ->leftjoin('web', 'backlink_detail.BLID', '=', 'web.BLID')
                    ->where('backlink_detail.BLID', '=', $_GET['web_id'])
                    ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
            }
        } elseif (!empty($_GET['emp_id'])) {
            if (!empty($_GET['start_date'])) {
                if (!empty($_GET['end_date'])) {
                    $Job = DB::table('backlink_detail')
                        ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'backlink_detail.WID', '=', 'web.BLID')
                        ->where('backlink_detail.UID', '=', $_GET['emp_id'])
                        ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                        ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
                } else {
                    $Job = DB::table('backlink_detail')
                        ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'backlink_detail.WID', '=', 'web.BLID')
                        ->where('backlink_detail.UID', '=', $_GET['emp_id'])
                        ->where('job_date', '=', $_GET['start_date'])
                        ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
                }
            } else {
                $Job = DB::table('backlink_detail')
                    ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                    ->leftjoin('web', 'backlink_detail.WID', '=', 'web.BLID')
                    ->where('backlink_detail.UID', '=', $_GET['emp_id'])
                    ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
            }
        } elseif (!empty($_GET['start_date'])) {
            if (!empty($_GET['end_date'])) {
                $Job = DB::table('backlink_detail')
                    ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                    ->leftjoin('web', 'backlink_detail.BLID', '=', 'web.BLID')
                    ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                    ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
            } else {
                $Job = DB::table('backlink_detail')
                    ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                    ->leftjoin('web', 'backlink_detail.BLID', '=', 'web.BLID')
                    ->where('job_date', '=', $_GET['start_date'])
                    ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
            }
        } else {
            $Job = DB::table('backlink_detail')
                ->leftjoin('employee', 'backlink_detail.UID', '=', 'employee.id')
                ->leftjoin('web', 'backlink_detail.BLID', '=', 'web.BLID')
                ->whereRaw("MONTH(`backlink_detail`.`created_at`) = MONTH(CURDATE())")
                ->orderByRaw('job_date desc, backlink_detail.created_at desc')
                ->get();
        }
        //search
        return view('job/backlink/backlink', compact('Emp', 'Web', 'Job'));
    }
    public function addBacklink(Request $request)
    {
        $Web = DB::table('web')
            ->get();

        return view('job/backlink/backlink_add', compact('Web'));
    }
    public function backlinkAddjob(Request $request)
    {
        $data = $request->input();

        try {
            DB::table('backlink_detail')->insert([
                'BLID' => $data['web_id'],
                'Webposted' => $data['job_web'],
                'groupf' => $data['job_forum'],
                'job_date' => $data['job_date'],
                'UID' => Auth::user()->emp_id,
                'comment' => $data['job_ps']
            ]);
            // line notify
            $message = "\n" . 'ส่งงานกราฟฟิค';
            $message .= "\n" . 'ชื่อพนักงาน : ' . Auth::user()->name . "\n" . 'เว็บที่โพสต์ : ' . $data['job_web'] . "\n" . 'ชื่อกลุ่ม/forum ที่นำไปโพสต์ : ' . $data['job_forum'];
            $message .= "\n" . 'วันที่ : ' . Carbon::parse($data['job_date'])->format('d-m-Y');
            $message .= "\n" . 'เวลาบันทึก : ' . Carbon::parse(Carbon::now())->format('d-m-Y H:i:s');
            $message .= "\n" . 'หมายเหตุ : ' . $data['job_ps'] . "\n";
            // sendlinemesg(6); //change by notify id
            header('Content-Type: text/html; charset=utf-8');
            notify_message($message, 6);

            alert()->success('บันทึกข้อมูลแล้ว');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }
    // backlink

    // Graphic
    public function viewGraphic(Request $request)
    {
        $Emp = DB::table('graphic_detail')->join('employee', 'employee.id', '=', 'graphic_detail.UID')->groupby('UID')->get();

        if (!empty($_GET['del_id'])) {
            DB::table('graphic_detail')
                ->where('PicID', '=', $_GET['del_id'])
                ->delete();
            alert()->success('ลบเรียบร้อย');
        }

        //search
        if (!empty($_GET['emp_id']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $Job = DB::table('graphic_detail')
                ->leftjoin('employee', 'graphic_detail.UID', '=', 'employee.id')
                ->where('graphic_detail.UID', '=', $_GET['emp_id'])
                ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                ->orderByRaw('job_date desc, graphic_detail.created_at desc')
                ->get();
        } elseif (!empty($_GET['emp_id'])) {
            if (!empty($_GET['start_date'])) {
                $Job = DB::table('graphic_detail')
                    ->leftjoin('employee', 'graphic_detail.UID', '=', 'employee.id')
                    ->where('graphic_detail.UID', '=', $_GET['emp_id'])
                    ->where('job_date', '=', $_GET['start_date'])
                    ->orderByRaw('job_date desc, graphic_detail.created_at desc')
                ->get();
            } else {
                $Job = DB::table('graphic_detail')
                    ->leftjoin('employee', 'graphic_detail.UID', '=', 'employee.id')
                    ->where('graphic_detail.UID', '=', $_GET['emp_id'])
                    ->orderByRaw('job_date desc, graphic_detail.created_at desc')
                ->get();
            }
        } elseif (!empty($_GET['start_date'])) {
            if (!empty($_GET['end_date'])) {
                $Job = DB::table('graphic_detail')
                    ->leftjoin('employee', 'graphic_detail.UID', '=', 'employee.id')
                    ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                    ->orderByRaw('job_date desc, graphic_detail.created_at desc')
                ->get();
            } else {
                $Job = DB::table('graphic_detail')
                    ->leftjoin('employee', 'graphic_detail.UID', '=', 'employee.id')
                    ->where('job_date', '=', $_GET['start_date'])
                    ->orderByRaw('job_date desc, graphic_detail.created_at desc')
                ->get();
            }
        } else {
            $Job = DB::table('graphic_detail')
                ->leftjoin('employee', 'graphic_detail.UID', '=', 'employee.id')
                ->whereRaw("MONTH(`graphic_detail`.`created_at`) = MONTH(CURDATE())")
                ->orderByRaw('job_date desc, graphic_detail.created_at desc')
                ->get();
        }
        //search

        return view('job/graphic/graphic', compact('Emp', 'Job'));
    }
    public function graphicAddjob(Request $request)
    {
        $data = $request->input();

        try {
            DB::table('graphic_detail')->insert([
                'job_date' => $data['job_date'],
                'pic_name' => $data['job_name'],
                'job_type' => $data['job_type'],
                'pic_detail' => $data['job_detail'],
                'pic_address' => $data['job_link'],
                'UID' => Auth::user()->emp_id,
                'comment' => $data['job_ps']
            ]);

            // line notify
            if ($data['job_type'] == 1) {
                $job_type = 'รูปภาพ';
            } else {
                $job_type = 'วิดีโอ';
            }
            $message = "\n" . 'ส่งงานกราฟฟิค';
            $message .= "\n" . 'ชื่อพนักงาน : ' . Auth::user()->name . "\n" . 'ชื่องาน : ' . $data['job_name'] . "\n" . 'ประเภท : ' . $job_type;
            $message .= "\n" . 'รายละเอียด : ' . $data['job_detail'] . "\n" . 'ที่อยู่ไฟล์ : ' . $data['job_link'];
            $message .= "\n" . 'วันที่ : ' . Carbon::parse($data['job_date'])->format('d-m-Y');
            $message .= "\n" . 'เวลาบันทึก : ' . Carbon::parse(Carbon::now())->format('d-m-Y H:i:s');
            $message .= "\n" . 'หมายเหตุ : ' . $data['job_ps'] . "\n";
            // sendlinemesg(5); //change by notify id
            header('Content-Type: text/html; charset=utf-8');
            notify_message($message, 5);

            alert()->success('บันทึกข้อมูลแล้ว');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }
    // Graphic

    // Social
    public function viewSocial(Request $request)
    {
        $Emp = DB::table('social_detail')->join('employee', 'employee.id', '=', 'social_detail.UID')->groupby('UID')->get();

        $Page = DB::table('social_page')
            ->get();

        if (!empty($_GET['del_id'])) {
            DB::table('social_detail')
                ->where('PID', '=', $_GET['del_id'])
                ->delete();
            alert()->success('ลบเรียบร้อย');
        }

        //search
        if (!empty($_GET['web_id']) && !empty($_GET['emp_id']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $Job = DB::table('social_detail')
                ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                ->where('social_detail.SMID', '=', $_GET['web_id'])
                ->where('social_detail.UID', '=', $_GET['emp_id'])
                ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
        } elseif (!empty($_GET['web_id'])) {
            if (!empty($_GET['emp_id'])) {
                if (!empty($_GET['start_date'])) {
                    $Job = DB::table('social_detail')
                        ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                        ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                        ->where('social_detail.SMID', '=', $_GET['web_id'])
                        ->where('social_detail.UID', '=', $_GET['emp_id'])
                        ->where('job_date', '=', $_GET['start_date'])
                        ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
                } else {
                    $Job = DB::table('social_detail')
                        ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                        ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                        ->where('social_detail.SMID', '=', $_GET['web_id'])
                        ->where('social_detail.UID', '=', $_GET['emp_id'])
                        ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
                }
            } elseif (!empty($_GET['start_date'])) {
                if (!empty($_GET['end_date'])) {
                    $Job = DB::table('social_detail')
                        ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                        ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                        ->where('social_detail.SMID', '=', $_GET['web_id'])
                        ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                        ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
                } else {
                    $Job = DB::table('social_detail')
                        ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                        ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                        ->where('social_detail.SMID', '=', $_GET['web_id'])
                        ->where('job_date', '=', $_GET['start_date'])
                        ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
                }
            } else {
                $Job = DB::table('social_detail')
                    ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                    ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                    ->where('social_detail.SMID', '=', $_GET['web_id'])
                    ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
            }
        } elseif (!empty($_GET['emp_id'])) {
            if (!empty($_GET['start_date'])) {
                if (!empty($_GET['end_date'])) {
                    $Job = DB::table('social_detail')
                        ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                        ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                        ->where('social_detail.UID', '=', $_GET['emp_id'])
                        ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                        ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
                } else {
                    $Job = DB::table('social_detail')                        
                        ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                        ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                        ->where('social_detail.UID', '=', $_GET['emp_id'])
                        ->where('job_date', '=', $_GET['start_date'])
                        ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
                }
            } else {
                $Job = DB::table('social_detail')
                    ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                    ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                    ->where('social_detail.UID', '=', $_GET['emp_id'])
                    ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
            }
        } elseif (!empty($_GET['start_date'])) {
            if (!empty($_GET['end_date'])) {
                $Job = DB::table('social_detail')
                    ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                    ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                    ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                    ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
            } else {
                $Job = DB::table('social_detail')
                    ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                    ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                    ->where('job_date', '=', $_GET['start_date'])
                    ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
            }
        } else {
            $Job = DB::table('social_detail')
                ->leftjoin('employee', 'social_detail.UID', '=', 'employee.id')
                ->leftjoin('social_page', 'social_detail.SMID', '=', 'social_page.SMID')
                ->whereRaw("MONTH(`social_detail`.`created_at`) = MONTH(CURDATE())")
                ->orderByRaw('job_date desc, social_detail.created_at desc')
                ->get();
        }
        //search

        return view('job/social/social', compact('Emp', 'Page', 'Job'));
    }
    public function addSocial(Request $request)
    {
        $Page = DB::table('social_page')
            ->get();

        return view('job/social/social_add', compact('Page'));
    }
    public function socialAddjob(Request $request)
    {
        $data = $request->input();

        try {
            DB::table('social_detail')->insert([
                'SMID' => $data['SMID'],
                'p_name' => $data['job_name'],
                'p_url' => $data['job_link'],
                'UID' => Auth::user()->emp_id,
                'job_date' => $data['job_date'],
                'comment' => $data['job_ps']
            ]);

            // line notify
            $message = "\n" . 'ส่งงานโซเชียล';
            $message .= "\n" . 'ชื่อพนักงาน : ' . Auth::user()->name . "\n" . 'ชื่องาน : ' . $data['job_name'] . "\n" . 'ลิงค์บทความ : ' . $data['job_link'];
            $message .= "\n" . 'วันที่ : ' . Carbon::parse($data['job_date'])->format('d-m-Y');
            $message .= "\n" . 'เวลาบันทึก : ' . Carbon::parse(Carbon::now())->format('d-m-Y H:i:s');
            $message .= "\n" . 'หมายเหตุ : ' . $data['job_ps'] . "\n";
            // sendlinemesg(4); //change by notify id
            header('Content-Type: text/html; charset=utf-8');
            notify_message($message, 4);

            alert()->success('บันทึกข้อมูลแล้ว');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }
    // Social

    // Content
    public function viewContent(Request $request)
    {
        $data = $request->input();

        $Emp = DB::table('content_detail')->join('employee', 'employee.id', '=', 'content_detail.UID')->groupby('UID')->get();

        $Web = DB::table('web')
            ->get();

        if (!empty($_GET['del_id'])) {
            DB::table('content_detail')
                ->where('CID', '=', $_GET['del_id'])
                ->delete();
            alert()->success('ลบเรียบร้อย');
            return redirect()->back();
        }

        if (!empty($_GET['CID'])) {
            $now = date("Y-m-d");
            DB::table('content_detail')
                ->where('CID', $_GET['CID'])
                ->update([
                    'con_st' => 1,
                    'chkDate' => $now,
                    'remark' => ''
                ]);
            alert()->success('ตรวจเรียบร้อย');
            return redirect()->back();
        }

        if (!empty($_GET['RE_CID'])) {
            $now = date("Y-m-d");
            DB::table('content_detail')
                ->where('CID', $_GET['RE_CID'])
                ->update([
                    'con_st' => 2,
                    'remark' => $_GET['remark']
                ]);
            alert()->success('ส่งแก้ไขเรียบร้อย');
            return redirect()->back();
        }

        //search
        if (!empty($_GET['web_id']) && !empty($_GET['emp_id']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $Job = DB::table('content_detail')
                ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                ->where('content_detail.WID', '=', $_GET['web_id'])
                ->where('content_detail.UID', '=', $_GET['emp_id'])
                ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
        } elseif (!empty($_GET['web_id'])) {
            if (!empty($_GET['emp_id'])) {
                if (!empty($_GET['start_date'])) {
                    $Job = DB::table('content_detail')
                        ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                        ->where('content_detail.WID', '=', $_GET['web_id'])
                        ->where('content_detail.UID', '=', $_GET['emp_id'])
                        ->where('job_date', '=', $_GET['start_date'])
                        ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
                } else {
                    $Job = DB::table('content_detail')
                        ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                        ->where('content_detail.WID', '=', $_GET['web_id'])
                        ->where('content_detail.UID', '=', $_GET['emp_id'])
                        ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
                }
            } elseif (!empty($_GET['start_date'])) {
                if (!empty($_GET['end_date'])) {
                    $Job = DB::table('content_detail')
                        ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                        ->where('content_detail.WID', '=', $_GET['web_id'])
                        ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                        ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
                } else {
                    $Job = DB::table('content_detail')
                        ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                        ->where('content_detail.WID', '=', $_GET['web_id'])
                        ->where('job_date', '=', $_GET['start_date'])
                        ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
                }
            } else {
                $Job = DB::table('content_detail')
                    ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                    ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                    ->where('content_detail.WID', '=', $_GET['web_id'])
                    ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
            }
        } elseif (!empty($_GET['emp_id'])) {
            if (!empty($_GET['start_date'])) {
                if (!empty($_GET['end_date'])) {
                    $Job = DB::table('content_detail')
                        ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                        ->where('content_detail.UID', '=', $_GET['emp_id'])
                        ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                        ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
                } else {
                    $Job = DB::table('content_detail')
                        ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                        ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                        ->where('content_detail.UID', '=', $_GET['emp_id'])
                        ->where('job_date', '=', $_GET['start_date'])
                        ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
                }
            } else {
                $Job = DB::table('content_detail')
                    ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                    ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                    ->where('content_detail.UID', '=', $_GET['emp_id'])
                    ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
            }
        } elseif (!empty($_GET['start_date'])) {
            if (!empty($_GET['end_date'])) {
                $Job = DB::table('content_detail')
                    ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                    ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                    ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                    ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
            } else {
                $Job = DB::table('content_detail')
                    ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                    ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                    ->where('job_date', '=', $_GET['start_date'])
                    ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
            }
        } else {
            $Job = DB::table('content_detail')
                ->leftjoin('employee', 'content_detail.UID', '=', 'employee.id')
                ->leftjoin('web', 'content_detail.WID', '=', 'web.BLID')
                ->whereRaw("MONTH(`content_detail`.`created_at`) = MONTH(CURDATE())")
                ->orderByRaw('job_date desc, content_detail.created_at desc')
                ->get();
        }
        //search
        return view('job/content/content', compact('Emp', 'Web', 'Job'));
    }
    public function addContent(Request $request)
    {
        $Web = DB::table('web')
            ->get();

        return view('job/content/content_add', compact('Web'));
    }
    public function ContentAddjob(Request $request)
    {
        $data = $request->input();

        try {
            DB::table('content_detail')->insert([
                'WID' => $data['web_id'],
                'c_name' => $data['job_name'],
                'c_url' => $data['job_link'],
                'job_date' => $data['job_date'],
                'UID' => Auth::user()->emp_id,
                'comment' => $data['job_ps']
            ]);

            // line notify
            $message = "\n" . 'ส่งงานคอนเทนต์';
            $message .= "\n" . 'ชื่อพนักงาน : ' . Auth::user()->name . "\n" . 'ชื่องาน : ' . $data['job_name'] . "\n" . 'ลิงค์บทความ : ' . $data['job_link'];
            $message .= "\n" . 'วันที่ : ' . Carbon::parse($data['job_date'])->format('d-m-Y');
            $message .= "\n" . 'เวลาบันทึก : ' . Carbon::parse(Carbon::now())->format('d-m-Y H:i:s');
            $message .= "\n" . 'หมายเหตุ : ' . $data['job_ps'] . "\n";
            // sendlinemesg(3); //change by notify id
            header('Content-Type: text/html; charset=utf-8');
            notify_message($message, 3);

            alert()->success('บันทึกข้อมูลแล้ว');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }
    // content

    // programmer
    public function viewProgrammer(Request $request)
    {
        $Emp = DB::table('programmer_detail')->join('employee', 'employee.id', '=', 'programmer_detail.UID')->groupby('UID')->get();

        if (!empty($_GET['del_id'])) {
            DB::table('programmer_detail')
                ->where('pm_id', '=', $_GET['del_id'])
                ->delete();
            alert()->success('ลบเรียบร้อย');
        }

        //search
        if (!empty($_GET['emp_id']) && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $Job = DB::table('programmer_detail')
                ->leftjoin('employee', 'programmer_detail.UID', '=', 'employee.id')
                ->where('programmer_detail.UID', '=', $_GET['emp_id'])
                ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                ->orderByRaw('job_date desc, programmer_detail.created_at desc')
                ->get();
        } elseif (!empty($_GET['emp_id'])) {
            if (!empty($_GET['start_date'])) {
                $Job = DB::table('programmer_detail')
                    ->leftjoin('employee', 'programmer_detail.UID', '=', 'employee.id')
                    ->where('programmer_detail.UID', '=', $_GET['emp_id'])
                    ->where('job_date', '=', $_GET['start_date'])
                    ->orderByRaw('job_date desc, programmer_detail.created_at desc')
                ->get();
            } else {
                $Job = DB::table('programmer_detail')
                    ->leftjoin('employee', 'programmer_detail.UID', '=', 'employee.id')
                    ->where('programmer_detail.UID', '=', $_GET['emp_id'])
                    ->orderByRaw('job_date desc, programmer_detail.created_at desc')
                ->get();
            }
        } elseif (!empty($_GET['start_date'])) {
            if (!empty($_GET['end_date'])) {
                $Job = DB::table('programmer_detail')
                    ->leftjoin('employee', 'programmer_detail.UID', '=', 'employee.id')
                    ->whereRaw("(`job_date` between '" . $_GET['start_date'] . "' and '" . $_GET['end_date'] . "')")
                    ->orderByRaw('job_date desc, programmer_detail.created_at desc')
                ->get();
            } else {
                $Job = DB::table('programmer_detail')
                    ->leftjoin('employee', 'programmer_detail.UID', '=', 'employee.id')
                    ->where('job_date', '=', $_GET['start_date'])
                    ->orderByRaw('job_date desc, programmer_detail.created_at desc')
                ->get();
            }
        } else {
            $Job = DB::table('programmer_detail')
                ->leftjoin('employee', 'programmer_detail.UID', '=', 'employee.id')
                ->whereRaw("MONTH(`programmer_detail`.`created_at`) = MONTH(CURDATE())")
                ->orderByRaw('job_date desc, programmer_detail.created_at desc')
                ->get();
        }
        //search

        return view('job/programmer/programmer', compact('Emp', 'Job'));
    }
    public function programmerAddjob(Request $request)
    {
        $data = $request->input();

        try {
            DB::table('programmer_detail')->insert([
                'job_date' => $data['job_date'],
                'pm_name' => $data['job_name'],
                'pm_detail' => $data['job_detail'],
                'comment' => $data['job_ps'],
                'UID' => Auth::user()->emp_id
            ]);

            // line notify
            $message = "\n" . 'ส่งงานโปรแกรมเมอร์';
            $message .= "\n" . 'ชื่อพนักงาน : ' . Auth::user()->name . "\n" . 'ชื่องาน : ' . $data['job_name'] . "\n" . 'รายละเอียดงาน : ' . $data['job_detail'];
            $message .= "\n" . 'วันที่ : ' . Carbon::parse($data['job_date'])->format('d-m-Y');
            $message .= "\n" . 'เวลาบันทึก : ' . Carbon::parse(Carbon::now())->format('d-m-Y H:i:s');
            $message .= "\n" . 'หมายเหตุ : ' . $data['job_ps'] . "\n";
            // sendlinemesg(2); //change by notify id
            header('Content-Type: text/html; charset=utf-8');
            notify_message($message, 2);

            alert()->success('บันทึกข้อมูลแล้ว');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }
    // programmer

    // promote
    public function showPromote(Request $request)
    {
        $Emp = DB::table('page_cost')->select('page_cost.*', 'employee.id', 'employee.nickname')->join('employee', 'employee.id', '=', 'page_cost.emp_id')->groupby('emp_id')->get();
        
        if (!empty($_GET['del_id'])) {
            DB::table('page_cost')
                ->where('pcid', '=', $_GET['del_id'])
                ->delete();
            alert()->success('ลบเรียบร้อย');
        }

        //search
        if (!empty($_GET['emp_id']) && !empty($_GET['start_boost']) && !empty($_GET['end_boost'])) {
            $Job = DB::table('page_cost')
                ->leftjoin('employee', 'page_cost.emp_id', '=', 'employee.id')
                ->where('page_cost.emp_id', '=', $_GET['emp_id'])
                ->whereRaw("(`start_boost` between '" . $_GET['start_boost'] . "' and '" . $_GET['end_boost'] . "') or (`end_boost` between '" . $_GET['start_boost'] . "' and '" . $_GET['end_boost'] . "')")
                ->orderBy('page_cost.created_at', 'desc')
                ->get();
        } elseif (!empty($_GET['emp_id'])) {
            if (!empty($_GET['start_boost'])) {
                $Job = DB::table('page_cost')
                    ->leftjoin('employee', 'page_cost.emp_id', '=', 'employee.id')
                    ->where('page_cost.emp_id', '=', $_GET['emp_id'])
                    ->where('start_boost', '=', $_GET['start_boost'])
                    ->orderBy('page_cost.created_at', 'desc')
                    ->get();
            } else {
                $Job = DB::table('page_cost')
                    ->leftjoin('employee', 'page_cost.emp_id', '=', 'employee.id')
                    ->where('page_cost.emp_id', '=', $_GET['emp_id'])
                    ->orderBy('page_cost.created_at', 'desc')
                    ->get();
            }
        } elseif (!empty($_GET['start_boost'])) {
            if (!empty($_GET['end_boost'])) {
                $Job = DB::table('page_cost')
                    ->leftjoin('employee', 'page_cost.emp_id', '=', 'employee.id')
                    ->whereRaw("(`start_boost` between '" . $_GET['start_boost'] . "' and '" . $_GET['end_boost'] . "') or (`end_boost` between '" . $_GET['start_boost'] . "' and '" . $_GET['end_boost'] . "')")
                    ->orderBy('page_cost.created_at', 'desc')
                    ->get();
            } else {
                $Job = DB::table('page_cost')
                    ->leftjoin('employee', 'page_cost.emp_id', '=', 'employee.id')
                    ->where('start_boost', '=', $_GET['start_boost'])
                    ->orderBy('page_cost.created_at', 'desc')
                    ->get();
            }
        } else {
            $Job = DB::table('page_cost')->select('page_cost.*', 'employee.nickname')
                ->leftjoin('employee', 'employee.id', '=', 'page_cost.emp_id')
                ->whereRaw("MONTH(`start_boost`) = MONTH(CURDATE())")
                ->orderBy('page_cost.created_at', 'desc')
                ->get();
        }
        //search
        
        return view('job/pagecost/show', compact('Job', 'Emp'));
    }
    function addCost(Request $request)
    {
        $data = $request->input();
        $now = date("Y-m-d");

        try {
            DB::table('page_cost')->insert([
                'page_name' => $data['page_name'],
                'pc_link' => $data['pc_link'],
                'pc_content' => $data['pc_content'],
                'boost_cost' => $data['boost_cost'],
                'start_boost' => $data['start_boost'],
                'end_boost' => $data['end_boost'],
                'user_id' => Auth::user()->id,
                'emp_id' => Auth::user()->emp_id
            ]);

            // line notify
            $message = "\n" . 'ส่งงานโปรโมท';
            $message .= "\n" . 'ชื่อพนักงาน : ' . Auth::user()->name . "\n" . 'ชื่อเพจ : ' . $data['page_name'] . "\n" . 'ราคา : ' . $data['boost_cost'];
            $message .= "\n" . 'ลิงค์ : ' . $data['pc_link'] . "\n" . 'ชื่อคอนเทนต์ : ' . $data['pc_content'];
            $message .= "\n" . 'วันที่เริ่ม : ' . Carbon::parse($data['start_boost'])->format('d-m-Y') . "\n" . 'วันที่สิ้นสุด : ' . Carbon::parse($data['end_boost'])->format('d-m-Y');
            $message .= "\n" . 'เวลาบันทึก : ' . Carbon::parse(Carbon::now())->format('d-m-Y H:i:s');
            // sendlinemesg(7); //change by notify id
            header('Content-Type: text/html; charset=utf-8');
            notify_message($message, 7);

            alert()->success('บันทึกข้อมูลแล้ว');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }
    // promote

    public function newLine()
    {
        return view('job/line/new');
    }

    public function postLine(Request $request)
    {
        try {
            DB::table('tb_lineat')->insert(
                ['name' => $request->get('line')]
            );
            alert()->success('บันทึกข้อมูลสำเร็จ');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    // program
    public function viewProgram(){
        $program = DB::table('program_detail')->join('employee', 'program_detail.pd_uid', '=', 'employee.id')->get();
        foreach ($program as $key => $value) {
            $events[] = Calendar::event(
                $value->nickname . ' || '.$value->pd_name . ' || '. Carbon::parse($value->pd_start_time)->format('h:i')  . ' - ' . Carbon::parse($value->pd_end_time)->format('h:i'),
                true,
                new \DateTime($value->pd_date),
                new \DateTime($value->pd_date . ' +1 day'),
                null,
                // Add color and link on event
                [
                    'color' => '#000',
                ]
            );
        }
        $calendar = Calendar::addEvents($events)->setOptions([ //set fullcalendar options
            'locale' => 'th',
            'editable' => false,
            'navLinks' => true,
            'selectable'  => true,
            'defaultView' => 'month',
        ]);
        return view('job/program/program', compact('calendar'));
    }
    public function addProgram(Request $request){
        try {
            DB::table('program_detail')->insert([
                'pd_uid' => Auth::user()->emp_id,
                'pd_name' => $request->get('pd_name'),
                'pd_date' => $request->get('pd_date'),
                'pd_start_time' => $request->get('pd_start_time'),
                'pd_end_time' => $request->get('pd_end_time'),
            ]);

            // line notify
            $message = "\n" . 'ส่งงานรายการ';
            $message .= "\n" . 'ชื่อพนักงาน : ' . Auth::user()->name . "\n" . 'ชื่อรายการ : ' .$request->get('pd_name');
            $message .= "\n" . 'วันที่ : ' . $request->get('pd_date');
            $message .= "\n" . 'เวลาเริ่ม : ' . Carbon::parse($request->get('pd_start_time'))->format('h:i') . "\n" . 'เวลาสิ้นสุด : ' . Carbon::parse($request->get('pd_end_time'))->format('h:i');
            $message .= "\n" . 'เวลาบันทึก : ' . Carbon::parse(Carbon::now())->format('d-m-Y H:i:s');
            // sendlinemesg(7); //change by notify id
            header('Content-Type: text/html; charset=utf-8');
            notify_message($message, 9);

            alert()->success('บันทึกข้อมูลแล้ว');
            return redirect()->back();
        } catch (QueryException $e) {
            alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
            return redirect()->back();
        }
    }

    public function viewLine()
    {
        return view('job/line/line');
    }

    public function jobLine(Request $request)
    {
        $date = Carbon::now()->format('Y-n-d');
        global $thai_day_arr, $thai_month_arr;
        $thai_month_arr = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        $thai_date_return = '';
        $thai_date_return .= " " . date("j", strtotime($date));
        $thai_date_return .= " " . $thai_month_arr[date("n", strtotime($date))];
        $thai_date_return .= " " . (date("Y", strtotime($date)) + 543);

        $get_line = [];
        $line_id = [];

        $line_at = DB::table('tb_lineat')->where('status', 1)->get();

        if (Carbon::now()->format('H:i') < '12:00') {
            $starttime = Carbon::now()->format('Y-m-d 00:00');
            $endtime = Carbon::now()->format('Y-m-d 11:59');
        } else {
            $starttime = Carbon::now()->format('Y-m-d 12:00');
            $endtime = Carbon::now()->format('Y-m-d 23:59');
        }
        foreach ($line_at as $line) {
            $linecheck = DB::table('tb_lineat')
                ->select('tb_lineat.name', 'cked_line.status as chk_status', 'cked_line.line_id as line_id')
                ->leftjoin('cked_line', 'cked_line.line_id', 'tb_lineat.id')
                ->whereBetween('date_checked', [$starttime, $endtime])
                ->where('line_id', $line->id)->where('uid', $request->user()->id)
                ->get();
            foreach ($linecheck as $line_get) {
                # code...
                $get_line[] = [
                    'name' => $line_get->name,
                    'line_id' => $line->id
                ];
            }
        }
        foreach ($get_line as $getline) {
            $line_id[] = $getline['line_id'];
        }
        if (count($line_id)) {
            $lineat = Line::where('status', '<>', 2)->whereRaw('`id` NOT IN (' . implode(',', $line_id) . ')')->get();
        } else {
            $lineat = Line::where('status', '<>', 2)->get();
        }
        // $lineat = Line::where('status', '<>' , 2)->whereNotIn('tb_lineat.id', $line_id)->get();
        return view('job/line/check', compact('lineat', 'thai_date_return', 'line_id'));
        // $count_rows = count($get_line);
        // if ($count_rows != 0) {
        // } else {
        //     $get_line[] = [
        //         'name' => '',
        //         'line_id' => ''
        //     ];

        //     return view('job/line/check', compact('get_line', 'lineat'));
        // }


    }

    public function checkLine(Request $request)
    {
        if (!empty($_GET['line'])) {
            if ($_GET['status'] == 1) {
                try {
                    DB::table('cked_line')->insert(
                        [
                            'line_id' => $_GET['line'],
                            'uid' => $request->user()->id
                        ]
                    );
                    alert()->success('ตรวจสอบไลน์@ แล้ว');
                    return redirect()->back();
                } catch (QueryException $e) {
                    alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
                    return redirect()->back();
                }
            } else {
                try {
                    DB::table('cked_line')->insert(
                        [
                            'line_id' => $_GET['line'],
                            'status' => 2,
                            'uid' => $request->user()->id
                        ]
                    );
                    DB::table('tb_lineat')->where('id', $_GET['line'])
                        ->update(['status' => 2]);

                    alert()->success('บันทึกข้อมูลไลน์@ ปลิวแล้ว');
                    return redirect()->back();
                } catch (QueryException $e) {
                    alert()->error('บันทึกข้อมูลไม่สำเร็จ กรุณาตรวจสอบข้อมูลอีกครั้ง')->autoclose(5000);
                    return redirect()->back();
                }
            }
        }
    }

    public function reportLine()
    {
        $data = DB::table('cked_line')
            ->select('tb_lineat.name', 'cked_line.date_checked', 'cked_line.status as chk_status', 'cked_line.line_id as line_id', 'users.name as uname')
            ->leftjoin('tb_lineat', 'tb_lineat.id', 'cked_line.line_id')
            ->join('users', 'users.id', 'cked_line.uid')
            ->get();
        if ($data->count()) {
            foreach ($data as $value) {
                if ($value->chk_status == 1) {
                    $color = 'green';
                } else {
                    $color = 'red';
                }
                $events[] = Calendar::event(
                    ($value->chk_status == 1) ?  $value->uname . ' || ' . $value->name . ' || ปกติ' : $value->uname . ' || ' . $value->name . ' || ปลิว',
                    true,
                    new \DateTime($value->date_checked),
                    new \DateTime($value->date_checked . ' +1 hour'),
                    null,
                    // Add color and link on event
                    [
                        'color' => $color,
                        'url' => 'pass here url and any route',
                        // 'rendering' => 'background'
                    ]
                );
            }
        }
        $calendar = Calendar::addEvents($events)->setOptions([ //set fullcalendar options
            'locale' => 'th',
            'editable' => true,
            'navLinks' => true,
            'selectable'  => true,
            'defaultView' => 'month',
        ]);
        return view('job/line/report', compact('calendar'));
    }
}

function sendlinemesg($t_id)
{
    $leave = DB::table('token_line')->where('t_deptid', '=', $t_id)->get();
    $leave_token = $leave[0]->t_token;
    define('LINE_API', "https://notify-api.line.me/api/notify");
    define('LINE_TOKEN', $leave_token);
}

function notify_message($message, $t_id)
{
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
    // return $res;
    $curl = curl_init();
    $leave = DB::table('token_line')->where('t_deptid', '=', $t_id)->get();
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
