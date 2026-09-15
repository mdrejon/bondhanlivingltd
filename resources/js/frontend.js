import '../css/frontend.css';
import 'flatpickr/dist/flatpickr.min.css';
import flatpickr from 'flatpickr';

// ── Flatpickr date-pair initialiser ─────────────────────────────────────────
// Used on: Check Availability bar, FAQ Booking card, Booking popup
// Rule: check-in min = today; checkout min = check-in + 1 day (auto-updated)
function dayAfter(date) {
    const d = new Date(date);
    d.setDate(d.getDate() + 1);
    d.setHours(0, 0, 0, 0);
    return d;
}

function initDatePair(ciSel, coSel) {
    let coFp;

    const ciFp = flatpickr(ciSel, {
        dateFormat:    'Y-m-d',
        altInput:      true,
        altFormat:     'D, d M Y',
        minDate:       'today',
        disableMobile: true,
        onChange(selectedDates) {
            if (!selectedDates[0] || !coFp) return;
            const minCO = dayAfter(selectedDates[0]);
            coFp.set('minDate', minCO);
            if (!coFp.selectedDates[0] || coFp.selectedDates[0] < minCO) {
                coFp.setDate(minCO, false);
            }
        },
    });

    coFp = flatpickr(coSel, {
        dateFormat:    'Y-m-d',
        altInput:      true,
        altFormat:     'D, d M Y',
        minDate:       dayAfter(new Date()),
        disableMobile: true,
    });

    return { ci: ciFp, co: coFp };
}

// Check Availability bar
if (document.getElementById('caf-checkin')) {
    initDatePair('#caf-checkin', '#caf-checkout');
}

// FAQ / Booking card
if (document.getElementById('bf-checkin')) {
    initDatePair('#bf-checkin', '#bf-checkout');
}

// Booking popup — expose globally so openPopup() (inline blade script) can call setDate()
if (document.getElementById('bkSumCheckin')) {
    const bkPair = initDatePair('#bkSumCheckin', '#bkSumCheckout');
    window.bkCheckinFp  = bkPair.ci;
    window.bkCheckoutFp = bkPair.co;
}
// ────────────────────────────────────────────────────────────────────────────

// Mobile menu toggle
const mobileToggle = document.getElementById('mobileMenuToggle');
const mobileMenu   = document.getElementById('mobileMenu');
if (mobileToggle && mobileMenu) {
    mobileToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('is-open');
        mobileToggle.classList.toggle('is-active');
    });
}

// Mobile sub-menu accordion
document.querySelectorAll('.mobile-item.has-sub > a').forEach(link => {
    link.addEventListener('click', e => {
        e.preventDefault();
        const item = link.closest('.mobile-item');
        item.classList.toggle('open');
    });
});

// Sidebar toggle
const sidebarToggle  = document.getElementById('sidebarToggle');
const sidebarPanel   = document.getElementById('sidebarPanel');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const sidebarClose   = document.getElementById('sidebarClose');

function openSidebar() {
    sidebarPanel?.classList.add('is-open');
    sidebarOverlay?.classList.add('is-visible');
    document.body.classList.add('sidebar-open');
}
function closeSidebar() {
    sidebarPanel?.classList.remove('is-open');
    sidebarOverlay?.classList.remove('is-visible');
    document.body.classList.remove('sidebar-open');
}

sidebarToggle?.addEventListener('click', openSidebar);
sidebarClose?.addEventListener('click', closeSidebar);
sidebarOverlay?.addEventListener('click', closeSidebar);

// Chat widget toggle
const chatToggle = document.getElementById('chatToggle');
const chatPopup  = document.getElementById('chatPopup');
const chatClose  = document.getElementById('chatClose');

chatToggle?.addEventListener('click', () => chatPopup?.classList.toggle('is-open'));
chatClose?.addEventListener('click',  () => chatPopup?.classList.remove('is-open'));

// Sticky header on scroll
const mainHeader = document.querySelector('.main-header');
if (mainHeader) {
    window.addEventListener('scroll', () => {
        mainHeader.classList.toggle('is-sticky', window.scrollY > 80);
    }, { passive: true });
}

// Back to top
const backToTop = document.getElementById('backToTop');
if (backToTop) {
    window.addEventListener('scroll', () => {
        backToTop.classList.toggle('is-visible', window.scrollY > 400);
    }, { passive: true });
    backToTop.addEventListener('click', e => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}
