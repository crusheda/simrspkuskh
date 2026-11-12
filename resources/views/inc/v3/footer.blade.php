<footer class="content-footer">
    <span class="footer-brand">
        <span>© {{ date('Y') }}</span>
        <strong>SIRMED V3</strong>
    </span>

    <span class="footer-right">
        <span id="footer-clock">Pukul --:--:-- WIB</span>
        {{-- <span class="footer-separator">·</span> --}}
        <span>
            {{-- Dibuat untuk pelayanan yang lebih baik --}}
            <i
                class="bi bi-heart-fill footer-heart"
                aria-label="dengan sepenuh hati"
                role="img"
            ></i>
        </span>
    </span>
</footer>

<script>
    $(document).ready(function () {

        function updateFooterClock() {
            const now = new Date();

            const time = now.toLocaleTimeString('id-ID', {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });

            $('#footer-clock').html(`Pukul <b>${time}</b> WIB`);
        }

        // Update pertama kali
        updateFooterClock();

        // Update setiap 1 detik
        setInterval(updateFooterClock, 1000);

    });
</script>
