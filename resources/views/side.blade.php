<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link " href="{{ route('app.main') }}">
          <i class="bi bi-grid"></i>
          <span>Acceuil</span>
        </a>
      </li>
      @if(in_array('maintenance_in', $permissions)||in_array('maintenance_out', $permissions))
      <li class="nav-item ">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi  bi-wrench "></i><span>Maintenance</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          @if(in_array('maintenance_in', $permissions))
            <li>
              <a href="{{ route('app.maintenance.maintenance_in') }}">
                <i class="bi bi-circle"></i><span>Remplire</span>
              </a>
            </li>
          @endif
          @if(in_array('maintenance_fix', $permissions))
            <li>
              <a href="{{ route('app.maintenance.maintenance_fix') }}">
                <i class="bi bi-circle"></i><span>Modifier</span>
              </a>
            </li>
          @endif
          @if(in_array('maintenance_out', $permissions))
            <li>
              <a href="{{ route('app.maintenance.maintenance_show') }}">
                <i class="bi bi-circle"></i><span>Extraire</span>
              </a>
            </li>
          @endif
        </ul>
      </li>
      @endif


  </aside>