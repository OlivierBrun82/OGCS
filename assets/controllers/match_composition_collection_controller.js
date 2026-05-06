import { Controller } from '@hotwired/stimulus';

/** Ajoute des entrées CollectionType Symfony (prototype + __name__) pour la composition d’un match. */
export default class extends Controller {
    static targets = ['holder'];

    static values = {
        nextIndex: { type: Number, default: 0 },
    };

    add(event) {
        event.preventDefault();
        const proto = this.holderTarget.getAttribute('data-prototype');
        if (!proto) {
            return;
        }

        const html = proto.replace(/__name__/g, String(this.nextIndexValue));
        this.holderTarget.insertAdjacentHTML('beforeend', html);
        this.nextIndexValue++;
    }
}
