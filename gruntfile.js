module.exports = function(grunt) {

      grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        watch: {
            scripts: {
                files: ['dist/js/*.js', 'dist/css/*.css', 'dist/extras/slick/*.css'],
                tasks: ['concat:dist1', 'concat:dist2', 'concat:dist3', 'concat:dist4', 'concat:dist5', 'concat:dist6', 'uglify:dist2min', 'cssmin:dist3min']
            },
        },
        concat: {
            options: {
                separator: '\n',
                sourceMap: false
            },
            dist1: {
                src: [
                    'dist/js/bootstrap.js',
                    'dist/js/fontawesome.js',
                    'dist/js/info.js',
                    'dist/js/jquery.mobile.custom.js',
                    'dist/js/main.js',
                    'dist/js/menu.js',
                    'dist/js/modal.js',
                    'dist/js/modernizr.js',
                    'dist/js/reviews.js',
                    'dist/js/player.js',
                    'dist/js/uploader.js',
                    'dist/js/dropdown.js',
                    'dist/js/jquery.menu-aim.js',
                    'dist/extras/slick/slick.js',
                    'dist/extras/sweetalert/sweetalert.min.js',
                ],
                dest: 'dist/development/js/streamium.js'
            },
            dist2: {
                src: [
                    'dist/css/bootstrap.css',
                    'dist/css/info.css',
                    'dist/css/tiles.css',
                    'dist/css/programs.css',
                    'dist/css/menu.css',
                    'dist/css/modal.css',
                    'dist/css/reviews.css',
                    'dist/css/s2member.css',
                    'dist/css/uploader.css',
                    'dist/css/woocommerce.css',
                    'dist/css/dropdown.css',
                    'dist/extras/slick/slick.css',
                    'dist/extras/slick/slick-theme.css',
                    'dist/extras/sweetalert/sweetalert.css',
                    'dist/css/main.css',
                    'dist/css/mobile.css',
                ],
                dest: 'dist/development/css/streamium.css'
            },
            dist3: {
                src: [
                    'dist/js/chosen.jquery.js',
                    'dist/js/admin.js',
                ],
                dest: 'dist/development/js/admin.js'
            },
            dist4: {
                src: [
                    'dist/css/chosen.css',
                    'dist/css/admin.css',
                ],
                dest: 'dist/development/css/admin.css'
            },
            dist5: {
                src: [
                    'dist/js/custom.post.type.general.js',
                ],
                dest: 'dist/development/js/custom.post.type.general.js'
            },
            dist6: {
                src: [
                    'dist/js/custom.post.type.stream.js',
                ],
                dest: 'dist/development/js/custom.post.type.stream.js'
            },
        },
        uglify: {
            dist2min: {
                options: {
                    mangle: true,
                    drop_console: true
                },
                files: {
                    'production/js/streamium.min.js': ['dist/development/js/streamium.js'],
                    'production/js/admin.min.js': ['dist/development/js/admin.js'],
                    'production/js/custom.post.type.general.min.js': ['dist/development/js/custom.post.type.general.js'],
                    'production/js/custom.post.type.stream.min.js': ['dist/development/js/custom.post.type.stream.js'],
                }
            }
        },
        cssmin: {
            options: {},
            dist3min: {
                files: {
                    'production/css/streamium.min.css': ['dist/development/css/streamium.css'],
                    'production/css/admin.min.css': ['dist/development/css/admin.css'],
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.registerTask('default', ['watch', 'concat:dist1', 'concat:dist2', 'concat:dist3', 'concat:dist4', 'concat:dist5', 'concat:dist6', 'uglify:dist2min', 'cssmin:dist3min']);

};