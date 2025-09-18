$(document).ready(function () {
    console.log('Custom.js loaded - Final Fix Version');

    // Function untuk format Rupiah
    function formatRupiah(angka) {
        return 'Rp. ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Function untuk mengambil nilai numeric - DIPERBAIKI
    function getNumericValue(value) {
        if (typeof value === 'string') {
            let cleanValue = value.replace(/[^0-9]/g, '');
            return parseInt(cleanValue) || 0;
        }
        return parseInt(value) || 0;
    }

    // Function untuk menghitung total per item cart - DIPERBAIKI
    function hitungTotalPerItem(cartItem) {
        const hargaInput = cartItem.find('input[name="harga"]');
        const qtyInput = cartItem.find('input[name="stok"]');
        const totalInput = cartItem.find('input[name="total"]');

        // PENTING: Ambil harga mentah (angka asli tanpa format)
        const harga = parseInt(hargaInput.val()) || 0; // Jangan gunakan getNumericValue untuk harga
        const qty = parseInt(qtyInput.val()) || 1;
        const total = harga * qty;

        // Update field total dengan format Rupiah
        totalInput.val(formatRupiah(total));

        console.log('Item calculation:', {
            harga: harga,
            qty: qty,
            total: total,
            formatted: formatRupiah(total)
        });

        return total;
    }

    // Function untuk menghitung subtotal keseluruhan
    function hitungSubtotalKeseluruhan() {
        let subtotalKeseluruhan = 0;

        $('.cart-item').each(function () {
            const totalItem = hitungTotalPerItem($(this));
            subtotalKeseluruhan += totalItem;
        });

        // Update tampilan subtotal dan grand total
        $('#subtotal-keseluruhan').text(formatRupiah(subtotalKeseluruhan));
        $('#grandtotal').text(formatRupiah(subtotalKeseluruhan));
        $('#totalpembayaran').val(subtotalKeseluruhan);

        console.log('Subtotal keseluruhan:', subtotalKeseluruhan);
        return subtotalKeseluruhan;
    }

    // Event handler untuk tombol PLUS - DIPERBAIKI
    $(document).on('click', '.plus', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation(); // Mencegah double-click

        const cartItem = $(this).closest('.cart-item');
        const qtyInput = cartItem.find('input[name="stok"]');
        let currentQty = parseInt(qtyInput.val()) || 1;

        console.log('Plus clicked - Current qty:', currentQty);

        if (currentQty < 999) {
            const newQty = currentQty + 1;
            qtyInput.val(newQty);
            console.log('Plus - New qty:', newQty);

            // Delay sedikit untuk memastikan DOM ter-update
            setTimeout(function () {
                hitungSubtotalKeseluruhan();
            }, 50);
        }

        return false; // Mencegah form submission
    });

    // Event handler untuk tombol MINUS - DIPERBAIKI
    $(document).on('click', '.minus', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation(); // Mencegah double-click

        const cartItem = $(this).closest('.cart-item');
        const qtyInput = cartItem.find('input[name="stok"]');
        let currentQty = parseInt(qtyInput.val()) || 1;

        console.log('Minus clicked - Current qty:', currentQty);

        if (currentQty > 1) {
            const newQty = currentQty - 1;
            qtyInput.val(newQty);
            console.log('Minus - New qty:', newQty);

            // Delay sedikit untuk memastikan DOM ter-update
            setTimeout(function () {
                hitungSubtotalKeseluruhan();
            }, 50);
        }

        return false; // Mencegah form submission
    });

    // Event handler untuk perubahan quantity secara manual
    $(document).on('input change', 'input[name="stok"]', function (e) {
        // Jangan jalankan jika dipicu oleh tombol plus/minus
        if (e.originalEvent && e.originalEvent.isTrusted === false) {
            return;
        }

        let qty = parseInt($(this).val()) || 1;
        if (qty < 1) qty = 1;
        if (qty > 999) qty = 999;
        $(this).val(qty);

        console.log('Manual qty change:', qty);
        hitungSubtotalKeseluruhan();
    });

    // Event handler untuk form submission checkout individual
    $('form[action*="checkout.proses"]').on('submit', function (e) {
        const cartItem = $(this).closest('.cart-item');
        const harga = parseInt(cartItem.find('input[name="harga"]').val()) || 0;
        const stok = parseInt(cartItem.find('input[name="stok"]').val()) || 1;
        const total = harga * stok;

        // Update hidden input total dengan angka murni (tanpa format)
        cartItem.find('input[name="total"]').val(total);

        console.log('Form submitted:', {
            harga: harga,
            stok: stok,
            total: total
        });
    });

    // Inisialisasi saat halaman dimuat
    function initializeCart() {
        console.log('Initializing cart...');
        console.log('Found cart items:', $('.cart-item').length);

        // Debug: Log data setiap cart item
        $('.cart-item').each(function (index) {
            const harga = $(this).find('input[name="harga"]').val();
            const stok = $(this).find('input[name="stok"]').val();
            console.log(`Item ${index + 1}:`, {
                harga: harga,
                stok: stok,
                hargaType: typeof harga,
                stokType: typeof stok
            });
        });

        // Hitung total untuk setiap item yang ada
        $('.cart-item').each(function () {
            hitungTotalPerItem($(this));
        });

        // Hitung subtotal keseluruhan
        hitungSubtotalKeseluruhan();

        console.log('Cart initialized');
    }

    // Jalankan inisialisasi dengan delay
    setTimeout(function () {
        initializeCart();
    }, 100);

    // Remove readonly dari input stok untuk memungkinkan edit manual
    $('input[name="stok"]').removeAttr('readonly');

    // Debug: Log semua cart items
    console.log('Total cart items found:', $('.cart-item').length);

    // TAMBAHAN: Function untuk checkout (jika digunakan di halaman checkout)
    function hitungTotalKeseluruhan() {
        let subtotalKeseluruhan = getNumericValue($('#subtotal').val()) || 0;
        let ongkir = getNumericValue($('#ongkir').val()) || 0;
        let layanan = getNumericValue($('#layanan').val()) || 0;
        let grandTotal = subtotalKeseluruhan + ongkir + layanan;

        console.log('Checkout calculation:', {
            subtotal: subtotalKeseluruhan,
            ongkir: ongkir,
            layanan: layanan,
            grandTotal: grandTotal
        });

        $('#totalpembayaran').val(formatRupiah(grandTotal));
        return grandTotal;
    }

    // Event handler untuk ekspedisi (jika di halaman checkout)
    $(document).on('change', '#ekspedisi', function () {
        let ekspedisi = $(this).val();
        let ongkir = 0;

        switch (ekspedisi) {
            case 'jnt': ongkir = 15000; break;
            case 'jne': ongkir = 20000; break;
            case 'pos': ongkir = 12000; break;
            case 'sicepat': ongkir = 18000; break;
            default: ongkir = 0;
        }

        console.log('Ekspedisi changed:', ekspedisi, 'Ongkir:', ongkir);
        $('#ongkir').val(formatRupiah(ongkir));
        hitungTotalKeseluruhan();
    });

    // Event handler untuk metode pembayaran (jika di halaman checkout)
    $(document).on('change', '#metode', function () {
        let metode = $(this).val();
        let biayaLayanan = 0;

        switch (metode) {
            case 'cod': biayaLayanan = 1000; break;
            case 'dana': biayaLayanan = 1500; break;
            case 'ovo': biayaLayanan = 1200; break;
            case 'gopay': biayaLayanan = 1000; break;
            case 'transfer': biayaLayanan = 0; break;
            default: biayaLayanan = 0;
        }

        console.log('Metode changed:', metode, 'Biaya layanan:', biayaLayanan);
        $('#layanan').val(formatRupiah(biayaLayanan));
        hitungTotalKeseluruhan();
    });

    // Inisialisasi untuk halaman checkout
    function initializeCheckout() {
        if ($('#subtotal').length > 0) {
            let currentSubtotal = getNumericValue($('#subtotal').val());
            if (currentSubtotal > 0) {
                $('#subtotal').val(formatRupiah(currentSubtotal));
            }
            $('#ongkir').val(formatRupiah(0));
            $('#layanan').val(formatRupiah(0));
            hitungTotalKeseluruhan();
        }
    }

    // Jalankan inisialisasi checkout
    initializeCheckout();
});

