import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', () => {
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
});
