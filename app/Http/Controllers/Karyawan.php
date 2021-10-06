<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\tbl_karyawan;

class Karyawan extends Controller
{
    // simpan
    public function create(Request $request)
    {
        $this->validate($request, [
            'foto' => 'required|max:2048'
        ]);

        $file = $request->file('foto');
        $nama_file = time() . '.jpg';
        $tujuan = 'data_file';
        // dd($nama_file, $tujuan, $file);

        if ($file->move($tujuan, $nama_file)) {
            $data = tbl_karyawan::create([
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'umur' => $request->umur,
                'alamat' => $request->alamat,
                'foto' => $nama_file
            ]);

            $res['message'] = 'Success';
            $res['value'] = $data;

            return response($res);
        }
    }

    // baca
    public function read()
    {
        $data = DB::table('tbl_karyawan')->get();
        if (count($data) > 0) {
            $res['message'] = 'Success!';
            $res['value'] = $data;
            return response($res);
            // return response('tes123');
        } else {
            $res['message'] = 'Data not found!';
            return response($res);
        }
    }

    // ubah
    public function update(Request $request)
    {
        if (!empty($request->file)) {
            $this->validate($request, [
                'foto' => 'required|max:2048'
            ]);

            $file = $request->file('foto');
            $nama_file = time() . '_' . $file->getClientOriginalName();
            $tujuan = 'data_file';
            $file->move($tujuan, $nama_file);

            $data = DB::table('tbl_karyawan')->where('id', $request->id)->get();

            foreach ($data as $karyawan) {
                @unlink(public_path('data_file/' . $karyawan->foto));
                $update = DB::table('tbl_karyawan')->where('id', $request->id)->update([
                    'nama' => $request->nama,
                    'jabatan' => $request->jabatan,
                    'umur' => $request->umur,
                    'alamat' => $request->alamat,
                    'foto' => $nama_file
                ]);

                $res['message'] = 'Success!';
                $res['value'] = $update;

                return response($res);
            }
        } else {
            $data = DB::table('tbl_karyawan')->where('id', $request->id)->get();

            foreach ($data as $karyawan) {
                $update = DB::table('tbl_karyawan')->where('id', $request->id)->update([
                    'nama' => $request->nama,
                    'jabatan' => $request->jabatan,
                    'umur' => $request->umur,
                    'alamat' => $request->alamat
                ]);

                $res['message'] = 'Success!';
                $res['value'] = $update;

                return response($res);
            }
        }
    }

    public function delete($id)
    {
        $data = DB::table('tbl_karyawan')->where('id', $id)->get();
        if (count($data) > 0) {
            foreach ($data as $karyawan) {
                if (file_exists(public_path('data_file/' . $karyawan->foto))) {
                    @unlink(public_path('data_file/' . $karyawan->foto));
                    DB::table('tbl_karyawan')->where('id', $id)->delete();
                    $res['message'] = 'Success!';
                    return response($res);
                } else {
                    $res['message'] = 'Empty Photo!';
                    return response($res);
                }
            }
        } else {
            $res['message'] = 'Data not found!';
            return response($res);
        }
    }
}
