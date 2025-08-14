import React, { useEffect, useState } from 'react';
import TopBar from '../../components/TopBar';
import api from '../../lib/api';

const Resources: React.FC = () => {
	const [list, setList] = useState<any[]>([]);
	const [title, setTitle] = useState('');
	const [description, setDescription] = useState('');

	const load = async () => {
		const res = await api.get('/resources/list');
		setList(res.data.resources);
	};
	useEffect(() => { load(); }, []);

	const upload = async (e: React.FormEvent<HTMLFormElement>) => {
		e.preventDefault();
		const form = e.currentTarget;
		const file = (form.elements.namedItem('file') as HTMLInputElement).files?.[0];
		if (!file) return;
		const fd = new FormData();
		fd.append('file', file);
		fd.append('title', title || file.name);
		fd.append('description', description);
		await fetch(`${import.meta.env.VITE_API_BASE}/resources/upload`, {
			method: 'POST',
			headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` },
			body: fd,
		});
		setTitle(''); setDescription('');
		await load();
	};

	return (
		<div>
			<TopBar />
			<div className="max-w-6xl mx-auto p-6">
				<h2 className="text-2xl font-semibold mb-4">Resources</h2>
				<form onSubmit={upload} className="card space-y-2 max-w-lg">
					<input className="input" placeholder="Title" value={title} onChange={e=>setTitle(e.target.value)} />
					<textarea className="input" placeholder="Description" value={description} onChange={e=>setDescription(e.target.value)} />
					<input className="input" type="file" name="file" />
					<button className="btn">Upload</button>
				</form>
				<div className="mt-6 grid gap-3">
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

export default Resources;