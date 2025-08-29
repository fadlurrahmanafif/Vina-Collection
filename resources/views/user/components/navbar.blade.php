  <nav class="navbar navbar-dark navbar-expand-lg" style="background-color:#00D4E7">
      <div class="container">
          <a class="navbar-brand" href="#">
              <h3>Vina Collection</h3>
          </a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
              <ul class="navbar-nav gap-4 align-items-center">
                  <li class="nav-item">
                      <a class="nav-link fs-5 {{ Request::path() == '/' ? 'active' : '' }}" href="/">Home</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link fs-5 {{ Request::path() == 'statuspesanan' ? 'active' : '' }}"
                          href="/statuspesanan">Status Pesanan</a>
                  </li>

                  @guest
                      <li class="nav-item">
                          <a href="{{ route('login') }}">
                              <button type="button" class="btn btn-outline-light">Login | Register</button>
                          </a>
                      </li>
                  @endguest

                  @auth
                      <li class="nav-item">
                          <form method="POST" action="{{ route('logout') }}">
                              @csrf
                              <button type="submit" class="btn btn-outline-light">Logout</button>
                          </form>
                      </li>
                  @endauth

                  <li class="nav-item">
                      <div class="notif">
                          <a href="/cart" class="fs-4 nav-link {{ Request::path() == 'cart' ? 'active' : '' }}">
                              <i class="fa-solid fa-bag-shopping"></i>
                          </a>
                          <div class="circle">10</div>
                      </div>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
