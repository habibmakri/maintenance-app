<aside id="sidebar" class="sidebar">
  
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link " href="{{ route('app.main') }}">
        <i class="bi bi-grid"></i>
        <span>Acceuil</span>
      </a>
    </li>
    {{-- Maintenance tabs --}}
      @if(in_array('maintenance_in', $permissions)||in_array('maintenance_out', $permissions)||in_array('maintenance_fix', $permissions)||in_array('maintenance_panne', $permissions)||in_array('maintenance_vidange', $permissions))
      <li class="nav-item ">
        <a class="nav-link collapsed" data-bs-target="#maintenance-nav" data-bs-toggle="collapse" href="#">
          <i class="bi  bi-wrench "></i><span>Maintenance</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="maintenance-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          @if(in_array('maintenance_in', $permissions))
            <li>
              <a href="{{ route('app.maintenance.maintenance_in') }}">
                <i class="bi bi-circle"></i><span>Remplire Fiche Maintenance</span>
              </a>
            </li>
          @endif
          @if(in_array('maintenance_fix', $permissions))
            <li>
              <a href="{{ route('app.maintenance.maintenance_fix') }}">
                <i class="bi bi-circle"></i><span>Modifier Fiche Maintenance</span>
              </a>
            </li>
          @endif
          @if(in_array('maintenance_jauge', $permissions))
            <li>
              <a href="{{ route('app.maintenance.maintenance_jauge') }}">
                <i class="bi bi-circle"></i><span>Jauge</span>
              </a>
            </li>
          @endif
          @if(in_array('maintenance_vidange', $permissions))
            <li>
              <a href="{{ route('app.maintenance.maintenance_vidange') }}">
                <i class="bi bi-circle"></i><span>Vidange</span>
              </a>
            </li>
          @endif
          @if(in_array('maintenance_panne', $permissions))
          <li>
            <a href="{{ route('app.maintenance.maintenance_panne') }}">
              <i class="bi bi-circle"></i><span>Liste des pannes</span>
            </a>
          </li>
          @endif
          @if(in_array('maintenance_in', $permissions))
            <li>
              <a href="{{ route('app.maintenance.traveaux_libre') }}">
                <i class="bi bi-circle"></i><span>Traveaux libre</span>
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
          @if(in_array('manage_piece', $permissions))
            <li>
              <a href="{{ route('app.gestion.manage_piece') }}">
                <i class="bi bi-circle"></i><span>Gestion des pieces</span>
              </a>
            </li>
          @endif

        </ul>
      </li>
      @endif
      {{-- Personelle tabs --}}
      @if(in_array('personelle_stat', $permissions))
      <li class="nav-item ">
        <a class="nav-link collapsed" data-bs-target="#personelle-nav" data-bs-toggle="collapse" href="#">
          <i class="bi  bi-person-fill"></i><span>Personelle</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="personelle-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          @if(in_array('personelle_stat', $permissions))
            <li>
              <a href="{{ route('app.personelle.statistiques') }}">
                <i class="bi bi-circle"></i><span>Statistiques Personelle</span>
              </a>
            </li>
          @endif
        </ul>
      </li>
      @endif
      {{-- comptabilite tabs --}}
      @if(in_array('comptabilite_stat', $permissions))
      <li class="nav-item ">
        <a class="nav-link collapsed" data-bs-target="#comptabilite-nav" data-bs-toggle="collapse" href="#">
          <i class="bi  bi-currency-dollar"></i><span>Comptabilite</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="comptabilite-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          @if(in_array('comptabilite_stat', $permissions))
            <li>
              <a href="{{ route('app.comptabilite.statistiques') }}">
                <i class="bi bi-circle"></i><span>Statistiques Comptabilite</span>
              </a>
            </li>
          @endif
        </ul>
      </li>
      @endif
      {{-- exploatation tabs --}}
      @if(in_array('exploatation_stat', $permissions))
      <li class="nav-item ">
        <a class="nav-link collapsed" data-bs-target="#exploatation-nav" data-bs-toggle="collapse" href="#">
          <i class=" ri-bus-fill"></i><span>Exploatation</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="exploatation-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          @if(in_array('exploatation_stat', $permissions))
            <li>
              <a href="{{ route('app.exploatation.statistiques') }}">
                <i class="bi bi-circle"></i><span>Statistiques Exploatation</span>
              </a>
            </li>
          @endif
        </ul>
      </li>
      @endif
      {{-- Technique tabs --}}
      @if(in_array('ctechnique_rendezvous', $permissions)||in_array('ctechnique_evaluations', $permissions))
      <li class="nav-item ">
        <a class="nav-link collapsed" data-bs-target="#Ctechnique-nav" data-bs-toggle="collapse" href="#">
          <i class="  ri-car-line"></i><span>Controle Technique</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="Ctechnique-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          @if(in_array('ctechnique_rendezvous', $permissions))
            <li>
              <a href="{{ route('app.exploatation.statistiques') }}">
                <i class="bi bi-circle"></i><span>Liste Client</span>
              </a>
            </li>
          @endif
          @if(in_array('ctechnique_evaluations', $permissions))
            <li>
              <a href="{{ route('app.ctechnique.evaluations') }}">
                <i class="bi bi-circle"></i><span>Evaluations</span>
              </a>
            </li>
          @endif
        </ul>
      </li>
      @endif
      {{-- Judiciaire tabs --}}
      @if(in_array('judiciaire_in', $permissions))
      <li class="nav-item ">
        <a class="nav-link collapsed" data-bs-target="#judiciaire-nav" data-bs-toggle="collapse" href="#">
          <i class=" ri-auction-fill"></i><span>Judiciaire</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="judiciaire-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          @if(in_array('judiciaire_in', $permissions))
            <li>
              <a href="{{ route('app.exploatation.statistiques') }}">
                <i class="bi bi-circle"></i><span>Remplire</span>
              </a>
            </li>
          @endif
          @if(in_array('judiciaire_in', $permissions))
            <li>
              <a href="{{ route('app.exploatation.statistiques') }}">
                <i class="bi bi-circle"></i><span>Modifier</span>
              </a>
            </li>
          @endif
          @if(in_array('judiciaire_out', $permissions))
            <li>
              <a href="{{ route('app.exploatation.statistiques') }}">
                <i class="bi bi-circle"></i><span>Extraire</span>
              </a>
            </li>
          @endif
        </ul>
      </li>
      @endif


  </aside>