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
          ? "block py-1 pl-4 text-xs text-gray-500 hover:text-pg-hover"
          : "block py-1.5 text-sm font-medium text-gray-600 hover:text-pg-hover";
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
    document.querySelectorAll("#header-search, #mobile-search, .sidebar-search-input, #kf-sf-search-input, #kf-sf-search-mobile-input").forEach((input) => {
      input.addEventListener("input", (e) => {
        const q = e.target.value.toLowerCase();
        cards.forEach((c) => { c.style.display = !q || c.dataset.title.toLowerCase().includes(q) ? "" : "none"; });
      });
    });
  }

  function initStorefrontHeader() {
    const root = document.querySelector(".kf-header-storefront");
    if (!root) return;

    const drawer = document.getElementById("kf-sf-drawer");
    const overlay = document.getElementById("kf-sf-overlay");
    const openMenuBtn = root.querySelector(".kf-sf-menu-open");
    const closeMenuBtn = drawer?.querySelector(".kf-sf-drawer__close");
    const searchWrap = root.querySelector(".kf-sf-search");
    const searchInput = document.getElementById("kf-sf-search-input");
    const searchPanel = document.getElementById("kf-sf-search-panel");
    const mobileSearch = document.getElementById("kf-sf-search-mobile");
    const openSearchBtn = root.querySelector(".kf-sf-search-open");
    const closeSearchBtn = mobileSearch?.querySelector(".kf-sf-search-mobile__close");
    const mobileSearchInput = document.getElementById("kf-sf-search-mobile-input");
    const megaItems = root.querySelectorAll(".kf-sf-nav__item.has-mega");

    let drawerOpen = false;
    let deskSearchOpen = false;
    let mobileSearchOpen = false;
    let megaTimers = new WeakMap();

    const lockBody = (on) => {
      document.body.style.overflow = on ? "hidden" : "";
    };

    const setDrawer = (v) => {
      if (!drawer || drawerOpen === v) return;
      drawerOpen = v;
      if (v) setMobileSearch(false);
      drawer.classList.toggle("is-open", v);
      overlay?.classList.toggle("is-open", v);
      drawer.setAttribute("aria-hidden", String(!v));
      overlay?.setAttribute("aria-hidden", String(!v));
      openMenuBtn?.setAttribute("aria-expanded", String(v));
      openMenuBtn?.setAttribute("aria-label", v ? "Close menu" : "Open menu");
      lockBody(v || mobileSearchOpen);

      if (!v) {
        drawer.querySelectorAll(".pg-h-nav__item.is-active").forEach((item) => {
          item.classList.remove("is-active");
          item.querySelector(".pg-h-nav__parent")?.setAttribute("aria-expanded", "false");
        });
        openMenuBtn?.focus({ preventScroll: true });
      }
    };

    const setDeskSearch = (v) => {
      if (!searchWrap || deskSearchOpen === v) return;
      deskSearchOpen = v;
      searchWrap.classList.toggle("is-open", v);
      searchPanel?.classList.toggle("is-open", v);
      searchPanel?.setAttribute("aria-hidden", String(!v));
      searchInput?.setAttribute("aria-expanded", String(v));
    };

    const setMobileSearch = (v) => {
      if (!mobileSearch || mobileSearchOpen === v) return;
      mobileSearchOpen = v;
      if (v) setDrawer(false);
      mobileSearch.classList.toggle("is-open", v);
      mobileSearch.setAttribute("aria-hidden", String(!v));
      openSearchBtn?.setAttribute("aria-expanded", String(v));
      lockBody(v || drawerOpen);
      if (v) {
        window.setTimeout(() => mobileSearchInput?.focus({ preventScroll: true }), reduced ? 0 : 60);
      } else {
        openSearchBtn?.focus({ preventScroll: true });
      }
    };

    const closeAllMegas = (except) => {
      megaItems.forEach((item) => {
        if (item === except) return;
        item.classList.remove("is-open");
        const trigger = item.querySelector(".kf-sf-nav__trigger");
        const panel = item.querySelector(".kf-sf-mega");
        trigger?.setAttribute("aria-expanded", "false");
        panel?.setAttribute("aria-hidden", "true");
        panel?.classList.remove("is-open");
      });
    };

    const setMega = (item, open) => {
      const trigger = item.querySelector(".kf-sf-nav__trigger");
      const panel = item.querySelector(".kf-sf-mega");
      if (!trigger || !panel) return;
      if (open) closeAllMegas(item);
      item.classList.toggle("is-open", open);
      panel.classList.toggle("is-open", open);
      trigger.setAttribute("aria-expanded", String(open));
      panel.setAttribute("aria-hidden", String(!open));
    };

    openMenuBtn?.addEventListener("click", () => setDrawer(!drawerOpen));
    closeMenuBtn?.addEventListener("click", () => setDrawer(false));
    overlay?.addEventListener("click", () => setDrawer(false));

    drawer?.querySelectorAll(".pg-h-nav__parent").forEach((parentBtn) => {
      parentBtn.addEventListener("click", () => {
        const item = parentBtn.closest(".pg-h-nav__item");
        if (!item) return;
        const willOpen = !item.classList.contains("is-active");
        drawer.querySelectorAll(".pg-h-nav__item.is-active").forEach((openItem) => {
          if (openItem !== item) {
            openItem.classList.remove("is-active");
            openItem.querySelector(".pg-h-nav__parent")?.setAttribute("aria-expanded", "false");
          }
        });
        item.classList.toggle("is-active", willOpen);
        parentBtn.setAttribute("aria-expanded", String(willOpen));
      });
    });

    drawer?.querySelectorAll(".pg-h-nav__submenu a, .pg-h-nav__link, .pg-h-nav__popular-item").forEach((a) => {
      a.addEventListener("click", () => setDrawer(false));
    });

    if (searchInput && searchWrap) {
      const openDesk = () => setDeskSearch(true);
      searchInput.addEventListener("focus", openDesk);
      searchInput.addEventListener("input", openDesk);
      document.addEventListener("pointerdown", (e) => {
        if (!deskSearchOpen) return;
        if (searchWrap.contains(e.target)) return;
        setDeskSearch(false);
      });
    }

    openSearchBtn?.addEventListener("click", () => setMobileSearch(true));
    closeSearchBtn?.addEventListener("click", () => setMobileSearch(false));

    megaItems.forEach((item) => {
      const clearTimer = () => {
        const t = megaTimers.get(item);
        if (t) window.clearTimeout(t);
      };

      item.addEventListener("mouseenter", () => {
        clearTimer();
        setMega(item, true);
      });

      item.addEventListener("mouseleave", () => {
        clearTimer();
        megaTimers.set(
          item,
          window.setTimeout(() => setMega(item, false), reduced ? 0 : 160)
        );
      });

      item.querySelector(".kf-sf-nav__trigger")?.addEventListener("click", (e) => {
        e.preventDefault();
        const open = !item.classList.contains("is-open");
        setMega(item, open);
      });

      item.addEventListener("focusin", () => {
        clearTimer();
        setMega(item, true);
      });

      item.addEventListener("focusout", (e) => {
        if (item.contains(e.relatedTarget)) return;
        clearTimer();
        megaTimers.set(
          item,
          window.setTimeout(() => setMega(item, false), reduced ? 0 : 120)
        );
      });
    });

    document.addEventListener("keydown", (e) => {
      if (e.key !== "Escape") return;
      if (mobileSearchOpen) {
        setMobileSearch(false);
        return;
      }
      if (deskSearchOpen) {
        setDeskSearch(false);
        return;
      }
      if (drawerOpen) {
        setDrawer(false);
        return;
      }
      closeAllMegas();
    });

    document.addEventListener("pointerdown", (e) => {
      if (!root.contains(e.target) && !e.target.closest?.(".kf-sf-mega")) {
        closeAllMegas();
      }
    });
  }

  function formatCount(n) {
    const num = Number(n) || 0;
    if (num >= 1000000) return `${(num / 1000000).toFixed(1).replace(/\.0$/, "")}m`;
    if (num >= 1000) return `${(num / 1000).toFixed(1).replace(/\.0$/, "")}k`;
    return String(num);
  }

  function initHeroFeaturedCarousel() {
    document.querySelectorAll(".kf-hero-featured").forEach((root) => {
      const track = root.querySelector("[data-hero-track]");
      const slides = root.querySelectorAll("[data-hero-slide]");
      if (!track || slides.length < 2) return;

      let idx = 0;
      let timer = null;
      const autoplay = root.dataset.autoplay === "1" && !reduced;
      const ms = Math.max(2000, parseInt(root.dataset.autoplayMs || "6000", 10) || 6000);
      const dots = root.querySelectorAll("[data-hero-dots] .kf-hero-carousel__dot");
      const prev = root.querySelector(".kf-hero-carousel__prev");
      const next = root.querySelector(".kf-hero-carousel__next");

      const go = (i) => {
        idx = ((i % slides.length) + slides.length) % slides.length;
        track.style.transform = `translateX(-${idx * 100}%)`;
        slides.forEach((slide, n) => {
          slide.setAttribute("aria-hidden", String(n !== idx));
        });
        dots.forEach((dot, n) => {
          const on = n === idx;
          dot.classList.toggle("is-active", on);
          dot.classList.toggle("bg-neutral-900", on);
          dot.classList.toggle("bg-neutral-300", !on);
          dot.setAttribute("aria-selected", String(on));
        });
      };

      const stop = () => {
        if (timer) {
          window.clearInterval(timer);
          timer = null;
        }
      };

      const start = () => {
        stop();
        if (!autoplay) return;
        timer = window.setInterval(() => go(idx + 1), ms);
      };

      prev?.addEventListener("click", () => {
        go(idx - 1);
        start();
      });
      next?.addEventListener("click", () => {
        go(idx + 1);
        start();
      });
      dots.forEach((dot) => {
        dot.addEventListener("click", () => {
          go(parseInt(dot.dataset.index || "0", 10));
          start();
        });
      });

      root.addEventListener("mouseenter", stop);
      root.addEventListener("mouseleave", start);
      root.addEventListener("focusin", stop);
      root.addEventListener("focusout", (e) => {
        if (!root.contains(e.relatedTarget)) start();
      });

      go(0);
      start();
    });
  }

  function initTrendingCarousel() {
    document.querySelectorAll(".kf-trending").forEach((root) => {
      const track = root.querySelector("[data-trending-track]");
      const prev = root.querySelector(".kf-trending__prev");
      const next = root.querySelector(".kf-trending__next");
      if (!track) return;

      const update = () => {
        const overflow = track.scrollWidth > track.clientWidth + 4;
        prev?.classList.toggle("kf-trending__nav--visible", overflow);
        next?.classList.toggle("kf-trending__nav--visible", overflow);
        if (!overflow) return;
        const max = track.scrollWidth - track.clientWidth;
        prev?.classList.toggle("is-disabled", track.scrollLeft <= 2);
        next?.classList.toggle("is-disabled", track.scrollLeft >= max - 2);
      };

      prev?.addEventListener("click", () => {
        track.scrollBy({ left: -Math.max(160, track.clientWidth * 0.6), behavior: reduced ? "auto" : "smooth" });
      });
      next?.addEventListener("click", () => {
        track.scrollBy({ left: Math.max(160, track.clientWidth * 0.6), behavior: reduced ? "auto" : "smooth" });
      });
      track.addEventListener("scroll", update, { passive: true });
      window.addEventListener("resize", update);
      update();
    });
  }

  function initPostsByCategorySwiper() {
    if (typeof window.Swiper === "undefined") return;

    document.querySelectorAll(".kf-posts-by-cat__swiper").forEach((slider) => {
      if (slider.swiper) return;

      new window.Swiper(slider, {
        slidesPerView: 3.5,
        spaceBetween: 24,
        grabCursor: true,
        allowTouchMove: true,
        simulateTouch: true,
        touchEventsTarget: "container",
        preventClicks: true,
        preventClicksPropagation: true,
        watchOverflow: true,
        keyboard: {
          enabled: true,
          onlyInViewport: true,
        },
        navigation: false,
        pagination: false,
      });
    });
  }

  function initEngagement() {
    const cfg = window.kratomFeed || {};

    document.querySelectorAll(".kf-eng-like").forEach((btn) => {
      btn.addEventListener("click", async () => {
        if (btn.getAttribute("aria-pressed") === "true" || btn.disabled) return;
        const postId = btn.dataset.postId;
        if (!postId || !cfg.ajaxUrl) return;

        btn.disabled = true;
        try {
          const body = new URLSearchParams({
            action: "kratom_feed_like_post",
            nonce: cfg.nonce || "",
            post_id: postId,
          });
          const res = await fetch(cfg.ajaxUrl, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" },
            body,
            credentials: "same-origin",
          });
          const json = await res.json();
          if (!json?.success) throw new Error("like failed");

          const countEl = btn.querySelector(".kf-eng-like__count");
          const label = json.data?.label || formatCount(json.data?.count);
          if (countEl) countEl.textContent = label;
          btn.setAttribute("aria-pressed", "true");
          const path = btn.querySelector("svg path");
          if (path) path.setAttribute("fill", "#ef4444");
        } catch {
          btn.disabled = false;
        }
      });
    });

    document.querySelectorAll(".kf-eng-share").forEach((btn) => {
      btn.addEventListener("click", async () => {
        const url = btn.dataset.shareUrl || location.href;
        const title = btn.dataset.shareTitle || document.title;
        try {
          if (navigator.share) {
            await navigator.share({ title, url });
            return;
          }
        } catch {
          /* fall through to copy */
        }
        try {
          await navigator.clipboard.writeText(url);
          const orig = btn.getAttribute("aria-label");
          btn.setAttribute("aria-label", cfg.i18n?.copied || "Link copied");
          window.setTimeout(() => btn.setAttribute("aria-label", orig || "Share"), 2000);
        } catch { /* noop */ }
      });
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    initHeaderShadow();
    initStorefrontHeader();
    initReadingProgress();
    initTOC();
    initCopyLink();
    initNewsletter();
    initArchiveFilter();
    initHeroFeaturedCarousel();
    initTrendingCarousel();
    initPostsByCategorySwiper();
    initEngagement();
  });
})();

