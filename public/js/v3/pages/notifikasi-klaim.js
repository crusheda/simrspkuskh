(function ($, window, document) {
    'use strict';

    if (!$ || window.NotificationKlaim) {
        return;
    }

    const NotificationKlaim = {
        endpoint: null,
        allUrl: null,
        interval: 30000,
        timer: null,
        xhr: null,

        init: function () {
            const $dropdown = $('#notificationDropdown');
            const $menu = $dropdown.closest('.notification-dropdown');

            // Tidak ada header pada halaman iframe detail pasien.
            if (!$dropdown.length || !$menu.length) {
                return;
            }

            this.endpoint = $menu.data('endpoint');
            this.allUrl = $menu.data('all-url');

            if (!this.endpoint) {
                console.error('Endpoint notifikasi belum dikonfigurasi.');
                return;
            }

            this.bindEvents();
            this.load();

            // Pastikan hanya ada satu timer polling.
            if (this.timer) {
                clearInterval(this.timer);
            }

            this.timer = setInterval(() => {
                this.load();
            }, this.interval);
        },

        bindEvents: function () {
            const self = this;

            // Klik notifikasi dan ambil detail pasien sebelum membuka workspace.
            $(document)
                .off('click.notificationKlaim', '.js-notification-item')
                .on('click.notificationKlaim', '.js-notification-item', function (event) {
                    event.preventDefault();

                    const $item = $(this);

                    const id = String($item.attr('data-id') || '').trim();
                    const nomor = String($item.attr('data-nomor') || '').trim();
                    const norm = String($item.attr('data-norm') || '').trim();
                    const nama = String($item.attr('data-nama') || '').trim();
                    const url = self.safeUrl($item.attr('href'));

                    if (!nomor || !url) {
                        console.warn('Nomor kunjungan atau URL notifikasi tidak valid.');
                        return;
                    }

                    // Tutup dropdown sebelum membuka workspace.
                    const dropdownElement = document.getElementById('notificationDropdown');

                    if (dropdownElement && window.bootstrap) {
                        window.bootstrap.Dropdown
                            .getOrCreateInstance(dropdownElement)
                            .hide();
                    }

                    /**
                     * Membuka EMR Workspace dengan data pasien yang sudah dipetakan.
                     */
                    const openWorkspace = function (patientData = {}) {
                        const getValue = function (...values) {
                            for (const value of values) {
                                if (
                                    value !== undefined &&
                                    value !== null &&
                                    String(value).trim() !== ''
                                ) {
                                    return value;
                                }
                            }

                            return '';
                        };

                        const patient = {
                            nomor: nomor,

                            norm: getValue(
                                patientData.norm,
                                patientData.NORM,
                                norm
                            ),

                            nama: getValue(
                                patientData.nama,
                                patientData.NAMAPASIEN,
                                nama,
                                'Pasien'
                            ),

                            tglLahir: getValue(
                                patientData.tglLahir,
                                patientData.TGLLAHIRPASIEN,
                                patientData.TANGGAL_LAHIR
                            ),

                            umur: getValue(
                                patientData.umur,
                                patientData.UMURPASIEN,
                                patientData.UMUR
                            ),

                            namaRuangan: getValue(
                                patientData.namaRuangan,
                                patientData.NAMARUANGAN,
                                patientData.NAMARUANG
                            ),

                            namaDokter: getValue(
                                patientData.namaDokter,
                                patientData.NAMADOKTER,
                                patientData.DPJP
                            ),

                            namaAsuransi: getValue(
                                patientData.namaAsuransi,
                                patientData.NAMAASURANSI
                            ),

                            refIdAsuransi: getValue(
                                patientData.refIdAsuransi,
                                patientData.REFIDASURANSI,
                                patientData.REF_ID_ASURANSI
                            ),

                            idAsuransi: getValue(
                                patientData.idAsuransi,
                                patientData.IDASURANSI,
                                patientData.ID_ASURANSI
                            ),

                            noAsuransi: getValue(
                                patientData.noAsuransi,
                                patientData.NOASURANSI,
                                patientData.NOBPJS,
                                patientData.NO_KARTU
                            ),

                            tglMasuk: getValue(
                                patientData.tglMasuk,
                                patientData.TGLMASUK,
                                patientData.TANGGALMASUK
                            ),

                            tglKeluar: getValue(
                                patientData.tglKeluar,
                                patientData.TGLKELUAR,
                                patientData.TANGGALKELUAR
                            ),

                            statusKunjungan: getValue(
                                patientData.statusKunjungan,
                                patientData.STATUSKUNJUNGAN
                            ),

                            url: url
                        };

                        const currentPath = window.location.pathname.replace(/\/+$/, '');
                        const isMedicalRecordPage = currentPath === '/v3/emr';

                        if (!isMedicalRecordPage) {
                            try {
                                sessionStorage.setItem(
                                    'sirmed.pendingEmrPatient',
                                    JSON.stringify(patient)
                                );
                            } catch (error) {
                                console.error('Gagal menyimpan kunjungan:', error);
                                return;
                            }

                            if (
                                window.Livewire &&
                                typeof window.Livewire.navigate === 'function'
                            ) {
                                window.Livewire.navigate('/v3/emr');
                            } else {
                                console.error('Livewire.navigate tidak tersedia.');
                            }

                            return;
                        }

                        if (
                            window.EMRWorkspace &&
                            typeof window.EMRWorkspace.openPatient === 'function'
                        ) {
                            window.EMRWorkspace.openPatient(patient);
                        } else {
                            console.error(
                                'EMRWorkspace belum tersedia pada halaman Medical Record.'
                            );
                        }
                    };

                    // Detail pasien harus diambil agar parameter workspace lengkap.
                    if (!id) {
                        console.warn(
                            'ID notifikasi tidak tersedia untuk mengambil detail pasien.'
                        );

                        openWorkspace({
                            norm: norm,
                            nama: nama
                        });

                        return;
                    }

                    $.ajax({
                        url: `/v3/setting/notifikasi/data/${encodeURIComponent(id)}`,
                        type: 'GET',
                        dataType: 'json',
                        cache: false,

                        success: function (response) {
                            if (!response || response.status !== true) {
                                console.warn('Respons detail notifikasi tidak valid.');
                                openWorkspace({
                                    norm: norm,
                                    nama: nama
                                });

                                return;
                            }

                            const detail = response.data || {};
                            const pasien = detail.pasien || detail;

                            // Gabungkan data detail notifikasi dengan data pasien.
                            openWorkspace({
                                ...detail,
                                ...pasien,

                                norm: pasien.NORM || pasien.norm || norm,

                                nama:
                                    pasien.NAMAPASIEN ||
                                    pasien.nama ||
                                    nama,

                                tglLahir:
                                    pasien.TGLLAHIRPASIEN ||
                                    pasien.tglLahir,

                                umur:
                                    pasien.UMURPASIEN ||
                                    pasien.umur ||
                                    pasien.UMUR,

                                namaRuangan:
                                    pasien.NAMARUANGAN ||
                                    pasien.namaRuangan,

                                namaDokter:
                                    pasien.NAMADOKTER ||
                                    pasien.namaDokter,

                                namaAsuransi:
                                    pasien.NAMAASURANSI ||
                                    pasien.namaAsuransi,

                                refIdAsuransi:
                                    pasien.REFIDASURANSI ||
                                    pasien.refIdAsuransi,

                                idAsuransi:
                                    pasien.IDASURANSI ||
                                    pasien.idAsuransi,

                                noAsuransi:
                                    pasien.NOASURANSI ||
                                    pasien.NOBPJS ||
                                    pasien.noAsuransi,

                                tglMasuk:
                                    pasien.TGLMASUK ||
                                    pasien.tglMasuk,

                                tglKeluar:
                                    pasien.TGLKELUAR ||
                                    pasien.tglKeluar,

                                statusKunjungan:
                                    pasien.STATUSKUNJUNGAN ||
                                    pasien.statusKunjungan
                            });
                        },

                        error: function (xhr) {
                            console.error(
                                'Gagal mengambil detail pasien dari notifikasi:',
                                xhr.status,
                                xhr.responseJSON || xhr.responseText
                            );

                            // Tetap buka kunjungan jika detail gagal diambil.
                            openWorkspace({
                                norm: norm,
                                nama: nama
                            });
                        }
                    });
                });

            // Muat ulang saat dropdown dibuka.
            $(document)
                .off('shown.bs.dropdown.notificationKlaim', '#notificationDropdown')
                .on(
                    'shown.bs.dropdown.notificationKlaim',
                    '#notificationDropdown',
                    function () {
                        self.load();
                    }
                );

            // Klik tombol footer membuka halaman semua notifikasi.
            $(document)
                .off('click.notificationAll', '.notification-all-link')
                .on('click.notificationAll', '.notification-all-link', function (event) {
                    const url = self.safeUrl(self.allUrl);

                    if (!url) {
                        event.preventDefault();
                        console.error('URL halaman semua notifikasi tidak valid.');
                        return;
                    }

                    $(this).attr('href', url);
                });
        },

        load: function () {
            if (this.xhr && this.xhr.readyState !== 4) {
                return;
            }

            this.xhr = $.ajax({
                url: this.endpoint,
                type: 'GET',
                dataType: 'json',
                cache: false,

                success: (response) => {
                    if (!response || response.status !== true) {
                        return;
                    }

                    this.updateBadge(response.total || 0);
                    this.render(response.data || []);
                },

                error: (xhr) => {
                    if (xhr.status !== 401 && xhr.status !== 419) {
                        console.warn(
                            'Gagal mengambil notifikasi klaim:',
                            xhr.status
                        );
                    }
                }
            });
        },

        updateBadge: function (total) {
            const count = Math.max(0, Number(total) || 0);
            const label = count > 99 ? '99+' : String(count);

            $('#notificationBadge').toggle(count > 0);

            $('#notificationHeaderBadge')
                .text(label)
                .toggle(count > 0);
        },

        render: function (data) {
            const $list = $('#notificationList');

            if (!$list.length) {
                return;
            }

            $list.empty();

            if (!Array.isArray(data) || data.length === 0) {
                const $state = $('<li>', {
                    class: 'notification-state'
                });

                $('<i>', {
                    class: 'bi bi-bell-slash'
                }).appendTo($state);

                $('<span>', {
                    text: 'Tidak ada notifikasi baru.'
                }).appendTo($state);

                $list.append($state);
                return;
            }

            data.forEach((item) => {
                const nomor = String(item.nomor || '');
                const deskripsi = String(item.deskripsi || '');
                const waktu = this.formatTime(item.created_at);
                const url = this.safeUrl(item.url);

                if (!url || !nomor) {
                    return;
                }

                const $link = $('<a>', {
                    class: 'notification-item-link js-notification-item',
                    href: url
                })
                    .attr('data-id', item.id || '')
                    .attr('data-nomor', nomor)
                    .attr('data-norm', item.norm || item.NORM || '')
                    .attr('data-nama', item.nama || item.NAMAPASIEN || '');

                const $icon = $('<div>', {
                    class: 'notification-item-icon'
                }).append(
                    $('<i>', {
                        class: 'bi bi-file-earmark-medical'
                    })
                );

                const $content = $('<div>', {
                    class: 'notification-item-content'
                });

                const $top = $('<div>', {
                    class: 'notification-item-top'
                });

                $('<span>', {
                    class: 'notification-item-title',
                    text: 'Verifikasi Klaim'
                }).appendTo($top);

                $('<span>', {
                    class: 'notification-item-time',
                    text: waktu
                }).appendTo($top);

                $('<span>', {
                    class: 'notification-item-number',
                    text: 'No. Kunjungan: ' + nomor
                }).appendTo($content);

                $('<span>', {
                    class: 'notification-item-description',
                    text: deskripsi || 'Terdapat notifikasi yang perlu ditinjau.'
                })
                    .attr('title', deskripsi)
                    .appendTo($content);

                $content.prepend($top);
                $link.append($icon, $content);

                $('<li>', {
                    class: 'notification-item'
                })
                    .append($link)
                    .appendTo($list);
            });

            if (!$list.children().length) {
                $('<li>', {
                    class: 'notification-state',
                    text: 'Tidak ada notifikasi yang dapat ditampilkan.'
                }).appendTo($list);
            }
        },

        safeUrl: function (value) {
            if (!value) {
                return null;
            }

            try {
                const url = new URL(value, window.location.origin);

                if (url.origin !== window.location.origin) {
                    return null;
                }

                return url.href;
            } catch (error) {
                return null;
            }
        },

        formatTime: function (dateString) {
            if (!dateString) {
                return '';
            }

            const date = new Date(String(dateString).replace(' ', 'T'));

            if (Number.isNaN(date.getTime())) {
                return '';
            }

            const options = {
                day: 'numeric',
                month: 'short'
            };

            if (date.getFullYear() !== new Date().getFullYear()) {
                options.year = 'numeric';
            }

            return date.toLocaleDateString('id-ID', options);
        }
    };

    window.NotificationKlaim = NotificationKlaim;

    function initNotificationKlaim() {
        NotificationKlaim.init();
    }

    // Inisialisasi saat halaman selesai dimuat.
    $(initNotificationKlaim);

    // Dukungan navigasi Livewire.
    document.addEventListener(
        'livewire:navigated',
        initNotificationKlaim
    );

})(window.jQuery, window, document);
