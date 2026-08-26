<div class="d-flex justify-content-between">
    <button
        type="button"
        class="btn btn-danger waves-effect waves-light"
        id="btnDokter"
        data-bs-toggle="collapse"
        data-bs-target="#riA_dokter"
        aria-expanded="false"
        aria-controls="riA_dokter"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Dokter
    </button>

    <button
        type="button"
        class="btn btn-success waves-effect waves-light collapsed"
        id="btnPerawat"
        data-bs-toggle="collapse"
        data-bs-target="#riA_perawat"
        aria-expanded="false"
        aria-controls="riA_perawat"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Keperawatan
    </button>
</div>

<div class="accordion" id="riaAccordion">
    <div
        class="multi-collapse collapse"
        data-bs-parent="#riaAccordion"
        id="riA_dokter"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="riA_dokter"
        data-url="{{ route('v2.emr.form.sub.rawat-inap.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'ria_dokter']) }}"
    >
        <div class="form-content mt-3"></div>
    </div>

    <div
        class="multi-collapse collapse"
        data-bs-parent="#riaAccordion"
        id="riA_perawat"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="riA_perawat"
        data-url="{{ route('v2.emr.form.sub.rawat-inap.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'ria_perawat']) }}"
    >
        <div class="form-content mt-3"></div>
    </div>
</div>

