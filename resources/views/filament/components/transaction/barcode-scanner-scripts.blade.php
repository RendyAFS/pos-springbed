<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('barcodeScanner', () => ({
            cameraActive: false,
            html5QrCode: null,
            statusMsg: '',
            isError: false,

            init() {
                this.html5QrCode = new Html5Qrcode("barcode-reader");
            },

            startCamera() {
                this.statusMsg = '';
                this.cameraActive = true;
                this.html5QrCode.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: (viewfinderWidth, viewfinderHeight) => {
                            const size = Math.min(viewfinderWidth, viewfinderHeight);
                            return {
                                width: Math.floor(size * 0.75),
                                height: Math.floor(size * 0.75),
                            };
                        },
                        aspectRatio: 1.7777778,
                    },
                    (decodedText) => this.onScanSuccess(decodedText),
                    () => {}
                ).catch((err) => {
                    this.isError = true;
                    this.statusMsg = 'Tidak bisa mengakses kamera: ' + err;
                    this.cameraActive = false;
                });
            },

            stopCamera() {
                if (this.html5QrCode && this.cameraActive) {
                    this.html5QrCode.stop()
                        .then(() => {
                            this.cameraActive = false;
                        })
                        .catch(() => {
                            this.cameraActive = false;
                        });
                }
            },

            scanFile(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.statusMsg = '';

                const run = () => {
                    this.html5QrCode.scanFile(file, true)
                        .then((decodedText) => this.onScanSuccess(decodedText))
                        .catch((err) => {
                            this.isError = true;
                            this.statusMsg = 'Barcode tidak terbaca dari gambar: ' + err;
                        });
                };

                if (this.cameraActive) {
                    this.html5QrCode.stop().then(() => {
                        this.cameraActive = false;
                        run();
                    });
                } else {
                    run();
                }

                event.target.value = '';
            },

            onScanSuccess(decodedText) {
                this.isError = false;
                this.statusMsg = 'Terbaca: ' + decodedText + ' — memproses...';

                const dispatchAndClose = () => {
                    this.$wire.dispatch('barcode-scanned', {
                        code: decodedText
                    });
                };

                if (this.cameraActive) {
                    this.html5QrCode.stop()
                        .then(() => {
                            this.cameraActive = false;
                            dispatchAndClose();
                        })
                        .catch(() => {
                            this.cameraActive = false;
                            dispatchAndClose();
                        });
                } else {
                    dispatchAndClose();
                }
            }
        }));
    });
</script>
