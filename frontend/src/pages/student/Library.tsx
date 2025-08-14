import React, { useEffect, useState } from 'react';
import TopBar from '../../components/TopBar';
import api from '../../lib/api';

const Library: React.FC = () => {
	const [list, setList] = useState<any[]>([]);
	useEffect(() => {
		api.get('/resources/list').then(res => setList(res.data.resources));
	}, []);
	return (
		<div>
			<TopBar />
			<div className="max-w-4xl mx-auto p-6">
				<h2 className="text-2xl font-semibold mb-4">Resources</h2>
				<div className="grid gap-3">
					{list.map(r => (
						<div key={r.id} className="card flex items-center justify-between">
							<div>
								<div className="font-medium">{r.title}</div>
								<div className="text-xs text-gray-500">{new Date(r.created_at).toLocaleString()}</div>
							</div>
							<a className="btn" href={`${import.meta.env.VITE_API_BASE}/resources/${r.id}/download?token=${localStorage.getItem('token')}`}>Download</a>
						</div>
					))}
				</div>
			</div>
		</div>
	);
};

export default Library;