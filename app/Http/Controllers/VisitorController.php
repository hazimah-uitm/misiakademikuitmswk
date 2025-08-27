<?php

namespace App\Http\Controllers;

use App\Imports\VisitorsImport;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VisitorController extends Controller
{
    use SoftDeletes;

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $visitorList = Visitor::latest()->paginate($perPage);

        return view('pages.visitor.index', [
            'visitorList' => $visitorList,
            'perPage' => $perPage,
        ]);
    }


    public function dashboard(Request $request)
    {
        // ===== Filters =====
        $tahun   = $request->input('tahun');
        $lokasi  = $request->input('lokasi');
        $search  = $request->input('search');

        $base = Visitor::query();

        // apply filters
        if (!empty($tahun)) {
            $base->whereYear('response_at', $tahun);
        }
        if (!empty($lokasi)) {
            $base->where('lokasi', $lokasi);
        }
        if (!empty($search)) {
            $base->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('lokasi', 'LIKE', "%{$search}%")
                    ->orWhere('program_bidang', 'LIKE', "%{$search}%");
            });
        }

        // untuk dropdown & paparan
        $availableYears   = Visitor::whereNotNull('response_at')
            ->select(DB::raw('YEAR(response_at) as y'))->distinct()
            ->orderBy('y', 'desc')->pluck('y');
        $availableLokasi  = Visitor::whereNotNull('lokasi')
            ->select('lokasi')->distinct()->orderBy('lokasi')->pluck('lokasi');


        // Top Program/Bidang (bar chart) 
        $allPrograms = (clone $base)
            ->whereNotNull('program_bidang')
            ->where('program_bidang', '<>', '')
            ->pluck('program_bidang');

        $counter = [];
        foreach ($allPrograms as $progStr) {
            if (!$progStr) continue;
            // pecah ikut koma/semicolon
            $items = preg_split('/[,;]+/', $progStr);
            foreach ($items as $raw) {
                $name = trim($raw);
                if ($name === '') continue;
                $name = preg_replace('/\s+/', ' ', $name); // normalisasi ringkas
                $counter[$name] = isset($counter[$name]) ? $counter[$name] + 1 : 1;
            }
        }

        arsort($counter);
        $topProgram     = array_slice($counter, 0, 10, true);
        $programLabels  = array_keys($topProgram);
        $programData    = array_values($topProgram);

        // All program
        $allProgramLabels = array_keys($counter);
        $allProgramData   = array_values($counter);

        // lokasi
        $lokasiCounts = (clone $base)
            ->select('lokasi', DB::raw('COUNT(*) as total'))
            ->whereNotNull('lokasi')
            ->where('lokasi', '<>', '')
            ->groupBy('lokasi')
            ->orderByDesc('total')
            ->get();

        $lokasiLabels = $lokasiCounts->pluck('lokasi')->toArray();
        $lokasiData   = $lokasiCounts->pluck('total')->toArray();

        // KPI ringkas 
        $totalResponden   = (clone $base)->count();
        $jumlahBidangUnik = count($counter);

        $jumlahLokasiUnik = (clone $base)
            ->whereNotNull('lokasi')
            ->where('lokasi', '<>', '')
            ->distinct()
            ->count('lokasi');

        return view('pages.visitor.dashboard', [
            // filters & dropdowns
            'tahun'            => $tahun,
            'lokasi'           => $lokasi,
            'search'           => $search,
            'availableYears'   => $availableYears,
            'availableLokasi'  => $availableLokasi,

            // KPI
            'totalResponden'   => $totalResponden,
            'jumlahBidangUnik'   => $jumlahBidangUnik,
            'jumlahLokasiUnik'   => $jumlahLokasiUnik,

            // charts
            'programLabels'    => $programLabels,
            'programData'      => $programData,
            'allProgramLabels'  => $allProgramLabels,
            'allProgramData'    => $allProgramData,
            'lokasiLabels'     => $lokasiLabels,
            'lokasiData'       => $lokasiData,
        ]);
    }

    public function create()
    {
        return view('pages.visitor.create', [
            'save_route' => route('visitor.store'),
            'str_mode' => 'Tambah',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'response_at'    => 'nullable|date',
            'full_name'      => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'program_bidang' => 'nullable|string',
            'lokasi'         => 'nullable|string|max:255',
        ], [
            'response_at.date' => 'Tarikh/masa tidak sah.',
            'email.email'      => 'Format emel tidak sah.',
        ]);

        $data = $request->only([
            'response_at',
            'full_name',
            'phone',
            'email',
            'program_bidang',
            'lokasi'
        ]);

        if (!empty($data['response_at'])) {
            $data['response_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['response_at'])
                ->format('Y-m-d H:i:s');
        }

        $visitor = new Visitor();
        $visitor->fill($data);
        $visitor->save();

        return redirect()->route('visitor')->with('success', 'Maklumat berjaya disimpan.');
    }

    public function show($id)
    {
        $visitor = Visitor::findOrFail($id);

        return view('pages.visitor.view', [
            'visitor' => $visitor,
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new VisitorsImport, $request->file('file'));

        return back()->with('success', 'Data Pengunjung berjaya diimport!');
    }

    public function edit(Request $request, $id)
    {
        return view('pages.visitor.edit', [
            'save_route' => route('visitor.update', $id),
            'str_mode' => 'Kemas Kini',
            'visitor' => Visitor::findOrFail($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);

        $request->validate([
            'response_at'    => 'nullable|date',
            'full_name'      => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'program_bidang' => 'nullable|string',
            'lokasi'         => 'nullable|string|max:255',
        ], [
            'response_at.date' => 'Tarikh/masa tidak sah.',
            'email.email'      => 'Format emel tidak sah.',
        ]);

        $data = $request->only([
            'response_at',
            'full_name',
            'phone',
            'email',
            'program_bidang',
            'lokasi'
        ]);

        if (!empty($data['response_at'])) {
            $data['response_at'] = Carbon::createFromFormat('Y-m-d\TH:i', $data['response_at'])
                ->format('Y-m-d H:i:s');
        }

        $visitor->fill($data);
        $visitor->save();

        return redirect()->route('visitor')->with('success', 'Maklumat berjaya dikemaskini.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $visitorList = Visitor::where('full_name', 'LIKE', "%$search%")
                ->latest()
                ->paginate(10);
        } else {
            $visitorList = Visitor::latest()->paginate(10);
        }

        return view('pages.visitor.index', [
            'visitorList' => $visitorList,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $visitor = Visitor::findOrFail($id);

        $visitor->delete();

        return redirect()->route('visitor')->with('success', 'Maklumat berjaya dihapuskan');
    }

    public function trashList()
    {
        $trashList = Visitor::onlyTrashed()->latest()->paginate(10);

        return view('pages.visitor.trash', [
            'trashList' => $trashList,
        ]);
    }

    public function restore($id)
    {
        Visitor::withTrashed()->where('id', $id)->restore();

        return redirect()->route('visitor')->with('success', 'Maklumat berjaya dikembalikan');
    }


    public function forceDelete($id)
    {
        $visitor = Visitor::withTrashed()->findOrFail($id);

        $visitor->forceDelete();

        return redirect()->route('visitor.trash')->with('success', 'Maklumat berjaya dihapuskan sepenuhnya');
    }
}
