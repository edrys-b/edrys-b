import React from 'react';
import { Link, useNavigate } from 'react-router-dom';

const TopBar: React.FC = () => {
	const navigate = useNavigate();
	const user = localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user') as string) : null;
	const logout = () => {
		localStorage.removeItem('token');
		localStorage.removeItem('user');
		navigate('/login');
	};
	return (
		<header className="bg-white border-b shadow-sm">
			<div className="max-w-6xl mx-auto flex items-center justify-between p-3">
				<div className="flex items-center gap-3">
					<img src="/logo.png" className="h-10" alt="School Logo" />
					<Link to="/" className="font-semibold text-lg text-[color:var(--brand-green)]">CS Student Support</Link>
				</div>
				<nav className="flex items-center gap-4">
					<Link to="/chat" className="hover:underline">Chat</Link>
					<Link to="/library" className="hover:underline">Resources</Link>
					{user?.role === 'admin' && (<>
						<Link to="/admin" className="hover:underline">Admin</Link>
						<Link to="/admin/kb" className="hover:underline">Knowledge</Link>
						<Link to="/admin/resources" className="hover:underline">Uploads</Link>
					</>)}
					{user ? (
						<button onClick={logout} className="btn">Logout</button>
					) : (
						<Link to="/login" className="btn">Login</Link>
					)}
				</nav>
			</div>
		</header>
	);
};

export default TopBar;