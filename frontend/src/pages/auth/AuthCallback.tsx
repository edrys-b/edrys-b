import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../../lib/api';

const AuthCallback: React.FC = () => {
	const navigate = useNavigate();
	useEffect(() => {
		const hash = new URLSearchParams(window.location.hash.replace('#', ''));
		const token = hash.get('token');
		if (token) {
			localStorage.setItem('token', token);
			api.get('/auth/me').then(res => {
				localStorage.setItem('user', JSON.stringify(res.data.user));
				navigate('/');
			});
		}
	}, [navigate]);
	return <div className="p-8">Signing you in...</div>;
};

export default AuthCallback;