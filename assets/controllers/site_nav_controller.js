import { Controller } from '@hotwired/stimulus';

/**
 * Navigation mobile : panneau + backdrop, verrouillage du scroll (≤900px).
 */
export default class extends Controller {
    static targets = ['panel', 'backdrop', 'toggle'];

    connect() {
        this._keydown = this._onKeydown.bind(this);
        this._resize = this._apply.bind(this);
        this._open = false;
        window.addEventListener('resize', this._resize);
        this._apply();
    }

    disconnect() {
        window.removeEventListener('resize', this._resize);
        document.removeEventListener('keydown', this._keydown);
        document.body.classList.remove('site-nav-no-scroll');
    }

    toggle() {
        if (!this._isMobile()) {
            return;
        }
        this._open = !this._open;
        this._apply();
    }

    close(event) {
        if (event) {
            event.preventDefault();
        }
        this._open = false;
        this._apply();
    }

    closeOnLink(event) {
        const link = event.target.closest('a[href]');
        if (link && this._isMobile()) {
            this._open = false;
            this._apply();
        }
    }

    _isMobile() {
        return window.matchMedia('(max-width: 900px)').matches;
    }

    _apply() {
        const mobile = this._isMobile();

        if (!mobile) {
            this._open = false;
            this.panelTarget?.classList.remove('site-nav__panel--open');
            this.backdropTarget?.classList.remove('site-nav__backdrop--visible');
            document.body.classList.remove('site-nav-no-scroll');
            document.removeEventListener('keydown', this._keydown);

            if (this.hasBackdropTarget) {
                this.backdropTarget.setAttribute('aria-hidden', 'true');
            }

            if (this.hasToggleTarget) {
                this.toggleTarget.setAttribute('aria-expanded', 'false');
                this.toggleTarget.setAttribute('aria-label', 'Ouvrir le menu de navigation');
            }

            return;
        }

        document.removeEventListener('keydown', this._keydown);

        this.panelTarget?.classList.toggle('site-nav__panel--open', this._open);
        this.backdropTarget?.classList.toggle('site-nav__backdrop--visible', this._open);
        document.body.classList.toggle('site-nav-no-scroll', this._open);

        if (this.hasBackdropTarget) {
            this.backdropTarget.setAttribute(
                'aria-hidden',
                this._open ? 'false' : 'true'
            );
        }

        if (this.hasToggleTarget) {
            this.toggleTarget.setAttribute('aria-expanded', this._open ? 'true' : 'false');
            this.toggleTarget.setAttribute(
                'aria-label',
                this._open ? 'Fermer le menu de navigation' : 'Ouvrir le menu de navigation'
            );
        }

        if (this._open) {
            document.addEventListener('keydown', this._keydown);
        }
    }

    _onKeydown(event) {
        if (event.key === 'Escape') {
            this.close();
        }
    }
}
