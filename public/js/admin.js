/**
 * AgroFinca Admin Panel Interactions
 */
document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('adminSidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebarOverlay = document.getElementById('sidebarOverlay');
  
  // Sidebar Toggle
  if (sidebarToggle && sidebar && sidebarOverlay) {
    sidebarToggle.addEventListener('click', () => {
      sidebar.classList.toggle('show');
      sidebarOverlay.classList.toggle('show');
    });

    sidebarOverlay.addEventListener('click', () => {
      sidebar.classList.remove('show');
      sidebarOverlay.classList.remove('show');
    });
  }

  // Sidebar Accordion Menus Toggle
  const accordionButtons = document.querySelectorAll('[data-af-toggle="sidebar-accordion"]');
  accordionButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const currentGroup = btn.closest('.nav-accordion-group');
      if (currentGroup) {
        currentGroup.classList.toggle('open');
      }
    });
  });

  // Dropdowns Management with Event Delegation
  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('[data-af-toggle="dropdown"]');
    if (trigger) {
      e.preventDefault();
      e.stopPropagation();
      const targetId = trigger.getAttribute('data-af-target');
      const targetMenu = document.getElementById(targetId);
      
      // Close other dropdowns
      document.querySelectorAll('.af-dropdown-box').forEach(menu => {
        if (menu !== targetMenu) {
          menu.classList.remove('show');
        }
      });

      if (targetMenu) {
        targetMenu.classList.toggle('show');
      }
      return;
    }

    // Close dropdowns on outside click
    if (!e.target.closest('.af-dropdown-box')) {
      document.querySelectorAll('.af-dropdown-box').forEach(menu => {
        menu.classList.remove('show');
      });
    }
  });

  // Modal Management
  window.openAfModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.add('show');
      document.body.style.overflow = 'hidden';
    }
  };

  window.closeAfModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
      modal.classList.remove('show');
      document.body.style.overflow = '';
    }
  };

  // Open modal buttons
  document.querySelectorAll('[data-af-modal-open]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const modalId = btn.getAttribute('data-af-modal-open');
      window.openAfModal(modalId);
    });
  });

  // Close modal buttons
  document.querySelectorAll('[data-af-modal-close]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const modalId = btn.getAttribute('data-af-modal-close');
      window.closeAfModal(modalId);
    });
  });

  // Close modal on background click
  document.querySelectorAll('.af-modal-overlay').forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
      }
    });
  });

  // Global Keyboard Shortcuts (Ctrl + K for Search, Esc for closing menus/modals)
  document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
      e.preventDefault();
      const searchInput = document.getElementById('adminGlobalSearch') || document.getElementById('tableLiveSearch');
      if (searchInput) {
        searchInput.focus();
      }
    }

    if (e.key === 'Escape') {
      document.querySelectorAll('.af-dropdown-box').forEach(menu => {
        menu.classList.remove('show');
      });
      document.querySelectorAll('.af-modal-overlay').forEach(modal => {
        modal.classList.remove('show');
      });
      document.body.style.overflow = '';
      if (sidebar && sidebarOverlay) {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
      }
    }
  });

  // Table Live Search & State Filter
  const tableLiveSearch = document.getElementById('tableLiveSearch');
  const tableStateFilter = document.getElementById('tableStateFilter');

  function applyTableFilters() {
    const searchTerm = tableLiveSearch ? tableLiveSearch.value.toLowerCase().trim() : '';
    const stateFilter = tableStateFilter ? tableStateFilter.value.toLowerCase().trim() : 'todos';
    const rows = document.querySelectorAll('.af-table-data tbody tr');

    rows.forEach(row => {
      if (row.querySelector('.table-empty-state')) return;
      const text = row.textContent.toLowerCase();
      const statusCell = row.getAttribute('data-status') || text;
      
      const matchesSearch = !searchTerm || text.includes(searchTerm);
      const matchesState = (stateFilter === 'todos' || !stateFilter) || statusCell.toLowerCase().includes(stateFilter);

      if (matchesSearch && matchesState) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  if (tableLiveSearch) {
    tableLiveSearch.addEventListener('input', applyTableFilters);
  }
  if (tableStateFilter) {
    tableStateFilter.addEventListener('change', applyTableFilters);
  }
});



