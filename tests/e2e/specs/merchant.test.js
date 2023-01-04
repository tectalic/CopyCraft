/**
 * Internal dependencies
 */
import {merchant} from '@woocommerce/e2e-utils';

const TIMEOUT = 20000;

describe('Merchant', () => {
	it('Can log in to WP-Admin', async () => {
		await merchant.login();
	}, TIMEOUT);
});
