# Changelog

All notable changes to the All-in-One WP Migration Unlimited Extension will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-01-17

### Added
- Initial stable release
- Remove JavaScript file size restrictions from All-in-One WP Migration
- Chunked upload system for handling large files
- Automatic retry logic for failed uploads with exponential backoff
- Real-time progress tracking during upload
- Support for WordPress multisite installations
- Automatic file size validation bypass
- Compatible with All-in-One WP Migration 7.0+
- Comprehensive documentation (README.md and readme.txt)

### Changed
- N/A (initial release)

### Deprecated
- N/A

### Removed
- N/A

### Fixed
- N/A

### Security
- Maintains WordPress nonce and secret key authentication
- No data collection or external API calls
- All processing happens locally on WordPress installation

---

## Development Versions (Pre-Release)

These versions were part of development and testing:

### [0.9.0] - 2026-01-16
- Beta testing phase
- Implemented core chunked upload functionality
- Added version compatibility checking
- Fixed field name issues (upload-file vs upload_file)
- Resolved browser caching problems
- Fixed parameter passing between JavaScript and PHP

### [0.5.0] - 2026-01-15
- Alpha testing phase  
- Initial proof of concept
- Basic file size restriction removal
- Early chunked upload implementation

---

## Planned Features

### [1.1.0] - TBD
- [ ] Add compression support for faster uploads
- [ ] Implement resume capability for interrupted uploads
- [ ] Add admin settings page for chunk size configuration
- [ ] Improve error messaging with actionable solutions
- [ ] Add support for alternative storage methods (S3, FTP, etc.)

### [1.2.0] - TBD
- [ ] Add multi-language support (i18n)
- [ ] Implement background processing for very large files
- [ ] Add upload queue management
- [ ] Performance optimizations

---

[1.0.0]: https://github.com/yourusername/ai1wm-unlimited-extension/releases/tag/v1.0.0
