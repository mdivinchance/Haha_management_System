<x-app-layout>
    <div class="p-6 max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">Create Product</h1>
            <p class="text-gray-500 text-sm mt-1">Add a new item to your inventory</p>
        </div>

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="bg-neutral-900 rounded-xl p-6 space-y-5"
              x-data="{
                imageSource: 'file',
                previewUrl: null,
                cameraStream: null,
                videoRef: null,
                cameras: [],
                selectedCamera: '',
                init() {
                    this.videoRef = this.$refs.video;
                },
                switchSource(source) {
                    this.imageSource = source;
                    if (source === 'camera') {
                        this.$nextTick(() => { this.enumerateCameras(); this.startCamera(); });
                    } else {
                        this.stopCamera();
                    }
                },
                async enumerateCameras() {
                    try {
                        const devices = await navigator.mediaDevices.enumerateDevices();
                        this.cameras = devices.filter(d => d.kind === 'videoinput');
                        if (this.cameras.length && !this.selectedCamera) {
                            this.selectedCamera = this.cameras[0].deviceId;
                        }
                    } catch {}
                },
                startCamera() {
                    this.stopCamera();
                    const constraints = { video: { deviceId: this.selectedCamera ? { exact: this.selectedCamera } : undefined } };
                    navigator.mediaDevices.getUserMedia(constraints)
                        .then(stream => {
                            this.cameraStream = stream;
                            if (this.videoRef) this.videoRef.srcObject = stream;
                        })
                        .catch(() => alert('Could not access camera. Please use file upload.'));
                },
                stopCamera() {
                    if (this.cameraStream) {
                        this.cameraStream.getTracks().forEach(t => t.stop());
                        this.cameraStream = null;
                    }
                },
                switchCamera() {
                    const currentIdx = this.cameras.findIndex(c => c.deviceId === this.selectedCamera);
                    this.selectedCamera = this.cameras[(currentIdx + 1) % this.cameras.length].deviceId;
                    this.startCamera();
                },
                capturePhoto() {
                    const canvas = document.createElement('canvas');
                    canvas.width = this.videoRef.videoWidth;
                    canvas.height = this.videoRef.videoHeight;
                    canvas.getContext('2d').drawImage(this.videoRef, 0, 0);
                    this.previewUrl = canvas.toDataURL('image/jpeg', 0.8);
                    document.getElementById('camera_image').value = this.previewUrl;
                    this.stopCamera();
                },
                onFileSelect(e) {
                    const file = e.target.files[0];
                    if (file) {
                        this.previewUrl = URL.createObjectURL(file);
                    }
                },
                removeImage() {
                    this.previewUrl = null;
                    document.getElementById('image_file').value = '';
                    document.getElementById('camera_image').value = '';
                }
              }">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label for="name" class="form-label">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required class="input-field" placeholder="Product name">
                    @error('name') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" name="description" rows="2" class="input-field" placeholder="Optional description">{{ old('description') }}</textarea>
                    @error('description') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="category_id" class="form-label">Category</label>
                    <select id="category_id" name="category_id" required class="input-field">
                        <option value="">Select category</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="sku" class="form-label">SKU</label>
                    <input id="sku" type="text" name="sku" value="{{ old('sku') }}" required class="input-field" placeholder="e.g. SHO-001">
                    @error('sku') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="purchase_price" class="form-label">Purchase Price (FRW)</label>
                    <input id="purchase_price" type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price') }}" required class="input-field" placeholder="0.00">
                    @error('purchase_price') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="selling_price" class="form-label">Selling Price (FRW)</label>
                    <input id="selling_price" type="number" step="0.01" name="selling_price" value="{{ old('selling_price') }}" required class="input-field" placeholder="0.00">
                    @error('selling_price') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                    <input id="stock_quantity" type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required class="input-field" placeholder="0">
                    @error('stock_quantity') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="low_stock_threshold" class="form-label">Low Stock Threshold</label>
                    <input id="low_stock_threshold" type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" required class="input-field" placeholder="5">
                    @error('low_stock_threshold') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="form-label">Product Image</label>

                    <div class="flex gap-2 mb-3">
                        <button type="button" @click="switchSource('file')"
                                :class="imageSource === 'file' ? 'bg-teal-500/20 text-teal-400 border-teal-500' : 'bg-neutral-800 text-neutral-400 border-neutral-700'"
                                class="flex-1 rounded-lg border px-4 py-2 text-sm font-medium transition-colors">
                            Upload File
                        </button>
                        <button type="button" @click="switchSource('camera')"
                                :class="imageSource === 'camera' ? 'bg-teal-500/20 text-teal-400 border-teal-500' : 'bg-neutral-800 text-neutral-400 border-neutral-700'"
                                class="flex-1 rounded-lg border px-4 py-2 text-sm font-medium transition-colors">
                            Use Camera
                        </button>
                    </div>

                    <div x-show="imageSource === 'file'" class="space-y-3">
                        <input id="image_file" type="file" name="image" accept="image/*"
                               @change="onFileSelect"
                               class="block w-full text-sm text-neutral-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-500/10 file:text-teal-400 hover:file:bg-teal-500/20">
                    </div>

                    <div x-show="imageSource === 'camera'" x-cloak class="space-y-3">
                        <div class="rounded-lg overflow-hidden bg-neutral-800 border border-neutral-700">
                            <video x-ref="video" autoplay playsinline class="w-full max-h-64 object-cover"></video>
                        </div>
                        <template x-if="cameras.length > 1">
                            <select x-model="selectedCamera" @change="startCamera"
                                    class="input-field text-sm">
                                <template x-for="cam in cameras" :key="cam.deviceId">
                                    <option :value="cam.deviceId" x-text="cam.label || 'Camera ' + (cameras.indexOf(cam) + 1)"></option>
                                </template>
                            </select>
                        </template>
                        <div class="flex gap-2">
                            <button type="button" @click="capturePhoto" class="btn-primary text-sm flex-1">Capture Photo</button>
                            <button type="button" @click="switchCamera" x-show="cameras.length > 1"
                                    class="flex-1 rounded-lg border border-neutral-700 bg-neutral-800 text-neutral-400 px-4 py-2 text-sm font-medium hover:text-white transition-colors">Switch</button>
                        </div>
                    </div>

                    <template x-if="previewUrl">
                        <div class="mt-3 relative inline-block">
                            <img :src="previewUrl" class="max-h-40 rounded-lg border border-neutral-700">
                            <button type="button" @click="removeImage"
                                    class="absolute -top-2 -right-2 h-5 w-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center hover:bg-red-400">&times;</button>
                        </div>
                    </template>

                    <input type="hidden" id="camera_image" name="camera_image" value="">
                    @error('image') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Create Product</button>
                <a href="{{ route('products.index') }}" class="text-sm text-neutral-400 hover:text-neutral-300">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
