import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  connect() {
    alert(this.element.dataset.message || 'Hello from plugin controller!');
  }
}
