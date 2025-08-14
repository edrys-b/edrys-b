import React, { useEffect, useState } from 'react';
import TopBar from '../../components/TopBar';
import api from '../../lib/api';

const KnowledgeBase: React.FC = () => {
	const [docs, setDocs] = useState<any[]>([]);
	const [url, setUrl] = useState('');
	const [title, setTitle] = useState('');

	const load = async () => {
		const res = await api.get('/kb/list');
		setDocs(res.data.documents);
	};

	useEffect(() => { load(); }, []);

	const upload = async (e: React.FormEvent<HTMLFormElement>) => {
		e.preventDefault();
		const form = e.currentTarget;
		const file = (form.elements.namedItem('file') as HTMLInputElement).files?.[0];
		if (!file) return;
		const fd = new FormData();
		fd.append('file', file);
		if (title) fd.append('title', title);
		await fetch(`${import.meta.env.VITE_API_BASE}/kb/upload`, {
			method: 'POST',
			headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` },
			body: fd,
		});
		setTitle('');
		await load();
	};

	const addUrl = async (e: React.FormEvent) => {
		e.preventDefault();
		await api.post('/kb/url', { url, title: title || url });
		setUrl(''); setTitle('');
		await load();
	};

	const del = async (id: number) => {
		await api.delete(`/kb/${id}`);
		await load();
	};

	return (
		<div>
			<TopBar />
			<div className="max-w-6xl mx-auto p-6">
				<h2 className="text-2xl font-semibold mb-4">Knowledge Base</h2>
				<div className="grid md:grid-cols-2 gap-6">
					<form onSubmit={upload} className="card space-y-2">
						<h3 className="font-semibold">Upload Text/Markdown</h3>
						<input className="input" placeholder="Title (optional)" value={title} onChange={e=>setTitle(e.target.value)} />
						<input name="file" type="file" className="input" />
						<button className="btn">Upload</button>
					</form>
					<form onSubmit={addUrl} className="card space-y-2">
						<h3 className="font-semibold">Add from URL</h3>
						<input className="input" placeholder="Title (optional)" value={title} onChange={e=>setTitle(e.target.value)} />
						<input className="input" placeholder="https://example.edu/page" value={url} onChange={e=>setUrl(e.target.value)} />
						<button className="btn">Ingest</button>
					</form>
				</div>
				<div className="mt-6 grid gap-3">
					{docs.map(d => (
						<div key={d.id} className="card flex items-center justify-between">
							<div>
								<div className="font-medium">{d.title}</div>
								<div className="text-xs text-gray-500">{d.source} {d.source_url || ''}</div>
							</div>
							<button className="btn" onClick={()=>del(d.id)}>Delete</button>
						</div>
					))}
				</div>
			</div>
		</div>
	);
};

export default KnowledgeBase;