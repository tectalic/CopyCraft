/**
 * @param {string} selector The CSS selector for the element.
 * @returns {ElementHandle} The element handle.
 */

async function getElement (page, selector) {
  const element = await page.$(selector);
  if (element === null) {
    throw new Error(`Element ${selector} not found`);
  }
  return element;
}

/**
 * Expect an element to exist on the page.
 *
 * @param {*} selector
 * @returns
 */
async function toHaveElement (page, selector) {
  const element = await page.$(selector);
  if (element !== null) {
    return {
      message: () => `expected "${selector}" not to be find in the page`,
      pass: true
    };
  } else {
    return {
      message: () => `expected "${selector}" to be find in the page`,
      pass: false
    };
  }
}

/**
 * Expect an element to exist with the specified text or value.
 *
 * @param {*} selector The CSS selector for the element.
 * @param {*} expectedText The expected text or value.
 */
async function toElementEquals (page, selector, expectedText) {
  const element = await getElement(page, selector);
  // Get the text from the element via Puppeteer
  let elementText = await (await element.getProperty('textContent')).jsonValue();
  if (typeof elementText === 'undefined' || elementText === null || elementText === '') {
    // No text for this element, get the value from the element via Puppeteer.
    elementText = await (await element.getProperty('value')).jsonValue();
  }
  // Trim whitespace.
  elementText = elementText.trim();
  if (elementText === expectedText) {
    return {
      message: () => `expected text for "${selector}" not to be equal to "${expectedText}".`,
      pass: true
    };
  } else {
    return {
      message: () => `expected text for "${selector}" to be equal to "${expectedText}". Found "${elementText}" instead.`,
      pass: false
    };
  }
}

/**
 * Expect the page to include the given text.
 *
 * @param {*} expectedText The text to search for.
 */
async function toIncludeText (page, expectedText) {
  // Get the page as text via Puppeteer.
  const pageText = await page.evaluate(() => document.documentElement.textContent);
  if (pageText.includes(expectedText)) {
    return {
      message: () => `expected "${expectedText}" to be not found in the page`,
      pass: true
    };
  } else {
    return {
      message: () => `expected "${expectedText}" to be  found in the page`,
      pass: false
    };
  }
}

/**
 * Expect an input element to be filled with the given text.
 *
 * @param {*} selector The CSS selector for the element.
 * @param {*} text The text to fill the element with.
 */
async function toFillElement (page, selector, text) {
  const element = await getElement(page, selector);

  // Clear all input text as per https://evanhalley.dev/post/clearing-input-field-puppeteer/
  await element.click({ clickCount: 3 });
  await element.press('Backspace');
  await element.type(text);

  return await toElementEquals(page, selector, text);
}

module.exports = {
  toElementEquals,
  toFillElement,
  toHaveElement,
  toIncludeText
};
