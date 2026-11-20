
(() => {
    const textEl = document.getElementById('simgosStatusText');
    const indicatorElUp = document.getElementById('simgosStatusIndicatorUp');
    const indicatorEl = document.getElementById('simgosStatusIndicator');
    const iconEl = document.getElementById('simgosStatusIcon');

    if (!textEl || !indicatorElUp || !indicatorEl || !iconEl) return;

    let checking = false;

    function setStatus(online) {
        if (online) {
            indicatorElUp.classList.remove('is-down');
            indicatorElUp.classList.add('is-online');
            textEl.textContent = 'Terhubung dengan sistem';
            indicatorEl.classList.remove('is-down');
            indicatorEl.classList.add('is-online');

            iconEl.classList.remove('is-down');
            iconEl.classList.add('is-online');
            iconEl.innerHTML = '<i class="bi bi-shield-check"></i>';
        } else {
            indicatorElUp.classList.remove('is-online');
            indicatorElUp.classList.add('is-down');
            textEl.textContent = 'Sistem Utama sedang Down';
            indicatorEl.classList.remove('is-online');
            indicatorEl.classList.add('is-down');

            iconEl.classList.remove('is-online');
            iconEl.classList.add('is-down');
            iconEl.innerHTML = '<i class="bi bi-exclamation-triangle"></i>';
        }
    }

    async function checkSimgos() {
        if (checking) return;

        checking = true;

        try {
            const endpoint = document
                .getElementById('welcomeBanner')
                ?.dataset.simgosStatusUrl;

            if (!endpoint) return;

            const response = await fetch(endpoint, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                cache: 'no-store'
            });

            if (!response.ok) {
                throw new Error('Health check request failed');
            }

            const data = await response.json();
            setStatus(data.online === true);

        } catch (error) {
            setStatus(false);
        } finally {
            checking = false;
        }
    }

    // Cek pertama kali saat halaman dibuka
    checkSimgos();

    // Cek ulang setiap 30 detik
    setInterval(checkSimgos, 30000);

})();
