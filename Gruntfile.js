module.exports = function( grunt ) {

	grunt.initConfig({
		pkg: grunt.file.readJSON( 'package.json' ),
		sass: {
			dist: {
				options: {
					sourceMap: true,
					outputStyle: 'expanded'
				},
				files: {
					'css/mm-components-admin.css' : 'scss/mm-components-admin.scss',
					'css/mm-posts-template-builder.css' : 'scss/mm-posts-template-builder.scss',
					'css/mm-components-public.css' : 'scss/mm-components-public.scss'
				}
			}
		},
		watch: {
			css: {
				files: '**/*.scss',
				tasks: ['sass'],
				options: {
					livereload: true,
				},
			},
		},
		postcss: {
			options: {
				processors: [
					require( 'autoprefixer' )({ browsers: 'last 2 versions' }), // Add vendor prefixes.
				]
			},
			dist: {
				src: 'css/*.css'
			}
		},
		wp_readme_to_markdown: {
			your_target: {
				files: {
					'readme.md': 'readme.txt'
				}
			}
		},
		makepot: {
			target: {
				options: {
					type: 'wp-plugin'
				}
			}
		}
	});

	// Load plugins.
	grunt.loadNpmTasks( 'grunt-sass' );
	grunt.loadNpmTasks( 'grunt-postcss' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-wp-readme-to-markdown' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );

	// Configure tasks.
	grunt.registerTask( 'build', ['sass', 'postcss', 'wp_readme_to_markdown', 'makepot'] );
};
