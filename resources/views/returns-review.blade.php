@extends('layouts.main')

@section('content')

<div class="container">

    <h3 class="mb-4 font-bold text-xl">
        Return Review
    </h3>

    <div class="card p-3">

        <table class="table-auto w-full border mt-4">

            <thead>

                <tr class="bg-gray-200">

                    <th class="border px-2 py-2">Product</th>

                    <th class="border px-2 py-2">Qty</th>

                    <th class="border px-2 py-2">Reason</th>

                    <th class="border px-2 py-2">Status</th>

                    <th class="border px-2 py-2">User</th>

                    <th class="border px-2 py-2">Action</th>

                </tr>

            </thead>

            <tbody>

                @foreach($returns as $return)

                <tr>

                    {{-- Product --}}
                    <td class="border px-2 py-2">

                        {{ $return->product->name }}

                    </td>

                    {{-- Qty --}}
                    <td class="border px-2 py-2">

                        {{ $return->qty }}

                    </td>

                    {{-- Reason --}}
                    <td class="border px-2 py-2">

                        {{ $return->reason }}

                    </td>

                    {{-- Status --}}
                    <td class="border px-2 py-2">

                        @if($return->status == 'pending')

                            <span class="bg-yellow-200 px-2 py-1 rounded">
                                Pending
                            </span>

                        @elseif($return->status == 'approved')

                            <span class="bg-green-200 px-2 py-1 rounded">
                                Approved
                            </span>

                        @elseif($return->status == 'rejected')

                            <span class="bg-red-200 px-2 py-1 rounded">
                                Rejected
                            </span>

                        @endif

                    </td>

                    {{-- User --}}
                    <td class="border px-2 py-2">

                        {{ $return->user->name }}

                    </td>

                    {{-- Action --}}
                    <td class="border px-2 py-2">

                        @if($return->status == 'pending')

                            <form action="/returns/{{ $return->id }}/approve" method="POST" class="inline">

                                @csrf
                                @method('PATCH')

                                <button class="bg-green-500 text-white px-3 py-1 rounded">

                                    Approve

                                </button>

                            </form>

                            {{-- <button class="bg-green-500 text-white px-3 py-1 rounded">
                                Approve
                            </button> --}}

                            <form action="/returns/{{ $return->id }}/reject" method="POST" class="inline">

                                @csrf
                                @method('PATCH')

                                <button class="bg-red-500 text-white px-3 py-1 rounded">

                                    Reject

                                </button>

                            </form>

{{--                             <button class="bg-red-500 text-white px-3 py-1 rounded">
                                Reject
                            </button> --}}

                        @else

                            <span class="text-gray-500">
                                No Action
                            </span>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        <div class="mt-4">

            {{ $returns->links() }}

        </div>

    </div>

</div>

@endsection