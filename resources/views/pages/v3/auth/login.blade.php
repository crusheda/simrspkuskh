
<div id="sirmed-login-v3">

    <main class="cl-login04">

        {{-- BACKGROUND IMAGE --}}
        <img class="cl-login04__bg"
            src="{{ asset('images/pku/bg.png') }}"
            width="1200"
            height="800"
            alt=""
            style="opacity:20%">

        {{-- SIMRS BRANDING --}}
        <div class="cl-login04__intro sirmed-intro">

            <div class="sirmed-brand-row">

                <a class="sirmed-logo"
                    href="{{ route('v3.login') }}"
                    aria-label="SIRMED v3">

                    <img src="{{ asset('images/logo/logo.png') }}" alt="SIRMED">

                </a>

                <a class="sirmed-logo sirmed-logo--simgos"
                    href="http://192.168.1.2/"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="SIMGOS v2">

                    <img src="{{ asset('assets/v2/images/simgos.png') }}" alt="SIMGOS">

                </a>

                <span class="sirmed-live">
                    <i></i>
                    SIMRS ONLINE
                </span>

            </div>

            <div class="sirmed-heading">

                <p class="sirmed-eyebrow">
                    SISTEM INFORMASI REKAM MEDIS ELEKTRONIK
                </p>

                <h1 class="sirmed-title">
                    SIRMED <span>v.3</span>
                </h1>

                <p class="sirmed-hospital">
                    RUMAH SAKIT PKU MUHAMMADIYAH SUKOHARJO
                </p>

                <div class="sirmed-integration-badge">
                    <span class="sirmed-badge-pulse"></span>
                    <i class="fa-solid fa-link" aria-hidden="true"></i>
                    Terintegrasi dengan Simgos v.2
                </div>

            </div>

            <div class="sirmed-scene-caption">
                <span class="sirmed-caption-line"></span>
                <span>Digital Care · Connected Medical Record</span>
            </div>

        </div>

        {{-- LOGIN FORM --}}
        <section class="cl-login04__glass" aria-labelledby="cl-login04-title">

            <div data-view>

                <h1 class="cl-login04__title" id="cl-login04-title">
                    Masuk <b style="color:#00B3ED">SIRMED</b>
                </h1>

                <p class="cl-login04__sub">
                    Gunakan akun SIMGOS Anda untuk mengakses rekam medis elektronik.
                </p>

                {{-- ERROR --}}
                @if (session('error') || $errors->any())
                    <div class="cl-login04__error" style="display:block;margin-bottom:15px">
                        {{ session('error') ?? $errors->first() }}
                    </div>
                @endif

                {{-- FORM --}}
                <form class="cl-login04__form"
                    method="POST"
                    action="{{ route('v3.login.process') }}"
                    data-form>

                    @csrf

                    {{-- USERNAME --}}
                    <div class="cl-login04__field" data-field>

                        <label class="cl-login04__label" for="cl-login04-email">
                            Username
                        </label>

                        <input
                            class="cl-login04__input"
                            id="cl-login04-email"
                            name="name"
                            type="text"
                            autocomplete="username"
                            value="{{ old('name') }}"
                            placeholder="e.g. sunaryo"
                            autofocus
                            required>

                    </div>

                    {{-- PASSWORD --}}
                    <div class="cl-login04__field" data-field>

                        <label class="cl-login04__label" for="cl-login04-pw">
                            Password
                        </label>

                        <div class="cl-login04__pw">

                            <input
                                class="cl-login04__input"
                                id="cl-login04-pw"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                required>

                            <button
                                class="cl-login04__eye"
                                type="button"
                                id="togglePassword"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                                aria-controls="cl-login04-pw">

                                <svg class="cl-login04__eye-on"
                                    viewBox="0 0 24 24"
                                    width="20"
                                    height="20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true">

                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                                    <circle cx="12" cy="12" r="3"/>

                                </svg>

                                <svg class="cl-login04__eye-off"
                                    viewBox="0 0 24 24"
                                    width="20"
                                    height="20"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true">

                                    <path d="M3 3l18 18M10.6 5.1A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a17.6 17.6 0 0 1-3.2 4.1M6.6 6.6C3.9 8.3 2 12 2 12s3.6 7 10 7a9.8 9.8 0 0 0 5.4-1.6M9.9 9.9a3 3 0 0 0 4.2 4.2"/>

                                </svg>

                            </button>

                        </div>

                    </div>

                    {{-- REMEMBER + FORGOT PASSWORD --}}
                    <div class="cl-login04__row">

                        <label class="cl-login04__check">

                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                {{ old('remember') ? 'checked' : '' }}>

                            <span>Ingat Saya</span>

                        </label>

                        <a class="cl-login04__link"
                            href="{{ route('lupapassword.index') }}">
                            Lupa Password?
                        </a>

                    </div>

                    {{-- SUBMIT --}}
                    <button class="cl-login04__submit btn btn-info text-white"
                        type="submit">

                        <span class="cl-login04__submit-text">Masuk</span>

                        <svg viewBox="0 0 24 24"
                            width="18"
                            height="18"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true">

                            <path d="M5 12h14M13 6l6 6-6 6"/>

                        </svg>

                    </button>

                </form>

                {{-- ACCOUNT INFORMATION --}}
                <p class="cl-login04__foot">

                    Belum memiliki Akun?

                    <a class="cl-login04__link"
                        href="#"
                        data-bs-toggle="modal"
                        data-bs-target="#hubsdi">

                        Buat Akun Baru

                    </a>

                </p>

            </div>

            {{-- FOOTER --}}
            <p class="cl-login04__demo">
                @ Tim IT RS
            </p>

        </section>

    </main>

    {{-- MODAL INFORMASI AKUN --}}
    <div class="modal fade"
        id="hubsdi"
        tabindex="-1"
        aria-labelledby="hubsdiLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="hubsdiLabel">
                        <i class="fa-solid fa-phone me-2"></i>
                        Daftar Nomor Yang Bisa Dihubungi
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                    </button>

                </div>

                <div class="modal-body">

                    <h6 class="mb-3">
                        Masalah <mark>Terkait Akun</mark>
                        silakan Hubungi No.Telp Bagian SDI:

                        <b class="text-primary">188</b>
                        (Hanya Disaat Jam Kerja)
                    </h6>

                    <h6>Nomor Bagian SDI:</h6>

                    <ul>
                        <li>
                            Novita Yuliani, S.KM, M.Kes
                            (<b>Whatsapp</b>: <b>089689514960</b>)
                        </li>

                        <li>
                            Kholid Hidayat Al-Khoiri, S.Psi
                            (<b>Whatsapp</b>: <b>0882003805027</b>)
                        </li>

                        <li>
                            Sri Suryani, SM
                            (<b>Whatsapp</b>: <b>081330795309</b>)
                        </li>
                    </ul>

                    <h6 class="mb-0">
                        Masalah <mark>Teknis Sistem</mark>
                        silakan No.Telp IT:

                        <b class="text-primary">102</b>
                        /
                        <b class="text-primary">193</b>
                    </h6>

                </div>

                <div class="modal-footer">

                    <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Tutup

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- JQUERY --}}
<script src="{{ asset('js/jquery.min.js') }}"></script>

