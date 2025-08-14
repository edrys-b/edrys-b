import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import api from '../../lib/api';
import TopBar from '../../components/TopBar';

const Register: React.FC = () => {
	const [name, setName] = useState('');
	const [email, setEmail] = useState('');
	const [password, setPassword] = useState('');
	const [error, setError] = useState('');
	const navigate = useNavigate();

	const submit = async (e: React.FormEvent) => {
		e.preventDefault();
		setError('');
		try {
			const res = await api.post('/auth/register', { name, email, password });
			localStorage.setItem('token', res.data.token);
			const me = await api.get('/auth/me');
			localStorage.setItem('user', JSON.stringify(me.data.user));
			navigate('/');
		} catch (err: any) {
			setError(err?.response?.data?.message || 'Registration failed');
		}
	};

	return (
		<div>
			<TopBar />
			<div className="max-w-md mx-auto mt-10 card">
				<h2 className="text-2xl font-semibold mb-4">Register</h2>
				<form onSubmit={submit} className="space-y-3">
					<input className="input" placeholder="Full name" value={name} onChange={e => setName(e.target.value)} />
					<input className="input" placeholder="Email" value={email} onChange={e => setEmail(e.target.value)} />
					<input className="input" type="password" placeholder="Password" value={password} onChange={e => setPassword(e.target.value)} />
					{error && <div className="text-red-600 text-sm">{error}</div>}
					<button className="btn w-full" type="submit">Create account</button>
				</form>
				<p className="mt-3 text-sm">Already have an account? <Link to="/login" className="text-[color:var(--brand-green)] underline">Login</Link></p>
			</div>
		</div>
	);
};

export default Register;