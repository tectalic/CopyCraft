/**
 * Internal dependencies
 */
import {visitAdminPage} from '@wordpress/e2e-test-utils';

describe('Merchant Plugins Screen', () => {
	it('CopyCraft listed in active plugins', async () => {
		await visitAdminPage('plugins.php', 'plugin_status=active');
		await expect(page).toMatchElement('tr[data-slug="copycraft"] .plugin-title strong', {text: 'CopyCraft'});
	});
});
