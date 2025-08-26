<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

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

        // ✅ Tukar "YYYY-MM-DDTHH:MM" => "YYYY-MM-DD HH:MM:SS"
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
