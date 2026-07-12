<div x-data="barcodeScanner()" x-init="init()" wire:ignore class="space-y-4">
    <div class="flex gap-2">
        <button type="button" x-on:click="startCamera()" x-show="!cameraActive"
            class="fi-btn fi-btn-size-md fi-color-primary fi-btn-color-primary rounded-lg px-4 py-2 bg-primary-600 text-white">
            📷 Buka Kamera
        </button>
        <button type="button" x-on:click="stopCamera()" x-show="cameraActive"
            class="fi-btn fi-btn-size-md rounded-lg px-4 py-2 bg-danger-600 text-white">
            Tutup Kamera
        </button>
    </div>

    <div id="barcode-reader" style="width:100%;"></div>

    <div class="border-t pt-4">
        <label class="text-sm font-medium block mb-2">Atau upload gambar barcode</label>
        <input type="file" accept="image/*" x-on:change="scanFile($event)"
            class="block w-full text-sm text-gray-600 cursor-pointer
               file:mr-4 file:py-2 file:px-4
               file:rounded-lg file:border-0
               file:text-sm file:font-medium
               file:bg-primary-600 file:text-white
               hover:file:bg-primary-700
               file:cursor-pointer" />
    </div>

    <p x-show="statusMsg" x-text="statusMsg" class="text-sm" :class="isError ? 'text-danger-600' : 'text-success-600'">
    </p>
</div>
