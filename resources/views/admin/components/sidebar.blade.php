<div class="sidebar">
    <div class="sidebar-header">
        <h2>Vina Collection</h2>
        <p>Admin Dashboard</p>
    </div>

    <nav class="nav-menu">
        <div class="nav-item {{ Request::is('dasboard*') ? 'active' : '' }}">
            <a href="{{ route('dasboard') }}" class="nav-link">
                <i class="material-icons">dashboard</i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="nav-item {{ Request::is('product*') ? 'active' : '' }}">
            <a href="{{ route('product') }}" class="nav-link">
                <i class="material-icons">inventory_2</i>
                <span>Products</span>
            </a>
        </div>

        <div class="nav-item {{ Request::is('datapesanan*') ? 'active' : '' }}">
            <a href="{{ route('data.pesanan') }}" class="nav-link">
                <i class="material-icons">shopping_cart</i>
                <span>Data Pesanan</span>
            </a>
        </div>

        <div class="nav-item {{ Request::is('userdata*') ? 'active' : '' }}">
            <a href="{{ route('user.data') }}" class="nav-link">
                <i class="material-icons">people</i>
                <span>User Data</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="material-icons">analytics</i>
                <span>Reports</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="#" class="nav-link">
                <i class="material-icons">settings</i>
                <span>Settings</span>
            </a>
        </div>

        <div class="nav-item">
            <form action="{{ route('logout.admin') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link bg-transparent border-0">
                    <i class="material-icons">logout</i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hapus script yang mengubah active class karena sudah dihandle oleh server-side

        // Notification button click (jika ada)
        const notificationBtn = document.querySelector('.notification-btn');
        if (notificationBtn) {
            notificationBtn.addEventListener('click', function() {
                alert('You have 3 new notifications!');
            });
        }

        // User avatar click (jika ada)
        const userAvatar = document.querySelector('.user-avatar');
        if (userAvatar) {
            userAvatar.addEventListener('click', function() {
                alert('User profile menu would open here');
            });
        }

        // Stat cards hover effect (jika ada)
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(-5px) scale(1)';
            });
        });
    });
</script>
