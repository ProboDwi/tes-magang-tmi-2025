<div id="custom-preloader"
    style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background-color: white; z-index: 9999;
            display: flex; align-items: center; justify-content: center;">
    <div class="spinner-wrapper">
        <div class="spinner-circle"></div> <!-- arah default -->
        <div class="spinner-circle reverse"></div> <!-- arah berlawanan -->
        <img src="{{ asset('images/preloader-logo.png') }}" alt="EkaSnack" class="spinner-logo">
    </div>
</div>

@push('css')
<style>
    /* .main-sidebar {
        visibility: hidden;
    }

    body.loaded .main-sidebar {
        visibility: visible;
    } */

    /* Kontainer luar */
    .spinner-wrapper {
        position: relative;
        width: 60px;
        height: 60px;
    }

    /* Spinner utama - kuning */
    .spinner-circle {
        width: 100%;
        height: 100%;
        border: 3px solid rgba(243, 156, 18, 0.2);
        /* kuning transparan */
        border-top: 3px solid #f39c12;
        /* kuning utama */
        border-radius: 50%;
        animation: spin 1s linear infinite;
        position: absolute;
        top: 0;
        left: 0;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
    }

    /* Spinner reverse - merah */
    .spinner-circle.reverse {
        border: 3px solid rgba(231, 76, 60, 0.2);
        /* merah transparan */
        border-bottom: 3px solid red;
        /* merah utama */
        animation: spinReverse 1.5s linear infinite;
        position: absolute;
        width: 72px;
        height: 72px;
        top: -6px;
        left: -6px;
        border-radius: 50%;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
    }

    /* Logo di tengah */
    .spinner-logo {
        width: 32px;
        height: 32px;
        object-fit: contain;
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    /* Animasi */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes spinReverse {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(-360deg);
        }
    }

    /* Preloader container */
    #custom-preloader {
        transition: opacity 0.3s ease;
        will-change: opacity;
    }

    /* Saat preloader menghilang */
    .fade-out {
        opacity: 0;
        transition: opacity 0.4s ease;
        pointer-events: none;
        visibility: hidden;
    }
</style>
@endpush

@push('js')
<script>
    const preloader = document.getElementById('custom-preloader');
    const pageLoad = new Promise(resolve => {
        window.addEventListener('load', () => resolve());
    });
    const minTime = new Promise(resolve => {
        setTimeout(resolve, 400); // minimal 2 detik
    });
    Promise.all([pageLoad, minTime]).then(() => {
        if (preloader) {
            preloader.classList.add('fade-out');
        }
    });
</script>
@endpush