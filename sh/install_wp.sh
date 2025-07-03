#!/bin/bash

## Description: Create a folder in the .ddev directory if it doesn't already exist
## Usage: ./install_wp.sh

# Prompt the user to enter a folder name
read -p "Please enter the name of the folder: " folder_name

# Check if the user provided input
if [ -z "$folder_name" ]; then
    echo "No folder name provided. Exiting script."
    exit 1
fi

# Prompt the user for admin credentials
read -p "Enter admin username: " admin_user
read -p "Enter admin password: " admin_password
read -p "Enter admin email: " admin_email

# Check if any of the credentials are empty
if [ -z "$admin_user" ] || [ -z "$admin_password" ] || [ -z "$admin_email" ]; then
    echo "Admin credentials are incomplete. Exiting script."
    exit 1
fi

# Define the base directory where the folder should be created
BASE_DIR="/Users/andy/ddev"

# Build the full path to the folder
TARGET_DIR="$BASE_DIR/$folder_name"

# Check if the folder already exists
if [ -d "$TARGET_DIR" ]; then
    echo "The folder '$folder_name' already exists in '$BASE_DIR'."
else
    # Create the folder if it doesn't exist
    mkdir -p "$TARGET_DIR"
    echo "The folder '$folder_name' has been successfully created in '$BASE_DIR'."
fi

# Additional check: Verify if the folder is empty
if [ "$(ls -A "$TARGET_DIR")" ]; then
    echo "The folder '$folder_name' is not empty."
else
    echo "The folder '$folder_name' is empty."
fi

# Navigate to the newly created directory
cd "$TARGET_DIR" || exit

# Configure DDEV for WordPress in the new directory
echo "Configuring DDEV for WordPress..."
ddev config --project-name "$folder_name" --project-type wordpress

# Start DDEV containers
echo "Starting DDEV containers..."
ddev start

# Download WordPress core files
echo "Downloading WordPress core files..."
ddev wp core download --locale=de_DE --version=latest


# Extract primary URL from .ddev/config.yaml
DDEV_PRIMARY_URL=$(grep 'primary_hostname:' .ddev/config.yaml | awk '{print $2}')
DDEV_PRIMARY_URL="https://${DDEV_PRIMARY_URL}" 

# Check if URL was successfully retrieved
if [ -z "$DDEV_PRIMARY_URL" ]; then
    echo "Error: Could not retrieve primary URL from DDEV. Exiting script."
    exit 1
fi

# Install WordPress with provided credentials using WP-CLI via DDEV and URL
echo "Installing WordPress..."
ddev wp core install \
    --url="$DDEV_PRIMARY_URL" \
    --title="$folder_name" \
    --admin_user="$admin_user" \
    --admin_password="$admin_password" \
    --admin_email="$admin_email"
# ===================================================
# WordPress Tweaks and Cleanup
# ===================================================
echo ""
echo "Applying WordPress tweaks and cleanup:"

# 1. Disable admin bar for user with ID 1
echo "Disabling admin bar for user (ID: 1)..."
ddev wp user meta update 1 show_admin_bar_front false

# 2. Delete all inactive themes
echo "Deleting inactive themes..."
ddev wp theme delete $(ddev wp theme list --status=inactive --field=name)

# 3. Remove all inactive plugins
echo "Removing all inactive plugins..."
ddev wp plugin delete $(ddev wp plugin list --status=inactive --field=name)

# 4. Delete sample post (if exists)
echo "Deleting sample post (if any)..."
ddev wp post delete $(ddev wp post list --post_type=post --posts_per_page=1 --post_status=publish --format=ids) --force

# 5. Delete sample page named 'beispiel-seite' (if exists)
echo "Deleting sample page 'beispiel-seite' (if any)..."
ddev wp post delete $(ddev wp post list --post_type=page --name=beispiel-seite --format=ids) --force

# 6. Create Home and News pages
echo "Creating 'Startseite' and 'News' pages..."
ddev wp post create --post_type=page --post_title='Startseite' --post_status=publish
ddev wp post create --post_type=page --post_title='News' --post_status=publish

# 7. Set reading options
echo "Setting reading options..."
ddev wp option update page_on_front $(ddev wp post list --post_type=page --name=startseite --field=ID)
ddev wp option update page_for_posts $(ddev wp post list --post_type=page --name=news --field=ID)
ddev wp option update show_on_front 'page'

# 8. Adjust discussion settings
echo "Adjusting discussion settings..."
ddev wp option update default_pingback_flag 'closed'
ddev wp option update default_ping_status 'closed'
ddev wp option update default_comment_status 'closed'
ddev wp option update comment_moderation 1
ddev wp option update comment_whitelist 1
ddev wp option update show_avatars 0

# 9. Set permalink structure and custom category/tag base
echo "Setting permalink structure and custom category/tag base..."
ddev wp rewrite structure '/%postname%/'
ddev wp option update category_base 'kategorie'
ddev wp option update tag_base 'tag'
ddev wp rewrite flush

echo "WordPress tweaks and cleanup completed!"

# Display success message with login details and URL
echo ""
echo "---------------------------------------------------------------------------"
echo "WordPress installation completed!"
echo "Username: $admin_user"
echo "Password: $admin_password"
echo "Email: $admin_email"
echo "---------------------------------------------------------------------------"

# Wait 5 seconds before opening the backend URL in default browser
sleep 5

echo "Opening WordPress in your browser..."
ddev launch 