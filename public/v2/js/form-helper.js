// ============================================================
// GLOBAL FORM HELPER
// ============================================================
window.FormHelper = {
    // ==========================================================
    // CEK VALUE TERISI DAN BUKAN 0
    // ==========================================================
    // null / undefined / '' / 0 / '0' / '0.00' => false
    // selain itu => true
    //
    // Contoh:
    // FormHelper.hasValue(94)      => true
    // FormHelper.hasValue('94.00') => true
    // FormHelper.hasValue('0.00')  => false
    // FormHelper.hasValue(null)    => false
    // ==========================================================
    hasValue: function (value) {

        return (
            value !== null &&
            value !== undefined &&
            value !== ''
            // && Number(value) !== 0
        );
    },

    // ==========================================================
    // SET VALUE
    // Input, textarea, select, number, range, time
    // ==========================================================
    setValue: function ($form, name, value) {

        const $el = $form.find(`[name="${name}"]`);

        if (!$el.length) return;

        const type = ($el.attr('type') || '').toLowerCase();
        const tagName = $el.prop('tagName').toLowerCase();

        // ======================================================
        // SELECT
        // ======================================================
        if (tagName === 'select') {

            // null, undefined, '', 0 => option value=""
            if (
                value === null ||
                value === undefined ||
                value === '' ||
                Number(value) === 0
            ) {
                value = '';
            }

            $el.val(String(value)).trigger('change');

            return;
        }

        // ======================================================
        // VALUE KOSONG
        // ======================================================
        if (value === null || value === undefined || value === '') {

            $el.val('').trigger('change');

            return;
        }

        // ======================================================
        // NUMBER
        // ======================================================
        if (type === 'number') {

            const number = Number(
                String(value).replace(',', '.')
            );

            if (!isNaN(number)) {
                value = number;
            }
        }

        // ======================================================
        // RANGE
        // ======================================================
        else if (type === 'range') {

            let number = Number(
                String(value).replace(',', '.')
            );

            if (!isNaN(number)) {

                const min = Number($el.attr('min'));
                const max = Number($el.attr('max'));
                const step = Number($el.attr('step'));

                // Min
                if (!isNaN(min)) {
                    number = Math.max(number, min);
                }

                // Max
                if (!isNaN(max)) {
                    number = Math.min(number, max);
                }

                // Step
                if (
                    !isNaN(step) &&
                    step > 0 &&
                    !isNaN(min)
                ) {
                    number =
                        min +
                        Math.round((number - min) / step) * step;
                }

                value = number;
            }
        }

        // ======================================================
        // TIME
        // ======================================================
        else if (type === 'time') {

            // 08:30:00 => 08:30
            value = String(value).substring(0, 5);
        }

        // ======================================================
        // SET
        // ======================================================
        $el.val(value).trigger('change');
    },


    // ==========================================================
    // CHECKBOX BOOLEAN
    // =================== =======================================
    // Contoh:
    // FormHelper.setCheckbox($form, 'isokor', 1);
    // ==========================================================
    setCheckbox: function ($form, name, checked) {

        const $el = $form.find(`input[name="${name}"]`);

        if (!$el.length) return;

        $el
            .prop('checked', Number(checked) === 1)
            .trigger('change');
    },


    // ==========================================================
    // CHECKBOX BERDASARKAN VALUE
    // ==========================================================
    // Contoh:
    // <input name="status" value="1">
    // <input name="status" value="2">
    //
    // FormHelper.setCheckboxValue($form, 'status', 2);
    // ==========================================================
    setCheckboxValue: function ($form, name, value) {

        const $group = $form.find(`input[name="${name}"]`);

        if (!$group.length) return;

        const normalizedValue = String(value);

        $group
            .filter(function () {
                return String($(this).val()) === normalizedValue;
            })
            .first()
            .prop('checked', true)
            .trigger('change');
    },


    // ==========================================================
    // SINGLE CHECKBOX
    // Hanya satu pilihan berdasarkan value
    // ==========================================================
    // Contoh:
    // FormHelper.setSingleCheckbox($form, 'pupil', 2);
    // ==========================================================
    setSingleCheckbox: function ($form, name, value) {

        const $group = $form.find(`input[name="${name}"]`);

        if (!$group.length) return;

        // ======================================================
        // PASANG BEHAVIOR SINGLE CHECKBOX
        // Hanya satu checkbox yang boleh checked.
        // ======================================================
        if (!$group.data('single-checkbox-initialized')) {

            $group.data('single-checkbox-initialized', true);

            $group.on('click.singleCheckbox', function () {

                // Kalau checkbox ini akan dicentang,
                // langsung uncheck checkbox lain TERLEBIH DAHULU.
                if ($(this).is(':checked')) {

                    $group
                        .not(this)
                        .prop('checked', false);
                }
            });
        }

        // ======================================================
        // DATABASE KOSONG
        // Jangan mengganggu checked bawaan HTML.
        // ======================================================
        if (
            value === null ||
            value === undefined ||
            value === ''
        ) {
            return;
        }

        const normalizedValue = String(value);

        // ======================================================
        // UNCHECK SEMUA
        // ======================================================
        $group.prop('checked', false);

        // ======================================================
        // CHECK SESUAI VALUE DATABASE
        // ======================================================
        const $selected = $group
            .filter(function () {
                return String($(this).val()) === normalizedValue;
            })
            .first();

        if (!$selected.length) return;

        $selected
            .prop('checked', true)
            .trigger('change');
    },

        // BACKUP SETSINGLECHECKBOX -----------------------------------------------------------------------------
        // setSingleCheckbox: function ($form, name, value) {

        //     const $group = $form.find(`input[name="${name}"]`);

        //     if (!$group.length) return;

        //     // ======================================================
        //     // PASANG BEHAVIOR SINGLE CHECKBOX
        //     // Hanya satu checkbox yang boleh checked.
        //     // ======================================================
        //     if (!$group.data('single-checkbox-initialized')) {

        //         $group.data('single-checkbox-initialized', true);

        //         $group.on('click.singleCheckbox', function () {

        //             // Kalau checkbox ini akan dicentang,
        //             // langsung uncheck checkbox lain TERLEBIH DAHULU.
        //             if ($(this).is(':checked')) {

        //                 $group
        //                     .not(this)
        //                     .prop('checked', false);
        //             }
        //         });
        //     }

        //     // ======================================================
        //     // DATABASE KOSONG
        //     // Jangan mengganggu checked bawaan HTML.
        //     // ======================================================
        //     if (
        //         value === null ||
        //         value === undefined ||
        //         value === ''
        //     ) {
        //         return;
        //     }

        //     const normalizedValue = String(value);

        //     // ======================================================
        //     // UNCHECK SEMUA
        //     // ======================================================
        //     $group.prop('checked', false);

        //     // ======================================================
        //     // CHECK SESUAI VALUE DATABASE
        //     // ======================================================
        //     const $selected = $group
        //         .filter(function () {
        //             return String($(this).val()) === normalizedValue;
        //         })
        //         .first();

        //     if (!$selected.length) return;

        //     $selected
        //         .prop('checked', true)
        //         .trigger('change');
        // },
        // BACKUP SETSINGLECHECKBOX -----------------------------------------------------------------------------

    // ==========================================================
    // VALIDASI NUMBER BERDASARKAN MIN / MAX HTML
    // ==========================================================
    // Contoh:
    // FormHelper.setValidNumber($form, 'gcs_e');
    //
    // <input type="number" name="gcs_e" min="1" max="4">
    // ==========================================================
    setValidNumber: function ($form, name) {

        const $input = $form.find(`input[name="${name}"]`);

        if (!$input.length) return 0;

        const value = parseFloat($input.val());
        const min = parseFloat($input.attr('min'));
        const max = parseFloat($input.attr('max'));

        // Kosong / bukan angka
        if (isNaN(value)) {
            return 0;
        }

        // Di luar range
        if (
            (!isNaN(min) && value < min) ||
            (!isNaN(max) && value > max)
        ) {
            $input.val('').trigger('change');

            return 0;
        }

        return value;
    },

    // ==========================================
    // HITUNG GCS
    // ==========================================
    hitungGCS: function ($form, name) {

        const eye = this.setValidNumber($form, `${name}_e`);
        const verbal = this.setValidNumber($form, `${name}_v`);
        const motorik = this.setValidNumber($form, `${name}_m`);

        const $total = $form.find(`input[name="${name}_t"]`);

        if (!$total.length) return;

        // Jika salah satu belum diisi
        if (eye === 0 && verbal === 0 && motorik === 0) {
            $total.val('');
            return;
        }

        // Semua valid
        const total = eye + verbal + motorik;

        $total.val(total);
    },

    // ==========================================================
    // FORMAT DATETIME LOCAL
    // ==========================================================
    // 2026-08-10 12:30:00
    // =>
    // 2026-08-10T12:30
    // ==========================================================
    formatDateTimeLocal: function (value) {

        if (!value) return '';

        return String(value)
            .replace(' ', 'T')
            .substring(0, 16);
    }

};
