import React, { useEffect, useState } from 'react';
import TopBar from '../../components/TopBar';
import api from '../../lib/api';

type Msg = { role: 'user'|'assistant'; content: string };

const Chat: React.FC = () => {
	const [messages, setMessages] = useState<Msg[]>([]);
	const [input, setInput] = useState('');
	const [chatId, setChatId] = useState<number|undefined>(undefined);

	useEffect(() => {
		api.get('/auth/me').catch(()=>{ window.location.href = '/login'; });
	}, []);

	const send = async (e: React.FormEvent) => {
		e.preventDefault();
		if (!input.trim()) return;
		const cur = input;
		setMessages(m => [...m, { role: 'user', content: cur }]);
		setInput('');
		const res = await api.post('/chat', { message: cur, chatId });
		setChatId(res.data.chatId);
		setMessages(m => [...m, { role: 'assistant', content: res.data.answer }]);
	};

	return (
		<div>
			<TopBar />
			<div className="max-w-3xl mx-auto p-6">
				<h2 className="text-2xl font-semibold mb-4">Chat with Support Assistant</h2>
				<div className="card h-[60vh] overflow-auto space-y-3">
					{messages.map((m, i) => (
						<div key={i} className={m.role === 'user' ? 'text-right' : 'text-left'}>
							<div className={`inline-block px-3 py-2 rounded ${m.role==='user' ? 'bg-[color:var(--brand-green)] text-white' : 'bg-gray-100'}`}>{m.content}</div>
						</div>
					))}
				</div>
				<form onSubmit={send} className="mt-3 flex gap-2">
					<input className="input" placeholder="Ask about courses, schedules, fees..." value={input} onChange={e=>setInput(e.target.value)} />
					<button className="btn">Send</button>
				</form>
			</div>
		</div>
	);
};

export default Chat;