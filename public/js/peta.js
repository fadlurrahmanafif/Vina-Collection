// peta.js - Map Integration untuk Checkout
let map;
let marker;
let userLocation = null;
let isMapInitialized = false;

// Configuration
const MAP_CONFIG = {
    defaultLat: -6.2088,  // Jakarta
    defaultLng: 106.8456,
    defaultZoom: 13,
    searchZoom: 16,
    maxSuggestions: 5
};

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function () {
    initializeMap();
    setupEventListeners();
    setupShippingCostCalculator();
});

function validateRequiredLocation() {
    const lat = document.getElementById('latitude').value;
    const lng = document.getElementById('longitude').value;

    if (!lat || !lng) {
        showNotification('Lokasi pengiriman harus ditentukan pada peta!', 'error');
        return false;
    }
    return true;
}

/**
 * Initialize Leaflet map
 */
function initializeMap() {
    try {
        // Create map instance
        map = L.map('map').setView([MAP_CONFIG.defaultLat, MAP_CONFIG.defaultLng], MAP_CONFIG.defaultZoom);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Add click event to map
        map.on('click', function (e) {
            addMarkerToMap(e.latlng.lat, e.latlng.lng);
            reverseGeocode(e.latlng.lat, e.latlng.lng);
        });

        isMapInitialized = true;
        console.log('Map initialized successfully');
    } catch (error) {
        console.error('Error initializing map:', error);
        showNotification('Error initializing map', 'error');
    }
}

/**
 * Setup all event listeners
 */
function setupEventListeners() {
    // Get current location button
    const getCurrentLocationBtn = document.getElementById('getCurrentLocationBtn');
    if (getCurrentLocationBtn) {
        getCurrentLocationBtn.addEventListener('click', getCurrentLocation);
    }

    // Clear map button
    const clearMapBtn = document.getElementById('clearMapBtn');
    if (clearMapBtn) {
        clearMapBtn.addEventListener('click', clearMapAndForm);
    }

    // Search address button
    const searchAddressBtn = document.getElementById('searchAddressBtn');
    if (searchAddressBtn) {
        searchAddressBtn.addEventListener('click', searchAddress);
    }

    // Address input events
    const addressInput = document.getElementById('alamat_anda');
    if (addressInput) {
        // Search on Enter key
        addressInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchAddress();
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#alamat_anda') && !e.target.closest('#addressSuggestions')) {
                hideSuggestions();
            }
        });
    }

    // Form submission
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', handleFormSubmission);
    }
}

/**
 * Get user's current location using HTML5 Geolocation API
 */
function getCurrentLocation() {
    if (!navigator.geolocation) {
        showNotification('Geolokasi tidak didukung oleh browser ini', 'error');
        return;
    }

    showLoading(true);

    const options = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 0
    };

    navigator.geolocation.getCurrentPosition(
        function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            userLocation = { lat, lng };

            // Center map to user location
            map.setView([lat, lng], MAP_CONFIG.searchZoom);
            addMarkerToMap(lat, lng);
            reverseGeocode(lat, lng);

            showLoading(false);
            showNotification('Lokasi berhasil ditemukan!', 'success');
        },
        function (error) {
            showLoading(false);
            handleGeolocationError(error);
        },
        options
    );
}

/**
 * Handle geolocation errors
 */
function handleGeolocationError(error) {
    let errorMsg = 'Tidak dapat mengakses lokasi. ';

    switch (error.code) {
        case error.PERMISSION_DENIED:
            errorMsg += 'Izin lokasi ditolak. Silakan izinkan akses lokasi di browser.';
            break;
        case error.POSITION_UNAVAILABLE:
            errorMsg += 'Informasi lokasi tidak tersedia.';
            break;
        case error.TIMEOUT:
            errorMsg += 'Permintaan lokasi timeout. Silakan coba lagi.';
            break;
        default:
            errorMsg += 'Terjadi kesalahan yang tidak diketahui.';
            break;
    }

    showNotification(errorMsg, 'error');
}

