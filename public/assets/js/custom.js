(function () {
    const $themeConfig = {
        locale: "en", // en, da, de, el, es, fr, hu, it, ja, pl, pt, ru, sv, tr, zh
        theme: "light", // light, dark, system
        menu: "vertical", // Always vertical sidebar
        layout: "full", // full, boxed-layout
        rtlClass: "ltr", // rtl, ltr
        animation: "", // animate__fadeIn, animate__fadeInDown, animate__fadeInUp, animate__fadeInLeft, animate__fadeInRight, animate__slideInDown, animate__slideInLeft, animate__slideInRight, animate__zoomIn
        navbar: "navbar-sticky", // navbar-sticky, navbar-floating, navbar-static
        semidark: false,
    };
    const dismissLoader = () => {
        const screen_loader = document.getElementsByClassName("screen_loader");
        if (screen_loader?.length) {
            screen_loader[0].classList.add("animate__fadeOut");
            setTimeout(() => {
                if (screen_loader[0] && screen_loader[0].parentNode) {
                    screen_loader[0].parentNode.removeChild(screen_loader[0]);
                }
            }, 150);
        }
        if (typeof Alpine !== "undefined" && Alpine.store && Alpine.store("app")) {
            Alpine.store("app").setRTLLayout();
        }
    };

    if (document.readyState === "complete" || document.readyState === "interactive") {
        setTimeout(dismissLoader, 50);
    } else {
        document.addEventListener("DOMContentLoaded", dismissLoader);
        window.addEventListener("load", dismissLoader);
        setTimeout(dismissLoader, 300); // Safety fallback so user is never stuck
    }

    // remove animation after complete
    const element = document.querySelector(".dvanimation");
    element?.addEventListener("animationend", () => {
        element.classList.remove(Alpine.store("app").animation);
    });

    // set current year in footer
    const yearEle = document.querySelector("#footer-year");
    if (yearEle) {
        yearEle.innerHTML = new Date().getFullYear();
    }

    // perfect scrollbar
    const initPerfectScrollbar = () => {
        const container = document.querySelectorAll(".perfect-scrollbar");
        for (let i = 0; i < container.length; i++) {
            new PerfectScrollbar(container[i], {
                wheelPropagation: true,
                // suppressScrollX: true,
            });
        }
    };
    initPerfectScrollbar();

    document.addEventListener("alpine:init", () => {
        Alpine.data("collapse", () => ({
            collapse: false,

            collapseSidebar() {
                this.collapse = !this.collapse;
            },
        }));

        Alpine.data("dropdown", (initialOpenState = false) => ({
            open: initialOpenState,

            toggle() {
                this.open = !this.open;
            },
        }));

        Alpine.data("actionDropdown", () => ({
            instance: null,
            init() {
                this.$nextTick(() => {
                    const btn = this.$refs.btn;
                    const menu = this.$refs.menu;
                    if (!btn || !menu) return;

                    const initTippy = () => {
                        if (typeof tippy === "undefined") {
                            setTimeout(initTippy, 50);
                            return;
                        }
                        menu.classList.remove("hidden");
                        this.instance = tippy(btn, {
                            content: menu,
                            interactive: true,
                            trigger: "click",
                            placement: "bottom-end",
                            appendTo: () => document.body,
                            animation: "fade",
                            arrow: false,
                            offset: [0, 6],
                            theme: "table-action",
                            hideOnClick: true,
                            zIndex: 99999,
                        });
                    };

                    initTippy();
                });
            },
            destroy() {
                if (this.instance) {
                    this.instance.destroy();
                }
            },
        }));
        Alpine.data("modal", (initialOpenState = false) => ({
            open: initialOpenState,

            toggle() {
                this.open = !this.open;
            },
        }));

        // Magic: $tooltip
        Alpine.magic("tooltip", (el) => (message, placement) => {
            let instance = tippy(el, {
                content: message,
                trigger: "manual",
                placement: placement || undefined,
                allowHTML: true,
            });

            instance.show();
        });

        Alpine.directive(
            "dynamictooltip",
            (el, { expression }, { evaluate }) => {
                let string = evaluate(expression);
                tippy(el, {
                    content: string.charAt(0).toUpperCase() + string.slice(1),
                });
            },
        );

        // Directive: x-tooltip
        Alpine.directive("tooltip", (el, { expression }) => {
            tippy(el, {
                content: expression,
                placement: el.getAttribute("data-placement") || undefined,
                allowHTML: true,
                delay: el.getAttribute("data-delay") || 0,
                animation: el.getAttribute("data-animation") || "fade",
                theme: el.getAttribute("data-theme") || "",
            });
        });

        // Magic: $popovers
        Alpine.magic("popovers", (el) => (message, placement) => {
            let instance = tippy(el, {
                content: message,
                placement: placement || undefined,
                interactive: true,
                allowHTML: true,
                // hideOnClick: el.getAttribute("data-dismissable") ? true : "toggle",
                delay: el.getAttribute("data-delay") || 0,
                animation: el.getAttribute("data-animation") || "fade",
                theme: el.getAttribute("data-theme") || "",
                trigger: el.getAttribute("data-trigger") || "click",
            });

            instance.show();
        });

        // main - custom functions
        Alpine.data("main", (value) => ({}));

        Alpine.data("scrollToTop", () => ({
            showTopButton: false,
            init() {
                window.onscroll = () => {
                    this.scrollFunction();
                };
            },

            scrollFunction() {
                if (
                    document.body.scrollTop > 50 ||
                    document.documentElement.scrollTop > 50
                ) {
                    this.showTopButton = true;
                } else {
                    this.showTopButton = false;
                }
            },

            goToTop() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            },
        }));

        // theme customization
        Alpine.data("customizer", () => ({
            showCustomizer: false,
        }));

        // sidebar section
        Alpine.data("sidebar", () => ({
            init() {
                const selector = document.querySelector(
                    '.sidebar ul a[href="' + window.location.pathname + '"]',
                );
                if (selector) {
                    selector.classList.add("active");
                    const ul = selector.closest("ul.sub-menu");
                    if (ul) {
                        let ele = ul
                            .closest("li.menu")
                            .querySelectorAll(".nav-link");
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.click();
                            });
                        }
                    }
                }
            },
        }));

        // header section
        Alpine.data("header", () => ({
            init() {
                const selector = document.querySelector(
                    'ul.horizontal-menu a[href="' +
                        window.location.pathname +
                        '"]',
                );
                if (selector) {
                    selector.classList.add("active");
                    const ul = selector.closest("ul.sub-menu");
                    if (ul) {
                        let ele = ul
                            .closest("li.menu")
                            .querySelectorAll(".nav-link");
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.classList.add("active");
                            });
                        }
                    }
                }
            },

            notifications: [
                {
                    id: 1,
                    profile: "user-profile.jpeg",
                    message:
                        '<strong class="text-sm mr-1">John Doe</strong>invite you to <strong>Prototyping</strong>',
                    time: "45 min ago",
                },
                {
                    id: 2,
                    profile: "profile-34.jpeg",
                    message:
                        '<strong class="text-sm mr-1">Adam Nolan</strong>mentioned you to <strong>UX Basics</strong>',
                    time: "9h Ago",
                },
                {
                    id: 3,
                    profile: "profile-16.jpeg",
                    message:
                        '<strong class="text-sm mr-1">Anna Morgan</strong>Upload a file',
                    time: "9h Ago",
                },
            ],

            messages: [
                {
                    id: 1,
                    image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-success-light dark:bg-success text-success dark:text-success-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg></span>',
                    title: "Congratulations!",
                    message: "Your OS has been updated.",
                    time: "1hr",
                },
                {
                    id: 2,
                    image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-info-light dark:bg-info text-info dark:text-info-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg></span>',
                    title: "Did you know?",
                    message: "You can switch between artboards.",
                    time: "2hr",
                },
                {
                    id: 3,
                    image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-danger-light dark:bg-danger text-danger dark:text-danger-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></span>',
                    title: "Something went wrong!",
                    message: "Send Reposrt",
                    time: "2days",
                },
                {
                    id: 4,
                    image: '<span class="grid place-content-center w-9 h-9 rounded-full bg-warning-light dark:bg-warning text-warning dark:text-warning-light"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">    <circle cx="12" cy="12" r="10"></circle>    <line x1="12" y1="8" x2="12" y2="12"></line>    <line x1="12" y1="16" x2="12.01" y2="16"></line></svg></span>',
                    title: "Warning",
                    message: "Your password strength is low.",
                    time: "5days",
                },
            ],

            languages: [
                {
                    id: 1,
                    key: "Chinese",
                    value: "zh",
                },
                {
                    id: 2,
                    key: "Danish",
                    value: "da",
                },
                {
                    id: 3,
                    key: "English",
                    value: "en",
                },
                {
                    id: 4,
                    key: "French",
                    value: "fr",
                },
                {
                    id: 5,
                    key: "German",
                    value: "de",
                },
                {
                    id: 6,
                    key: "Greek",
                    value: "el",
                },
                {
                    id: 7,
                    key: "Hungarian",
                    value: "hu",
                },
                {
                    id: 8,
                    key: "Italian",
                    value: "it",
                },
                {
                    id: 9,
                    key: "Japanese",
                    value: "ja",
                },
                {
                    id: 10,
                    key: "Polish",
                    value: "pl",
                },
                {
                    id: 11,
                    key: "Portuguese",
                    value: "pt",
                },
                {
                    id: 12,
                    key: "Russian",
                    value: "ru",
                },
                {
                    id: 13,
                    key: "Spanish",
                    value: "es",
                },
                {
                    id: 14,
                    key: "Swedish",
                    value: "sv",
                },
                {
                    id: 15,
                    key: "Turkish",
                    value: "tr",
                },
                {
                    id: 16,
                    key: "Arabic",
                    value: "ae",
                },
            ],

            removeNotification(value) {
                this.notifications = this.notifications.filter(
                    (d) => d.id !== value,
                );
            },

            removeMessage(value) {
                this.messages = this.messages.filter((d) => d.id !== value);
            },
        }));

        Alpine.store("app", {
            // theme
            theme: Alpine.$persist($themeConfig.theme),
            isDarkMode: Alpine.$persist(false),
            toggleTheme(val) {
                if (!val) {
                    val = this.theme || $themeConfig.theme; // light|dark|system
                }

                this.theme = val;

                if (this.theme == "light") {
                    this.isDarkMode = false;
                } else if (this.theme == "dark") {
                    this.isDarkMode = true;
                } else if (this.theme == "system") {
                    if (
                        window.matchMedia &&
                        window.matchMedia("(prefers-color-scheme: dark)")
                            .matches
                    ) {
                        this.isDarkMode = true;
                    } else {
                        this.isDarkMode = false;
                    }
                }
            },

            // navigation menu
            menu: "vertical",
            toggleMenu(val) {
                this.sidebar = false;
                this.menu = "vertical";
            },

            // layout
            layout: Alpine.$persist($themeConfig.layout),
            toggleLayout(val) {
                if (!val) {
                    val = this.layout || $themeConfig.layout; // full, boxed-layout
                }

                this.layout = val;
            },

            // rtl support
            rtlClass: Alpine.$persist($themeConfig.rtlClass),
            toggleRTL(val) {
                if (!val) {
                    val = this.rtlClass || $themeConfig.rtlClass; // rtl, ltr
                }

                this.rtlClass = val;
                this.setRTLLayout();
            },

            setRTLLayout() {
                document
                    .querySelector("html")
                    .setAttribute(
                        "dir",
                        this.rtlClass || $themeConfig.rtlClass,
                    );
            },

            // animation
            animation: Alpine.$persist($themeConfig.animation),
            toggleAnimation(val) {
                if (!val) {
                    val = this.animation || $themeConfig.animation; // animate__fadeIn, animate__fadeInDown, animate__fadeInLeft, animate__fadeInRight
                }
                val = val?.trim();

                this.animation = val;
            },

            // navbar type
            navbar: Alpine.$persist($themeConfig.navbar),
            toggleNavbar(val) {
                if (!val) {
                    val = this.navbar || $themeConfig.navbar; // navbar-sticky, navbar-floating, navbar-static
                }

                this.navbar = val;
            },

            // semidark
            semidark: Alpine.$persist($themeConfig.semidark),
            toggleSemidark(val) {
                if (!val) {
                    val = this.semidark || $themeConfig.semidark;
                }

                this.semidark = val;
            },

            // multi language
            locale: Alpine.$persist($themeConfig.locale),
            toggleLocale(val) {
                if (!val) {
                    val = this.locale || $themeConfig.locale;
                }

                this.locale = val;
                if (this.locale?.toLowerCase() === "ae") {
                    this.toggleRTL("rtl");
                } else {
                    this.toggleRTL("ltr");
                }
            },

            // sidebar
            sidebar: false,
            toggleSidebar() {
                this.sidebar = !this.sidebar;
            },
        });
    });
})();
