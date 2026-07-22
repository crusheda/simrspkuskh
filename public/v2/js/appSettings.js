(() => {
    'use strict';

    const APP_SIDEBAR_BREAKPOINT = 1191;
    const docEl = document.documentElement;

    let appSettings = {
        appTheme: 'light',
        appSidebar: 'full',
        appColor: 'blue',
    };

    function setAppSettings(newSettings = {}) {
        appSettings = {
            ...appSettings,
            ...newSettings
        };

        applySettings();
    }

    function applySettings() {

        docEl.setAttribute('data-bs-theme', appSettings.appTheme);

        // Desktop
        if (window.innerWidth >= APP_SIDEBAR_BREAKPOINT) {
            docEl.setAttribute('data-app-sidebar', appSettings.appSidebar);
        }
        // Mobile
        else {
            docEl.setAttribute('data-app-sidebar', 'mini-hover');
        }

        docEl.setAttribute('data-color-theme', appSettings.appColor);
    }

    document.addEventListener('DOMContentLoaded', applySettings);

    window.addEventListener('resize', applySettings);

    window.setAppSettings = setAppSettings;

})();
