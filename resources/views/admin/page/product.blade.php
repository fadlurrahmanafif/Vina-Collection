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
                <div class="breadcrumb">Product Management</div>
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
                <button class="add-product-btn" id="addData">
                    <i class="material-icons">add</i>
                    Tambah Product
                </button>

                <input type="text" class="search-box" placeholder="Search products...">
            </div>

            <!-- Product Table -->
            <div class="table-container">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date In</th>
                            <th>Foto</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Tipe</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $y => $x)
                            <tr class="align-middle">
                                <td>{{ ++$y }}</td>
                                <td>{{ $x->created_at }}</td>
                                <td><img src="{{ asset('storage/produk/' . $x->foto) }}" alt="product"
                                        style="width: 100px; height: 100px;"></td>
                                <td>{{ $x->nama_produk }}</td>
                                <td>{{ $x->kategori }}</td>
                                <td>{{ $x->tipe }}</td>
                                <td class="price-format">Rp {{ $x->harga }}</td>
                                <td>{{ $x->stok }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn edit-btn editModal" type="button"
                                            data-id="{{ $x->id }}">
                                            <i class="material-icons">edit</i>
                                        </button>
                                        <button class="action-btn delete-btn deleteData" type="button"
                                            data-id="{{ $x->id }}">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </div>
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
    <div class="tampilData" style="displat: none;"></div>
    <div class="tampilEditData"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation functionality
            const navItems = document.querySelectorAll('.nav-item');
            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    navItems.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Search functionality
            const searchBox = document.querySelector('.search-box');
            const tableRows = document.querySelectorAll('.product-table tbody tr');

            searchBox.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();

                tableRows.forEach(row => {
                    const productName = row.cells[3].textContent.toLowerCase();
                    const sku = row.cells[2].textContent.toLowerCase();
                    const category = row.cells[4].textContent.toLowerCase();

                    if (productName.includes(searchTerm) ||
                        sku.includes(searchTerm) ||
                        category.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            $(document).ready(function() {

                // ADD DATA MODAL
                $('#addData').on('click', function(e) {
                    e.preventDefault();
                    console.log('Add button clicked');

                    $.ajax({
                        url: "{{ route('add.modal') }}",
                        type: 'GET',
                        success: function(response) {
                            console.log('Add modal loaded successfully');
                            $('.tampilData').html(response);
                            $('#addModal').modal("show");
                        },
                        error: function(xhr, status, error) {
                            console.log('Add modal error:', error);
                            alert('Error loading add modal: ' + error);
                        }
                    });
                });

                // EDIT DATA MODAL - VERSI DIPERBAIKI
                $(document).on('click', '.editModal', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var id = $(this).data('id');
                    console.log('=== EDIT DEBUG START ===');
                    console.log('Edit button clicked, ID:', id);
                    console.log('Button element:', this);
                    console.log('Data attribute:', $(this).attr('data-id'));

                    // Validasi ID
                    if (!id) {
                        console.error('No ID found on button');
                        alert('Error: Product ID not found');
                        return;
                    }

                    // Clear previous content dan show loading
                    $('.tampilEditData').html(
                        '<div class="d-flex justify-content-center p-4"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                    );

                    var url = "{{ route('edit.modal', ['id' => ':id']) }}".replace(':id', id);
                    console.log('Requesting URL:', url);

                    $.ajax({
                        type: "GET",
                        url: url,
                        dataType: 'html',
                        timeout: 10000,
                        beforeSend: function() {
                            console.log('Sending AJAX request...');
                        },
                        success: function(response, textStatus, jqXHR) {
                            console.log('=== SUCCESS ===');
                            console.log('Response status:', textStatus);
                            console.log('Response length:', response.length);
                            console.log('Response preview:', response.substring(0,
                                100));

                            // Insert response into container
                            $('.tampilEditData').html(response);

                            // Show modal
                            setTimeout(function() {
                                console.log('Attempting to show modal...');
                                $('#editModal').modal('show');
                                console.log('Modal show command executed');
                            }, 100);
                        },
                        error: function(xhr, status, error) {
                            console.log('=== ERROR ===');
                            console.log('Status:', status);
                            console.log('Error:', error);
                            console.log('Response Text:', xhr.responseText);
                            console.log('Status Code:', xhr.status);
                            console.log('Ready State:', xhr.readyState);

                            $('.tampilEditData').html(
                                '<div class="alert alert-danger">Error loading edit form</div>'
                            );
                            alert('Error loading edit modal: ' + error + ' (Status: ' +
                                xhr.status + ')');
                        }
                    });
                });

                // Debug info
                setTimeout(function() {
                    console.log('=== DEBUG INFO ===');
                    console.log('Edit buttons found:', $('.editModal').length);
                    console.log('Edit container exists:', $('.tampilEditData').length > 0);
                    console.log('Bootstrap loaded:', typeof $.fn.modal !== 'undefined');

                    $('.editModal').each(function(index) {
                        console.log('Button', index, 'ID:', $(this).data('id'));
                    });
                }, 1000);

                // Auto open modal untuk validation errors (hanya untuk ADD modal)
                @if ($errors->any() || session('showModal'))
                    // Hanya untuk add modal, bukan edit
                    if (!'{{ session('editMode') }}') {
                        $.ajax({
                            url: "{{ route('add.modal') }}",
                            success: function(response) {
                                $('.tampilData').html(response);
                                $('#addModal').modal("show");

                                $('#addModal').on('shown.bs.modal', function() {
                                    @if ($errors->any())
                                        let errors = @json($errors->all());
                                        showModalErrors(errors);
                                    @endif

                                    @if (old())
                                        let oldData = @json(old());
                                        populateOldValues(oldData);
                                    @endif
                                });
                            }
                        });
                    }
                @endif
            });

            // Function untuk show errors
            function showModalErrors(errors) {
                let errorHtml = '';
                errors.forEach(function(error) {
                    errorHtml += '<li>' + error + '</li>';
                });

                $('#errorAlert').show();
                $('#errorList').html(errorHtml);
            }

            // Function untuk populate old values  
            function populateOldValues(oldData) {
                Object.keys(oldData).forEach(function(key) {
                    if (key !== '_token') {
                        let element = $('[name="' + key + '"]');
                        if (element.length > 0) {
                            if (element.is('select')) {
                                element.val(oldData[key]);
                            } else {
                                element.val(oldData[key]);
                            }
                        }
                    }
                });
            }

            // Delete buttons
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

            // Filter button
            const filterBtn = document.querySelector('.filter-btn');
            filterBtn.addEventListener('click', function() {
                const startDate = document.querySelectorAll('.date-input')[0].value;
                const endDate = document.querySelectorAll('.date-input')[1].value;

                if (startDate && endDate) {
                    alert(`Filtering products from ${startDate} to ${endDate}`);
                } else {
                    alert('Please select both start and end dates');
                }
            });

            // Notification and user avatar
            const notificationBtn = document.querySelector('.notification-btn');
            const userAvatar = document.querySelector('.user-avatar');

            notificationBtn.addEventListener('click', function() {
                alert('You have 3 new notifications!');
            });

            userAvatar.addEventListener('click', function() {
                alert('User profile menu would open here');
            });

            // Table row hover effects
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.01)';
                    this.style.transition = 'all 0.2s ease';
                });

                row.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
@endsection
