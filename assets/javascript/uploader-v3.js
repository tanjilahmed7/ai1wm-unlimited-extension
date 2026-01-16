/**
 * AI1WM Unlimited Extension - Custom Uploader
 * Based on the official extension's approach
 * Handles chunked uploads without file size restrictions
 * Version: 3.0.0 - Stable Release
 */
(function($) {
    'use strict';
    
    // Ensure ai1wmue_uploader.params is initialized as an object
    if (typeof ai1wmue_uploader !== 'undefined') {
        if (Array.isArray(ai1wmue_uploader.params)) {
            ai1wmue_uploader.params = {};
        }
    }

    // Custom uploader for unlimited extension (inspired by official extension)
    var Ai1wmue_Uploader = function() {
        this.file = null;
        this.fileSize = 0;
        this.retries = 0;
        this.maxRetries = 30;
        this.chunkSize = (typeof ai1wm_uploader !== 'undefined' && ai1wm_uploader.chunk_size) || (5 * 1024 * 1024); // Use localized or default to 5MB
        this.model = null;
        this.stopUpload = false;
    };

    Ai1wmue_Uploader.prototype.init = function() {
        var self = this;
        var $form = $('#ai1wm-import-form');
        var $fileInput = $('#ai1wm-import-file');
        var $dragDrop = $('#ai1wm-drag-drop-area');

        // File input change handler
        $fileInput.on('change', function(e) {
            self.reset();
            var file = e.target.files.item(0);
            if (file) {
                self.handleFile(file);
            }
            $form.trigger('reset');
            e.preventDefault();
        });

        // Drag and drop handlers
        $dragDrop.on('dragenter', function(e) {
            $dragDrop.addClass('ai1wm-drag-over');
            e.preventDefault();
        });

        $dragDrop.on('dragover', function(e) {
            $dragDrop.addClass('ai1wm-drag-over');
            e.preventDefault();
        });

        $dragDrop.on('dragleave', function(e) {
            $dragDrop.removeClass('ai1wm-drag-over');
            e.preventDefault();
        });

        $dragDrop.on('drop', function(e) {
            self.reset();
            $dragDrop.removeClass('ai1wm-drag-over');
            var file = e.originalEvent.dataTransfer.files.item(0);
            if (file) {
                self.handleFile(file);
            }
            $form.trigger('reset');
            e.preventDefault();
        });
    };

    Ai1wmue_Uploader.prototype.reset = function() {
        this.model = new Ai1wm.Import();
        this.stopUpload = false;
    };

    Ai1wmue_Uploader.prototype.handleFile = function(file) {
        this.file = file;
        this.fileSize = file.size;

        // Check disk space first
        var self = this;
        this.model.checkDiskSpace(this.fileSize, function() {
            try {
                self.validateFile(file);
                self.prepareUpload(file);
                self.upload(file, 0); // Start uploading with 0 retries
            } catch (err) {
                self.showError(err.message);
            }
        });
    };

    Ai1wmue_Uploader.prototype.validateFile = function(file) {
        // Check file extension
        if (!ai1wmue_uploader.filters.ai1wm_archive_extension.some(function(ext) {
            return file.name.substr(-ext.length) === ext;
        })) {
            throw new Error(ai1wm_locale.invalid_archive_extension);
        }

        // Check compatibility
        if (ai1wm_compatibility.messages.length > 0) {
            throw new Error(ai1wm_compatibility.messages.join());
        }

        // Set beforeunload handler
        $(window).bind('beforeunload', function() {
            return ai1wm_locale.stop_importing_your_website;
        });
    };

    Ai1wmue_Uploader.prototype.prepareUpload = function(file) {
        var storage = Ai1wm.Util.random(12);
        var params = Ai1wm.Util.form('#ai1wm-import-form')
            .concat({name: 'storage', value: storage})
            .concat({name: 'archive', value: file.name})
            .concat({name: 'file', value: 1})
            .concat({name: 'total_archive_size', value: this.fileSize}); // Pass total file size!

        this.model.setParams(params);

        // Ensure params is an object before extending
        if (typeof ai1wmue_uploader.params !== 'object' || Array.isArray(ai1wmue_uploader.params)) {
            ai1wmue_uploader.params = {};
        }

        // Merge new params with existing ones (preserves priority and secret_key)
        ai1wmue_uploader.params = $.extend({}, ai1wmue_uploader.params, {
            storage: storage,
            archive: file.name,
            total_archive_size: this.fileSize
        });

        // Set stop handler
        var self = this;
        this.model.onStop = function() {
            self.stopUpload = true;
            self.model.clean();
        };

        // Initialize progress
        this.model.setStatus({
            type: 'progress',
            percent: '0.00'
        });
    };

    Ai1wmue_Uploader.prototype.upload = function(file, retryCount) {
        var self = this;
        retryCount = retryCount || 0;

        if (this.stopUpload) {
            return;
        }

        // Upload entire file at once (PHP limits are configured to handle it)
        var chunkSize = file.size;
        
        // Create FormData with the chunk
        var formData = this.getFormData(file, chunkSize);

        // Upload via AJAX
        $.ajax({
            url: ai1wm_uploader.url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            timeout: 0,
            success: function(response, textStatus, jqXHR) {
                if (self.stopUpload) {
                    return;
                }

                // Parse response - handle different response types
                var parsedResponse;
                
                try {
                    if (typeof response === 'object') {
                        // Already parsed by jQuery
                        parsedResponse = response;
                    } else if (typeof response === 'string') {
                        // Try multiple parsing methods
                        
                        // Method 1: Use Ai1wm.Util.json
                        var jsonStr = Ai1wm.Util.json(response);
                        if (jsonStr) {
                            parsedResponse = JSON.parse(jsonStr);
                        } else {
                            // Method 2: Try direct JSON.parse (might be already JSON)
                            try {
                                parsedResponse = JSON.parse(response);
                            } catch (e) {
                                // Method 3: Look for JSON pattern in response
                                var jsonMatch = response.match(/\{[\s\S]*\}/);
                                if (jsonMatch) {
                                    parsedResponse = JSON.parse(jsonMatch[0]);
                                } else {
                                    throw new Error('Invalid response format (no JSON found)');
                                }
                            }
                        }
                    }
                    
                    // Check for errors
                    if (parsedResponse && parsedResponse.errors && parsedResponse.errors.length === 0) {
                        // Slice off the uploaded chunk
                        file = file.slice(chunkSize, file.size, 'application/octet-binary');
                        
                        // Calculate progress
                        var progress = ((self.fileSize - file.size) / self.fileSize) * 100;
                        
                        self.model.setStatus({
                            type: 'progress',
                            percent: progress.toFixed(2)
                        });

                        // Check if complete
                        if (progress === 100) {
                            self.onUploadComplete();
                        } else {
                            // Upload next chunk
                            self.upload(file, 0); // Reset retries for next chunk
                        }
                    } else {
                        var errorMsg = parsedResponse && parsedResponse.errors ? 
                            parsedResponse.errors.join(', ') : 'Unknown error in response';
                        throw new Error(errorMsg);
                    }
                } catch (err) {
                    self.showError(err.message);
                }
            },
            error: function(xhr, status, error) {
                if (self.stopUpload) {
                    return;
                }

                // Handle 413 (payload too large) by reducing chunk size
                if (xhr.status === 413) {
                    self.chunkSize = chunkSize / 2;
                    self.upload(file, 0);
                    return;
                }

                // Retry logic
                var delay = 1000 * retryCount;
                if (retryCount < self.maxRetries) {
                    setTimeout(function() {
                        self.upload(file, retryCount + 1);
                    }, delay);
                } else {
                    self.showError(ai1wm_locale.problem_while_uploading_your_file);
                }
            }
        });
    };

    Ai1wmue_Uploader.prototype.getFormData = function(file, chunkSize) {
        var formData = new FormData();
        
        // Append the chunk - use underscore to match PHP $_FILES array
        formData.append('upload_file', file.slice(0, chunkSize, 'application/octet-binary'));
        
        // Append all params from ai1wmue_uploader.params
        for (var key in ai1wmue_uploader.params) {
            if (ai1wmue_uploader.params.hasOwnProperty(key)) {
                formData.append(key, ai1wmue_uploader.params[key]);
            }
        }
        
        return formData;
    };

    Ai1wmue_Uploader.prototype.onUploadComplete = function() {
        this.model.start(); // Trigger the import process
    };

    Ai1wmue_Uploader.prototype.showError = function(message) {
        if (this.model) {
            this.model.setStatus({
                type: 'error',
                title: ai1wm_locale.unable_to_import,
                message: message
            });
        } else {
            alert('Error: ' + message);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        // Unbind the main plugin's handlers first to take control
        setTimeout(function() {
            // Remove existing handlers from file input and drag-drop area
            $('#ai1wm-import-file').off('change');
            $('#ai1wm-drag-drop-area').off('dragenter dragover dragleave drop');
            
            // Initialize our custom uploader
            var uploader = new Ai1wmue_Uploader();
            uploader.init();
        }, 500); // Wait for main plugin to initialize first
    });

})(jQuery);
