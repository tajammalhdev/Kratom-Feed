/**
 * Kratom Feed — Strain recommendation quiz (vanilla JS).
 * Scoring is client-side only. Gate is UI-only; no network submit / storage.
 */
(function () {
  "use strict";

  function prefersReducedMotion() {
    return window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  }

  function parseConfig(root) {
    const node = root.querySelector(".kf-quiz__config");
    if (!node) return null;
    try {
      return JSON.parse(node.textContent || "{}");
    } catch {
      return null;
    }
  }

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(value || "").trim());
  }

  function scrollToQuiz(root) {
    const top = root.getBoundingClientRect().top + window.scrollY - 80;
    window.scrollTo({ top: Math.max(0, top), behavior: prefersReducedMotion() ? "auto" : "smooth" });
  }

  function announce(root, message) {
    const status = root.querySelector("[data-quiz-status]");
    if (status) status.textContent = message || "";
  }

  function getOptionByKey(question, optionKey) {
    return (question.options || []).find((o) => o.key === optionKey) || null;
  }

  function scoreAnswers(config, answers) {
    const scores = {};
    const questions = config.questions || [];

    questions.forEach((q) => {
      const selected = answers[q.key];
      if (!selected) return;
      const opt = getOptionByKey(q, selected);
      if (!opt) return;
      (opt.weights || []).forEach((w) => {
        const key = w.result_key;
        if (!key) return;
        scores[key] = (scores[key] || 0) + (Number(w.points) || 0);
      });
    });

    const ranked = Object.keys(scores)
      .map((key) => {
        const result = (config.results && config.results[key]) || null;
        return {
          key,
          score: scores[key],
          priority: result ? Number(result.priority) || 100 : 100,
          result,
        };
      })
      .filter((row) => row.result)
      .sort((a, b) => {
        if (b.score !== a.score) return b.score - a.score;
        return a.priority - b.priority;
      });

    return ranked;
  }

  function findBonus(config, answers, usedKeys) {
    const questions = config.questions || [];
    for (let i = 0; i < questions.length; i++) {
      const q = questions[i];
      const selected = answers[q.key];
      if (!selected) continue;
      const opt = getOptionByKey(q, selected);
      if (!opt || !opt.bonus_result) continue;
      if (usedKeys.has(opt.bonus_result)) continue;
      const result = config.results && config.results[opt.bonus_result];
      if (result) return { key: opt.bonus_result, result };
    }
    return null;
  }

  function fillCard(root, prefix, result, ctaFallback) {
    const name = root.querySelector(`[data-quiz-${prefix}-name]`);
    const format = root.querySelector(`[data-quiz-${prefix}-format]`);
    const why = root.querySelector(`[data-quiz-${prefix}-why]`);
    const cta = root.querySelector(`[data-quiz-${prefix}-cta]`);
    const tags = root.querySelector(`[data-quiz-${prefix}-tags]`);
    const caution = root.querySelector(`[data-quiz-${prefix}-caution]`);

    if (name) name.textContent = result.name || "";
    if (format) {
      format.textContent = result.format || "";
      format.hidden = !result.format;
    }
    if (why) why.textContent = result.why || "";
    if (cta) {
      cta.href = result.ctaUrl || "#";
      cta.textContent = result.ctaLabel || ctaFallback || cta.textContent;
    }
    if (tags) {
      tags.innerHTML = "";
      (result.tags || []).forEach((tag) => {
        const span = document.createElement("span");
        span.className =
          "inline-block rounded bg-neutral-100 px-2.5 py-0.5 text-xs text-neutral-600";
        span.textContent = tag;
        tags.appendChild(span);
      });
    }
    if (caution) {
      caution.textContent = result.caution || "";
      caution.classList.toggle("hidden", !result.caution);
    }
  }

  function initQuiz(root) {
    if (root.dataset.kfQuizReady === "1") return;
    const config = parseConfig(root);
    if (!config || !Array.isArray(config.questions) || !config.questions.length) return;

    root.dataset.kfQuizReady = "1";

    const total = config.questions.length;
    let step = 1;
    const answers = {};

    const panels = {
      steps: root.querySelector('[data-quiz-panel="steps"]'),
      gate: root.querySelector('[data-quiz-panel="gate"]'),
      results: root.querySelector('[data-quiz-panel="results"]'),
    };

    const progressEl = root.querySelector("[data-quiz-progress]");
    const progressBar = root.querySelector("[data-quiz-progressbar]");
    const progressText = root.querySelector("[data-quiz-progress-text]");

    function setPanel(name) {
      Object.keys(panels).forEach((key) => {
        if (!panels[key]) return;
        panels[key].hidden = key !== name;
      });
    }

    function updateProgress() {
      const pct = Math.round((step / total) * 100);
      if (progressEl) progressEl.style.width = pct + "%";
      if (progressBar) {
        progressBar.setAttribute("aria-valuenow", String(step));
        progressBar.setAttribute("aria-valuemax", String(total));
      }
      if (progressText) {
        const label = (config.progressLabel || "Question {current} of {total}")
          .replace("{current}", String(step))
          .replace("{total}", String(total));
        progressText.textContent = label;
      }
    }

    function getStepEl(n) {
      return root.querySelector('[data-quiz-step="' + n + '"]');
    }

    function syncStepControls(stepEl) {
      if (!stepEl) return;
      const qKey = stepEl.getAttribute("data-question-key");
      const nextBtn = stepEl.querySelector("[data-quiz-next]");
      const backBtn = stepEl.querySelector("[data-quiz-back]");
      const selected = answers[qKey];
      if (nextBtn) nextBtn.disabled = !selected;
      if (backBtn) backBtn.disabled = step <= 1;

      stepEl.querySelectorAll("[data-quiz-option]").forEach((btn) => {
        const key = btn.getAttribute("data-option-key");
        const isSelected = key === selected;
        btn.classList.toggle("is-selected", isSelected);
        btn.setAttribute("aria-checked", isSelected ? "true" : "false");
      });
    }

    function showStep(n, focusOptions) {
      step = n;
      root.querySelectorAll("[data-quiz-step]").forEach((el) => {
        const num = Number(el.getAttribute("data-quiz-step"));
        const active = num === step;
        el.classList.toggle("is-active", active);
        el.hidden = !active;
      });
      updateProgress();
      syncStepControls(getStepEl(step));
      setPanel("steps");
      if (focusOptions) {
        const firstOpt = getStepEl(step)?.querySelector("[data-quiz-option]");
        firstOpt?.focus();
      }
      scrollToQuiz(root);
    }

    function selectOption(stepEl, optionBtn) {
      const qKey = stepEl.getAttribute("data-question-key");
      const optKey = optionBtn.getAttribute("data-option-key");
      if (!qKey || !optKey) return;
      answers[qKey] = optKey;
      syncStepControls(stepEl);
      announce(root, "");

      // Auto-advance shortly after selection for a snappy flow.
      window.setTimeout(() => {
        if (answers[qKey] !== optKey) return;
        if (step < total) {
          showStep(step + 1, true);
        } else {
          openGate();
        }
      }, prefersReducedMotion() ? 0 : 280);
    }

    function openGate() {
      setPanel("gate");
      const email =
        (root.id && document.getElementById(root.id + "-email")) ||
        root.querySelector('input[name="email"]');
      email?.focus();
      scrollToQuiz(root);
    }

    function openResults(firstName) {
      const ranked = scoreAnswers(config, answers);
      const fallbackKey = "green_maeng_da";
      const primary =
        ranked[0] ||
        (config.results[fallbackKey]
          ? { key: fallbackKey, result: config.results[fallbackKey], score: 0 }
          : null);

      if (!primary) return;

      const used = new Set([primary.key]);
      let secondary = ranked[1] || null;
      if (secondary) used.add(secondary.key);

      // If only one scored result, invent a gentle secondary from next priority result.
      if (!secondary) {
        const alt = Object.keys(config.results || {})
          .map((key) => ({
            key,
            result: config.results[key],
            priority: Number(config.results[key].priority) || 100,
          }))
          .filter((row) => !used.has(row.key))
          .sort((a, b) => a.priority - b.priority)[0];
        if (alt) {
          secondary = alt;
          used.add(alt.key);
        }
      }

      const bonus = findBonus(config, answers, used);

      const titleEl = root.querySelector("[data-quiz-results-title]");
      if (titleEl) {
        titleEl.textContent = firstName
          ? (config.resultsTitle || "Here's What We Recommend") + ", " + firstName
          : config.resultsTitle || "Here's What We Recommend";
      }

      fillCard(root, "primary", primary.result, config.ctaPrimary);
      if (secondary) {
        const secWrap = root.querySelector("[data-quiz-secondary]");
        if (secWrap) secWrap.hidden = false;
        fillCard(root, "secondary", secondary.result, config.ctaSecondary);
      }

      const bonusWrap = root.querySelector("[data-quiz-bonus]");
      if (bonusWrap) {
        if (bonus) {
          bonusWrap.hidden = false;
          fillCard(root, "bonus", bonus.result, config.ctaSecondary);
        } else {
          bonusWrap.hidden = true;
        }
      }

      setPanel("results");
      scrollToQuiz(root);
    }

    function retake() {
      Object.keys(answers).forEach((k) => delete answers[k]);
      root.querySelectorAll("[data-quiz-option]").forEach((btn) => {
        btn.classList.remove("is-selected");
        btn.setAttribute("aria-checked", "false");
      });
      const bonusWrap = root.querySelector("[data-quiz-bonus]");
      if (bonusWrap) bonusWrap.hidden = true;
      showStep(1, true);
    }

    root.addEventListener("click", (event) => {
      const option = event.target.closest("[data-quiz-option]");
      if (option && root.contains(option)) {
        const stepEl = option.closest("[data-quiz-step]");
        if (stepEl) selectOption(stepEl, option);
        return;
      }

      const next = event.target.closest("[data-quiz-next]");
      if (next && root.contains(next)) {
        const stepEl = next.closest("[data-quiz-step]");
        const qKey = stepEl?.getAttribute("data-question-key");
        if (!qKey || !answers[qKey]) {
          announce(root, (config.i18n && config.i18n.selectOption) || "Please select an option.");
          return;
        }
        if (step < total) showStep(step + 1, true);
        else openGate();
        return;
      }

      const back = event.target.closest("[data-quiz-back]");
      if (back && root.contains(back) && step > 1) {
        showStep(step - 1, true);
        return;
      }

      const retakeBtn = event.target.closest("[data-quiz-retake]");
      if (retakeBtn && root.contains(retakeBtn)) {
        retake();
      }
    });

    root.addEventListener("keydown", (event) => {
      const option = event.target.closest("[data-quiz-option]");
      if (!option || !root.contains(option)) return;
      const group = option.closest('[role="radiogroup"]');
      if (!group) return;
      const options = Array.from(group.querySelectorAll("[data-quiz-option]"));
      const idx = options.indexOf(option);
      if (event.key === "ArrowDown" || event.key === "ArrowRight") {
        event.preventDefault();
        options[(idx + 1) % options.length]?.focus();
      } else if (event.key === "ArrowUp" || event.key === "ArrowLeft") {
        event.preventDefault();
        options[(idx - 1 + options.length) % options.length]?.focus();
      } else if (event.key === " " || event.key === "Enter") {
        event.preventDefault();
        option.click();
      }
    });

    const gateForm = root.querySelector("[data-quiz-gate-form]");
    gateForm?.addEventListener("submit", (event) => {
      event.preventDefault();
      const emailInput = gateForm.querySelector('input[name="email"]');
      const nameInput = gateForm.querySelector('input[name="first_name"]');
      const email = emailInput ? emailInput.value.trim() : "";
      const firstName = nameInput ? nameInput.value.trim() : "";
      if (!isValidEmail(email)) {
        announce(root, (config.i18n && config.i18n.emailRequired) || "Please enter a valid email.");
        emailInput?.focus();
        return;
      }
      // UI-only gate: no AJAX, no Omnisend, no localStorage of answers/email.
      openResults(firstName);
    });

    updateProgress();
    syncStepControls(getStepEl(1));
  }

  function boot() {
    document.querySelectorAll("[data-kf-quiz]").forEach(initQuiz);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", boot);
  } else {
    boot();
  }
})();
