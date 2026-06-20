@extends('layouts.main')

@section('content')

<div class="table-responsive">

    <h3 class="mb-4 font-bold text-xl">
        Return Review
    </h3>

    <div class="card mb-3">

        <div class="card-header">

            Pending Approval ({{ $pendingReturns->count() }})

        </div>

        <div class="card-body">

            {{-- Tabel Pending --}}
            <h4 class="mb-3">Pending Approval</h4>
            <table class="table table-bordered table-striped table-sm small">     
                <thead>

                    <tr class="bg-gray-200">

                        <th class="border px-2 py-2">Product</th>

                        <th class="border px-2 py-2 d-none d-md-table-cell">Qty</th>

                        <th class="border px-2 py-2">Reason</th>

                        <th class="border px-2 py-2">Status</th>

                        <th class="border px-2 py-2 d-none d-md-table-cell">User</th>

                        <th class="border px-2 py-2">Action</th>

                    </tr>

                </thead>
                <tbody>
                    
                    @foreach($pendingReturns as $return)

                        <tr>

                            <td>{{ $return->product->name }}</td>

                            <td>{{ $return->qty }}</td>

                            <td>{{ $return->reason }}</td>

                            <td>
                                <span class="badge bg-warning">
                                    Pending
                                </span>
                            </td>

                            <td>{{ $return->user->name }}</td>

                            <td>

                            <div class="d-flex gap-2">

                               <form
                                    action="/returns/{{ $return->id }}/approve"
                                    method="POST"

                                    onsubmit="return confirm('Yakin approve return ini?')">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="btn btn-success btn-sm">

                                        Approve

                                    </button>

                                </form>
                                <form
                                action="/returns/{{ $return->id }}/reject" method="POST"
                                onsubmit="return confirm('Yakin reject return ini?')">
                                @csrf
                                    @method('PATCH')

                                    <button
                                        class="btn btn-danger btn-sm">

                                        Reject

                                    </button>
                                </form>

                                </div>
                            </td>

                        </tr>

                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

    {{-- Tabel history --}}
    <h4 class="mb-3">Return History</h4>
    <table class="table table-bordered table-striped table-sm small">
        
        <thead>

            <tr class="bg-gray-200">

                <th class="border px-2 py-2">Product</th>

                <th class="border px-2 py-2 d-none d-md-table-cell">Qty</th>

                <th class="border px-2 py-2">Reason</th>

                <th class="border px-2 py-2">Status</th>

                <th class="border px-2 py-2 d-none d-md-table-cell">User</th>


            </tr>

        </thead>

         <tbody>
            
            @foreach($historyReturns as $return)
                <tr>

                    <td>{{ $return->product->name }}</td>

                    <td>{{ $return->qty }}</td>

                    <td>{{ $return->reason }}</td>

                    <td class="border px-1 py-1">

                                @if($return->status == 'pending')

                                    <span class="bg-yellow-200 px-1 py-1 rounded">
                                        Pending
                                    </span>

                                @endif
                                @if($return->status == 'cancelled')

                                    <span class="bg-gray-200 px-1 py-1 rounded">
                                        Cancelled
                                    </span>

                                @endif
                                @if($return->status == 'rejected')

                                    <span class="bg-red-200 px-1 py-1 rounded">
                                        Rejected
                                    </span>

                                @endif
                                @if($return->status == 'approved')

                                    <span class="bg-green-200 px-1 py-1 rounded">
                                        Approved
                                    </span>

                                @endif

                    </td>

                    <td>{{ $return->user->name }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">

        {{-- dulu {{ $returns->links() }} --}}
        {{ $historyReturns->links() }}

    </div>

</div>

@endsection