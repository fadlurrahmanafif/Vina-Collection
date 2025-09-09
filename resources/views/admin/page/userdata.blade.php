@extends('admin.layout.master')
<link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="page-title">
                <h1>{{ $title }}</h1>
                <div class="breadcrumb">User Management</div>
            </div>

            <div class="user-info">
                <button class="notification-btn">
                    <i class="material-icons">notifications</i>
                    <span class="notification-badge">3</span>
                </button>

                <div class="user-avatar">
                    <i class="material-icons">person</i>
                </div>
            </div>
        </div>
        <div class="content">
            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-row">
                    <input type="date" class="date-input" placeholder="Start Date">
                    <input type="date" class="date-input" placeholder="End Date">
                    <button class="filter-btn">Filter</button>
                </div>
            </div>

            <!-- Product Controls -->
            <div class="product-controls">
                

                <input type="text" class="search-box" placeholder="Search User...">
            </div>

            <!-- Product Table -->
            <div class="table-container">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date In</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No.Hp</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $y => $x)
                            <tr class="align-middle">
                                <td>{{ ++$y }}</td>
                                <td>{{ $x->created_at }}</td>
                                <td>{{ $x->nama }}</td>
                                <td>{{ $x->email }}</td>
                                <td>{{ $x->no_hp }}</td>
                                <td></td>
                                <td>
                                    <button class="action-btn delete-btn deleteData" type="button"
                                        data-id="{{ $x->id }}">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination mt-5 d-flex flex-row justify-content-between align-items-center">
                <div class="showData">
                    Data ditampilkan {{ $data->count() }} dari {{ $data->total() }}
                </div>
                {{ $data->links() }}
            </div>

        </div>
    </div>

    <script>
        $(document).on('click', '.deleteData', function(e) {
            e.preventDefault();

            var id = $(this).data('id');
            var button = $(this);

            if (!id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Product ID not found!'
                });
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });

                    // Create and submit form
                    var form = $('<form>', {
                        'method': 'POST',
                        'action': '/product/deleteData/' + id
                    });

                    // Add CSRF token
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': $('meta[name="csrf-token"]').attr('content')
                    }));

                    // Add method spoofing untuk DELETE
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));

                    // Append form ke body dan submit
                    $('body').append(form);
                    form.submit();
                }
            });
        });
    </script>
@endsection
