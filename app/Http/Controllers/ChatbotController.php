<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Event;

define('LINE_TOKEN_URI', 'https://api.line.me/oauth2/v2.1/token');
define('LINE_DETAIL_URI', 'https://api.line.me/v2/profile');

class ChatbotController extends Controller{

    public function thai_date($time){
        $thai_month_arr=array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม");
        $thai_date_return = date("j",strtotime($time));
        $thai_date_return .=" ".$thai_month_arr[date("n",strtotime($time))];
        $thai_date_return .= " ".substr((date("Y",strtotime($time))+543) , 2);
        return $thai_date_return;
    }

    public function callbackLine(Request $request)
    {
        $dataline = DB::table('linebot')->first();
        $dataReturn = (object)[];
        $dataGet = null;
        $ch = curl_init();
        $queries = $request->query();
        $fields = 'grant_type=authorization_code&code='. $queries['code'].'&redirect_uri='.$dataline->LO_CALLBACK_URI.'chatbot/callbackdata&client_id='.$dataline->LO_CLIENT_ID.'&client_secret='.$dataline->LO_CLIENT_SECRET;
        curl_setopt($ch, CURLOPT_URL, LINE_TOKEN_URI);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
    
        $res = curl_exec($ch);
        curl_close($ch);
    
        if ($res == false)
            throw new Exception(curl_error($ch), curl_errno($ch));
    
        $json = json_decode($res);
        if(empty($json->error)){
            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL, LINE_DETAIL_URI);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: Bearer ".$json->access_token]);
            
                $resdateil = curl_exec($curl);
                curl_close($curl);
            
                if ($res == false)
                    throw new Exception(curl_error($curl), curl_errno($curl));
            
                $dataUser = json_decode($resdateil);
                $dataReturn->status = "Success";

                // save to database
                // $dataSet = new Dgmdata();
                // $dataSet->iduser = $queries['state'];
                // $dataSet->usetID = $dataUser->userId;
                // $dataSet->name = $dataUser->displayName;
                // $dataSet->save();
                $setType = intval($queries['state']);
                if($setType !== 0){
                    DB::table('employee')->where('id', '=', $queries['state'])->update([
                        'lineUUID' => $dataUser->userId
                    ]);
                    alert()->success('เชื่อมต่อเรียบร้อย');
                    return redirect("/leave/profile");
                }
                else{
                    $dataAdmin = DB::table('users')->where('name', '=', $queries['state'])->first();
                    if(!is_null($dataUser)){
                        $datalinelink = DB::table('user_linebot')->where('lineuuid', '=', $dataUser->userId)->first();
                        if(empty($datalinelink)){
                            DB::table('user_linebot')->insert([
                                'uuid' => $dataAdmin->id,
                                'lineuuid' => $dataUser->userId,
                                'type_noti' => "HR",
                                'line_name' => $dataUser->displayName,
                            ]);
                            alert()->success('เชื่อมต่อเรียบร้อย');
                            return redirect("/admin/linebotsetting");
                        }
                    }
                    else{
                        alert()->error('คุณไม่มีสิทธิเข้าหน้านี้');
                        return redirect()->back();
                    }
                }
        }
        else{
            $dataReturn = $json;
        }
        return $this->responseSuccess($dataReturn);
    }

    public function disconnecttline(Request $request)
    {
        if (!empty($_GET['emp_id'])) {
            $setType = intval($_GET['emp_id']);
            if($setType !== 0){
                DB::table('user_linebot')->where('id', '=', $_GET['emp_id'])->update([
                    'lineUUID' => null
                ]);
            }
            else{
                DB::table('employee')->where('uuid', '=', $_GET['emp_id'])->update([
                    'lineuuid' => null
                ]);
            }
            alert()->success('ยกเลิกการเชื่อมต่อเรียบร้อย');
            return redirect()->back();
        }
    }

    protected function sendtoLine($encodeJson, $url){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $encodeJson,
            CURLOPT_HTTPHEADER => array(
            "authorization: Bearer ".$url['token'],
            "cache-control: no-cache",
            "content-type: application/json; charset=UTF-8",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $datasReturn['result'] = 'E';
            $datasReturn['message'] = $err;
            return $this->responseError($datasReturn);            
        } else {
            if($response == "{}"){
                $datasReturn['result'] = 'S';
                $datasReturn['message'] = 'Success';
                return $datasReturn;
            }else{
                $datasReturn['result'] = 'E';
                $datasReturn['message'] = $response;
                $datasReturn['data'] = $encodeJson;
                $dataSave['result'] = 'E';
                $dataSave['message'] = json_decode($response);
                $dataSave['data'] = json_decode($encodeJson);
                return $datasReturn;                
            }
        }
    }
    
    protected function responseSuccess($res){
        return response()->json(["ststus" => "success","data" => $res],200)
            ->header("Access-Control-Allow-Origin", "*")
            ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");
    }

    protected function responseError($res){
        return response()->json(["ststus" => "error","data" => $res],204)
            ->header("Access-Control-Allow-Origin", "*")
            ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");
    }

    protected function responseUnauthorized(){
        return response()->json(["ststus" => "error", "data" => "Unauthorized"], 401)
            ->header("Access-Control-Allow-Origin", "*")
            ->header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");
    }
}

function thai_date($time){
    $thai_month_arr = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม");
    $thai_date_return = date("j",strtotime($time));
    $thai_date_return .=" ".$thai_month_arr[date("n",strtotime($time))];
    $thai_date_return .= " ".substr((date("Y",strtotime($time))+543) , 2);
    return $thai_date_return;
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
            "authorization: Bearer KfSH9SHb5o8M/nhEqZUBNuiRyg59OpR0u/PY/Np1kVXYxdU06Td3fACSvzfKGcreB7ne6kc6JqpOt6fry7IBSRpgo4QShu0hhIAJ8DFE5GGfKR6PRTlmUcLDQSOLIU3Zrj5gNNrIOGnhXRG7iUXCF1GUYhWQfeY8sLGRXgo3xvw=",
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