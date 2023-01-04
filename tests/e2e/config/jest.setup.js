function setTestTimeouts() {
	// Increase the default test execution timeout from 5 seconds to 20 seconds.
	jest.setTimeout( 20000 );
}

beforeEach( async () => {
	setTestTimeouts();
});
