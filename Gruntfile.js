module.exports = function(grunt) {

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		sass: {
			dist: {
				options: {
					style: 'expanded'
				},
				files: {
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
				}
			}
		},
		postcss: {
			options: {
				processors: [
					require('pixrem')(), // Adds pixel fallbacks for rem units.
					require('autoprefixer')({browsers: 'last 2 versions'}), // Adds vendor prefixes where required; removes redundant prefixes.
					require('cssnano')() // Optimizes and minifies resulting CSS output.
				]
			},
			dist: {
				src: 'css/*.css'
			}
		}

	});

	// Load Grunt plugins.
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-postcss');

	// Configure default task(s).
	grunt.registerTask('default', ['watch']);

};
