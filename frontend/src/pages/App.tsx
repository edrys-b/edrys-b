import React from 'react';
import { Link } from 'react-router-dom';
import TopBar from '../components/TopBar';

const App: React.FC = () => {
	return (
		<div>
			<TopBar />
			<section className="max-w-6xl mx-auto p-6 grid md:grid-cols-2 gap-8 items-center">
				<div>
					<h1 className="text-4xl font-bold text-[color:var(--brand-green)]">Computer Science Student Support Assistant</h1>
					<p className="mt-4 text-gray-700">Get instant answers about courses, schedules, resources, and student services. Built with secure SSO and knowledge base retrieval from official sources.</p>
					<div className="mt-6 flex gap-3">
						<Link to="/chat" className="btn">Start Chat</Link>
						<Link to="/library" className="btn">Browse Resources</Link>
					</div>
				</div>
				<img src="/logo.png" alt="School" className="w-56 justify-self-center" />
			</section>
		</div>
	);
};

export default App;