/**** Tailwind CSS Config ****/
module.exports = {
	content: [
		'./index.html',
		'./src/**/*.{ts,tsx,js,jsx}',
	],
	theme: {
		extend: {
			colors: {
				school: {
					green: '#0a8a3a',
					greenDark: '#06692c',
					white: '#ffffff'
				}
			}
		}
	},
	plugins: [],
};