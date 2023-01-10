/* global describe, expect, it, jestPuppeteer, page */

const { visitAdminPage } = require('@wordpress/e2e-test-utils');

describe('CopyCraft Settings', () => {
  beforeEach(async () => {
    await jestPuppeteer.resetPage();
    await visitAdminPage('options-general.php', 'page=copycraft_options');
  });
  it('Shows elements', async () => {
    expect.assertions(3);

    await expect(page).toMatchElement('#wpbody-content > div.wrap > h1', 'CopyCraft');
    await expect(page).toMatchElement('#wpbody-content > div.wrap > form > h2', 'OpenAI');
    await expect(page).toMatchElement('#wpbody-content > div.wrap > form > table > tbody > tr > th', 'OpenAI API Key');
  });
  it('Can save valid key', async () => {
    expect.assertions(3);

    // Enter the OpenAI API Key.
    await expect(page).toFill(
      'form input[name="copycraft_options[openai_api_key]"]',
      'sk-12345678901234567890123456789012'
    );
    // Click "Save Changes" button.
    await page.evaluate((selector) => document.querySelector(selector).click(), '#submit');
    // Wait for page to reload.
    await page.waitForNavigation({ waitUntil: 'load' });
    // Verify new page saved.
    await expect(page).toMatchElement(
      'form input[name="copycraft_options[openai_api_key]"]',
      'sk-12345678901234567890123456789012'
    );
    await expect(page).toMatch('Settings saved.');
  });
  it('Refuse to save invalid key', async () => {
    expect.assertions(2);

    // Enter the OpenAI API Key.
    await expect(page).toFill('form input[name="copycraft_options[openai_api_key]"]', 'invalid key');
    // Click "Save Changes" button.
    await page.evaluate((selector) => document.querySelector(selector).click(), '#submit');
    // Wait for page to reload.
    await page.waitForNavigation({ waitUntil: 'load' });
    // Verify new page saved.
    await expect(page).toMatch('Your OpenAI API Key is invalid. Please check that you have copied the key correctly.');
  });
});
