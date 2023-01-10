#!/bin/bash

echo ""
echo "Install Storefront"
wp theme install storefront --activate

echo ""
echo "Adding basic WooCommerce settings..."
wp option set woocommerce_store_address "Example Address Line 1"
wp option set woocommerce_store_address_2 "Example Address Line 2"
wp option set woocommerce_store_city "Example City"
wp option set woocommerce_default_country "US:CA"
wp option set woocommerce_store_postcode "94110"
wp option set woocommerce_currency "USD"
wp option set woocommerce_product_type "both"
wp option set woocommerce_allow_tracking "no"

echo ""
echo "Importing WooCommerce shop pages..."
wp wc --user=admin tool run install_pages

echo ""
echo "Importing WooCommerce sample products..."
wp import wp-content/plugins/woocommerce/sample-data/sample_products.xml --authors=skip --quiet

echo ""
echo "Success! Your E2E Test Environment is now ready."
