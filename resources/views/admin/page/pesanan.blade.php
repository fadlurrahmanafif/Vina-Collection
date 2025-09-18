@extends('admin.layout.master')
<link rel="stylesheet" href="{{ asset('css/admin/product.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/pesanan.css') }}">
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
                <div class="breadcrumb">Pesanan Management</div>
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

            <!-- Pesanan Controls -->
            <div class="product-controls">
                <input type="text" class="search-box" placeholder="Search pesanan...">
            </div>

            <!-- Scroll Indicator -->
            <div class="scroll-indicator">
                <i class="material-icons" style="font-size: 16px; vertical-align: middle;">swipe</i>
                Geser tabel ke kiri atau kanan untuk melihat kolom lainnya
            </div>

            <!-- Pesanan Table dengan Horizontal Scroll -->
            <div class="table-scroll-container">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>No Telpon</th>
                            <th>Alamat</th>
                            <th>Produk</th>
                            <th>Total Qty</th>
                            <th>Ekspedisi</th>
                            <th>Pembayaran</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($pesanan->isEmpty())
                            <tr class="text-center">
                                <td colspan="13">Belum ada pesanan</td>
                            </tr>
                        @else
                            @foreach ($pesanan as $y => $transaksi)
                                <tr class="align-middle">
                                    <td>{{ ($pesanan->currentPage() - 1) * $pesanan->perPage() + $loop->iteration }}</td>

                                    <td>
                                        <strong>{{ $transaksi->code_transaksi }}</strong>
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_pemesanan)->format('Y-m-d H:i') }}</td>

                                    <td>{{ $transaksi->nama_pelanggan }}</td>

                                    <td>{{ $transaksi->no_telp }}</td>

                                    <td>
                                        <div class="alamat-cell" title="{{ $transaksi->alamat }}">
                                            {{ $transaksi->alamat }}
                                        </div>
                                    </td>

                                    <td>
                                        @if ($transaksi->details && $transaksi->details->count() > 0)
                                            <div class="produk-cell">
                                                @foreach ($transaksi->details->take(2) as $detail)
                                                    <small>{{ $detail->nama_barang }}</small>
                                                @endforeach
                                                @if ($transaksi->details->count() > 2)
                                                    <small class="text-info">+{{ $transaksi->details->count() - 2 }}
                                                        lainnya</small>
                                                @endif
                                            </div>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>

                                    <td>{{ $transaksi->total_qty ?? '-' }}</td>

                                    <td>
                                        @switch($transaksi->ekspedisi)
                                            @case('jnt')
                                                J&T Express
                                            @break

                                            @case('jne')
                                                JNE Express
                                            @break

                                            @case('pos')
                                                Pos Indonesia
                                            @break

                                            @case('sicepat')
                                                SiCepat
                                            @break

                                            @default
                                                {{ $transaksi->ekspedisi }}
                                        @endswitch
                                    </td>

                                    <td>
                                        @switch($transaksi->metode_pembayaran)
                                            @case('cod')
                                                COD
                                            @break

                                            @case('dana')
                                                DANA
                                            @break

                                            @case('gopay')
                                                GoPay
                                            @break

                                            @case('transfer')
                                                Transfer
                                            @break

                                            @default
                                                {{ $transaksi->metode_pembayaran }}
                                        @endswitch
                                    </td>

                                    <td class="price-format">Rp.
                                        {{ number_format($transaksi->total_pembayaran, 0, ',', '.') }}</td>

                                    <td>
                                        <span class="badge {{ $transaksi->getStatusBadgeClass() }}">
                                            {{ $transaksi->getStatusText() }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="action-buttons">
                                            <div class="dropdown action-buttons" data-bs-container="body">
                                                <button class="action-btn edit-btn dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" data-bs-container="body" {{-- ✅ ini penting --}}
                                                    aria-expanded="false">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 200px;">
                                                    <li>
                                                        <h6 class="dropdown-header">Ubah Status Pesanan</h6>
                                                    </li>
                                                    <li>
                                                        <div class="px-3 py-2">
                                                            <form
                                                                action="{{ route('admin.update.status.pesanan', $transaksi->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <select name="status_pesanan"
                                                                    class="form-select form-select-sm"
                                                                    onchange="this.form.submit()">
                                                                    <option value="pending"
                                                                        {{ $transaksi->status_pesanan == 'pending' ? 'selected' : '' }}>
                                                                        Pending
                                                                    </option>
                                                                    <option value="dikonfirmasi"
                                                                        {{ $transaksi->status_pesanan == 'dikonfirmasi' ? 'selected' : '' }}>
                                                                        Dikonfirmasi
                                                                    </option>
                                                                    <option value="diproses"
                                                                        {{ $transaksi->status_pesanan == 'diproses' ? 'selected' : '' }}>
                                                                        Diproses
                                                                    </option>
                                                                    <option value="dikirim"
                                                                        {{ $transaksi->status_pesanan == 'dikirim' ? 'selected' : '' }}>
                                                                        Dikirim
                                                                    </option>
                                                                    <option value="selesai"
                                                                        {{ $transaksi->status_pesanan == 'selesai' ? 'selected' : '' }}>
                                                                        Selesai
                                                                    </option>
                                                                    <option value="dibatalkan"
                                                                        {{ $transaksi->status_pesanan == 'dibatalkan' ? 'selected' : '' }}>
                                                                        Dibatalkan
                                                                    </option>
                                                                </select>
                                                            </form>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <form action="{{ route('admin.delete.pesanan', $transaksi->id) }}"
                                                    method="post">
                                                    @method('delete')
                                                    @csrf
                                                    <button class="btn btn-danger">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination mt-5 d-flex flex-row justify-content-between align-items-center">
                <div class="showData">
                    Data ditampilkan {{ $pesanan->count() }} dari {{ $pesanan->total() }}
                </div>
                {{ $pesanan->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Detail Pesanan -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <!-- Detail content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


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
                    if (row.cells.length > 1) {
                        const kodeTransaksi = row.cells[1].textContent.toLowerCase();
                        const dateIn = row.cells[2].textContent.toLowerCase();
                        const namaPelanggan = row.cells[3].textContent.toLowerCase();
                        const noTelpon = row.cells[4].textContent.toLowerCase();
                        const Alamat = row.cells[5].textContent.toLowerCase();
                        const produk = row.cells[6].textContent.toLowerCase();
                        const Ekspedisi = row.cells[8].textContent.toLowerCase();
                        const Pembayaran = row.cells[9].textContent.toLowerCase();
                        const status = row.cells[10].textContent.toLowerCase();




                        if (kodeTransaksi.includes(searchTerm) ||
                            dateIn.includes(searchTerm) ||
                            namaPelanggan.includes(searchTerm) ||
                            noTelpon.includes(searchTerm) ||
                            Alamat.includes(searchTerm) ||
                            produk.includes(searchTerm) ||
                            Ekspedisi.includes(searchTerm) ||
                            Pembayaran.includes(searchTerm) ||
                            status.includes(searchTerm) || ) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });

            // Filter button
            const filterBtn = document.querySelector('.filter-btn');
            filterBtn.addEventListener('click', function() {
                const startDate = document.querySelectorAll('.date-input')[0].value;
                const endDate = document.querySelectorAll('.date-input')[1].value;

                if (!startDate || !endDate) {
                    alert('Please select both start and end dates');
                    return;
                }

                // Ubah ke object Date agar bisa dibandingkan
                const start = new Date(startDate);
                const end = new Date(endDate);

                tableRows.forEach(row => {
                    const dateInText = row.cells[1].textContent.trim(); // kolom Date In
                    const rowDate = new Date(dateInText);

                    if (rowDate >= start && rowDate <= end) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
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

            // Smooth scroll untuk table container
            const tableContainer = document.querySelector('.table-scroll-container');
            if (tableContainer) {
                tableContainer.addEventListener('wheel', function(e) {
                    if (e.deltaY !== 0) {
                        e.preventDefault();
                        this.scrollLeft += e.deltaY;
                    }
                });
            }
        });

        // Function untuk view detail pesanan
        function viewDetail(transaksiId) {
            // Anda bisa implement AJAX call untuk load detail atau redirect ke halaman detail
            alert('Detail pesanan untuk ID: ' + transaksiId);
            // Atau bisa redirect ke halaman detail:
            // window.location.href = '/admin/pesanan/' + transaksiId;
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.dropdown').forEach(function(el) {
                let menu = el.querySelector('.dropdown-menu');

                // simpan parent asli
                let originalParent = el;

                el.addEventListener('show.bs.dropdown', function(e) {
                    if (menu) {
                        document.body.appendChild(menu); // pindahkan ke body
                        let rect = el.getBoundingClientRect();

                        menu.style.display = 'block';
                        menu.style.position = 'absolute';
                        menu.style.top = (rect.bottom + window.scrollY) + 'px';
                        menu.style.left = (rect.left + window.scrollX) + 'px';
                        menu.style.minWidth = rect.width + 'px';
                        menu.style.zIndex = 9999;
                    }
                });

                el.addEventListener('hidden.bs.dropdown', function(e) {
                    if (menu) {
                        originalParent.appendChild(menu); // kembalikan ke parent
                        menu.removeAttribute("style"); // reset style
                    }
                });
            });
        });
    </script>
@endsection
