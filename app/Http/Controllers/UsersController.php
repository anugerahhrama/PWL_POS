<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list'  => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $active_menu = 'user'; // set menu yang sedang aktif

        $user = UserModel::with('level')->get();

        $level = LevelModel::all();

        return view('User.index', ['data' => $user, 'breadcrumb' => $breadcrumb, 'activeMenu' => $active_menu, 'page' => $page, 'level' => $level]);
    }

    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')->with('level');

        // Filter data user berdasarkan level_id
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn  = '<a href="' . route('user.show', $user->user_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . route('user.edit', $user->user_id) . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . route('user.destroy', $user->user_id) . '">'
                    . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
                        </form>';
                return $btn;
            })
            ->editColumn('level.level_nama', function ($user) {
                return $user->level ? $user->level->level_nama : 'Tidak ada level';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all();
        $active_menu = 'user';

        return view('User.create', ['breadcrumb' => $breadcrumb, 'level' => $level, 'activeMenu' => $active_menu, 'page' => $page]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'username' => ['required', 'string', 'max:255'],
                'nama' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
                'level_id' => ['required']
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();

            UserModel::create([
                'username' => $validated['username'],
                'nama' => $validated['nama'],
                'password' => Hash::make($validated['password']),
                'level_id' => $validated['level_id'],
            ]);

            DB::commit();
            return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        if (!$user) {
            abort(404, 'User tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list'  => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user'
        ];

        $active_menu = 'user';

        return view('User.show', ['user' => $user, 'breadcrumb' => $breadcrumb, 'activeMenu' => $active_menu, 'page' => $page]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = UserModel::findOrFail($id);
        $level = LevelModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list'  => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user'
        ];

        $active_menu = 'user';

        return view('User.edit', ['user' => $user, 'breadcrumb' => $breadcrumb, 'activeMenu' => $active_menu, 'page' => $page, 'level' => $level]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'username' => ['required', 'string', 'max:255'],
                'nama' => ['required', 'string', 'max:255'],
                'password' => ['nullable', 'string', 'min:8'],
                'level_id' => ['required']
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = UserModel::findOrFail($id);

            $validated = $validator->validated();

            $data->update([
                'username' => $validated['username'],
                'nama' => $validated['nama'],
                'password' => $validated['password'] ? Hash::make($validated['password']) : $data->password,
                'level_id' => $validated['level_id'],
            ]);

            DB::commit();
            return redirect()->route('user.index')->with('success', 'User berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $data = UserModel::findOrFail($id);

            $data->delete();

            DB::commit();
            return redirect()->back()->with('success', 'User berhasil dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
