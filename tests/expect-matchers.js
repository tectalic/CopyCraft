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
 * Expect an element to exist.
 *
 * @param {*} selector
 * @returns
 */
async function toHasElement (page, selector) {
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
 * Expect an element text to equals the expected text.
 *
 * @param {*} selector The CSS selector for the element.
 * @param {*} expectedText The expected text.
 */
async function toElementEquals (page, selector, expectedText) {
  const element = await getElement(page, selector);
  // Get the text from the element via Puppeteer
  const elementText = await page.evaluate((el) => el.textContent, element);
  if (elementText === expectedText) {
    return {
      message: () => `expected text for "${selector}" not to be equal to "${expectedText}"`,
      pass: true
    };
  } else {
    return {
      message: () => `expected text for "${selector}" to be equal to "${expectedText}"`,
      pass: false
    };
  }
}

/**
 * Expect the page to include the given text.
 *
 * @param {*} expectedText The text to search for.
 */
async function toIncludes (page, expectedText) {
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
 * Expect an element to be filled with the given text.
 *
 * @param {*} page Puppeteer page object.
 * @param {*} selector The CSS selector for the element.
 * @param {*} text The text to fill the element with.
 */
async function toFillElement (page, selector, text) {
  const element = await getElement(page, selector);

  await element.press('Delete');
  await element.type(text);

  await toElementEquals(page, selector, text);
}

module.exports = {
  toElementEquals,
  toFillElement,
  toHasElement,
  toIncludes
};
