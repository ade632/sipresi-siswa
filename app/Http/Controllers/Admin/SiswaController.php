<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SiswaImportTemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\SiswaImport;
use App\Models\AuditLog;
use App\Models\KartuAkses;
use App\Models\Kelas;
use App\Models\Pengaturan;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\User;
use App\Repositories\Contracts\SiswaRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function __construct(protected SiswaRepositoryInterface $siswaRepo) {}

    public function index(Request $request)
    {
        $filters = $request->only(['kelas_id', 'jurusan_id', 'status', 'cari']);
        
        $siswa = Siswa::with(['kelas.jurusan', 'orangTua'])
            ->when($request->cari, function($q, $cari) {
                $q->where('nama', 'like', "%{$cari}%")
                  ->orWhere('nis', 'like', "%{$cari}%")
                  ->orWhere('nisn', 'like', "%{$cari}%");
            })
            ->when($request->kelas_id, fn($q, $id) => $q->where('kelas_id', $id))
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->paginate(10)
            ->withQueryString();

        return view('siswa.index', [
            'siswa' => $siswa,
            'kelas' => Kelas::with('jurusan')->orderBy('nama')->get(),
        ]);
    }

    public function create()
    {
        return view('siswa.create', ['kelas' => Kelas::orderBy('nama')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nis' => ['required', 'string', 'max:20', 'unique:siswa,nis'],
            'nisn' => ['required', 'string', 'max:20', 'unique:siswa,nisn'],
            'nama' => ['required', 'string', 'max:150'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'nama_ortu' => ['required', 'string', 'max:150'],
            'email_ortu' => ['required', 'email', 'unique:users,email'],
            'no_hp_ortu' => ['nullable', 'string', 'max:20'],
            'rfid_uid' => ['nullable', 'string', 'unique:kartu_akses,kode'],
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('siswa', 'public');
        }

        $roleOrtu = Role::where('kode', Role::ORTU)->firstOrFail();
        $orangTua = User::create([
            'role_id' => $roleOrtu->id,
            'name' => $data['nama_ortu'],
            'email' => $data['email_ortu'],
            'no_hp' => $data['no_hp_ortu'] ?? null,
            'password' => Hash::make($data['nisn']),
        ]);

        $siswa = Siswa::create([
            'nis' => $data['nis'],
            'nisn' => $data['nisn'],
            'nama' => $data['nama'],
            'kelas_id' => $data['kelas_id'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'foto' => $data['foto'] ?? null,
            'orang_tua_id' => $orangTua->id,
            'status' => 'aktif',
        ]);

        KartuAkses::create([
            'siswa_id' => $siswa->id,
            'tipe' => 'qr',
            'kode' => (string) Str::uuid(),
            'diterbitkan_oleh' => $request->user()->id,
        ]);

        if (! empty($data['rfid_uid'])) {
            KartuAkses::create([
                'siswa_id' => $siswa->id,
                'tipe' => 'rfid',
                'kode' => $data['rfid_uid'],
                'diterbitkan_oleh' => $request->user()->id,
            ]);
        }

        AuditLog::catat("Menambah siswa baru: {$siswa->nama}", 'siswa', null, $siswa->toArray());

        return redirect()->route('admin.siswa.index')->with('success', "Siswa {$siswa->nama} berhasil ditambahkan.");
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', [
            'siswa' => $siswa->load('kartuAktif', 'orangTua'),
            'kelas' => Kelas::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'nis' => ['required', 'string', 'max:20', 'unique:siswa,nis,'.$siswa->id],
            'nisn' => ['required', 'string', 'max:20', 'unique:siswa,nisn,'.$siswa->id],
            'nama' => ['required', 'string', 'max:150'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'status' => ['required', 'in:aktif,lulus,pindah,keluar'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $before = $siswa->toArray();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('siswa', 'public');
        }

        $siswa->update($data);

        AuditLog::catat("Mengubah data siswa: {$siswa->nama}", 'siswa', $before, $siswa->fresh()->toArray());

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $nama = $siswa->nama;
        $siswa->delete();

        AuditLog::catat("Menghapus siswa: {$nama}", 'siswa');

        return back()->with('success', "Siswa {$nama} berhasil dihapus.");
    }

    public function hapusPerKelas(Request $request)
    {
        $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        $jumlah = Siswa::where('kelas_id', $kelas->id)->count();
        
        Siswa::where('kelas_id', $kelas->id)->delete();

        AuditLog::catat("Menghapus seluruh siswa dari kelas: {$kelas->nama} ({$jumlah} siswa)", 'siswa');

        return back()->with('success', "Berhasil menghapus {$jumlah} data siswa dari kelas {$kelas->nama}.");
    }

    public function importForm()
    {
        return view('siswa.import');
    }

    public function template()
    {
        return Excel::download(new SiswaImportTemplateExport, 'template-import-siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $import = new SiswaImport($request->user()->id);
        Excel::import($import, $request->file('file'));

        AuditLog::catat(
            sprintf('Import data siswa massal (%d berhasil, %d gagal)', count($import->berhasil), count($import->gagal)),
            'siswa'
        );

        return redirect()->route('admin.siswa.import.form')->with('hasil_import', [
            'berhasil' => $import->berhasil,
            'gagal' => $import->gagal,
        ]);
    }

    public function cetakKartu(Siswa $siswa)
    {
        $kartu = $siswa->kartuAktif()->where('tipe', 'qr')->firstOrFail();
        $tahunAjaranAktif = \App\Models\TahunAjaran::aktif();
        
        $pengaturanKartu = (object) [
            'nama_kepsek'  => Pengaturan::get('nama_kepsek'),
            'nip_kepsek'   => Pengaturan::get('nip_kepsek'),
            'tanda_tangan' => Pengaturan::get('tanda_tangan_kepsek'),
            'cap_sekolah'  => Pengaturan::get('cap_sekolah'),
        ];

        return view('siswa.cetak-kartu', [
            'siswa' => $siswa->load('kelas.jurusan', 'orangTua'),
            'kartu' => $kartu,
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'pengaturanKartu' => $pengaturanKartu,
        ]);
    }

    public function cetakKartuKelas(Kelas $kelas)
    {
        $siswa = $kelas->siswaAktif()->with('kartuAktif', 'kelas.jurusan', 'orangTua')->get();
        $tahunAjaranAktif = \App\Models\TahunAjaran::aktif();
        
        $pengaturanKartu = (object) [
            'nama_kepsek'  => Pengaturan::get('nama_kepsek'),
            'nip_kepsek'   => Pengaturan::get('nip_kepsek'),
            'tanda_tangan' => Pengaturan::get('tanda_tangan_kepsek'),
            'cap_sekolah'  => Pengaturan::get('cap_sekolah'),
        ];

        return view('siswa.cetak-kartu-batch', [
            'siswa' => $siswa,
            'kelas' => $kelas->load('jurusan'),
            'tahunAjaranAktif' => $tahunAjaranAktif,
            'pengaturanKartu' => $pengaturanKartu,
        ]);
    }
}