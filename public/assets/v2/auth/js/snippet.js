/*!
 * login-page-04 - Colorlib. No jQuery, no framework.
 * Behaviours: own-script
 */
(() => {
    "use strict";

    const root = document.querySelector(".cl-login04");
    if (!root) return;

    const form = root.querySelector("[data-form]");
    const live = root.querySelector("[data-live]");

    if (!form) return;

    const messageFor = (el) => {
        const val = el.value.trim();

        if (el.required && !val)
            return el.dataset.msg || "This field is required.";

        if (el.validity.typeMismatch)
            return "That email looks incomplete.";

        if (el.minLength > 0 && el.value.length < el.minLength)
            return `At least ${el.minLength} characters.`;

        return "";
    };

    const check = (field) => {
        const el = field.querySelector("input");
        const error = field.querySelector("[data-error]");
        const msg = messageFor(el);

        if (error) error.textContent = msg;

        field.classList.toggle("is-error", !!msg);

        if (msg) {
            el.setAttribute("aria-invalid", "true");
        } else {
            el.removeAttribute("aria-invalid");
        }

        return !msg;
    };

    const fields = Array.from(
        form.querySelectorAll("[data-field]")
    );

    /* Validasi field */
    fields.forEach((field) => {

        field.addEventListener("focusout", (e) => {

            if (
                field.contains(e.relatedTarget) ||
                (e.relatedTarget &&
                    e.relatedTarget.matches("button")) ||
                !field.querySelector("input").value
            ) {
                return;
            }

            field.dataset.touched = "1";
            check(field);
        });

        field.addEventListener("input", () => {

            if (field.dataset.touched) {
                check(field);
            }
        });
    });

    /* Toggle password */
    root.querySelectorAll("[data-pw-toggle]").forEach((btn) => {

        const input = document.getElementById(
            btn.getAttribute("aria-controls")
        );

        if (!input) return;

        btn.addEventListener("click", () => {

            const show = input.type === "password";

            input.type = show ? "text" : "password";

            btn.setAttribute(
                "aria-pressed",
                String(show)
            );
        });
    });

    /* Provider button */
    root.querySelectorAll("[data-provider]").forEach((btn) => {

        btn.addEventListener("click", () => {

            if (live) {
                live.textContent =
                    `Demo — ${btn.dataset.provider} sign-in is not connected here.`;
            }
        });
    });

    /* Submit login */
    form.addEventListener("submit", (e) => {

        fields.forEach((field) => {
            field.dataset.touched = "1";
        });

        const bad = fields.filter(
            (field) => !check(field)
        );

        /* Validasi gagal */
        if (bad.length) {

            e.preventDefault();

            if (live) {
                live.textContent =
                    bad.length === 1
                        ? "One field perlu diperiksa."
                        : `${bad.length} field perlu diperiksa.`;
            }

            bad[0]
                .querySelector("input")
                .focus();

            return;
        }

        /*
        |----------------------------------------------------------------------
        | LOGIN VALID
        |----------------------------------------------------------------------
        | Jangan e.preventDefault().
        | Form diteruskan ke Laravel.
        */

        const btn = form.querySelector(
            '[type="submit"]'
        );

        if (!btn) return;

        btn.disabled = true;

        const text = btn.querySelector(
            ".btn-login-text"
        );

        const loading = btn.querySelector(
            ".btn-login-loading"
        );

        const arrow = btn.querySelector(
            ".btn-login-arrow"
        );

        if (text) {
            text.classList.add("d-none");
        }

        if (loading) {
            loading.classList.remove("d-none");
        }

        if (arrow) {
            arrow.classList.add("d-none");
        }
    });
})();
