<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function changePassword(Request $request)
    {
        if (!empty($_POST['id'])) {
            $user = User::find($_POST['id']);
        }
        else {
            $user = User::find($request->user()->id);
        }
        $newPass = trim($request->get('password'));
        $user->password = bcrypt($newPass);
        $user->save();
        alert()->success('บันทึกข้อมูลสำเร็จ');
        return redirect('/home');
    }

    public function password()
    {
        return view('admin/password-change');
    }

    public function adminmanage()
    {
        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {
            $dataget  = User::select('users.id', 'users.name', 'users.department','users.email', 'user_linebot.line_name')
                ->whereRaw('users.department = 1 or users.department = 2')
                ->leftJoin('user_linebot', 'users.id', 'user_linebot.uuid')->get();
            return view('admin/adminmanage', compact('dataget'));
        }
        else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }

    public function addadmin(Request $request)
    {
        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {
            if($_POST['password'] === $_POST['confirmpassword']){
                $dataget = User::where('email' , trim($_POST['email']))->first();
                if(empty($dataget)){
                    $newPass = trim($request->get('password'));
                    $dataUser = new User();
                    $dataUser->name = $_POST['name'];
                    $dataUser->email = $_POST['email'];
                    $dataUser->password = bcrypt($newPass);
                    $dataUser->permission = "boss";
                    $dataUser->department = $_POST['rank'];
                    $dataUser->piority = 1;
                    $dataUser->status = 1;
                    $dataUser->created_at = date('Y-m-d h:i:s');
                    $dataUser->updated_at = date('Y-m-d h:i:s');
                    try {
                        $dataUser->save();
                        alert()->success('บันทึกข้อมูลสำเร็จ');
                        return redirect()->back();
                    } catch (Throwable $th) {
                        alert()->error('บันทึกข้อมูลไม่สำเร็จ');
                        return redirect()->back();
                    }
                }
                else{
                    alert()->error('มีผู้ใช้นี้แล้ว');
                    return redirect()->back();
                }
            }
            else{
                alert()->warning('รหัสผ่านไม่ตรงกัน');
                return redirect()->back();
            }
        }
        else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }

    public function editadmin(Request $request)
    {
        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {
            $dataget = User::where('id' , trim($_POST['id']))->first();
            if(!empty($dataget)){
                $dataget->name = $_POST['name'];
                $dataget->email = $_POST['email'];
                $dataget->department = $_POST['rank'];
                try {
                    $dataget->save();
                    alert()->success('บันทึกข้อมูลสำเร็จ');
                    return redirect()->back();
                } catch (Throwable $th) {
                    alert()->error('บันทึกข้อมูลไม่สำเร็จ');
                    return redirect()->back();
                }
            }
            else{
                alert()->warning('ไม่พบข้อมูล');
                return redirect()->back();
            }
        }
        else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }

    public function editadminpassword(Request $request)
    {
        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {
            $dataget = User::where('id' , trim($_POST['id']))->first();
            if(!empty($dataget)){
                if($_POST['newpass'] === $_POST['comfirmnewpass']){
                    $newPass = trim($request->get('newpass'));
                    $dataget->password = bcrypt($newPass);
                    try {
                        $dataget->save();
                        alert()->success('บันทึกข้อมูลสำเร็จ');
                        return redirect()->back();
                    } catch (Throwable $th) {
                        alert()->error('บันทึกข้อมูลไม่สำเร็จ');
                        return redirect()->back();
                    }
                }
                else{
                    alert()->warning('รหัสผ่านไม่ตรงกัน');
                    return redirect()->back();
                }
            }
            else{
                alert()->warning('ไม่พบข้อมูล');
                return redirect()->back();
            }
        }
        else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }

    public function deleteadmin(Request $request)
    {
        if (Auth::user()->permission == 'programer' || Auth::user()->permission == 'boss' || Auth::user()->permission == 'hr') {
            $dataget = User::where('id' , $_POST['id'])->first();
            if(!empty($dataget)){
                try {
                    $datagetLine = DB::table('user_linebot')->where('uuid' , $_POST['id'])->first();
                    if(!empty($datagetLine)){
                        $datagetLine->delete();
                    }
                    $dataget->delete();
                    alert()->success('ลบข้อมูลสำเร็จ');
                    return redirect()->back();
                } catch (Throwable $th) {
                    alert()->error('ลบข้อมูลไม่สำเร็จ');
                    return redirect()->back();
                }
            }
            else{
                alert()->warning('ไม่พบข้อมูล');
                return redirect()->back();
            }
        }
        else {
            alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
            return redirect()->back();
        }
    }
}
