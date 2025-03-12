<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = (object) ['title' => 'Daftar kategori yang terdaftar dalam sistem.'];
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori'],
        ];

        return view('Kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => KategoriModel::all(),
            'activeMenu' => 'kategori',
        ]);
    }

    public function list(Request $request)
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        if ($request->kategori_id) {
            $kategori->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                return '
                    <a href="' . route('kategori.show', $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a>
                    <a href="' . route('kategori.edit', $kategori->kategori_id) . '" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" action="' . route('kategori.destroy', $kategori->kategori_id) . '" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
                    </form>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page = (object) ['title' => 'Tambah Kategori'];
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah'],
        ];

        return view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => 'kategori',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'kategori_kode' => ['required', 'string', 'max:255'],
                'kategori_nama' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();

            KategoriModel::create($validated);

            DB::commit();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
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
        $kategori = KategoriModel::find($id);

        $page = (object) ['title' => 'Detail Kategori'];
        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail'],
        ];

        return view('Kategori.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => 'kategori',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);

        if (!$kategori) {
            return redirect()->back()->with('error', 'Data kategori tidak ditemukan.');
        }

        $page = (object) ['title' => 'Edit Kategori'];
        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit'],
        ];

        return view('Kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => 'kategori',
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
                'kategori_kode' => ['required', 'string', 'max:255'],
                'kategori_nama' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                DB::rollBack();
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();

            KategoriModel::where('kategori_id', $id)->update($validated);

            DB::commit();
            return redirect()->route('kategori.index')->with('success', 'Level berhasil diedit.');
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

            KategoriModel::where('kategori_id', $id)->delete();

            DB::commit();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
