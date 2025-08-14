import React, { useEffect, useState } from 'react';
import TopBar from '../../components/TopBar';
import api from '../../lib/api';
import { useNavigate } from 'react-router-dom';

const Dashboard: React.FC = () => {
	const navigate = useNavigate();
	const [data, setData] = useState<any>(null);
	useEffect(() => {
		api.get('/auth/me').then(res => {
			if (res.data.user.role !== 'admin') navigate('/');
		});
		api.get('/admin/metrics').then(res => setData(res.data));
	}, [navigate]);
	return (
		<div>
			<TopBar />
			<div className="max-w-6xl mx-auto p-6">
				<h2 className="text-2xl font-semibold mb-4">Admin Dashboard</h2>
				<div className="grid grid-cols-2 md:grid-cols-5 gap-4">
					{data && Object.entries(data).map(([k, v]) => (
						<div key={k} className="card text-center">
							<div className="text-sm text-gray-500">{k}</div>
							<div className="text-3xl font-bold">{String(v)}</div>
						</div>
					))}
				</div>
			</div>
		</div>
	);
};

export default Dashboard;