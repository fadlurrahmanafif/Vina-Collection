$(document).ready(function () {
    var nilai = $('#qty').val();
    var harga = $('#harga').val();
    var total = $('#total').val();
    var subtotal = parseInt(nilai) * parseInt(harga);

    if (nilai > 0) {
        $('#total').val(subtotal)
    }

    if (nilai > 0) {
        $('#minus').prop('disabled', false);
    }

    $('#plus').click(function (e) {
        var nilai = $('#qty').val();
        var penjumlahan = parseInt(nilai) + parseInt(1);
        $('#qty').val(penjumlahan);
        var harga = $('#harga').val();
        var subtotal = parseInt(penjumlahan) * parseInt(harga);
        $('#total').val(subtotal);

        if (penjumlahan > 0) {
            $('#minus').prop('disabled', false);
        }
    });
    $('#minus').click(function (e) {
        var nilai = $('#qty').val();
        var penjumlahan = parseInt(nilai) - parseInt(1);
        $('#qty').val(penjumlahan);
        var harga = $('#harga').val();
        var subtotal = parseInt(penjumlahan) * parseInt(harga);
        $('#total').val(subtotal);

        if (penjumlahan == 1) {
            $('#minus').prop('disabled', true);
        }
    });
});

$(document).ready(function () {
    // Inisialisasi nilai
    let hargaBarang = parseInt($('#harga').val()) || 0;
    let qtyBarang = parseInt($('#qty').val()) || 1;

    // Hitung subtotal awal
    hitungSubtotal();
    hitungTotalPembayaran();

    // Function untuk format Rupiah
    function formatRupiah(angka) {
        return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Function untuk mengambil nilai numeric
    function getNumericValue(value) {
        if (typeof value === 'string') {
            return parseInt(value.replace(/[^0-9]/g, '')) || 0;
        }
        return parseInt(value) || 0;
    }

    // Function hitung subtotal
    function hitungSubtotal() {
        let harga = getNumericValue($('#harga').val());
        let qty = getNumericValue($('#qty').val());
        let subtotal = harga * qty;

        $('#total').val(formatRupiah(subtotal));
        $('#subtotal').val(formatRupiah(subtotal));

        return subtotal;
    }

    // Function hitung total pembayaran
    function hitungTotalPembayaran() {
        let subtotal = getNumericValue($('#subtotal').val());
        let ongkir = getNumericValue($('#ongkir').val());
        let layanan = getNumericValue($('#layanan').val());

        let total = subtotal + ongkir + layanan;
        $('#totalpembayaran').val(formatRupiah(total));
    }

    // Event listener untuk quantity
    $('#qty').on('input change', function () {
        hitungSubtotal();
        hitungTotalPembayaran();
    });

    // Event listener untuk harga (jika bisa diubah)
    $('#harga').on('input change', function () {
        hitungSubtotal();
        hitungTotalPembayaran();
    });

    // Event listener untuk ekspedisi
    $('#ekspedisi').change(function () {
        let ekspedisi = $(this).val();
        let ongkir = 0;

        switch (ekspedisi) {
            case 'jnt':
                ongkir = 15000;
                break;
            case 'jne':
                ongkir = 20000;
                break;
            default:
                ongkir = 0;
        }

        $('#ongkir').val(formatRupiah(ongkir));
        hitungTotalPembayaran();
    });

    // Event listener untuk metode pembayaran
    $('#metode').change(function () {
        let metode = $(this).val();
        let biayaLayanan = 0;

        switch (metode) {
            case 'cod':
                biayaLayanan = 1000;
                break;
            case 'dana':
                biayaLayanan = 1500;
                break;
            default:
                biayaLayanan = 0;
        }

        $('#layanan').val(formatRupiah(biayaLayanan));
        hitungTotalPembayaran();
    });
});