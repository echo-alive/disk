<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UploadController extends Controller
{
    //
    /**
     * 文件上传
     */

    public function uploadFiles(Request $request)
    {
        if ($_POST) {
            $id = session('userSession')->id;
            //上传图片具体操作
            $file_name = $_FILES['file']['name'];
            //$file_type = $_FILES["file"]["type"];
            $file_tmp = $_FILES["file"]["tmp_name"];
            $file_error = $_FILES["file"]["error"];
            $file_size = $_FILES["file"]["size"];
            if ($file_error > 0) { // 出错
                $message = $file_error;
            } else {
                $date = date('Ymd');
                $file_name_arr = explode('.', $file_name);
                $new_file_name = date('YmdHis') . rand(1111, 9999) . '.' . $file_name_arr[count($file_name_arr)-1];
                $path = "upload/" . $date . "/";
                $file_path = $path . $new_file_name;
                if (file_exists($file_path)) {
                    $message = "此文件已经存在啦";
                } else {
                    //TODO 判断当前的目录是否存在，若不存在就新建一个!
                    if (!is_dir($path)) {
                        mkdir($path, 0777);
                    }
                    $upload_result = move_uploaded_file($file_tmp, $file_path);
                    //此函数只支持 HTTP POST 上传的文件
                    if ($upload_result) {
                        $status = 1;
                        $message = $file_path;
                    } else {
                        $message = "文件上传失败，请稍后再尝试";
                    }
                }
                $data['file_size']=$file_size;
                $data['file_name']= $file_name;
                $data['file_type'] = $file_name_arr[count($file_name_arr)-1];
                $data['folder_id']= session('folder_id');
                $data['uid']= session('userSession')->id;
                $data['is_share']= session('is_share');
                $data['real_path'] = $file_path;
                $data['create_time'] = date('Y-m-d H:i:s');
                $data['update_time'] = date('Y-m-d H:i:s');
                DB::table('netdisk_files')->insert($data);
            }
        } else {
            $message = "参数错误";
        }
        return $this->showMsg($status, $message);
    }

    function showMsg($status, $message = '', $data = array())
    {
        $result = array(
            'status' => $status,
            'message' => $message,
            'data' => $data
        );
        exit(json_encode($result));
    }
}
