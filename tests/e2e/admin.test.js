/* global page */
const { visitAdminPage } = require('@wordpress/e2e-test-utils');

describe('Store Owner', () => {
  it('Can log in to WP-Admin', async () => {
    expect.assertions(1);

    await visitAdminPage('/');
    await expect(page).toMatchElement('#welcome-panel > div > div.welcome-panel-header > h2', 'Welcome to WordPress');
  });
});
