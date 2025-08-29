  <nav class="navbar navbar-dark navbar-expand-lg" style="background-color:#00D4E7 ">
      <div class="container">
          <a class="navbar-brand" href="#">
              <h3>Vina Collection</h3>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end d-flex " id="navbarSupportedContent">
              <ul class="navbar-nav gap-4 ">
                  <li class="nav-item">
                      <a class="nav-link fs-5  {{ Request::path() == '/' ? 'active' : '' }}" aria-current="page"
                          href="/">
                          Home
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link fs-5 {{ Request::path() == 'statuspesanan' ? 'active' : '' }}"
                          aria-current="page" href="/statuspesanan">
                          Staus Pesanan
                      </a>
                  </li>
                  <div class="d-flex gap-4 align-items-center">
                      {{-- Kalau belum login --}}
                      @guest
                          <a href="{{ route('login') }}">
                              <button type="button" class="btn btn-outline-light">Login | Register</button>
                          </a>
                      @endguest

                      {{-- Kalau sudah login --}}
                      @auth
                          <form method="POST" action="{{ route('logout') }}">
                              @csrf
                              <button type="submit" class="btn btn-outline-light">Logout</button>
                          </form>
                      @endauth

                      <div class="notif">
                          <a href="/cart" class="fs-4">
                              <i class="fa-solid icon-nav fa-bag-shopping "></i>
                          </a>
                          <div class="circle">10</div>
                      </div>
                  </div>
                  {{-- </li>
                  <li class="nav-item">
                      <button type="button"  class="btn btn-success" data-bs-toggle="modal"
                          data-bs-target="#exampleModal">
                          Login </button>
                  </li> --}}
                  {{-- <li class="nav-item">
                      <div class="notif">
                          <a href="/transaksi"
                              class="fs-5 nav-link {{ Request::path() == 'transaksi' ? 'active' : '' }}">
                              <i class="fa fa-bag-shopping"></i>
                          </a>
                          @if ($count)
                              <div class="circle">{{ $count }}</div>
                          @endif
                      </div>
                  </li> --}}
              </ul>
              {{-- <div class="d-flex gap-4 align-items-center">
                  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                      Login | Register</button>

              </div> --}}
          </div>
      </div>
  </nav>
