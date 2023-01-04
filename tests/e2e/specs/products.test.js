/**
 * Internal dependencies
 */
import {merchant} from "@woocommerce/e2e-utils";

const TIMEOUT = 20000;

describe('Store Owner Product Management', () => {
	it('Can create a new draft product', async () => {
		await merchant.openNewProduct();
		// Enter a Product Title
		await expect(page).toFill('#titlediv input', 'Product Name');
		// Click "Save Draft" button.
		await expect(page).toClick('#save-post');
		// Wait for the "Saved" message to appear.
		await page.waitForSelector('#message.updated', {text: 'Product draft updated'});
	});
	it('Can create a new published product', async () => {
		await merchant.openNewProduct();
		// Enter a Product Title
		await expect(page).toFill('#titlediv input', 'Product Name');
		// Click "Save Draft" button.
		await expect(page).toClick('#publish');
		// Wait for the "Saved" message to appear.
		await page.waitForSelector('#message.updated', {text: 'Product published.'});
	});
});
