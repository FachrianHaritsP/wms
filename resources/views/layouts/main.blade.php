{{-- <!DOCTYPE html>
<html>
<head>
    <title>Warehouse System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @if(auth()->check())

        <div>
            {{ auth()->user()->name }}
            ({{ auth()->user()->role }})
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-danger btn-sm">
                Logout
            </button>
            <!--<button type="submit">Logout</button>-->
        </form>

    @endif

    @yield('content')

</body>
</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Management System</title>

    {{-- Vite Laravel --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="bg-dark text-white p-3 vh-100" style="width: 200px;">

        <h4>WMS (Warehouse Management System)</h4>
        <hr>

        {{-- DASHBOARD --}}
        @if(in_array(auth()->user()->role, ['owner', 'leader']))
            <a href="/dashboard" class="d-block text-white text-decoration-none mb-2">
                Dashboard
            </a>
        @endif


        {{-- INVENTORY --}}
        @if(auth()->user()->role == 'leader')
            <a href="/inventory" class="d-block text-white text-decoration-none mb-2">
                Inventory
            </a>
        @endif

        {{-- TRANSACTIONS --}}
        @if(in_array(auth()->user()->role, ['leader', 'staff']))
            {{-- <a href="/transactions" class="d-block text-white text-decoration-none mb-2">
                Transactions
            </a> --}}
            <ul>
                <li class="d-block text-white text-decoration-none mb-2">Transactions
                    <ul>
                        <li>
                            <a href="/transactions-in" class="text-success">
                                Stock-in +
                            </a>
                        </li>
                        <li>
                            <a href="/transactions-out" class="text-danger">
                                Stock-out -
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        @endif
        
        {{-- Returns --}}
        @if(in_array(auth()->user()->role, ['leader','staff']))
            <a href="/returns" class="d-block text-white text-decoration-none mb-2">
                Returns
            </a>
        @endif

        {{-- Return-Reviews --}}
        @if(auth()->user()->role == 'leader')
            <a href="/returns/review" class="d-block text-white text-decoration-none mb-2">
                Returns review
            </a>
        @endif

            {{-- Return-Reviews --}}
        @if(auth()->user()->role == 'leader')
            <a href="/stock-opname" class="d-block text-white text-decoration-none mb-2">
                Stock opname
            </a>
        @endif


        <hr>

         {{-- USER INFO --}}
        <div class="mb-3">
            <small>  
                {{ auth()->user()->name }}
                ({{ auth()->user()->role }})
            </small>
        </div>

        {{-- LOGOUT --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm w-100">
                Logout
            </button>
        </form>

    </div>

    <!-- CONTENT -->
    <div class="p-4 w-100">
        @yield('content')
    </div>

</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>