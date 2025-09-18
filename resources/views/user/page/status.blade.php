@extends('user.layout.master')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Status Pesanan Anda</h2>

                @if (isset($transaksiUser) && count($transaksiUser) > 0)
                    @foreach ($transaksiUser as $index => $transaksi)
                        <div class="card mb-4 {{ $transaksi->status_pesanan === 'dibatalkan' ? 'border-danger' : '' }}">
                            <div class="card-header {{ $transaksi->status_pesanan === 'dibatalkan' ? 'bg-light' : '' }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="mb-0">Kode Pesanan: {{ $transaksi->code_transaksi }}</h5>
                                        <small class="text-muted">
                                            Tanggal:
                                            {{ $transaksi->tanggal_pemesanan ? \Carbon\Carbon::parse($transaksi->tanggal_pemesanan)->format('d F Y, H:i') : \Carbon\Carbon::parse($transaksi->created_at)->format('d F Y, H:i') }}
                                        </small>
                                        {{-- Tambahan info untuk pesanan dibatalkan --}}
                                        @if ($transaksi->status_pesanan === 'dibatalkan')
                                            <br><small class="text-danger"><i class="fas fa-info-circle"></i> Pesanan ini
                                                telah dibatalkan dan akan dihapus oleh admin</small>
                                        @endif
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <span class="badge {{ $transaksi->getStatusBadgeClass() }} px-3 py-2">
                                            {{ $transaksi->getStatusText() }}
                                        </span>

                                        {{-- Tombol hanya muncul untuk pesanan yang tidak dibatalkan --}}
                                        @if ($transaksi->status_pesanan !== 'dibatalkan')
                                            <!-- Tombol Pesanan Diterima - Hanya muncul jika status = selesai -->
                                            @if ($transaksi->status_pesanan === 'selesai')
                                                <form method="POST" action="{{ route('konfirmasi.pesanan.diterima') }}"
                                                    style="display: inline;" class="ms-2">
                                                    @csrf
                                                    <input type="hidden" name="code_transaksi"
                                                        value="{{ $transaksi->code_transaksi }}">
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('Yakin pesanan sudah diterima? Data pesanan akan dihapus setelah dikonfirmasi.')">
                                                        <i class="fas fa-check me-1"></i>Pesanan Diterima
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Tombol Batalkan Pesanan - Hanya muncul jika status = pending atau dikonfirmasi -->
                                            @if (in_array($transaksi->status_pesanan, ['pending', 'dikonfirmasi']))
                                                <form method="POST" action="{{ route('batalkan.pesanan') }}"
                                                    style="display: inline;" class="ms-2">
                                                    @csrf
                                                    <input type="hidden" name="code_transaksi"
                                                        value="{{ $transaksi->code_transaksi }}">
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin ingin membatalkan pesanan ini? Stok akan dikembalikan dan admin akan menghapus data ini.')">
                                                        <i class="fas fa-times me-1"></i>Batalkan Pesanan
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div
                                class="card-body {{ $transaksi->status_pesanan === 'dibatalkan' ? 'bg-light opacity-75' : '' }}">
                                <!-- Detail Pengiriman -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-user me-2"></i>Detail Penerima</h6>
                                        <p class="mb-1"><strong>Nama : {{ $transaksi->nama_pelanggan }}</strong></p>
                                        <p class="mb-1">Alamat : {{ $transaksi->alamat }}</p>
                                        <p class="mb-0">No.Telp : {{ $transaksi->no_telp }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-shipping-fast me-2"></i>Detail Pengiriman</h6>
                                        <p class="mb-1">
                                            <strong>
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
                                            </strong>
                                        </p>
                                        <p class="mb-0">Metode:
                                            @if ($transaksi->metode_pembayaran)
                                                @switch($transaksi->metode_pembayaran)
                                                    @case('cod')
                                                        Cash On Delivery
                                                    @break

                                                    @case('dana')
                                                        DANA
                                                    @break

                                                    @case('gopay')
                                                        GoPay
                                                    @break

                                                    @case('transfer')
                                                        Transfer Bank
                                                    @break

                                                    @default
                                                        {{ $transaksi->metode_pembayaran }}
                                                @endswitch
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Daftar Item -->
                                <h6><i class="fas fa-box me-2"></i>Item yang Dipesan ({{ $transaksi->total_qty }} item)
                                </h6>
                                @if ($transaksi->details && count($transaksi->details) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Foto</th>
                                                    <th>Nama Barang</th>
                                                    <th>Harga Satuan</th>
                                                    <th>Jumlah</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($transaksi->details as $detail)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>
                                                            @if ($detail->product && $detail->product->foto)
                                                                <img src="{{ asset('storage/produk/' . $detail->product->foto) }}"
                                                                    alt="{{ $detail->nama_barang }}"
                                                                    style="width: 50px; height: 50px; object-fit: cover;"
                                                                    class="rounded">
                                                            @else
                                                                <div style="width: 50px; height: 50px;"
                                                                    class="bg-light rounded d-flex align-items-center justify-content-center">
                                                                    <i class="fas fa-image text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>{{ $detail->nama_barang ?: ($detail->product ? $detail->product->nama_produk : 'Produk tidak ditemukan') }}
                                                        </td>
                                                        <td>Rp
                                                            {{ number_format($detail->harga_satuan ?: ($detail->product ? $detail->product->harga : 0), 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ $detail->stok }} pcs</td>
                                                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>Detail produk tidak tersedia
                                    </div>
                                @endif

                                <!-- Total Pembayaran -->
                                <div class="row mt-3">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span>Subtotal:</span>
                                                    <span>Rp
                                                        {{ number_format($transaksi->subtotal ?: $transaksi->total_harga, 0, ',', '.') }}</span>
                                                </div>
                                                @if ($transaksi->ongkir)
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Ongkir:</span>
                                                        <span>Rp
                                                            {{ number_format($transaksi->ongkir, 0, ',', '.') }}</span>
                                                    </div>
                                                @endif
                                                @if ($transaksi->biaya_layanan)
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>Biaya Layanan:</span>
                                                        <span>Rp
                                                            {{ number_format($transaksi->biaya_layanan, 0, ',', '.') }}</span>
                                                    </div>
                                                @endif
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <strong>Total:</strong>
                                                    <strong
                                                        class="{{ $transaksi->status_pesanan === 'dibatalkan' ? 'text-muted text-decoration-line-through' : 'text-primary' }}">
                                                        Rp
                                                        {{ number_format($transaksi->total_pembayaran ?: $transaksi->total_harga, 0, ',', '.') }}
                                                    </strong>
                                                </div>
                                                @if ($transaksi->status_pesanan === 'dibatalkan')
                                                    <small class="text-danger">Pesanan dibatalkan</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada pesanan</h5>
                            <p class="text-muted">Anda belum memiliki pesanan apapun saat ini.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
