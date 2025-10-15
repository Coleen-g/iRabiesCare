import './bootstrap';

// Lazy-mount the React login page when the login root exists.
// This keeps the existing non-React pages working.
const mountLogin = async () => {
	const rootEl = document.getElementById('react-login-root');
	if (!rootEl) return;

	try {
		// Dynamic import returns a module namespace object; use .default for the actual export
		const ReactModule = await import('react');
		const React = ReactModule.default || ReactModule;

		const ReactDOMModule = await import('react-dom/client');
		const ReactDOM = ReactDOMModule.default || ReactDOMModule;

		const LoginPageModule = await import('./Pages/Auth/LoginPage.jsx');
		const LoginPage = LoginPageModule.default || LoginPageModule;

		ReactDOM.createRoot(rootEl).render(React.createElement(LoginPage));
	} catch (e) {
		// Make sure errors are obvious in browser console instead of a silent white page
		// eslint-disable-next-line no-console
		console.error('Failed to mount React login page', e);
		const errEl = document.createElement('pre');
		errEl.style.color = 'red';
		errEl.style.padding = '1rem';
		errEl.textContent = 'Error initializing login page: ' + (e && e.message ? e.message : String(e));
		document.body.appendChild(errEl);
	}
};

mountLogin();

const mountRegister = async () => {
	const rootEl = document.getElementById('react-register-root');
	if (!rootEl) return;

	try {
		const ReactModule = await import('react');
		const React = ReactModule.default || ReactModule;
		const ReactDOMModule = await import('react-dom/client');
		const ReactDOM = ReactDOMModule.default || ReactDOMModule;
		const RegisterPageModule = await import('./Pages/Auth/RegisterPage.jsx');
		const RegisterPage = RegisterPageModule.default || RegisterPageModule;

		ReactDOM.createRoot(rootEl).render(React.createElement(RegisterPage));
	} catch (e) {
		// eslint-disable-next-line no-console
		console.error('Failed to mount React register page', e);
		const errEl = document.createElement('pre');
		errEl.style.color = 'red';
		errEl.style.padding = '1rem';
		errEl.textContent = 'Error initializing register page: ' + (e && e.message ? e.message : String(e));
		document.body.appendChild(errEl);
	}
};

mountRegister();

// Sidebar is now server-rendered in Blade; no client mount required.

// The admin SPA mount has been removed to keep server-rendered main content intact.
