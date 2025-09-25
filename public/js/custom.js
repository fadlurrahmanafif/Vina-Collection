$(document).ready(function () {
    console.log('Custom.js loaded - Fixed Total Calculation Version');

    // Function untuk format Rupiah
    function formatRupiah(angka) {
        return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Function untuk mengambil nilai numeric
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
        const totalDisplay = cartItem.find('.total-text'); // Elemen yang menampilkan total

        // Ambil harga mentah (angka asli tanpa format)
        const harga = parseInt(hargaInput.val()) || 0;
        const qty = parseInt(qtyInput.val()) || 1;
        const total = harga * qty;

        // Update field total dengan format Rupiah untuk tampilan
        if (totalDisplay.length > 0) {
            totalDisplay.text(formatRupiah(total));
        }

        // Update hidden input total dengan angka murni
        if (totalInput.length > 0) {
            totalInput.val(total);
        }

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
        if ($('#subtotal-keseluruhan').length > 0) {
            $('#subtotal-keseluruhan').text(formatRupiah(subtotalKeseluruhan));
        }
        if ($('#grandtotal').length > 0) {
            $('#grandtotal').text(formatRupiah(subtotalKeseluruhan));
        }
        if ($('#totalpembayaran').length > 0) {
            $('#totalpembayaran').val(subtotalKeseluruhan);
        }

        console.log('Subtotal keseluruhan:', subtotalKeseluruhan);

        // Update dynamic inputs untuk form checkout
        updateDynamicCheckoutInputs();

        return subtotalKeseluruhan;
    }

    // Function untuk update dynamic inputs di form checkout - BARU
    function updateDynamicCheckoutInputs() {
        const dynamicInputsDiv = $('#dynamic-inputs');
        if (dynamicInputsDiv.length === 0) return;

        dynamicInputsDiv.empty();

        $('.cart-item').each(function () {
            const cartItem = $(this);
            const itemId = cartItem.data('id');
            const qty = parseInt(cartItem.find('input[name="stok"]').val()) || 1;
            const harga = parseInt(cartItem.find('input[name="harga"]').val()) || 0;
            const total = qty * harga;

            // Buat hidden inputs untuk checkout
            const inputs = `
                <input type="hidden" name="items[${itemId}][id_barang]" value="${itemId}">
                <input type="hidden" name="items[${itemId}][stok]" value="${qty}">
                <input type="hidden" name="items[${itemId}][harga]" value="${harga}">
                <input type="hidden" name="items[${itemId}][total]" value="${total}">
            `;

            dynamicInputsDiv.append(inputs);
        });

        console.log('Dynamic checkout inputs updated');
    }

    function updateCartQty(cartItem) {
        const idBarang = cartItem.data('id');
        const qty = parseInt(cartItem.find('input[name="stok"]').val()) || 1;

        $.ajax({
            url: `/cart/update/${idBarang}`,
            type: "PUT",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                stok: qty
            },
            success: function (res) {
                console.log("Cart updated:", res);
            },
            error: function (xhr) {
                console.error("Cart update failed:", xhr.responseText);
            }
        });
    }

    // Event handler untuk tombol PLUS - DIPERBAIKI
    $(document).on('click', '.plus', function (e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('Plus button clicked');

        const cartItem = $(this).closest('.cart-item');
        const qtyInput = cartItem.find('input[name="stok"]');
        let currentQty = parseInt(qtyInput.val()) || 1;

        if (currentQty < 999) {
            const newQty = currentQty + 1;
            qtyInput.val(newQty);
            console.log('Plus - New qty:', newQty);

            // Langsung hitung ulang total
            hitungSubtotalKeseluruhan();

            updateCartQty(cartItem);
        }

        return false;
    });

    // Event handler untuk tombol MINUS - DIPERBAIKI
    $(document).on('click', '.minus', function (e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('Minus button clicked');

        const cartItem = $(this).closest('.cart-item');
        const qtyInput = cartItem.find('input[name="stok"]');
        let currentQty = parseInt(qtyInput.val()) || 1;

        if (currentQty > 1) {
            const newQty = currentQty - 1;
            qtyInput.val(newQty);
            console.log('Minus - New qty:', newQty);

            // Langsung hitung ulang total
            hitungSubtotalKeseluruhan();

            updateCartQty(cartItem);
        }

        return false;
    });

    // Event handler untuk perubahan quantity secara manual
    $(document).on('input change', 'input[name="stok"]', function (e) {
        let qty = parseInt($(this).val()) || 1;
        if (qty < 1) qty = 1;
        if (qty > 999) qty = 999;
        $(this).val(qty);

        console.log('Manual qty change:', qty);
        hitungSubtotalKeseluruhan();

        updateCartQty($(this).closest('.cart-item'));
    });

    // Event handler untuk form submission checkout - DIPERBAIKI
    $(document).on('submit', '#checkout-form', function (e) {
        console.log('Checkout form submitting...');

        // Pastikan semua data sudah terupdate
        updateDynamicCheckoutInputs();

        // Log data yang akan dikirim
        const formData = $(this).serializeArray();
        console.log('Form data being sent:', formData);

        return true; // Lanjutkan submit
    });

    // Event handler untuk form submission checkout individual (jika ada)
    $('form[action*="checkout.proses"]').on('submit', function (e) {
        const cartItem = $(this).closest('.cart-item');
        if (cartItem.length > 0) {
            const harga = parseInt(cartItem.find('input[name="harga"]').val()) || 0;
            const stok = parseInt(cartItem.find('input[name="stok"]').val()) || 1;
            const total = harga * stok;

            // Update hidden input total dengan angka murni (tanpa format)
            cartItem.find('input[name="total"]').val(total);

            console.log('Individual form submitted:', {
                harga: harga,
                stok: stok,
                total: total
            });
        }
    });

    // Inisialisasi saat halaman dimuat
    function initializeCart() {
        console.log('Initializing cart...');
        console.log('Found cart items:', $('.cart-item').length);

        // Debug: Log data setiap cart item
        $('.cart-item').each(function (index) {
            const harga = $(this).find('input[name="harga"]').val();
            const stok = $(this).find('input[name="stok"]').val();
            const hasPlus = $(this).find('.plus').length;
            const hasMinus = $(this).find('.minus').length;
            const hasTotalText = $(this).find('.total-text').length;

            console.log(`Item ${index + 1}:`, {
                harga: harga,
                stok: stok,
                hargaType: typeof harga,
                stokType: typeof stok,
                hasPlus: hasPlus,
                hasMinus: hasMinus,
                hasTotalText: hasTotalText
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

    // CHECKOUT FUNCTIONS - untuk halaman checkout
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