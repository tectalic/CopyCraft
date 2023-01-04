/**
 * External dependencies
 */
import config from 'config';

/**
 * Internal dependencies
 */
import { merchant } from '@woocommerce/e2e-utils';

const TIMEOUT = 20000;

describe( 'Store Owner', () => {
	it( 'Store owner can log in', async () => {
		await merchant.login();
	}, TIMEOUT );
} );
