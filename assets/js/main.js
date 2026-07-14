/**
 * Kratom Feed - Pacific Grass-style interactions
 */
(function () {
  "use strict";

  const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  function initHeaderShadow() {
    const header = document.getElementById("site-header");
    if (!header) return;
    const fn = () => header.classList.toggle("shadow-md", window.scrollY > 8);
    window.addEventListener("scroll", fn, { passive: true });
    fn();
  }

  function initMobileNav() {
    const header = document.getElementById("site-header");
    const btn = document.getElementById("mobile-menu-btn");
    const menu = document.getElementById("mobile-menu");
    const overlay = document.getElementById("menu-overlay");
    if (!btn || !menu || !header) return;

    let on = false;
    let locked = false;

    const setOpen = (v) => {
      if (locked || on === v) return;
      on = v;
      locked = true;

      if (v) window.kratomFeedCloseSearch?.();

      header.classList.toggle("is-open", v);
      btn.classList.toggle("is-open", v);
      menu.classList.toggle("is-open", v);
      overlay?.classList.toggle("is-open", v);

      btn.setAttribute("aria-expanded", String(v));
      btn.setAttribute("aria-label", v ? "Close menu" : "Open menu");
      menu.setAttribute("aria-hidden", String(!v));
      overlay?.setAttribute("aria-hidden", String(!v));
      document.body.style.overflow = v ? "hidden" : "";

      if (!v) {
        menu.querySelectorAll(".pg-h-nav__item.is-active").forEach((item) => {
          item.classList.remove("is-active");
          item.querySelector(".pg-h-nav__parent")?.setAttribute("aria-expanded", "false");
        });
      }

      window.setTimeout(() => {
        locked = false;
      }, reduced ? 50 : 500);

      if (!v) btn.focus({ preventScroll: true });
    };

    btn.addEventListener("click", () => setOpen(!on));
    overlay?.addEventListener("click", () => setOpen(false));

    menu.querySelectorAll(".pg-h-nav__parent").forEach((parentBtn) => {
      parentBtn.addEventListener("click", () => {
        const item = parentBtn.closest(".pg-h-nav__item");
        if (!item) return;
        const willOpen = !item.classList.contains("is-active");

        menu.querySelectorAll(".pg-h-nav__item.is-active").forEach((openItem) => {
          if (openItem !== item) {
            openItem.classList.remove("is-active");
            openItem.querySelector(".pg-h-nav__parent")?.setAttribute("aria-expanded", "false");
          }
        });

        item.classList.toggle("is-active", willOpen);
        parentBtn.setAttribute("aria-expanded", String(willOpen));
      });
    });

    menu.querySelectorAll(".pg-h-nav__submenu a, .pg-h-nav__link").forEach((a) => {
      a.addEventListener("click", () => setOpen(false));
    });

    document.addEventListener("keydown", (e) => {
      if (e.key !== "Escape" || !on) return;
      if (document.getElementById("search-modal")?.classList.contains("is-open")) return;
      setOpen(false);
    });

    window.kratomFeedCloseNav = () => setOpen(false);
  }

  function initSearchModal() {
    const modal = document.getElementById("search-modal");
    const openBtn = document.getElementById("search-modal-open");
    const closeBtn = document.getElementById("search-modal-close");
    const overlay = document.getElementById("search-modal-overlay");
    const input = document.getElementById("modal-search-input");
    if (!modal || !openBtn) return;

    let open = false;

    const setOpen = (v) => {
      if (open === v) return;
      open = v;
      modal.classList.toggle("is-open", v);
      modal.setAttribute("aria-hidden", String(!v));
      openBtn.setAttribute("aria-expanded", String(v));
      document.body.style.overflow = v ? "hidden" : "";

      if (v) {
        window.kratomFeedCloseNav?.();
        window.setTimeout(() => input?.focus({ preventScroll: true }), reduced ? 0 : 80);
      } else {
        openBtn.focus({ preventScroll: true });
      }
    };

    openBtn.addEventListener("click", () => setOpen(true));
    closeBtn?.addEventListener("click", () => setOpen(false));
    overlay?.addEventListener("click", () => setOpen(false));

    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && open) setOpen(false);
    });

    window.kratomFeedCloseSearch = () => setOpen(false);
  }

  function initFaq() {
    document.querySelectorAll(".faq-item").forEach((item) => {
      const trigger = item.querySelector(".faq-trigger");
      const panel = item.querySelector(".faq-panel");
      const icon = item.querySelector(".faq-icon");
      if (!trigger || !panel) return;
      trigger.addEventListener("click", () => {
        const open = trigger.getAttribute("aria-expanded") === "true";
        document.querySelectorAll(".faq-item").forEach((o) => {
          o.querySelector(".faq-trigger")?.setAttribute("aria-expanded", "false");
          o.querySelector(".faq-panel")?.classList.add("hidden");
          o.querySelector(".faq-icon")?.classList.remove("rotate-180");
        });
        if (!open) {
          trigger.setAttribute("aria-expanded", "true");
          panel.classList.remove("hidden");
          icon?.classList.add("rotate-180");
        }
      });
    });
  }

  function initReviewsCarousel() {
    const track = document.querySelector(".reviews-track");
    const slides = document.querySelectorAll(".review-slide");
    const prev = document.querySelector(".review-prev");
    const next = document.querySelector(".review-next");
    if (!track || slides.length < 2) return;

    let idx = 0;

    const getVisibleCount = () => {
      if (window.innerWidth >= 1024) return 3;
      if (window.innerWidth >= 640) return 2;
      return 1;
    };

    const getMaxIdx = () => Math.max(0, slides.length - getVisibleCount());

    const getStep = () => {
      const slide = slides[0];
      if (!slide) return 0;
      const style = window.getComputedStyle(track);
      const gap = parseFloat(style.gap) || 16;
      return slide.offsetWidth + gap;
    };

    const show = (i) => {
      idx = Math.max(0, Math.min(i, getMaxIdx()));
      track.style.transform = `translateX(-${idx * getStep()}px)`;
    };

    prev?.addEventListener("click", () => show(idx - 1));
    next?.addEventListener("click", () => show(idx + 1));
    window.addEventListener("resize", () => show(idx));
    if (!reduced) setInterval(() => show(idx >= getMaxIdx() ? 0 : idx + 1), 6000);
  }

  function initReadingProgress() {
    const bar = document.getElementById("reading-progress");
    const article = document.getElementById("article-content");
    if (!bar || !article) { bar?.style.setProperty("display", "none"); return; }
    const fn = () => {
      const top = article.getBoundingClientRect().top + window.scrollY;
      const p = Math.min(Math.max((window.scrollY - top) / article.offsetHeight, 0), 1);
      bar.style.width = `${p * 100}%`;
    };
    window.addEventListener("scroll", fn, { passive: true });
    fn();
  }

  function initTOC() {
    const article = document.getElementById("article-content");
    const desktopToc = document.getElementById("desktop-toc");
    const mobileToc = document.getElementById("mobile-toc");
    const mobileToggle = document.getElementById("mobile-toc-toggle");
    if (!article) return;
    const headings = article.querySelectorAll(".pg-prose h2, .pg-prose h3");
    if (!headings.length) return;

    headings.forEach((h, i) => {
      if (!h.id) h.id = `section-${i}`;
      const isH3 = h.tagName === "H3";
      const makeLink = (container) => {
        const a = document.createElement("a");
        a.href = `#${h.id}`;
        a.textContent = h.textContent;
        a.className = isH3
          ? "block py-1 pl-4 text-xs text-gray-500 hover:text-pg-green-dark"
          : "block py-1.5 text-sm font-medium text-gray-600 hover:text-pg-green-dark";
        a.addEventListener("click", (e) => {
          e.preventDefault();
          const t = document.getElementById(h.id);
          if (t) window.scrollTo({ top: t.getBoundingClientRect().top + window.scrollY - 110, behavior: reduced ? "auto" : "smooth" });
          mobileToc?.classList.add("hidden");
        });
        container?.appendChild(a);
      };
      makeLink(desktopToc);
      makeLink(mobileToc);
    });

    mobileToggle?.addEventListener("click", () => {
      const open = mobileToc?.classList.toggle("hidden") === false;
      mobileToggle.setAttribute("aria-expanded", String(open));
    });
  }

  function initCopyLink() {
    document.getElementById("copy-link-btn")?.addEventListener("click", async function () {
      try {
        await navigator.clipboard.writeText(location.href);
        const orig = this.textContent;
        this.textContent = "Copied!";
        setTimeout(() => { this.textContent = orig; }, 2000);
      } catch { /* noop */ }
    });
  }

  function initNewsletter() {
    document.querySelectorAll(".newsletter-form").forEach((form) => {
      form.addEventListener("submit", (e) => {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        if (!btn) return;
        const orig = btn.textContent;
        btn.textContent = "Subscribed!";
        btn.disabled = true;
        form.querySelector('input[type="email"]').value = "";
        setTimeout(() => { btn.textContent = orig; btn.disabled = false; }, 3000);
      });
    });
  }

  function initArchiveFilter() {
    const grid = document.getElementById("article-grid");
    if (!grid) return;
    const cards = grid.querySelectorAll("[data-title]");
    document.querySelectorAll("#modal-search-input, #header-search, #mobile-search, .sidebar-search-input").forEach((input) => {
      input.addEventListener("input", (e) => {
        const q = e.target.value.toLowerCase();
        cards.forEach((c) => { c.style.display = !q || c.dataset.title.toLowerCase().includes(q) ? "" : "none"; });
      });
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    initHeaderShadow();
    initMobileNav();
    initSearchModal();
    initFaq();
    initReviewsCarousel();
    initReadingProgress();
    initTOC();
    initCopyLink();
    initNewsletter();
    initArchiveFilter();
  });
})();

