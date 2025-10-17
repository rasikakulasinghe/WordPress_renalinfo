module.exports = {
	plugins: [
		require('postcss-preset-env')({
			stage: 3,
			features: {
				'nesting-rules': true,
				'custom-media-queries': true,
				'custom-properties': true
			}
		}),
		require('autoprefixer'),
		require('cssnano')({
			preset: ['default', {
				discardComments: {
					removeAll: true
				}
			}]
		})
	]
};