/**
 * Add marker to map
 */
function addMarkerToMap(lat, lng) {
    // Remove existing marker
    if (marker) {
        map.removeLayer(marker);
    }

    // Create new marker
    marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);

    // Update coordinates when marker is dragged
    marker.on('dragend', function (e) {
        const position = marker.getLatLng();
        reverseGeocode(position.lat, position.lng);
    });

    // Update location info and hidden form fields
    updateLocationInfo(lat, lng);
    updateCoordinateFields(lat, lng);
}

/**
 * Search address using Nominatim API
 */
function searchAddress() {
    const addressInput = document.getElementById('alamat_anda');
    const address = addressInput.value.trim();

    if (!address) {
        showNotification('Silakan masukkan alamat terlebih dahulu', 'warning');
        return;
    }

    showLoading(true);
    forwardGeocode(address);
}

/**
 * Forward geocoding - convert address to coordinates
 */
function forwardGeocode(address) {
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=${MAP_CONFIG.maxSuggestions}&countrycodes=ID`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            showLoading(false);

            if (data && data.length > 0) {
                showAddressSuggestions(data);
            } else {
                showNotification('Alamat tidak ditemukan. Silakan coba dengan kata kunci lain.', 'warning');
            }
        })
        .catch(error => {
            showLoading(false);
            console.error('Geocoding error:', error);
            showNotification('Terjadi kesalahan saat mencari alamat. Silakan coba lagi.', 'error');
        });
}

/**
 * Reverse geocoding - convert coordinates to address
 */
function reverseGeocode(lat, lng) {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                const addressInput = document.getElementById('alamat_anda');
                addressInput.value = data.display_name;

                const detectedAddress = document.getElementById('detectedAddress');
                if (detectedAddress) {
                    detectedAddress.textContent = data.display_name;
                }

                showLocationInfo(true);
            }
        })
        .catch(error => {
            console.error('Reverse geocoding error:', error);
        });
}

/**
 * Show address suggestions dropdown
 */
function showAddressSuggestions(suggestions) {
    const suggestionsContainer = document.getElementById('addressSuggestions');
    if (!suggestionsContainer) return;

    suggestionsContainer.innerHTML = '';

    suggestions.forEach(item => {
        const div = document.createElement('div');
        div.className = 'suggestion-item';
        div.innerHTML = `
            <div class="fw-bold">${item.display_name.split(',')[0]}</div>
            <small class="text-muted">${item.display_name}</small>
        `;
        div.onclick = () => selectAddressSuggestion(item);
        suggestionsContainer.appendChild(div);
    });

    suggestionsContainer.style.display = 'block';
}

/**
 * Select address from suggestions
 */
function selectAddressSuggestion(item) {
    const lat = parseFloat(item.lat);
    const lng = parseFloat(item.lon);

    // Update input field
    const addressInput = document.getElementById('alamat_anda');
    addressInput.value = item.display_name;

    // Hide suggestions
    hideSuggestions();

    // Update map
    map.setView([lat, lng], MAP_CONFIG.searchZoom);
    addMarkerToMap(lat, lng);

    // Update location info
    const detectedAddress = document.getElementById('detectedAddress');
    if (detectedAddress) {
        detectedAddress.textContent = item.display_name;
    }

    showLocationInfo(true);
    showNotification('Alamat berhasil dipilih!', 'success');
}

/**
 * Hide address suggestions
 */
function hideSuggestions() {
    const suggestionsContainer = document.getElementById('addressSuggestions');
    if (suggestionsContainer) {
        suggestionsContainer.style.display = 'none';
    }
}

/**
 * Clear map and form
 */
function clearMapAndForm() {
    // Remove marker
    if (marker) {
        map.removeLayer(marker);
        marker = null;
    }

    // Clear form fields
    const addressInput = document.getElementById('alamat_anda');
    if (addressInput) {
        addressInput.value = '';
    }

    // Clear coordinate fields
    updateCoordinateFields('', '');

    // Hide location info and suggestions
    showLocationInfo(false);
    hideSuggestions();

    // Reset map view
    map.setView([MAP_CONFIG.defaultLat, MAP_CONFIG.defaultLng], MAP_CONFIG.defaultZoom);

    showNotification('Peta dan form berhasil dibersihkan', 'info');
}

/**
 * Update location info display
 */
function updateLocationInfo(lat, lng) {
    const coordinatesEl = document.getElementById('coordinates');
    if (coordinatesEl) {
        coordinatesEl.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    }
}

/**
 * Update hidden coordinate form fields
 */
function updateCoordinateFields(lat, lng) {
    const latField = document.getElementById('latitude');
    const lngField = document.getElementById('longitude');

    if (latField) latField.value = lat;
    if (lngField) lngField.value = lng;
}

/**
 * Show/hide location info section
 */
function showLocationInfo(show) {
    const locationInfo = document.getElementById('locationInfo');
    if (locationInfo) {
        locationInfo.style.display = show ? 'block' : 'none';
    }
}

/**
 * Show/hide loading spinner
 */
function showLoading(show) {
    const loadingSpinner = document.getElementById('loadingSpinner');
    if (loadingSpinner) {
        loadingSpinner.style.display = show ? 'block' : 'none';
    }
}

/**
 * Setup shipping cost calculator
 */
function setupShippingCostCalculator() {
    const ekspedisiSelect = document.getElementById('ekspedisi');
    if (!ekspedisiSelect) return;

    ekspedisiSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const cost = selectedOption.dataset.cost || 0;

        updateShippingCost(parseInt(cost));
    });
}

/**
 * Update shipping cost and total payment
 */
function updateShippingCost(shippingCost) {
    const ongkirField = document.getElementById('ongkir');
    const totalField = document.getElementById('totalpembayaran');

    if (ongkirField) {
        ongkirField.value = `Rp. ${shippingCost.toLocaleString('id-ID')}`;
    }

    if (totalField && window.checkoutData) {
        const total = window.checkoutData.subtotal + shippingCost;
        totalField.value = `Rp. ${total.toLocaleString('id-ID')}`;
    }
}

/**
 * Handle form submission
 */
function handleFormSubmission(e) {
    // Validate required fields
    const requiredFields = ['nama_anda', 'alamat_anda', 'tlp', 'ekspedisi', 'metode'];
    let isValid = true;
    let firstInvalidField = null;

    requiredFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (!field || !field.value.trim()) {
            if (field) {
                field.classList.add('is-invalid');
                if (!firstInvalidField) firstInvalidField = field;
            }
            isValid = false;
        } else if (field) {
            field.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        e.preventDefault();
        showNotification('Silakan lengkapi semua field yang diperlukan', 'error');
        if (firstInvalidField) {
            firstInvalidField.focus();
        }
        return false;
    }

    // Log coordinates for debugging
    if (marker) {
        const position = marker.getLatLng();
        console.log('Form submitted with coordinates:', {
            lat: position.lat,
            lng: position.lng,
            address: document.getElementById('alamat_anda').value
        });
    }

    // Show success message
    showNotification('Memproses pesanan...', 'info');

    return true; // Allow form submission to proceed
}

/**
 * Show notification to user
 */
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotification = document.querySelector('.custom-notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${getBootstrapAlertClass(type)} alert-dismissible fade show custom-notification`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;

    notification.innerHTML = `
        <i class="fas ${getNotificationIcon(type)} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Add to body
    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification && notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

/**
 * Get Bootstrap alert class for notification type
 */
function getBootstrapAlertClass(type) {
    const classMap = {
        'success': 'success',
        'error': 'danger',
        'warning': 'warning',
        'info': 'info'
    };
    return classMap[type] || 'info';
}

/**
 * Get Font Awesome icon for notification type
 */
function getNotificationIcon(type) {
    const iconMap = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    return iconMap[type] || 'fa-info-circle';
}

/**
 * Utility function to format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

/**
 * Utility function to validate phone number
 */
function validatePhoneNumber(phone) {
    // Indonesian phone number validation
    const phoneRegex = /^(\+62|62|0)[2-9][0-9]{7,11}$/;
    return phoneRegex.test(phone.replace(/\s|-/g, ''));
}

/**
 * Enhanced form validation
 */
function validateForm() {
    let isValid = true;
    const errors = [];

    // Validate name
    const name = document.getElementById('nama_anda');
    if (!name.value.trim()) {
        errors.push('Nama harus diisi');
        name.classList.add('is-invalid');
        isValid = false;
    } else {
        name.classList.remove('is-invalid');
    }

    // Validate address
    const address = document.getElementById('alamat_anda');
    if (!address.value.trim()) {
        errors.push('Alamat harus diisi');
        address.classList.add('is-invalid');
        isValid = false;
    } else if (address.value.trim().length < 10) {
        errors.push('Alamat terlalu pendek');
        address.classList.add('is-invalid');
        isValid = false;
    } else {
        address.classList.remove('is-invalid');
    }

    // Validate phone number
    const phone = document.getElementById('tlp');
    if (!phone.value.trim()) {
        errors.push('Nomor telepon harus diisi');
        phone.classList.add('is-invalid');
        isValid = false;
    } else if (!validatePhoneNumber(phone.value)) {
        errors.push('Format nomor telepon tidak valid');
        phone.classList.add('is-invalid');
        isValid = false;
    } else {
        phone.classList.remove('is-invalid');
    }

    // Show errors if any
    if (!isValid) {
        showNotification(errors.join('<br>'), 'error');
    }

    return isValid;
}

/**
 * Initialize address autocomplete with debouncing
 */
function initAddressAutocomplete() {
    const addressInput = document.getElementById('alamat_anda');
    if (!addressInput) return;

    let debounceTimer;

    addressInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 3) {
            hideSuggestions();
            return;
        }

        debounceTimer = setTimeout(() => {
            searchAddressAutocomplete(query);
        }, 500);
    });
}

/**
 * Search address for autocomplete
 */
function searchAddressAutocomplete(query) {
    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=3&countrycodes=ID`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                showAddressSuggestions(data);
            } else {
                hideSuggestions();
            }
        })
        .catch(error => {
            console.error('Autocomplete error:', error);
            hideSuggestions();
        });
}

