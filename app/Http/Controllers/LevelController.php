<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = (object) ['title' => 'Daftar level yang terdaftar dalam sistem.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level'],
        ];

        return view('Level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => LevelModel::all(), 'activeMenu' => 'level']);
    }

    public function list(Request $request)
    {
        $level = LevelModel::select('level_id', 'level_kode', 'level_nama');
        if ($request->level_id) $level->where('level_id', $request->level_id);
        return DataTables::of($level)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                $btn = '<a href="' . route('level.show', $level->level_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . route('level.edit', $level->level_id) . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form method="POST" action="' . route('level.destroy', $level->level_id) . '" style="display:inline;">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page = (object) ['title' => 'Tambah Level.'];
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        return view('Level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => 'level']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'level_kode' => ['required', 'max:3'],
                'level_nama' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();

            LevelModel::create($validated);

            DB::commit();
            return redirect()->route('level.index')->with('success', 'Level berhasil ditambahkan');
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
        $level = LevelModel::find($id);
        $page = (object) ['title' => 'Detail Level'];
        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail'],
        ];

        return view('Level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => 'level']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $page = (object) ['title' => 'Edit Level'];
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        return view('level.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => LevelModel::find($id),
            'activeMenu' => 'level',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'level_kode' => ['required', 'max:3'],
                'level_nama' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();

            LevelModel::where('level_id', $id)->update($validated);

            DB::commit();
            return redirect()->route('level.index')->with('success', 'Level berhasil diedit.');
        } catch (\Throwable $th) {
            //throw $th;
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

            LevelModel::where('level_id', $id)->delete();

            DB::commit();
            return redirect()->route('level.index')->with('success', 'Level berhasil dihapus.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