<script>
(function($){
    'use strict';

    let activeSection = null;
    let activeRequest = null;

    function updateRanapAnakButton(){
        $('#btnDokter').prop('disabled',$('#riA_dokter').hasClass('show'));
        $('#btnPerawat').prop('disabled',$('#riA_perawat').hasClass('show'));
    }

    function abortActiveRequest(){
        if(activeRequest && activeRequest.readyState !== 4){
            console.log('[RIA] Abort request:',activeSection);
            activeRequest.abort();
        }
        activeRequest = null;
    }

    function executePartialScripts($content){
        $content.find('script').each(function(){
            const script=this;

            if(!script.src){
                try{
                    $.globalEval(
                        script.text ||
                        script.textContent ||
                        script.innerHTML
                    );
                }catch(error){
                    console.error(
                        '[RIA] Error menjalankan script partial:',
                        error
                    );
                }
            }else{
                if($('script[src="'+script.src+'"]').length===0){
                    const newScript=document.createElement('script');
                    newScript.src=script.src;
                    document.head.appendChild(newScript);
                }
            }
        });
    }

    function loadRanapAnakForm($section){
        if(!$section || !$section.length) return;

        const sectionId=$section.attr('id');
        const url=$section.data('url');
        const $content=$section.find('.form-content');

        if(!url || !$content.length){
            console.error(
                'URL atau container form Rawat Inap Anak tidak ditemukan.'
            );
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Batalkan request form sebelumnya
        |--------------------------------------------------------------------------
        */
        abortActiveRequest();

        /*
        |--------------------------------------------------------------------------
        | Tandai section aktif
        |--------------------------------------------------------------------------
        */
        activeSection=sectionId;

        /*
        |--------------------------------------------------------------------------
        | Bersihkan form sebelumnya
        |--------------------------------------------------------------------------
        */
        $('#riaAccordion')
            .find('.multi-collapse')
            .not($section)
            .find('.form-content')
            .empty();

        $('#riaAccordion')
            .find('.multi-collapse')
            .not($section)
            .removeData('loaded')
            .removeData('loading');

        /*
        |--------------------------------------------------------------------------
        | Loading
        |--------------------------------------------------------------------------
        */
        $section.data('loading',true);

        $content.html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Memuat...</span>
                </div>
                <div class="mt-2">Memuat sub formulir...</div>
            </div>
        `);

        console.log('[RIA] LOAD:',{
            section:sectionId,
            formKey:$section.data('form-key'),
            kunjungan:$section.data('kunjungan'),
            url:url
        });

        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */
        const request=$.ajax({
            url:url,
            type:'GET',
            dataType:'html',
            cache:false
        });

        activeRequest=request;

        request.done(function(html){
            /*
            |--------------------------------------------------------------------------
            | Request sudah tidak relevan karena user pindah form
            |--------------------------------------------------------------------------
            */
            if(
                activeSection!==sectionId ||
                !$section.hasClass('show')
            ){
                console.log(
                    '[RIA] Response diabaikan:',
                    sectionId
                );
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Masukkan partial
            |--------------------------------------------------------------------------
            */
            $content.empty().html(html);

            /*
            |--------------------------------------------------------------------------
            | Tandai berhasil dimuat
            |--------------------------------------------------------------------------
            */
            $section.data('loaded',true);

            /*
            |--------------------------------------------------------------------------
            | Jalankan script yang ada di partial
            |--------------------------------------------------------------------------
            */
            executePartialScripts($content);

            /*
            |--------------------------------------------------------------------------
            | Event custom
            |--------------------------------------------------------------------------
            */
            $section.trigger(
                'ria:form-loaded',
                {
                    kunjungan:$section.data('kunjungan'),
                    formKey:$section.data('form-key'),
                    section:sectionId
                }
            );

            console.log(
                '[RIA] LOAD SELESAI:',
                $section.data('form-key')
            );
        });

        request.fail(function(xhr,status,error){
            if(status==='abort'){
                console.log(
                    '[RIA] Request di-abort:',
                    $section.data('form-key')
                );
                return;
            }

            console.error(
                '[RIA] Gagal load:',
                {
                    formKey:$section.data('form-key'),
                    status:status,
                    error:error,
                    response:xhr.responseText
                }
            );

            if(
                activeSection===sectionId &&
                $section.hasClass('show')
            ){
                $content.html(`
                    <div class="alert alert-danger mb-0">
                        <i class="ri-error-warning-line me-1"></i>
                        Form gagal dimuat. Silakan coba kembali.
                    </div>
                `);
            }
        });

        request.always(function(){
            $section.removeData('loading');

            if(activeRequest===request){
                activeRequest=null;
            }
        });
    }

    $(function(){

        /*
        |--------------------------------------------------------------------------
        | INITIAL STATE
        |--------------------------------------------------------------------------
        |
        | Tidak ada form yang di-load otomatis.
        | Kedua tombol aktif.
        |--------------------------------------------------------------------------
        */
        activeSection=null;
        activeRequest=null;

        $('#riA_dokter,#riA_perawat').removeClass('show');
        $('#btnDokter,#btnPerawat').removeClass('disabled');
        $('#btnDokter,#btnPerawat').prop('disabled',false);

        updateRanapAnakButton();

        /*
        |--------------------------------------------------------------------------
        | ACCORDION SHOWN
        |--------------------------------------------------------------------------
        */
        $('#riaAccordion')
            .off('shown.bs.collapse.riaRanap','.multi-collapse')
            .on(
                'shown.bs.collapse.riaRanap',
                '.multi-collapse',
                function(){
                    const $section=$(this);

                    updateRanapAnakButton();
                    loadRanapAnakForm($section);

                    $section
                        .find('.form-content')
                        .scrollTop(0);
                }
            );

        /*
        |--------------------------------------------------------------------------
        | ACCORDION HIDDEN
        |--------------------------------------------------------------------------
        */
        $('#riaAccordion')
            .off('hidden.bs.collapse.riaRanap','.multi-collapse')
            .on(
                'hidden.bs.collapse.riaRanap',
                '.multi-collapse',
                function(){
                    const $section=$(this);

                    /*
                    | Jika section yang ditutup adalah section aktif,
                    | batalkan request-nya.
                    */
                    if(activeSection===$section.attr('id')){
                        abortActiveRequest();
                        activeSection=null;
                    }

                    /*
                    | Bersihkan form supaya ketika dibuka lagi
                    | GET dimulai dari awal.
                    */
                    $section
                        .find('.form-content')
                        .empty();

                    $section
                        .removeData('loaded')
                        .removeData('loading');

                    updateRanapAnakButton();

                    $section
                        .find('.form-content')
                        .scrollTop(0);
                }
            );

        /*
        |--------------------------------------------------------------------------
        | SINGLE CHECKBOX
        |--------------------------------------------------------------------------
        */
        $(document)
            .off(
                'change.riaSingleCheckbox',
                '#riaAccordion .form-content .single-checkbox'
            )
            .on(
                'change.riaSingleCheckbox',
                '#riaAccordion .form-content .single-checkbox',
                function(){
                    if(!this.checked) return;

                    $(this)
                        .closest('.form-content')
                        .find(
                            `input.single-checkbox[name="${this.name}"]`
                        )
                        .not(this)
                        .prop('checked',false);
                }
            );

        /*
        |--------------------------------------------------------------------------
        | SINGLE CHECKBOX BOS
        |--------------------------------------------------------------------------
        */
        $(document)
            .off(
                'change.riaSingleCheckboxBos',
                '#riaAccordion .form-content .single-checkbox-bos'
            )
            .on(
                'change.riaSingleCheckboxBos',
                '#riaAccordion .form-content .single-checkbox-bos',
                function(){
                    if(!this.checked){
                        this.checked=true;
                        return;
                    }

                    $(this)
                        .closest('.form-content')
                        .find(
                            `input.single-checkbox-bos[name="${this.name}"]`
                        )
                        .not(this)
                        .prop('checked',false);
                }
            );

    });

})(jQuery);
</script>
