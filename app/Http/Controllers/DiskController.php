<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiskController extends Controller
{
    //
    public function index(Request $request)
    {
        if (empty(session('userSession'))) {
            return redirect('/login');
        } else {

            $data = [
                'id' => session('userSession')->id,
                'username' => session('userSession')->username,
            ];
            session()->put('is_share', 0);//私有
            session()->put('folder_id', 0);//
            return view('disk', compact('data'));
        }

    }

    public function share(Request $request)
    {
        if (empty(session('userSession'))) {
            return redirect('/login');
        } else {

            $data = [
                'id' => session('userSession')->id,
                'username' => session('userSession')->username,
            ];
            session()->put('is_share', 1);//共用
            return view('share', compact('data'));
        }

    }

    public function shareDisk(Request $request)
    {
        $limit = $request->get('limit');
        $page = $request->get('page');
        $begin = ($page - 1) * $limit;
        $is_share = session('is_share');
        $folder_id = $request->get('folder_id');
        $folder_id = $folder_id ? $folder_id : session('folder_id');
        session()->put('folder_id', $folder_id);
        $file_list = DB::select('select id,file_name,file_size,file_type,real_path,folder_id,update_time from netdisk_files WHERE folder_id=? and is_delete=0 and is_share=? order by update_time desc', [$folder_id, $is_share]);
        $folder_list = DB::select('select id,folder_name as file_name,null as file_size,"pack" as file_type,null as real_path,null as folder_id,update_time from netdisk_folders WHERE father_id=? and is_delete=0 and is_share=? order by update_time desc', [$folder_id, $is_share]);
        $list = array_merge($folder_list, $file_list);
        $count = count($list);


        $rs['data'] = $list;
        $rs['code'] = 0;
        $rs['count'] = $count;
        return $rs;
    }

    public function myDisk(Request $request)
    {
        $id = session('userSession')->id;
        $limit = $request->get('limit');
        $page = $request->get('page');
        $begin = ($page - 1) * $limit;
        $folder_id = $request->get('folder_id');
        $folder_id = $folder_id ? $folder_id : session('folder_id');
        session()->put('folder_id', $folder_id);
        $is_share = session('is_share');
        $file_list = DB::select('select id,file_name,file_size,file_type,real_path,folder_id,update_time from netdisk_files WHERE folder_id=? and is_delete=0 and is_share=? and uid=? order by update_time desc', [$folder_id,$is_share,$id]);
        $folder_list = DB::select('select id,folder_name as file_name,null as file_size,"pack" as file_type,null as real_path,null as folder_id,update_time from netdisk_folders WHERE father_id=? and is_delete=0 and is_share=? and uid=?  order by update_time desc', [$folder_id,$is_share, $id]);
        $list = array_merge($folder_list, $file_list);
        $count = count($list);


        $rs['data'] = $list;
        $rs['code'] = 0;
        $rs['count'] = $count;
        return $rs;
    }

    public function goHome()
    {
        session()->put('folder_id', 0);
        return redirect('/');
    }

    public function goShare()
    {
        session()->put('folder_id', 0);
        return redirect('/share');
    }

    public function deleteFile(Request $request)
    {
        $id = $request->get('id');
        $file_type = $request->get('file_type');
        if ($file_type != 'pack') {
            DB::table('netdisk_files')->where('id', $id)->update(['is_delete' => 1, 'delete_time' => date('Y-m-d H:i:s')]);
        } else {
            DB::table('netdisk_folders')->where('id', $id)->update(['is_delete' => 1, 'delete_time' => date('Y-m-d H:i:s')]);
            DB::table('netdisk_files')->where('folder_id', $id)->update(['is_delete' => 1, 'delete_time' => date('Y-m-d H:i:s')]);
        }
        //

    }

    public function deleteAllFile(Request $request)
    {
        $data = $request->get('newData');
        foreach ($data as $v) {
            if ($v['file_type'] != 'pack') {
                DB::table('netdisk_files')->where('id', $v['id'])->update(['is_delete' => 1, 'delete_time' => date('Y-m-d H:i:s')]);
            } else {
                DB::table('netdisk_folders')->where('id', $v['id'])->update(['is_delete' => 1, 'delete_time' => date('Y-m-d H:i:s')]);
                DB::table('netdisk_files')->where('folder_id', $v['id'])->update(['is_delete' => 1, 'delete_time' => date('Y-m-d H:i:s')]);
            }
        }

        //

    }

    public function addFolder(Request $request)
    {
        $data['folder_name'] = $request->get('folder_name');
        $data['father_id'] = session('folder_id');
        $data['uid'] = session('userSession')->id;
        $data['is_share'] = session('is_share');
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['update_time'] = date('Y-m-d H:i:s');

        DB::table('netdisk_folders')->insert($data);

    }

    public function editFolder(Request $request)
    {
        $id = $request->get('id');
        $file_name = $request->get('file_name');
        $file_type = $request->get('file_type');
        $update_time = date('Y-m-d H:i:s');
        if ($file_type != 'pack') {
            DB::table('netdisk_files')->where(['id' => $id])->update(['file_name' => $file_name, 'update_time' => $update_time]);
        } else {
            DB::table('netdisk_folders')->where(['id' => $id])->update(['folder_name' => $file_name, 'update_time' => $update_time]);
        }


    }
}
