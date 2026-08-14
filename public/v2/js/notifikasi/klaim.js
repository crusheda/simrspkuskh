(function () {

    'use strict';

    const NotificationKlaim = {

        endpoint: '/api/v2/notifikasi/klaim',

        interval: 30000,

        init: function () {

            this.load();

            // Refresh setiap 30 detik
            setInterval(() => {
                this.load();
            }, this.interval);

        },

        load: function () {

            $.ajax({
                url: this.endpoint,
                type: 'GET',
                dataType: 'json',

                success: function (response) {

                    if (!response || !response.status) {
                        return;
                    }

                    NotificationKlaim.render(response.data || []);
                    NotificationKlaim.updateBadge(response.total || 0);

                },

                error: function (xhr) {

                    console.warn(
                        'Gagal mengambil notifikasi klaim:',
                        xhr.status,
                        xhr.responseText
                    );

                }

            });

        },

        updateBadge: function (total) {

            const $badge = $('#notificationBadge');
            const $headerBadge = $('#notificationHeaderBadge');

            if (!$badge.length) {
                return;
            }

            if (total > 0) {

                $badge.show();

                $headerBadge
                    .text(total > 99 ? '99+' : total)
                    .show();

            } else {

                $badge.hide();
                $headerBadge.hide();

            }

        },

        render: function (data) {

            const $list = $('#notificationList');

            if (!$list.length) {
                return;
            }

            $list.empty();

            if (!data.length) {

                $list.html(`
                    <li class="list-group-item text-center py-4">

                        <div class="text-muted mb-2">
                            <i class="ti ti-bell-off fs-3"></i>
                        </div>

                        <small class="text-muted">
                            Tidak ada notifikasi baru.
                        </small>

                    </li>
                `);

                return;
            }

            data.forEach(function (item) {

                const deskripsi = NotificationKlaim.escapeHtml(
                    item.deskripsi || ''
                );

                const nomor = NotificationKlaim.escapeHtml(
                    item.nomor || ''
                );

                const waktu = NotificationKlaim.formatTime(
                    item.created_at
                );

                const url = item.url || `/v2/emr/${item.nomor}`;

                $list.append(`

                    <li class="list-group-item p-2">

                        <a href="${url}" class="text-decoration-none text-body d-flex align-items-start">

                            <div class="avatar avatar-xs rounded-circle me-2 flex-shrink-0">
                                <div class="avatar-initial rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center">
                                    <i class="ti ti-file-description"></i>
                                </div>
                            </div>

                            <div class="ms-1 me-auto w-100"
                                style="min-width: 0;">

                                <div class="d-flex justify-content-between align-items-start gap-2 w-100">

                                    <h6 class="mb-1 text-body">
                                        Verifikasi Klaim
                                    </h6>

                                    <small class="text-muted text-nowrap ms-auto">
                                        ${waktu}
                                    </small>

                                </div>

                                <small class="text-muted d-block">
                                    No. Klaim:
                                    <strong>${nomor}</strong>
                                </small>

                                <small class="text-body d-block text-truncate"
                                    title="${deskripsi}">
                                    ${deskripsi}
                                </small>

                            </div>

                        </a>

                    </li>

                `);

            });

        },

        formatTime: function (dateString) {

            if (!dateString) {
                return '';
            }

            const date = new Date(
                dateString.replace(' ', 'T')
            );

            if (isNaN(date.getTime())) {
                return '';
            }

            const currentYear = new Date().getFullYear();
            const dateYear = date.getFullYear();

            if (dateYear === currentYear) {
                return date.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short'
                });
            }

            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });

        },

        escapeHtml: function (text) {

            return $('<div>')
                .text(text)
                .html();

        }

    };

    // Tunggu jQuery dan DOM
    $(document).ready(function () {

        NotificationKlaim.init();

    });

})();
