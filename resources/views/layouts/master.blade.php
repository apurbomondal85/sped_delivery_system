<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background: #04415f;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: #fff;
            padding: 12px 20px;
            display: block;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar {
            margin-left: 250px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table thead {
            background: #04415f;
            color: #fff;
        }

        .toggle-btn {
            display: none;
        }

        @media (max-width: 991px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.active {
                left: 0;
            }

            .content,
            .navbar {
                margin-left: 0;
            }

            .toggle-btn {
                display: inline-block;
                cursor: pointer;
                margin-right: 10px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4 class="text-center py-3">Admin Panel</h4>
        <nav class="nav flex-column">
            <a class="nav-link" href="{{ route('zones.index') }}"><i class="fas fa-map"></i> Delivery Zone</a>
            <a class="nav-link" href="{{ route('orders.index') }}"><i class="fas fa-cart-arrow-down"></i> Orders</a>
            <a class="nav-link" href="{{ route('deliveryMan') }}"><i class="fas fa-user"></i> Demo Delivery Man</a>
        </nav>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
        <span class="toggle-btn text-dark" id="menu-toggle"><i class="fas fa-bars"></i></span>
        <a class="navbar-brand" href="#">Dashboard</a>
        <div class="ms-auto d-flex align-items-center">
            <div class="me-3"><i class="fas fa-bell"></i></div>
            <div class="dropdown">
                <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#"
                    data-bs-toggle="dropdown">
                    <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="profile">
                    <span>Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid">

            @yield('content')

        </div>
    </div>

    <!-- Bootstrap JS & jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#menu-toggle").click(function() {
                $("#sidebar").toggleClass("active");
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
