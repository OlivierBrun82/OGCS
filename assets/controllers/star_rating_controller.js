import { Controller } from '@hotwired/stimulus';

/** Note 1–10 : clic sur ★ renseigne l’input caché Symfony. */
export default class extends Controller {
    static targets = ['input', 'starBtn'];

    previewValue;

    connect() {
        this.previewValue = null;
        this.refresh();
    }

    choose(event) {
        event.preventDefault();
        event.stopPropagation();
        const v = parseInt(event.currentTarget.dataset.value ?? '0', 10);
        if (!Number.isFinite(v) || v < 1) {
            return;
        }
        this.inputTarget.value = String(v);
        this.previewValue = null;
        this.refresh();
        this.dispatch('change');
    }

    preview(event) {
        const v = parseInt(event.currentTarget.dataset.value ?? '0', 10);
        this.previewValue = Number.isFinite(v) ? v : null;
        this.refresh();
    }

    clearPreview() {
        this.previewValue = null;
        this.refresh();
    }

    refresh() {
        const raw = parseInt(this.inputTarget.value, 10);
        const stable = Number.isFinite(raw) && raw >= 1 ? raw : 0;
        const hover =
            Number.isFinite(this.previewValue) && this.previewValue >= 1 ? this.previewValue : null;
        const active = hover !== null ? hover : stable;

        this.starBtnTargets.forEach((btn) => {
            const starVal = parseInt(btn.dataset.value ?? '0', 10);
            const filled = active > 0 && starVal <= active;
            btn.classList.toggle('star-rating-field__star--filled', filled);
            btn.classList.toggle(
                'star-rating-field__star--preview',
                hover !== null && starVal <= hover,
            );
            btn.setAttribute('aria-checked', filled ? 'true' : 'false');
        });
        if (stable > 0) {
            this.element.setAttribute('data-star-rating-value', String(stable));
        } else {
            this.element.removeAttribute('data-star-rating-value');
        }
    }
}
