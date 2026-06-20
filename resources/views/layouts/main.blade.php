<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>

    .sidebar{

        width:250px;
        min-height:100vh;
        transition:0.3s;
        margin-left:0;
        overflow-y:auto;
        overflow-x:hidden;

    }

    .sidebar.active{

        left:0;
    }

    .sidebar.hide{
        margin-left: -250px;
    }
       
    /* MOBILE */
    @media(max-width:768px){

        .sidebar{

            position:fixed;
            top:0;
            left:0;
            z-index:999;

        }
    }
    </style>
    <title>Warehouse Management System</title>

    {{-- Vite Laravel --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="d-flex">

     {{-- Sidebar --}}
    <div id="sidebar" class="sidebar bg-dark text-white p-3">

        <h4>WMS</h4>

        {{-- menu nanti disini --}}
        {{-- DASHBOARD --}}
        @if(in_array(auth()->user()->role, ['owner', 'leader']))
            <a href="/dashboard" class="d-block text-white text-decoration-none mb-2">
                Dashboard
            </a>
        @endif

        {{-- INVENTORY --}}
        @if(in_array(auth()->user()->role ,['owner','leader']))
            <a href="/inventory" class="d-block text-white text-decoration-none mb-2">
                Inventory
            </a>
        @endif
        
        {{-- TRANSACTIONS --}}
        @if(in_array(auth()->user()->role, ['leader', 'staff']))
        <button
            class="btn btn-dark w-100 text-start mb-2"
            onclick="toggleTransactionMenu()">

            Transactions ▼

        </button>

        <div id="transactionMenu" style="display:none;">

            <a href="/transactions-in"
            class="d-block text-success ms-3 mb-2 text-decoration-none">

                Stock-in +

            </a>

            <a href="/transactions-out"
            class="d-block text-danger ms-3 mb-2 text-decoration-none">

                Stock-out -

            </a>

        </div>
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

         {{-- Reports --}}
        @if(auth()->user()->role == 'owner')
            <a href="/reports/index" class="d-block text-white text-decoration-none mb-2">
                Report
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

    {{-- Main Content --}}
    <div class="flex-grow-1">

        {{-- topbar --}}
        <div class="bg-white shadow-sm p-2">

            <button class="btn btn-dark" onclick="toggleSidebar()">
                ☰
            </button>

        </div>

        <div class="p-3">

            @yield('content')

        </div>

    </div>

</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

function toggleSidebar(){

    document
        .getElementById('sidebar')
        .classList
        .toggle('hide');

}

function toggleTransactionMenu(){

    let menu =
        document.getElementById('transactionMenu');

    if(menu.style.display === 'none'){

        menu.style.display = 'block';

    }else{

        menu.style.display = 'none';

    }

}

window.onload = function(){

    if(window.innerWidth <= 768){

        document
            .getElementById('sidebar')
            .classList
            .add('hide');

    }

}

</script>

</body>
</html>