{{-- LOGIN VALIDATION + LOADING --}}
<script>
$(function () {
    const $form = $('[data-form]');
    const $btn = $form.find('button[type="submit"]');

    if (!$form.length || !$btn.length) return;

    $form.on('submit', function (e) {
        const $fields = $form.find('[data-field]');
        let valid = true;
        let $firstInvalid = null;

        $fields.each(function () {
            const $field = $(this);
            const $input = $field.find('input').first();

            if (!$input.length) return;

            $field.attr('data-touched', '1');

            const value = $.trim($input.val());

            if ($input.prop('required') && !value) {
                valid = false;

                $field.addClass('is-error');
                $input.attr('aria-invalid', 'true');

                if (!$firstInvalid) {
                    $firstInvalid = $input;
                }

                return;
            }

            $field.removeClass('is-error');
            $input.removeAttr('aria-invalid');
        });

        if (!valid) {
            e.preventDefault();

            const $live = $('[data-live]');

            if ($live.length) {
                $live.text('Username dan password wajib diisi.');
            }

            if ($firstInvalid) {
                $firstInvalid.trigger('focus');
            }

            return;
        }

        // Jangan preventDefault: form dikirim ke Laravel.
        $btn.prop('disabled', true);

        $btn.data('original-html', $btn.html());

        $btn.html(`
            <i class="fas fa-sync fa-spin me-1" style="font-size:12pt"></i>
            Memproses...
        `);
    });
});
</script>

