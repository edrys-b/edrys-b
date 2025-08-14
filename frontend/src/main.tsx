import React from 'react';
import { createRoot } from 'react-dom/client';
import { createBrowserRouter, RouterProvider } from 'react-router-dom';
import './styles.css';
import App from './pages/App';
import Login from './pages/auth/Login';
import Register from './pages/auth/Register';
import AuthCallback from './pages/auth/AuthCallback';
import Dashboard from './pages/admin/Dashboard';
import KnowledgeBase from './pages/admin/KnowledgeBase';
import Resources from './pages/admin/Resources';
import Chat from './pages/student/Chat';
import Library from './pages/student/Library';

const router = createBrowserRouter([
	{ path: '/', element: <App /> },
	{ path: '/login', element: <Login /> },
	{ path: '/register', element: <Register /> },
	{ path: '/auth/callback', element: <AuthCallback /> },
	{ path: '/admin', element: <Dashboard /> },
	{ path: '/admin/kb', element: <KnowledgeBase /> },
	{ path: '/admin/resources', element: <Resources /> },
	{ path: '/chat', element: <Chat /> },
	{ path: '/library', element: <Library /> },
]);

createRoot(document.getElementById('root')!).render(
	<React.StrictMode>
		<RouterProvider router={router} />
	</React.StrictMode>
);