/**
 * Internal dependencies
 */
import {merchant} from '@woocommerce/e2e-utils';

describe('Merchant', () => {
	it('Can log in to WP-Admin', async () => {
		await merchant.login();
	});
});
