{{--
Image Upload Component
Usage: @include('admin.partials.image-upload', ['name' => 'thumb', 'value' => $existingUrl, 'label' => 'Hình ảnh'])
--}}

@php
    $fieldName = $name ?? 'image';
    $fieldLabel = $label ?? 'Hình ảnh';
    $currentValue = $value ?? old($fieldName);
    $required = $required ?? false;
    $folder = $folder ?? 'products';
@endphp

<div class="image-upload-wrapper" data-field="{{ $fieldName }}">
    <label class="form-label">
        {{ $fieldLabel }}
        @if($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    {{-- Hidden input to store URL --}}
    <input type="hidden" name="{{ $fieldName }}" id="{{ $fieldName }}_input" value="{{ $currentValue }}">

    {{-- Upload Zone --}}
    <div class="upload-zone {{ $currentValue ? 'has-image' : '' }}" id="{{ $fieldName }}_zone">
        {{-- Preview Area --}}
        <div class="upload-preview" id="{{ $fieldName }}_preview" style="{{ $currentValue ? '' : 'display:none;' }}">
            <img src="{{ $currentValue }}" alt="Preview" id="{{ $fieldName }}_image">
            <div class="upload-preview-actions">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeImage('{{ $fieldName }}')">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>

        {{-- Upload Placeholder --}}
        <div class="upload-placeholder" id="{{ $fieldName }}_placeholder"
            style="{{ $currentValue ? 'display:none;' : '' }}">
            <i class="fa fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
            <p class="mb-1">Kéo thả hoặc click để upload</p>
            <small class="text-muted">PNG, JPG, WEBP (tối đa 5MB)</small>
        </div>

        {{-- Loading Overlay --}}
        <div class="upload-loading" id="{{ $fieldName }}_loading" style="display:none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
            <p class="mt-2 mb-0">Đang upload...</p>
        </div>

        {{-- File Input --}}
        <input type="file" id="{{ $fieldName }}_file" accept="image/png,image/jpeg,image/jpg,image/webp"
            style="display:none;" onchange="uploadImage(this, '{{ $fieldName }}', '{{ $folder }}')">
    </div>

    {{-- Error display --}}
    <div class="upload-error text-danger small mt-1" id="{{ $fieldName }}_error" style="display:none;"></div>

    @error($fieldName)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

@once
    @push('styles')
        <style>
            .image-upload-wrapper {
                margin-bottom: 1rem;
            }

            .upload-zone {
                border: 2px dashed var(--border-color);
                border-radius: var(--radius-md);
                padding: 24px;
                text-align: center;
                cursor: pointer;
                transition: all 0.2s ease;
                background: var(--gray-100);
                position: relative;
                min-height: 180px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .upload-zone:hover {
                border-color: var(--primary);
                background: var(--primary-soft);
            }

            .upload-zone.dragover {
                border-color: var(--primary);
                background: var(--primary-soft);
                transform: scale(1.02);
            }

            .upload-zone.has-image {
                border-style: solid;
                padding: 8px;
            }

            .upload-placeholder {
                display: flex;
                flex-direction: column;
                align-items: center;
                color: var(--gray-600);
            }

            .upload-preview {
                position: relative;
                width: 100%;
                max-width: 300px;
            }

            .upload-preview img {
                width: 100%;
                max-height: 200px;
                object-fit: contain;
                border-radius: var(--radius-sm);
            }

            .upload-preview-actions {
                position: absolute;
                top: 8px;
                right: 8px;
            }

            .upload-loading {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.9);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                border-radius: var(--radius-md);
            }

            .upload-error {
                color: var(--danger);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Click to open file dialog
            document.querySelectorAll('.upload-zone').forEach(zone => {
                zone.addEventListener('click', function (e) {
                    if (e.target.closest('.upload-preview-actions')) return;
                    const fieldName = this.closest('.image-upload-wrapper').dataset.field;
                    document.getElementById(fieldName + '_file').click();
                });

                // Drag and drop
                zone.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    this.classList.add('dragover');
                });

                zone.addEventListener('dragleave', function (e) {
                    e.preventDefault();
                    this.classList.remove('dragover');
                });

                zone.addEventListener('drop', function (e) {
                    e.preventDefault();
                    this.classList.remove('dragover');

                    const fieldName = this.closest('.image-upload-wrapper').dataset.field;
                    const folder = 'products'; // Default folder

                    if (e.dataTransfer.files.length > 0) {
                        const fileInput = document.getElementById(fieldName + '_file');
                        fileInput.files = e.dataTransfer.files;
                        uploadImage(fileInput, fieldName, folder);
                    }
                });
            });

            function uploadImage(input, fieldName, folder) {
                const file = input.files[0];
                if (!file) return;

                // Validate file
                const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showUploadError(fieldName, 'Chỉ chấp nhận file PNG, JPG, WEBP');
                    return;
                }

                if (file.size > 5 * 1024 * 1024) {
                    showUploadError(fieldName, 'File phải nhỏ hơn 5MB');
                    return;
                }

                // Show loading
                document.getElementById(fieldName + '_loading').style.display = 'flex';
                document.getElementById(fieldName + '_placeholder').style.display = 'none';
                document.getElementById(fieldName + '_preview').style.display = 'none';
                hideUploadError(fieldName);

                // Create FormData
                const formData = new FormData();
                formData.append('image', file);
                formData.append('folder', folder);

                // Upload via API
                fetch('/api/upload/image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById(fieldName + '_loading').style.display = 'none';

                        if (data.data && data.data.url) {
                            // Success
                            const url = data.data.url;
                            document.getElementById(fieldName + '_input').value = url;
                            document.getElementById(fieldName + '_image').src = url;
                            document.getElementById(fieldName + '_preview').style.display = 'block';
                            document.getElementById(fieldName + '_zone').classList.add('has-image');
                        } else if (data.url) {
                            // Alternative response format
                            const url = data.url;
                            document.getElementById(fieldName + '_input').value = url;
                            document.getElementById(fieldName + '_image').src = url;
                            document.getElementById(fieldName + '_preview').style.display = 'block';
                            document.getElementById(fieldName + '_zone').classList.add('has-image');
                        } else {
                            showUploadError(fieldName, data.message || 'Upload thất bại');
                            document.getElementById(fieldName + '_placeholder').style.display = 'flex';
                        }
                    })
                    .catch(error => {
                        console.error('Upload error:', error);
                        document.getElementById(fieldName + '_loading').style.display = 'none';
                        document.getElementById(fieldName + '_placeholder').style.display = 'flex';
                        showUploadError(fieldName, 'Đã có lỗi xảy ra. Vui lòng thử lại.');
                    });

                // Reset file input
                input.value = '';
            }

            function removeImage(fieldName) {
                document.getElementById(fieldName + '_input').value = '';
                document.getElementById(fieldName + '_image').src = '';
                document.getElementById(fieldName + '_preview').style.display = 'none';
                document.getElementById(fieldName + '_placeholder').style.display = 'flex';
                document.getElementById(fieldName + '_zone').classList.remove('has-image');
            }

            function showUploadError(fieldName, message) {
                const errorEl = document.getElementById(fieldName + '_error');
                errorEl.textContent = message;
                errorEl.style.display = 'block';
            }

            function hideUploadError(fieldName) {
                document.getElementById(fieldName + '_error').style.display = 'none';
            }
        </script>
    @endpush
@endonce