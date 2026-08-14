
@extends('layouts.main')

@section('content')

<div class="container-fluid">

    <h2 class="mt-2">Return Review</h2>


    {{-- ========================= --}}
    {{-- PENDING APPROVAL --}}
    {{-- ========================= --}}

    <div class="card mb-4">

        <div class="card-header">
            <strong>
                Pending Approval ({{ $pendingReturns->count() }})
            </strong>
        </div>

        <div class="card-body">

            <h5 class="mb-3">Pending Approval</h5>

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-sm small">

                    <thead class="table-dark">

                        <tr>

                            <th>Product</th>

                            <th>Qty</th>

                            <th class="d-none d-md-table-cell">Reason</th>

                            <th>Status</th>

                            <th class="d-none d-md-table-cell">
                                User
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @if($pendingReturns->isEmpty())

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center text-muted py-3">

                                    Tidak ada return yang menunggu approval.

                                </td>

                            </tr>

                        @else

                            @foreach($pendingReturns as $return)

                                <tr>

                                    <td>
                                        {{ $return->product->name }}
                                    </td>

                                    <td>
                                        {{ $return->qty }}
                                    </td>

                                    <td>
                                        {{ $return->reason }}
                                    </td>

                                    <td class="d-none d-md-table-cell">
                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>
                                    </td>

                                    <td class="d-none d-md-table-cell">
                                        {{ $return->user->name }}
                                    </td>

                                    <td>

                                        <div class="d-flex flex-column gap-1">

                                            <form
                                                action="/returns/{{ $return->id }}/approve"
                                                method="POST"
                                                onsubmit="return disableSubmit(this)">

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-success btn-sm w-100">

                                                    Approve

                                                </button>

                                            </form>


                                            <form
                                                action="/returns/{{ $return->id }}/reject"
                                                method="POST"
                                                onsubmit="return disableSubmit(this)">

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm w-100">

                                                    Reject

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        @endif

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================= --}}
    {{-- RETURN HISTORY --}}
    {{-- ========================= --}}

    <div class="card">

        <div class="card-body">

            <h5 class="mb-3">Return History</h5>

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-sm small">

                    <thead class="table-dark">

                        <tr>

                            <th>Product</th>

                            <th>
                                Qty
                            </th>

                            <th>Reason</th>

                            <th class="d-none d-md-table-cell">Status</th>

                            <th class="d-none d-md-table-cell">
                                User
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @if($historyReturns->isEmpty())

                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-3">

                                    Belum ada riwayat return.

                                </td>

                            </tr>

                        @else

                            @foreach($historyReturns as $return)

                                <tr>

                                    <td>
                                        {{ $return->product->name }}
                                    </td>

                                    <td>
                                        {{ $return->qty }}
                                    </td>

                                    <td>
                                        {{ $return->reason }}
                                    </td>

                                    <td class="d-none d-md-table-cell"> 

                                        @if($return->status == 'cancelled')

                                            <span class="badge bg-secondary">
                                                Cancelled
                                            </span>

                                        @elseif($return->status == 'rejected')

                                            <span class="badge bg-danger">
                                                Rejected
                                            </span>

                                        @elseif($return->status == 'approved')

                                            <span class="badge bg-success">
                                                Approved
                                            </span>

                                        @elseif($return->status == 'pending')

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                        @endif

                                    </td>

                                    <td class="d-none d-md-table-cell">
                                        {{ $return->user->name }}
                                    </td>

                                </tr>

                            @endforeach

                        @endif

                    </tbody>

                </table>

            </div>


            <div class="mt-3">

                {{ $historyReturns->links() }}

            </div>

        </div>

    </div>

</div>


<script>

function disableSubmit(form){

    let btn = form.querySelector('button');

    btn.disabled = true;

    btn.innerText = 'Processing...';

    return true;

}

</script>

@endsection