{{-- PASSWORD TOGGLE --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('cl-login04-pw');
    const button = document.getElementById('togglePassword');

    if (!input || !button) return;

    button.addEventListener('click', function () {
        const show = input.type === 'password';

        input.type = show ? 'text' : 'password';

        button.setAttribute('aria-pressed', show ? 'true' : 'false');

        button.setAttribute(
            'aria-label',
            show ? 'Sembunyikan password' : 'Tampilkan password'
        );
    });
});
</script>

{{-- THREE.JS MEDICAL DIGITAL BACKGROUND --}}
<script type="module">
import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.170.0/build/three.module.js';

(() => {
    const canvas = document.getElementById('sirmed-three-canvas');
    const root = document.getElementById('sirmed-login-v3');

    if (!canvas || !root) return;

    const reducedMotion = window.matchMedia(
        '(prefers-reduced-motion: reduce)'
    ).matches;

    const scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(0x071727, 0.025);

    const camera = new THREE.PerspectiveCamera(52, 1, 0.1, 100);
    camera.position.set(0, 0, 22);

    const renderer = new THREE.WebGLRenderer({
        canvas,
        alpha: true,
        antialias: true,
        powerPreference: 'low-power'
    });

    renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 1.5));
    renderer.setClearColor(0x000000, 0);
    renderer.outputColorSpace = THREE.SRGBColorSpace;

    scene.add(new THREE.AmbientLight(0x9acbff, 1.25));

    const cyanLight = new THREE.PointLight(0x00b3ed, 2.4, 45);
    cyanLight.position.set(-7, 5, 7);
    scene.add(cyanLight);

    const tealLight = new THREE.PointLight(0x2dd4bf, 1.8, 40);
    tealLight.position.set(8, -5, -3);
    scene.add(tealLight);

    const clock = new THREE.Clock();

    const pointer = { x: 0, y: 0 };
    const target = { x: 0, y: 0 };

    const sceneLayers = [];

    window.addEventListener('pointermove', (event) => {
        target.x = (event.clientX / window.innerWidth - 0.5) * 2;
        target.y = (event.clientY / window.innerHeight - 0.5) * 2;
    }, { passive: true });

    window.addEventListener('pointerleave', () => {
        target.x = 0;
        target.y = 0;
    }, { passive: true });

    /* MEDICAL PLUS GEOMETRY */
    function makePlusGeometry(size) {
        const t = size * 0.30;
        const h = size;

        const shape = new THREE.Shape();

        shape.moveTo(-t, h);
        shape.lineTo(t, h);
        shape.lineTo(t, t);
        shape.lineTo(h, t);
        shape.lineTo(h, -t);
        shape.lineTo(t, -t);
        shape.lineTo(t, -h);
        shape.lineTo(-t, -h);
        shape.lineTo(-t, -t);
        shape.lineTo(-h, -t);
        shape.lineTo(-h, t);
        shape.lineTo(-t, t);
        shape.closePath();

        return new THREE.ShapeGeometry(shape);
    }

    /* FALLING MEDICAL PLUS SPRINKLES */
    const crosses = new THREE.Group();
    scene.add(crosses);

    const crossItems = [];

    for (let i = 0; i < 125; i++) {
        const size = THREE.MathUtils.randFloat(0.07, 0.22);

        const material = new THREE.MeshBasicMaterial({
            color: i % 5 === 0
                ? 0x9be7ff
                : (i % 3 === 0 ? 0x2dd4bf : 0x62c8f2),
            transparent: true,
            opacity: THREE.MathUtils.randFloat(0.22, 0.76),
            side: THREE.DoubleSide,
            depthWrite: false,
            blending: THREE.AdditiveBlending
        });

        const mesh = new THREE.Mesh(makePlusGeometry(size), material);

        mesh.position.set(
            THREE.MathUtils.randFloatSpread(34),
            THREE.MathUtils.randFloatSpread(23),
            THREE.MathUtils.randFloat(-9, 3)
        );

        mesh.rotation.z = Math.random() * Math.PI;

        mesh.userData = {
            speed: THREE.MathUtils.randFloat(0.018, 0.075),
            spin: THREE.MathUtils.randFloat(-0.012, 0.012),
            drift: THREE.MathUtils.randFloat(0.15, 0.65),
            phase: Math.random() * Math.PI * 2,
            baseX: mesh.position.x,
            twinkle: THREE.MathUtils.randFloat(0.7, 2.2)
        };

        crosses.add(mesh);
        crossItems.push(mesh);
    }

    sceneLayers.push({ object: crosses, depth: 0.7 });

    /* DIGITAL DUST PARTICLES */
    const dustCount = 900;
    const dustPositions = new Float32Array(dustCount * 3);

    for (let i = 0; i < dustCount; i++) {
        dustPositions[i * 3] = THREE.MathUtils.randFloatSpread(38);
        dustPositions[i * 3 + 1] = THREE.MathUtils.randFloatSpread(26);
        dustPositions[i * 3 + 2] = THREE.MathUtils.randFloat(-12, 2);
    }

    const dustGeometry = new THREE.BufferGeometry();

    dustGeometry.setAttribute(
        'position',
        new THREE.BufferAttribute(dustPositions, 3)
    );

    const dust = new THREE.Points(
        dustGeometry,
        new THREE.PointsMaterial({
            color: 0x9bdfff,
            size: 0.045,
            transparent: true,
            opacity: 0.55,
            blending: THREE.AdditiveBlending,
            depthWrite: false
        })
    );

    scene.add(dust);
    sceneLayers.push({ object: dust, depth: 0.45 });

    /* DIGITAL NETWORK */
    const network = new THREE.Group();
    scene.add(network);

    const nodes = [];

    const nodeGeometry = new THREE.SphereGeometry(0.045, 8, 8);

    for (let i = 0; i < 72; i++) {
        const node = new THREE.Mesh(
            nodeGeometry,
            new THREE.MeshBasicMaterial({
                color: i % 4 === 0 ? 0x5eead4 : 0x7dd3fc,
                transparent: true,
                opacity: 0.7,
                blending: THREE.AdditiveBlending
            })
        );

        node.position.set(
            THREE.MathUtils.randFloatSpread(27),
            THREE.MathUtils.randFloatSpread(18),
            THREE.MathUtils.randFloat(-7, 1)
        );

        node.userData = {
            base: node.position.clone(),
            phase: Math.random() * 6.28
        };

        network.add(node);
        nodes.push(node);
    }

    const links = [];

    const linkMaterial = new THREE.LineBasicMaterial({
        color: 0x38bdf8,
        transparent: true,
        opacity: 0.10,
        blending: THREE.AdditiveBlending,
        depthWrite: false
    });

    for (let i = 0; i < nodes.length; i++) {
        for (let j = i + 1; j < nodes.length; j++) {
            if (nodes[i].position.distanceTo(nodes[j].position) < 2.65) {
                const geometry = new THREE.BufferGeometry().setFromPoints([
                    nodes[i].position,
                    nodes[j].position
                ]);

                const line = new THREE.Line(
                    geometry,
                    linkMaterial.clone()
                );

                network.add(line);
                links.push({ line, a: nodes[i], b: nodes[j] });
            }
        }
    }

    sceneLayers.push({ object: network, depth: 0.95 });

    /* ABSTRACT DNA HELIX */
    const helix = new THREE.Group();
    scene.add(helix);

    const helixConnectors = [];

    const helixDotGeo = new THREE.SphereGeometry(0.045, 8, 8);

    const helixMatA = new THREE.MeshBasicMaterial({
        color: 0x2dd4bf,
        transparent: true,
        opacity: 0.72,
        blending: THREE.AdditiveBlending
    });

    const helixMatB = new THREE.MeshBasicMaterial({
        color: 0x60a5fa,
        transparent: true,
        opacity: 0.72,
        blending: THREE.AdditiveBlending
    });

    const helixCount = 78;

    for (let i = 0; i < helixCount; i++) {
        const t = i / (helixCount - 1);
        const angle = t * Math.PI * 7;
        const y = (t - 0.5) * 11;

        const a = new THREE.Mesh(helixDotGeo, helixMatA);
        const b = new THREE.Mesh(helixDotGeo, helixMatB);

        a.position.set(
            Math.cos(angle) * 1.15,
            y,
            Math.sin(angle) * 1.15
        );

        b.position.set(
            Math.cos(angle + Math.PI) * 1.15,
            y,
            Math.sin(angle + Math.PI) * 1.15
        );

        helix.add(a, b);

        if (i % 6 === 0) {
            const connector = new THREE.Line(
                new THREE.BufferGeometry().setFromPoints([
                    a.position,
                    b.position
                ]),
                new THREE.LineBasicMaterial({
                    color: 0x93c5fd,
                    transparent: true,
                    opacity: 0.16,
                    blending: THREE.AdditiveBlending
                })
            );

            helix.add(connector);
            helixConnectors.push({ connector, a, b });
        }
    }

    helix.position.set(10, 0, -7);
    sceneLayers.push({ object: helix, depth: 1.25 });

    /* ORBITAL RINGS */
    const orbit = new THREE.Group();
    scene.add(orbit);

    const rings = [];

    for (let i = 0; i < 5; i++) {
        const ring = new THREE.Mesh(
            new THREE.TorusGeometry(
                1.25 + i * 0.52,
                0.012,
                8,
                100
            ),
            new THREE.MeshBasicMaterial({
                color: i % 2 ? 0x60a5fa : 0x2dd4bf,
                transparent: true,
                opacity: 0.22,
                blending: THREE.AdditiveBlending
            })
        );

        ring.rotation.x = Math.PI / 2.2;
        ring.rotation.y = Math.PI / 4;
        ring.userData.spin = (i % 2 ? -1 : 1) * 0.0025;

        orbit.add(ring);
        rings.push(ring);
    }

    orbit.position.set(-11, 5.2, -8);
    sceneLayers.push({ object: orbit, depth: 1.1 });

    /* SCAN BEAM */
    const scanBeam = new THREE.Mesh(
        new THREE.PlaneGeometry(36, 0.035),
        new THREE.MeshBasicMaterial({
            color: 0x5eead4,
            transparent: true,
            opacity: 0.13,
            blending: THREE.AdditiveBlending,
            depthWrite: false
        })
    );

    scanBeam.position.z = -3.5;
    scene.add(scanBeam);

    /* SOFT GLOW HALOS */
    const halos = new THREE.Group();
    scene.add(halos);

    [
        { x: -8, y: 2, scale: 4.4, color: 0x00b3ed, opacity: 0.045 },
        { x: 8, y: -3, scale: 5.4, color: 0x2dd4bf, opacity: 0.035 },
        { x: 1, y: 6, scale: 3.2, color: 0x3b82f6, opacity: 0.035 }
    ].forEach((halo) => {
        const mesh = new THREE.Mesh(
            new THREE.CircleGeometry(1, 64),
            new THREE.MeshBasicMaterial({
                color: halo.color,
                transparent: true,
                opacity: halo.opacity,
                depthWrite: false,
                blending: THREE.AdditiveBlending
            })
        );

        mesh.position.set(halo.x, halo.y, -10);
        mesh.scale.setScalar(halo.scale);
        halos.add(mesh);
    });

    sceneLayers.push({ object: halos, depth: 0.25 });

    /* RESIZE */
    function resize() {
        const rect = canvas.parentElement.getBoundingClientRect();

        const width = Math.max(1, rect.width);
        const height = Math.max(1, rect.height);

        camera.aspect = width / height;
        camera.updateProjectionMatrix();

        renderer.setSize(width, height, false);

        renderer.setPixelRatio(
            Math.min(window.devicePixelRatio || 1, 1.5)
        );
    }

    const resizeObserver = new ResizeObserver(resize);
    resizeObserver.observe(canvas.parentElement);
    resize();

    /* ANIMATION */
    let raf = 0;

    function render() {
        raf = requestAnimationFrame(render);

        const elapsed = clock.getElapsedTime();
        const delta = Math.min(clock.getDelta(), 0.04);

        pointer.x += (target.x - pointer.x) * 0.035;
        pointer.y += (target.y - pointer.y) * 0.035;

        camera.position.x = pointer.x * 0.8;
        camera.position.y = -pointer.y * 0.55;
        camera.position.z = 22;

        camera.lookAt(0, 0, 0);

        /* Parallax per layer */
        sceneLayers.forEach(({ object, depth }) => {
            object.position.x += (
                pointer.x * depth * 0.48 - object.position.x
            ) * 0.018;

            object.position.y += (
                -pointer.y * depth * 0.32 - object.position.y
            ) * 0.018;
        });

        if (!reducedMotion) {

            /* Falling + sprinkle */
            crossItems.forEach((cross) => {
                const d = cross.userData;

                cross.position.y -= d.speed * delta * 20;

                cross.position.x = d.baseX +
                    Math.sin(elapsed * d.twinkle + d.phase) * d.drift;

                cross.rotation.z += d.spin;

                cross.material.opacity =
                    0.22 +
                    (Math.sin(elapsed * d.twinkle + d.phase) + 1) * 0.25;

                if (cross.position.y < -14) {
                    cross.position.y = 14;

                    cross.position.x =
                        THREE.MathUtils.randFloatSpread(34);

                    d.baseX = cross.position.x;
                }
            });

            /* Dust movement */
            const p = dustGeometry.attributes.position.array;

            for (let i = 0; i < dustCount; i++) {
                p[i * 3 + 1] -= 0.012 * delta * 20;

                p[i * 3] +=
                    Math.sin(elapsed * 0.45 + i * 0.1) * 0.0005;

                if (p[i * 3 + 1] < -14) {
                    p[i * 3 + 1] = 14;

                    p[i * 3] =
                        THREE.MathUtils.randFloatSpread(38);
                }
            }

            dustGeometry.attributes.position.needsUpdate = true;

            dust.rotation.z = Math.sin(elapsed * 0.08) * 0.025;

            /* Network node drift */
            nodes.forEach((node) => {
                const d = node.userData;

                node.position.x =
                    d.base.x + Math.sin(elapsed * 0.38 + d.phase) * 0.13;

                node.position.y =
                    d.base.y + Math.cos(elapsed * 0.42 + d.phase) * 0.13;
            });

            /* Network link update */
            links.forEach(({ line, a, b }) => {
                const attr = line.geometry.attributes.position;

                attr.setXYZ(0, a.position.x, a.position.y, a.position.z);
                attr.setXYZ(1, b.position.x, b.position.y, b.position.z);

                attr.needsUpdate = true;
            });

            /* DNA helix */
            helix.rotation.y = elapsed * 0.16;
            helix.rotation.z = Math.sin(elapsed * 0.25) * 0.06;

            helixConnectors.forEach(({ connector, a, b }) => {
                const attr = connector.geometry.attributes.position;

                attr.setXYZ(0, a.position.x, a.position.y, a.position.z);
                attr.setXYZ(1, b.position.x, b.position.y, b.position.z);

                attr.needsUpdate = true;
            });

            /* Rings */
            rings.forEach((ring) => {
                ring.rotation.z += ring.userData.spin;
            });

            orbit.rotation.z = Math.sin(elapsed * 0.1) * 0.06;

            /* Scan */
            scanBeam.position.y = Math.sin(elapsed * 0.32) * 10;

            /* Moving lights */
            cyanLight.position.x = -7 + Math.sin(elapsed * 0.35) * 2;
            tealLight.position.y = -5 + Math.cos(elapsed * 0.28) * 2;

            halos.rotation.z = Math.sin(elapsed * 0.07) * 0.04;
        }

        renderer.render(scene, camera);
    }

    render();

    /* CLEANUP */
    window.addEventListener('pagehide', () => {
        cancelAnimationFrame(raf);
        resizeObserver.disconnect();
        renderer.dispose();
        dustGeometry.dispose();

        crossItems.forEach((cross) => {
            cross.geometry.dispose();
            cross.material.dispose();
        });
    }, { once: true });

})();
</script>
