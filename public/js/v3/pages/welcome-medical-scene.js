/* =========================================================
   SIRMED V3 - MEDICAL HUMAN WELCOME SCENE
   Three.js ES Module + GLB + AnimationMixer
========================================================= */

import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { MeshoptDecoder } from 'three/addons/libs/meshopt_decoder.module.js';

(async function () {
    'use strict';

    const container = document.getElementById('welcomeMedicalScene');

    if (!container || container.dataset.initialized === 'true') {
        return;
    }

    container.dataset.initialized = 'true';

    const loadingEl = container.querySelector('.welcome-scene-loading');
    const errorEl = container.querySelector('.welcome-scene-error');

    // =====================================================
    // CONFIG
    // =====================================================
    const CONFIG = {
        models: {
            doctor: '/models/medical/doctor-young.glb'
        },
        characters: {
            doctor: {
                height: 1.65,
                zoom: 0.65,
                position: [0.35, 0, 0],
                rotationY: -0.12
            }
        },
        camera: {
            position: [0, 5.7, 9],
            target: [0, 3.7, 0],
            fov: 38
        }
    };

    // =====================================================
    // SCENE
    // =====================================================

    const scene = new THREE.Scene();

    const camera = new THREE.PerspectiveCamera(
        CONFIG.camera.fov,
        container.clientWidth / container.clientHeight,
        0.1,
        100
    );

    camera.position.set(...CONFIG.camera.position);
    camera.lookAt(...CONFIG.camera.target);

    // =====================================================
    // RENDERER
    // =====================================================

    const renderer = new THREE.WebGLRenderer({
        alpha: true,
        antialias: true,
        powerPreference: 'low-power'
    });

    renderer.setPixelRatio(
        Math.min(window.devicePixelRatio || 1, 1.6)
    );

    renderer.setSize(
        container.clientWidth,
        container.clientHeight
    );

    renderer.outputColorSpace = THREE.SRGBColorSpace;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1.12;
    renderer.setClearColor(0x000000, 0);

    container.appendChild(renderer.domElement);

    // =====================================================
    // LIGHTING
    // =====================================================

    const ambient = new THREE.HemisphereLight(
        0xffffff,
        0x9ebbd4,
        2.5
    );

    scene.add(ambient);

    const keyLight = new THREE.DirectionalLight(
        0xffffff,
        3.2
    );

    keyLight.position.set(-3, 5, 6);
    scene.add(keyLight);

    const rimLight = new THREE.DirectionalLight(
        0x9bd9ff,
        2
    );

    rimLight.position.set(4, 3, -4);
    scene.add(rimLight);

    // =====================================================
    // MEDICAL DECORATION
    // =====================================================

    const decorationGroup = new THREE.Group();
    scene.add(decorationGroup);

    function createMedicalPlus(x, y, z, size, color) {
        const group = new THREE.Group();

        const material = new THREE.MeshBasicMaterial({
            color,
            transparent: true,
            opacity: 0.42
        });

        const vertical = new THREE.Mesh(
            new THREE.BoxGeometry(
                size * 0.28,
                size,
                size * 0.12
            ),
            material
        );

        const horizontal = new THREE.Mesh(
            new THREE.BoxGeometry(
                size,
                size * 0.28,
                size * 0.12
            ),
            material
        );

        group.add(vertical, horizontal);
        group.position.set(x, y, z);
        decorationGroup.add(group);

        return group;
    }

    const plusA = createMedicalPlus(
        -1.85, 2.30, -0.3,
        0.24, 0x60b7dc
    );

    const plusB = createMedicalPlus(
        1.95, 2.65, -0.3,
        0.18, 0x4bc1ad
    );

    const plusC = createMedicalPlus(
        0.00, 2.95, -0.6,
        0.12, 0x7bb9e4
    );

    // Floating bubbles

    const floatingObjects = [];

    for (let i = 0; i < 9; i++) {
        const radius = 0.035 + Math.random() * 0.035;

        const material = new THREE.MeshBasicMaterial({
            color: i % 2 ? 0x65c4b1 : 0x7dbce4,
            transparent: true,
            opacity: 0.25
        });

        const bubble = new THREE.Mesh(
            new THREE.SphereGeometry(radius, 12, 12),
            material
        );

        bubble.position.set(
            -2.2 + Math.random() * 4.4,
            0.6 + Math.random() * 2.4,
            -0.5
        );

        decorationGroup.add(bubble);

        floatingObjects.push({
            object: bubble,
            baseY: bubble.position.y,
            phase: Math.random() * Math.PI * 2,
            speed: 0.6 + Math.random() * 0.6
        });
    }

    // =====================================================
    // GLTF LOADER + MESHOPT DECODER
    // =====================================================

    const loader = new GLTFLoader();

    // Enable Meshopt compressed GLB
    loader.setMeshoptDecoder(MeshoptDecoder);

    const clock = new THREE.Clock();
    const characters = [];

    function getAnimation(gltf, keywords) {
        if (!gltf.animations || !gltf.animations.length) {
            return null;
        }

        for (const keyword of keywords) {
            const clip = gltf.animations.find(animation =>
                animation.name.toLowerCase().includes(keyword.toLowerCase())
            );

            if (clip) return clip;
        }

        return gltf.animations[0] || null;
    }

    // =====================================================
    // MODEL NORMALIZATION
    // =====================================================
    function prepareCharacter(model, targetHeight, zoom = 1) {
        // Bounding box asli
        const box = new THREE.Box3().setFromObject(model);

        const size = new THREE.Vector3();
        box.getSize(size);

        const originalHeight = size.y || 1;

        // Normalisasi tinggi model
        const scale = targetHeight / originalHeight;
        model.scale.setScalar(scale);

        // Bounding box setelah normalisasi
        const scaledBox = new THREE.Box3().setFromObject(model);

        const center = new THREE.Vector3();
        scaledBox.getCenter(center);

        // Center model
        model.position.x -= center.x;
        model.position.z -= center.z;
        model.position.y -= scaledBox.min.y;

        // Perkecil karakter
        model.scale.multiplyScalar(zoom);

        // Perbaiki posisi kaki setelah diperkecil
        const finalBox = new THREE.Box3().setFromObject(model);

        model.position.y -= finalBox.min.y;

        return model;
    }

    // =====================================================
    // DOCTOR PROCEDURAL GREETING & TALK ANIMATION
    // =====================================================

    function findBone(model, patterns) {
        let found = null;

        model.traverse(object => {
            if (found || !object.isBone) return;

            const name = object.name.toLowerCase();

            if (patterns.some(pattern => pattern.test(name))) {
                found = object;
            }
        });

        return found;
    }

    function setupDoctorGesture(model) {

        const bones = {
            rightClavicle: model.getObjectByName('Bip01_R_Clavicle'),
            rightArm: model.getObjectByName('Bip01_R_UpperArm'),
            rightForeArm: model.getObjectByName('Bip01_R_Forearm'),
            rightHand: model.getObjectByName('Bip01_R_Hand'),

            leftClavicle: model.getObjectByName('Bip01_L_Clavicle'),
            leftArm: model.getObjectByName('Bip01_L_UpperArm'),
            leftForeArm: model.getObjectByName('Bip01_L_Forearm'),
            leftHand: model.getObjectByName('Bip01_L_Hand'),

            head: model.getObjectByName('Bip01_Head')
        };

        // Simpan pose asli semua bone
        const base = {};

        Object.entries(bones).forEach(([key, bone]) => {
            if (bone) {
                base[key] = {
                    rotation: bone.rotation.clone(),
                    quaternion: bone.quaternion.clone()
                };
            }
        });

        console.log('🩺 Doctor Gesture Bone Mapping:', {
            rightClavicle: bones.rightClavicle?.name,
            rightArm: bones.rightArm?.name,
            rightForeArm: bones.rightForeArm?.name,
            rightHand: bones.rightHand?.name,
            leftClavicle: bones.leftClavicle?.name,
            leftArm: bones.leftArm?.name,
            leftForeArm: bones.leftForeArm?.name,
            leftHand: bones.leftHand?.name,
            head: bones.head?.name
        });

        return {
            bones,
            base
        };
    }

    function updateDoctorGesture(character, time) {
        if (!character.gesture) return;

        if (!character._gestureDebug) {
            console.log('Doctor gesture animation started');
            character._gestureDebug = true;
        }

        // Sesuaikan dengan struktur return setupDoctorGesture()
        const { bones, base } = character.gesture;

        // ================================================
        // ANIMATION TIMING
        // ================================================
        const elapsed = time - character.gestureStartTime;

        // Durasi pose menyapa
        const greeting = Math.max(
            0,
            1 - elapsed / 4.5
        );

        // Animasi berbicara tetap berjalan setelah sapaan
        const talking =
            0.35 + Math.sin(time * 2.2) * 0.25;

        // ================================================
        // RIGHT CLAVICLE / SHOULDER
        // ================================================

        if (bones.rightClavicle && base.rightClavicle) {
            const b = base.rightClavicle.rotation;

            bones.rightClavicle.rotation.x =
                b.x + greeting * 0.12;

            bones.rightClavicle.rotation.y =
                b.y + Math.sin(time * 1.2) * 0.04;

            bones.rightClavicle.rotation.z =
                b.z + greeting * 0.10 + talking * 0.04;
        }

        // ================================================
        // RIGHT UPPER ARM - GREETING POSE
        // ================================================

        if (bones.rightArm && base.rightArm) {
            const b = base.rightArm.rotation;

            // Gerakan angkat lengan lebih lembut
            bones.rightArm.rotation.x =
                b.x + greeting * 0.12;

            bones.rightArm.rotation.y =
                b.y + greeting * 0.08;

            // Kurangi bukaan lengan yang sebelumnya terlalu besar
            bones.rightArm.rotation.z =
                b.z + greeting * 0.30 + talking * 0.04;
        }

        // ================================================
        // RIGHT FOREARM - WELCOME WAVING
        // ================================================

        if (bones.rightForeArm && base.rightForeArm) {
            const b = base.rightForeArm.rotation;

            // Tekuk siku untuk membawa tangan lebih dekat ke tubuh
            bones.rightForeArm.rotation.x =
                b.x + greeting * 0.45
                + Math.sin(time * 3.8) * greeting * 0.08;

            bones.rightForeArm.rotation.y =
                b.y + Math.sin(time * 1.8) * greeting * 0.05;

            // Gerakan waving lebih dominan pada forearm
            bones.rightForeArm.rotation.z =
                b.z + Math.sin(time * 4.5) * greeting * 0.18;
        }

        // ================================================
        // RIGHT HAND
        // ================================================

        if (bones.rightHand && base.rightHand) {
            const b = base.rightHand.rotation;

            bones.rightHand.rotation.x =
                b.x + Math.sin(time * 5.0) * greeting * 0.18;

            bones.rightHand.rotation.y =
                b.y + Math.sin(time * 4.2) * greeting * 0.15;

            bones.rightHand.rotation.z =
                b.z + Math.sin(time * 5.0) * greeting * 0.20;
        }

        // ================================================
        // LEFT CLAVICLE
        // ================================================

        if (bones.leftClavicle && base.leftClavicle) {
            const b = base.leftClavicle.rotation;

            bones.leftClavicle.rotation.x =
                b.x + Math.sin(time * 1.4) * 0.035;

            bones.leftClavicle.rotation.y =
                b.y + Math.sin(time * 1.1) * 0.025;

            bones.leftClavicle.rotation.z =
                b.z + Math.sin(time * 1.6) * 0.035;
        }

        // ================================================
        // LEFT ARM - EXPLANATORY GESTURE
        // ================================================

        if (bones.leftArm && base.leftArm) {
            const b = base.leftArm.rotation;

            bones.leftArm.rotation.x =
                b.x + Math.sin(time * 1.8 + 1) * 0.06;

            bones.leftArm.rotation.y =
                b.y + Math.sin(time * 1.5) * 0.08;

            bones.leftArm.rotation.z =
                b.z + Math.sin(time * 2.0 + 1) * 0.08;
        }

        // ================================================
        // LEFT FOREARM
        // ================================================

        if (bones.leftForeArm && base.leftForeArm) {
            const b = base.leftForeArm.rotation;

            bones.leftForeArm.rotation.x =
                b.x + Math.sin(time * 1.8 + 1.5) * 0.04;

            bones.leftForeArm.rotation.y =
                b.y + Math.sin(time * 1.4) * 0.035;

            bones.leftForeArm.rotation.z =
                b.z + Math.sin(time * 1.9 + 1) * 0.045;
        }

        // ================================================
        // LEFT HAND
        // ================================================

        if (bones.leftHand && base.leftHand) {
            const b = base.leftHand.rotation;

            bones.leftHand.rotation.x =
                b.x + Math.sin(time * 2.0) * 0.025;

            bones.leftHand.rotation.y =
                b.y + Math.sin(time * 1.7) * 0.025;

            bones.leftHand.rotation.z =
                b.z + Math.sin(time * 2.2) * 0.035;
        }

        // ================================================
        // HEAD - NATURAL MOVEMENT
        // ================================================

        if (bones.head && base.head) {
            const b = base.head.rotation;

            bones.head.rotation.x =
                b.x + Math.sin(time * 1.3) * 0.04;

            bones.head.rotation.y =
                b.y + Math.sin(time * 0.8) * 0.08;

            bones.head.rotation.z =
                b.z + Math.sin(time * 1.1) * 0.025;
        }
    }

    // =====================================================
    // LOAD CHARACTER
    // =====================================================

    function loadCharacter(type, modelPath) {
        return new Promise((resolve, reject) => {
            loader.load(
                modelPath,

                (gltf) => {
                    const model = gltf.scene;
                    const characterConfig = CONFIG.characters[type];

                    prepareCharacter(
                        model,
                        characterConfig.height,
                        characterConfig.zoom
                    );

                    model.position.x += characterConfig.position[0];
                    model.position.y += characterConfig.position[1];
                    model.position.z += characterConfig.position[2];

                    model.rotation.y = characterConfig.rotationY;

                    model.traverse(object => {
                        if (!object.isMesh) return;

                        object.frustumCulled = true;

                        if (object.material) {
                            object.material.needsUpdate = true;
                        }
                    });

                    scene.add(model);

                    let mixer = null;
                    let activeAction = null;

                    // =====================================================
                    // DEBUG - ALL DOCTOR BONES
                    // =====================================================
                    const armBoneNames = [];

                    model.traverse(object => {
                        if (object.isBone && /arm|forearm|upperarm|hand|wrist|shoulder|clavicle/i.test(object.name)) {
                            armBoneNames.push(object.name);
                        }
                    });

                    console.log('========== DOCTOR ARM BONES ==========');
                    console.log(armBoneNames);
                    console.table(armBoneNames);

                    // Setup gesture khusus Doctor
                    const gesture = type === 'doctor'
                        ? setupDoctorGesture(model)
                        : null;

                    characters.push({
                        type,
                        model,
                        mixer,
                        activeAction,
                        gesture,
                        baseY: model.position.y,
                        baseRotationY: model.rotation.y,
                        phase: 1.4,

                        // Waktu mulai animasi karakter
                        gestureStartTime: clock.elapsedTime
                    });

                    resolve(model);
                },

                undefined,

                error => {
                    console.error(
                        `Gagal load model ${type}:`,
                        error
                    );

                    reject(error);
                }
            );
        });
    }

    // =====================================================
    // LOAD BOTH CHARACTERS
    // =====================================================

    function showError() {
        if (loadingEl) {
            loadingEl.classList.add('is-hidden');
        }

        if (errorEl) {
            errorEl.classList.remove('d-none');
        }
    }

    try {
        await loadCharacter(
            'doctor',
            CONFIG.models.doctor
        );

        if (loadingEl) {
            loadingEl.classList.add('is-hidden');
        }

    } catch (error) {
        console.error('Doctor scene gagal:', error);
        showError();
    }

    // =====================================================
    // ANIMATION LOOP
    // =====================================================

    let animationFrameId = null;
    let isVisible = true;

    function animate() {
        animationFrameId = requestAnimationFrame(animate);

        if (!isVisible) return;

        const delta = Math.min(clock.getDelta(), 0.05);
        const time = clock.elapsedTime;

        // Doctor animation
        characters.forEach(character => {
            if (character.mixer) {
                character.mixer.update(delta);
            }

            // Body floating / breathing
            character.model.position.y =
                character.baseY +
                Math.sin(time * 1.1 + character.phase) * 0.025;

            // Body movement
            character.model.rotation.y =
                character.baseRotationY +
                Math.sin(time * 0.5 + character.phase) * 0.025;

            // Doctor greeting & explaining gesture
            if (character.type === 'doctor') {
                updateDoctorGesture(character, time);
            }
        });

        // Medical plus
        plusA.rotation.z = Math.sin(time * 0.7) * 0.12;
        plusB.rotation.z = Math.sin(time * 0.9 + 1) * 0.14;
        plusC.rotation.z = Math.sin(time * 0.8 + 2) * 0.10;

        plusA.position.y = 2.30 + Math.sin(time * 1.1) * 0.07;
        plusB.position.y = 2.65 + Math.sin(time * 0.8 + 1) * 0.08;
        plusC.position.y = 2.95 + Math.sin(time * 0.9 + 2) * 0.06;

        // Bubbles
        floatingObjects.forEach(item => {
            item.object.position.y =
                item.baseY +
                Math.sin(time * item.speed + item.phase) * 0.12;
        });

        renderer.render(scene, camera);
    }

    animate();

    // =====================================================
    // RESPONSIVE
    // =====================================================

    const resizeObserver = new ResizeObserver(() => {
        const width = container.clientWidth;
        const height = container.clientHeight;

        if (!width || !height) return;

        camera.aspect = width / height;
        camera.updateProjectionMatrix();

        renderer.setSize(width, height);
    });

    resizeObserver.observe(container);

    // =====================================================
    // PERFORMANCE - PAUSE WHEN NOT VISIBLE
    // =====================================================

    const intersectionObserver = new IntersectionObserver(entries => {
        isVisible = entries[0].isIntersecting;
    });

    intersectionObserver.observe(container);

    // =====================================================
    // CLEANUP
    // =====================================================

    window.addEventListener('pagehide', () => {
        cancelAnimationFrame(animationFrameId);

        resizeObserver.disconnect();
        intersectionObserver.disconnect();

        characters.forEach(character => {
            character.mixer?.stopAllAction();

            character.model.traverse(object => {
                if (!object.isMesh) return;

                object.geometry?.dispose();

                if (Array.isArray(object.material)) {
                    object.material.forEach(material => material.dispose());
                } else {
                    object.material?.dispose();
                }
            });
        });

        renderer.dispose();
    });

})();