/**
 * Get user's approximate location based on IP (fallback)
 */
function getLocationByIP() {
    fetch('https://ipapi.co/json/')
        .then(response => response.json())
        .then(data => {
            if (data.latitude && data.longitude) {
                const lat = parseFloat(data.latitude);
                const lng = parseFloat(data.longitude);

                map.setView([lat, lng], MAP_CONFIG.defaultZoom);
                showNotification(`Lokasi perkiraan: ${data.city}, ${data.region}`, 'info');
            }
        })
        .catch(error => {
            console.error('IP location error:', error);
        });
}

/**
 * Check if user is online
 */
function checkOnlineStatus() {
    if (!navigator.onLine) {
        showNotification('Anda sedang offline. Beberapa fitur mungkin tidak berfungsi.', 'warning');
    }
}

/**
 * Initialize additional features
 */
function initAdditionalFeatures() {
    // Initialize autocomplete
    initAddressAutocomplete();

    // Check online status
    checkOnlineStatus();

    // Listen for online/offline events
    window.addEventListener('online', () => {
        showNotification('Koneksi internet tersambung kembali', 'success');
    });

    window.addEventListener('offline', () => {
        showNotification('Koneksi internet terputus', 'warning');
    });
}

// Initialize additional features when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    // Add small delay to ensure map is initialized first
    setTimeout(initAdditionalFeatures, 1000);
});

// Export functions for external use if needed
window.MapIntegration = {
    getCurrentLocation,
    clearMapAndForm,
    addMarkerToMap,
    searchAddress,
    validateForm,
    showNotification
};
