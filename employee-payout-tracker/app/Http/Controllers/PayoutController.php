<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePayoutRequest;
use App\Http\Requests\UpdatePayoutRequest;
use App\Http\Requests\UpdatePayoutStatusRequest;
use App\Models\Employee;
use App\Models\Payout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PayoutController extends Controller
{
    private const SORTABLE = ['created_at', 'amount', 'task', 'status'];

    public function index(Request $request): Response
    {
        $sort = in_array($request->string('sort')->toString(), self::SORTABLE, true)
            ? $request->string('sort')->toString()
            : 'created_at';
        $direction = $request->string('direction')->toString() === 'asc' ? 'asc' : 'desc';

        $payouts = Payout::query()
            ->with('employee:id,name')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($q) use ($search) {
                    $q->where('task', 'like', "%{$search}%")
                        ->orWhereHas('employee', fn ($eq) => $eq->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Payouts/Index', [
            'payouts' => $payouts,
            'filters' => $request->only(['search', 'status', 'sort', 'direction']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Payouts/Create', [
            'employees' => Employee::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StorePayoutRequest $request): RedirectResponse
    {
        Payout::create([
            ...$request->validated(),
            'status' => 'pending',
        ]);

        return redirect()->route('payouts.index')->with('success', 'Payout added.');
    }

    public function edit(Payout $payout): Response
    {
        return Inertia::render('Payouts/Edit', [
            'payout' => $payout->load('employee:id,name'),
            'employees' => Employee::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdatePayoutRequest $request, Payout $payout): RedirectResponse
    {
        $payout->update($request->validated());

        return redirect()->route('payouts.index')->with('success', 'Payout updated.');
    }

    public function updateStatus(UpdatePayoutStatusRequest $request, Payout $payout): RedirectResponse
    {
        $payout->update(['status' => $request->validated('status')]);

        return redirect()->route('payouts.index')->with('success', 'Payout status updated.');
    }

    public function destroy(Request $request, Payout $payout): RedirectResponse
    {
        $payout->delete();

        return redirect()->route('payouts.index')->with('success', 'Payout deleted.');
    }
}
