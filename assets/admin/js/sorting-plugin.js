import Sortable from 'sortablejs';
import '../css/sorting-plugin.css';

function initSortableContainer() {
  const sortableContainer = document.getElementById('sortableProducts');

  if (sortableContainer) {
    new Sortable(sortableContainer, {
      animation: 150,
      draggable: '.sortableItem',
      ghostClass: 'sortableItem-placeholder',
      onStart: () => {
        const flashMessages = document.querySelectorAll('.sylius-flash-message');
        flashMessages.forEach(msg => msg.style.display = 'none');
      }
    });
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initSortableContainer);
} else {
  initSortableContainer();
}
