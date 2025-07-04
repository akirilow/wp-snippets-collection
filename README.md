# WordPress Code Snippets & Shell Scripts

**Version:** 0.4.0

A collection of useful Advanced Custom Fields (ACF) PHP snippets and shell scripts for WordPress projects. This repository serves as a central place for reusable solutions and automation around WordPress.

## Contents

- **acf/**: Advanced Custom Fields (ACF) related PHP snippets (e.g., gallery, repeater fields, custom fields)
- **sh/**: Shell scripts for common tasks (e.g., WordPress installation, backups, updates)

## Examples

### ACF Snippets

- `acf_repeater.php` - Comprehensive examples for repeater field loops, nested repeaters, and error handling
- `acf_gallery.php` - Gallery field implementation with image handling

### Shell Scripts

- `install_wp.sh` - Automated WordPress installation with DDEV and German optimization

## Requirements

- WordPress with Advanced Custom Fields (ACF) plugin for ACF snippets
- DDEV for shell scripts (macOS)

## Usage

### ACF Snippets

Add the desired ACF snippet into your theme's `functions.php` file, a custom plugin, or use it within your ACF setup.

### Shell Scripts

1. Navigate to the `sh/` directory.
2. Make the script executable: chmod +x script-name.sh
3. Run the script: ./script-name.sh

## Categories

- Advanced Custom Fields (ACF)
- Backups & Maintenance (Shell)

## Contributing

Pull requests are welcome!  
Please comment your snippets and briefly describe what they do.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a complete list of changes.