// // Tambahkan di custom.js yang sudah ada
// $(document).on('change', '#ekspedisi', function () {
//     let ekspedisi = $(this).val();
//     let ongkir = 0;

//     switch (ekspedisi) {
//         case 'jnt': ongkir = 15000; break;
//         case 'jne': ongkir = 20000; break;
//         case 'pos': ongkir = 12000; break;
//         case 'sicepat': ongkir = 18000; break;
//         default: ongkir = 0;
//     }

//     $('#ongkir').val('Rp. ' + ongkir.toLocaleString('id-ID'));
//     hitungTotalKeseluruhan();
// });

// $(document).on('change', '#metode', function () {
//     let metode = $(this).val();
//     let biayaLayanan = 0;

//     switch (metode) {
//         case 'cod': biayaLayanan = 1000; break;
//         case 'dana': biayaLayanan = 1500; break;
//         case 'gopay': biayaLayanan = 1000; break;
//         case 'transfer': biayaLayanan = 0; break;
//         default: biayaLayanan = 0;
//     }

//     $('#layanan').val('Rp. ' + biayaLayanan.toLocaleString('id-ID'));
//     hitungTotalKeseluruhan();
// });

// // Function untuk hitung total checkout
// function hitungTotalKeseluruhan() {
//     let subtotal = getNumericValue($('#subtotal').val()) || 0;
//     let ongkir = getNumericValue($('#ongkir').val()) || 0;
//     let layanan = getNumericValue($('#layanan').val()) || 0;
//     let grandTotal = subtotal + ongkir + layanan;

//     $('#totalpembayaran').val('Rp. ' + grandTotal.toLocaleString('id-ID'));
//     return grandTotal;
// }