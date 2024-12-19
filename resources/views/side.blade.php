<aside id="sidebar" class="sidebar">
  
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link " href="{{ route('app.main') }}">
        <i class="bi bi-grid"></i>
        <span>Acceuil</span>
      </a>
    </li>
    {{-- Maintenance tabs --}}
      @if(in_array('maintenance_in', $permissions)||in_array('maintenance_out', $permissions)||in_array('maintenance_fix', $permissions))
      <li class="nav-item ">
        <a class="nav-link collapsed" data-bs-target="#maintenance-nav" data-bs-toggle="collapse" href="#">
          <i class="bi  bi-wrench "></i><span>Maintenance</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="maintenance-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
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

      {{-- Gestion tabs --}}
      @if(in_array('manage_user', $permissions)||in_array('manage_lines', $permissions)||in_array('manage_bus', $permissions)||in_array('manage_panne', $permissions))
      <li class="nav-item ">
        <a class="nav-link collapsed" data-bs-target="#gestion-nav" data-bs-toggle="collapse" href="#">
          <i class="bi  bi-gear"></i><span>Gestion</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="gestion-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          {{-- @if(in_array('add_user', $permissions))
            <li>
              <a href="{{ route('app.gestion.add_user') }}">
                <i class="bi bi-circle"></i><span>Création d'un compte</span>
              </a>
            </li>
          @endif --}}
          @if(in_array('manage_user', $permissions))
            <li>
              <a href="{{ route('app.gestion.manage_user') }}">
                <i class="bi bi-circle"></i><span>Gestion des comptes</span>
              </a>
            </li>
          @endif
          @if(in_array('manage_bus', $permissions))
            <li>
              <a href="{{ route('app.gestion.manage_bus') }}">
                <i class="bi bi-circle"></i><span>Gestion des bus</span>
              </a>
            </li>
          @endif
          @if(in_array('manage_ligne', $permissions))
            <li>
              <a href="{{ route('app.gestion.manage_ligne') }}">
                <i class="bi bi-circle"></i><span>Gestion des lignes</span>
              </a>
            </li>
          @endif
          @if(in_array('manage_panne', $permissions))
            <li>
              <a href="{{ route('app.gestion.manage_panne') }}">
                <i class="bi bi-circle"></i><span>Gestion des pannes</span>
              </a>
            </li>
          @endif

        </ul>
      </li>
      @endif


  </aside>